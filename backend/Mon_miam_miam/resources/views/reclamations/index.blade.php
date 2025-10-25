{{-- resources/views/reclamations/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background: linear-gradient(135deg, #FFF8E1 0%, #FFE0B2 100%);">
    
    {{-- Header --}}
    <div class="bg-gradient-to-r from-amber-900 to-amber-800 text-white py-4 px-6 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12">
            <nav class="flex space-x-6">
                <a href="{{ route('gerant.dashboard') }}" class="hover:text-amber-300 transition">Dashboard</a>
                <a href="#" class="hover:text-amber-300 transition">Employé</a>
                <a href="#" class="hover:text-amber-300 transition">Statistiques</a>
                <a href="{{ route('reclamations.index') }}" class="text-amber-300 font-semibold border-b-2 border-amber-300">Réclamation</a>
            </nav>
        </div>
        <div class="flex items-center space-x-4">
            <span>{{ Auth::user()->name }}</span>
            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="Avatar" class="h-8 w-8 rounded-full border-2 border-white">
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        
        {{-- Titre --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Supervision des Réclamations</h1>
            <p class="text-gray-600 mt-1">Gérer et résoudre les réclamations clients</p>
        </div>

        {{-- Statistiques --}}
        <div class="grid grid-cols-4 gap-6 mb-8">
            
            {{-- Réclamations Reçues --}}
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform">
                <div class="flex items-center space-x-4">
                    <div class="bg-red-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Réclamations Reçues</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalReclamations }}</p>
                    </div>
                </div>
            </div>

            {{-- En cours --}}
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform">
                <div class="flex items-center space-x-4">
                    <div class="bg-yellow-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">En Cours</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $enAttenteCount }}</p>
                    </div>
                </div>
            </div>

            {{-- Total --}}
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalReclamations }}</p>
                    </div>
                </div>
            </div>

            {{-- Résolues --}}
            <div class="bg-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition-transform">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 p-4 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Résolues</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $traitesCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des réclamations --}}
        <div class="space-y-6" id="reclamationsList">
            @forelse($reclamations as $reclamation)
            @php
                $statutDisplay = match($reclamation->statut) {
                    'non_traitee' => 'Total',
                    'en_cours' => 'En attente',
                    'resolue' => 'Traité',
                    'fermee' => 'Fermée',
                    default => $reclamation->statut
                };
                
                $statutColor = match($reclamation->statut) {
                    'non_traitee' => 'blue',
                    'en_cours' => 'yellow',
                    'resolue' => 'green',
                    'fermee' => 'gray',
                    default => 'gray'
                };
            @endphp
            
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                
                {{-- En-tête de la réclamation --}}
                <div class="bg-gradient-to-r from-amber-50 to-amber-100 px-6 py-4 flex justify-between items-center border-b-2 border-amber-200">
                    <div class="flex items-center space-x-4">
                        <div class="bg-{{ $statutColor }}-100 p-3 rounded-full">
                            @if($reclamation->statut === 'en_cours')
                                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($reclamation->statut === 'resolue')
                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Réclamation #REC{{ str_pad($reclamation->id, 4, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Commande #{{ $reclamation->commande_id }}</span> • 
                                {{ $reclamation->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="px-5 py-2 rounded-full text-sm font-bold shadow-md bg-{{ $statutColor }}-400 text-{{ $statutColor }}-900">
                        {{ $statutDisplay }}
                    </span>
                </div>

                {{-- Contenu de la réclamation --}}
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        
                        {{-- Réclamation Client --}}
                        <div class="bg-amber-50 rounded-lg p-5 border-l-4 border-amber-500">
                            <h4 class="font-bold text-gray-800 mb-3 text-lg">Réclamation Client</h4>
                            <div class="mb-3 bg-white p-3 rounded-md">
                                <span class="text-xs font-bold text-amber-700 uppercase">Problème :</span>
                                <p class="text-sm text-gray-800 font-semibold mt-1">{{ $reclamation->type_probleme }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-md">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $reclamation->description }}</p>
                            </div>
                        </div>

                        {{-- Réponse Employé --}}
                        <div class="bg-blue-50 rounded-lg p-5 border-l-4 border-blue-500">
                            <h4 class="font-bold text-gray-800 mb-3 text-lg">Réponse Employé</h4>
                            @if($reclamation->commande)
                                <div class="mb-3 bg-white p-3 rounded-md">
                                    <span class="text-xs font-bold text-blue-700 uppercase">Client :</span>
                                    <p class="text-sm text-gray-800 font-semibold mt-1">
                                        {{ $reclamation->commande->user->name ?? 'Non assigné' }}
                                    </p>
                                </div>
                                <div class="bg-white p-3 rounded-md">
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $reclamation->reponse_employee ?? 'En attente de réponse de l\'employé...' }}
                                    </p>
                                </div>
                            @else
                                <div class="bg-white p-4 rounded-md text-center">
                                    <p class="text-sm text-gray-500 italic">Aucune réponse disponible</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex justify-start space-x-3 pt-4 border-t border-gray-200">
                        @if($reclamation->statut !== 'resolue')
                        <form action="{{ route('reclamations.updateStatus', $reclamation->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="Traité">
                            <button type="submit" class="px-6 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all font-bold text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                ✓ Résolu
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('reclamations.destroy', $reclamation->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-6 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all font-bold text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                ✗ Supprimer
                            </button>
                        </form>

                        @if($reclamation->statut === 'non_traitee')
                        <form action="{{ route('reclamations.updateStatus', $reclamation->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="statut" value="En attente">
                            <button type="submit" class="px-6 py-2.5 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-all font-bold text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                ⏱ Mettre en attente
                            </button>
                        </form>
                        @endif

                        <button onclick="openDetailModal({{ $reclamation->id }})" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all font-bold text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            👁 Détails complets
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-lg p-16 text-center">
                <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 mb-3">Aucune réclamation</h3>
                <p class="text-gray-500 text-lg">Il n'y a pas de réclamations à afficher pour le moment.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $reclamations->links() }}
        </div>
    </div>
</div>

{{-- Modal détails --}}
<div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full mx-4 transform transition-all">
        <div class="flex justify-between items-center mb-6 pb-4 border-b-2 border-amber-200">
            <h2 class="text-2xl font-bold text-gray-800">📋 Détails de la réclamation</h2>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="modalContent" class="space-y-4">
            {{-- Contenu chargé dynamiquement --}}
        </div>
    </div>
</div>

<script>
function openDetailModal(id) {
    fetch(`/reclamations/${id}`)
        .then(response => response.json())
        .then(data => {
            const statusClass = data.statut === 'En attente' ? 'bg-yellow-200 text-yellow-800' : 
                                data.statut === 'Traité' ? 'bg-green-200 text-green-800' : 
                                'bg-blue-200 text-blue-800';
            
            document.getElementById('modalContent').innerHTML = `
                <div class="space-y-5">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Numéro de réclamation</p>
                        <p class="text-xl font-bold text-gray-800">#REC${String(data.id).padStart(4, '0')}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Commande associée</p>
                        <p class="text-lg font-semibold text-gray-800">#${data.commande_id}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Type de problème</p>
                        <p class="text-lg font-semibold text-gray-800">${data.type_probleme}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Description complète</p>
                        <p class="text-base text-gray-700 leading-relaxed">${data.description}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-2">Statut actuel</p>
                        <span class="inline-block px-4 py-2 rounded-full text-sm font-bold ${statusClass}">${data.statut}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Date de création</p>
                        <p class="text-base font-medium text-gray-800">${new Date(data.created_at).toLocaleString('fr-FR', { 
                            day: '2-digit', 
                            month: '2-digit', 
                            year: 'numeric', 
                            hour: '2-digit', 
                            minute: '2-digit' 
                        })}</p>
                    </div>
                </div>
            `;
            document.getElementById('detailModal').classList.remove('hidden');
        });
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDetailModal();
    }
});
</script>

@if(session('success'))
<script>
    // Notification de succès
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-fade-in-down';
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 4000);
</script>
@endif
@endsection