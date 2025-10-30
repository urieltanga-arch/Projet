@extends('layouts.app')

@section('title', 'Gestion des employés')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Gestion des employés</h1>
        <a href="{{ route('gerant.employees.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-plus mr-2"></i>Ajouter un employé
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Téléphone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Date d'ajout</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $employee->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $employee->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $employee->email }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $employee->phone }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $employee->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('gerant.employees.edit', $employee) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('gerant.employees.delete', $employee) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection