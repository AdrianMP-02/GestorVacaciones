<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::with('manager')->paginate(10);
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $managers = User::whereHas('role', function($query) {
            $query->where('name', 'manager')->orWhere('name', 'admin');
        })->get();
        
        return view('departments.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Departamento creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $users = $department->users()->paginate(10);
        return view('departments.show', compact('department', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $managers = User::whereHas('role', function($query) {
            $query->where('name', 'manager')->orWhere('name', 'admin');
        })->get();
        
        return view('departments.edit', compact('department', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Departamento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        // Comprobar si tiene usuarios asignados
        if ($department->users()->count() > 0) {
            return redirect()->route('departments.index')
                ->with('error', 'No se puede eliminar un departamento con usuarios asignados.');
        }

        $department->delete();
        
        return redirect()->route('departments.index')
            ->with('success', 'Departamento eliminado correctamente.');
    }
}