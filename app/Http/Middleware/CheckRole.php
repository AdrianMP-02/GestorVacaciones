<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
  /**
   * Verifica que el usuario tenga uno de los roles requeridos.
   */
  public function handle(Request $request, Closure $next, ...$roles): Response
  {
    if (!$request->user()) {
      return redirect('login');
    }

    foreach ($roles as $role) {
      $method = 'is' . ucfirst($role);
      if (method_exists($request->user(), $method) && $request->user()->$method()) {
        return $next($request);
      }
    }

    return redirect('/dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
  }
}
