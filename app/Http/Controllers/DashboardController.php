<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
  /**
   * Mostrar el dashboard según el rol del usuario.
   */
  public function index(Request $request)
  {
    $user = $request->user();

    if ($user->isAdmin()) {
      return view('dashboard.admin', [
        'pendingRequests' => \App\Models\VacationRequest::where('status', 'pending')->count(),
        'totalUsers' => \App\Models\User::count(),
        'totalDepartments' => \App\Models\Department::count()
      ]);
    } elseif ($user->isManager()) {
      $departmentId = $user->managedDepartment->id ?? null;
      return view('dashboard.manager', [
        'pendingRequests' => \App\Models\VacationRequest::whereHas('user', function ($q) use ($departmentId) {
          $q->where('department_id', $departmentId);
        })->where('status', 'pending')->count(),
        'teamMembers' => \App\Models\User::where('department_id', $departmentId)->count()
      ]);
    } else {
      return view('dashboard.employee', [
        'requests' => $user->vacationRequests()->latest()->take(5)->get(),
        'remainingDays' => 20 - $user->vacationDays()->whereYear('date', date('Y'))->count() // Simplificado
      ]);
    }
  }
}
