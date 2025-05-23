<?php

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Estadísticas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-2">Usuarios</h3>
                    <p class="text-3xl">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-2">Departamentos</h3>
                    <p class="text-3xl">{{ $totalDepartments }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-2">Solicitudes pendientes</h3>
                    <p class="text-3xl">{{ $pendingRequests }}</p>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Acciones rápidas</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Crear usuario</a>
                    <a href="{{ route('departments.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Crear departamento</a>
                    <a href="{{ route('requests.approval') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Ver solicitudes pendientes</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>