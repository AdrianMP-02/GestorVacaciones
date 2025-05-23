<?php

namespace App\Http\Controllers;

use App\Models\VacationDay;
use App\Models\Holiday;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
  /**
   * Mostrar calendario personal del usuario
   */
  public function personal()
  {
    $user = Auth::user();

    // Obtener días de vacaciones del usuario
    $vacationDays = VacationDay::where('user_id', $user->id)
      ->whereHas('vacationRequest', function ($query) {
        $query->where('status', 'approved');
      })
      ->get();

    // Obtener días festivos
    $holidays = Holiday::all();

    return view('calendar.personal', compact('vacationDays', 'holidays'));
  }

  /**
   * Mostrar calendario del equipo (para gerentes)
   */
  public function team()
  {
    // Opción 1: Obtener el usuario con la relación ya cargada
    $user = \App\Models\User::with('role')->find(Auth::id());

    // Alternativa: Si lo anterior no funciona
    // $user = Auth::user();
    // $role = $user->role; // Esto forzará la carga de la relación

    if ($user->isAdmin()) {
      $departmentId = request('department_id');
      $departments = Department::all();
    } else {
      $departmentId = $user->managedDepartment->id ?? $user->department_id;
      $departments = collect([$user->department]);
    }

    // Obtener usuarios del departamento
    $users = Department::find($departmentId)->users;

    // Obtener días de vacaciones aprobados para todos los miembros
    $vacationDays = VacationDay::whereIn('user_id', $users->pluck('id'))
      ->whereHas('vacationRequest', function ($query) {
        $query->where('status', 'approved');
      })
      ->get();

    // Obtener días festivos
    $holidays = Holiday::all();

    return view('calendar.team', compact('departments', 'departmentId', 'users', 'vacationDays', 'holidays'));
  }
}
