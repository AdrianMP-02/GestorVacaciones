<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VacationRequest;
use App\Models\VacationDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationRequestController extends Controller
{
  /**
   * Mostrar listado de solicitudes del usuario actual
   */
  public function index()
  {
    // En lugar de usar la relación, hacemos una consulta directa
    $userId = Auth::id();
    $requests = VacationRequest::where('user_id', $userId)->latest()->paginate(10);
    return view('vacation-requests.index', compact('requests'));
  }

  /**
   * Mostrar formulario para crear una solicitud
   */
  public function create()
  {
    return view('vacation-requests.create');
  }

  /**
   * Almacenar una nueva solicitud
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
      'reason' => 'nullable|string|max:255',
    ]);

    // Crear solicitud directamente con el user_id
    $vacationRequest = VacationRequest::create([
      'user_id' => Auth::id(),
      'start_date' => $validated['start_date'],
      'end_date' => $validated['end_date'],
      'reason' => $validated['reason'] ?? null,
      'status' => 'pending',
    ]);

    // Crear los días de vacaciones individuales
    $startDate = new \DateTime($validated['start_date']);
    $endDate = new \DateTime($validated['end_date']);
    $interval = new \DateInterval('P1D');
    $dateRange = new \DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

    foreach ($dateRange as $date) {
      // Saltar fines de semana o días festivos si es necesario
      if ($date->format('N') < 6) { // Lunes a Viernes
        VacationDay::create([
          'user_id' => Auth::id(),
          'vacation_request_id' => $vacationRequest->id,
          'date' => $date->format('Y-m-d'),
        ]);
      }
    }

    return redirect()->route('vacation-requests.index')
      ->with('success', 'Solicitud creada correctamente y en espera de aprobación.');
  }

  /**
   * Mostrar detalles de una solicitud
   */
  public function show(VacationRequest $vacationRequest)
  {
    $this->authorize('view', $vacationRequest);

    return view('vacation-requests.show', compact('vacationRequest'));
  }

  /**
   * Listar solicitudes pendientes de aprobación
   */
  public function approvalList()
  {
    $user = \App\Models\User::with('role')->find(Auth::id());

    if ($user->isAdmin()) {
      $pendingRequests = VacationRequest::where('status', 'pending')->latest()->paginate(10);
    } else { // Manager
      $departmentId = $user->managedDepartment ? $user->managedDepartment->id : null;
      $pendingRequests = VacationRequest::whereHas('user', function ($query) use ($departmentId) {
        $query->where('department_id', $departmentId);
      })->where('status', 'pending')->latest()->paginate(10);
    }

    return view('vacation-requests.approval', compact('pendingRequests'));
  }

  /**
   * Aprobar una solicitud
   */
  public function approve(VacationRequest $request)
  {
    $this->authorize('approve', $request);

    $request->update([
      'status' => 'approved',
      'approver_id' => Auth::id(),
    ]);

    // Aquí podrías enviar una notificación al usuario

    return redirect()->route('requests.approval')
      ->with('success', 'Solicitud aprobada correctamente.');
  }

  /**
   * Rechazar una solicitud
   */
  public function reject(Request $httpRequest, VacationRequest $request)
  {
    $this->authorize('approve', $request);

    $validated = $httpRequest->validate([
      'rejection_reason' => 'required|string|max:255',
    ]);

    $request->update([
      'status' => 'rejected',
      'approver_id' => Auth::id(),
      'rejection_reason' => $validated['rejection_reason'],
    ]);

    // Aquí podrías enviar una notificación al usuario

    return redirect()->route('requests.approval')
      ->with('success', 'Solicitud rechazada correctamente.');
  }
}
