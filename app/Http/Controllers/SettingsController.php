<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Mostrar el formulario de configuración del sistema
     */
    public function edit()
    {
        // Aquí puedes cargar configuraciones desde una tabla de settings, 
        // o usar config() para las configuraciones principales
        $settings = [
            'app_name' => config('app.name'),
            'vacation_days_per_year' => config('app.vacation_days_per_year', 22),
            'approval_required' => config('app.approval_required', true),
        ];
        
        return view('settings.edit', compact('settings'));
    }

    /**
     * Actualizar configuraciones
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'vacation_days_per_year' => 'required|integer|min:1|max:60',
            'approval_required' => 'boolean',
        ]);

        // Actualizar .env o base de datos según sea necesario
        // Nota: Para actualizar el .env deberías usar un paquete específico
        // o implementar esa funcionalidad
        
        // Ejemplo de actualización de configuración en tiempo de ejecución
        config(['app.name' => $validated['app_name']]);
        config(['app.vacation_days_per_year' => $validated['vacation_days_per_year']]);
        config(['app.approval_required' => $validated['approval_required']]);
        
        // Limpiar caché
        Cache::flush();
        
        // Opcional: ejecutar comandos adicionales
        Artisan::call('optimize:clear');
        
        return redirect()->route('settings.edit')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}