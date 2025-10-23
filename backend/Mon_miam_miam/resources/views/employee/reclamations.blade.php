<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des réclamations - Order.cm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<!-- Ajoutez la navbar ici -->
    @include('layouts.employee-navigation')
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('employee.dashboard') }}" class="text-amber-600 hover:text-amber-700 mb-2 inline-block">
                ← Retour au dashboard
            </a>
            <h1 class="text-4xl font-bold text-gray-800">Gestion des réclamations</h1>
            <p class="text-gray-600 mt-2">Traitements de réclamation clients</p>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow">
                <div class="text-sm text-gray-600">Total</div>
                <div class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</div>
            </div>
            <div class="bg-red-50 rounded-xl p-4 shadow">
                <div class="text-sm text-red-600">Non traitées</div>
                <div class="text-2xl font-bold text-red-700">{{ $stats['non_traitees'] }}</div>
            </div>
            <div class="bg-yellow-50 rounded-xl p-4 shadow">
                <div class="text-sm text-yellow-600">En cours</div>
                <div class="text-2xl font-bold text-yellow-700">{{ $stats['en_cours'] }}</div>
            </div>
            <div class="bg-green-50 rounded-xl p-4 shadow">
                <div class="text-sm text-green-600">Résolues</div>
                <div class="text-2xl font-bold text-green-700">{{ $stats['resolues'] }}</div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <form method="GET" action="{{ route('employee.reclamations') }}" class="flex flex-col md:flex-row gap-4">
                <!-- Barre de recherche -->
                <div class="flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}"
                           placeholder="Rechercher par description, type ou N° commande..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>
                
                <!-- Filtres par statut -->
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('employee.reclamations') }}?statut=tous&search={{ $search }}" 
                       class="px-4 py-2 rounded-lg font-semibold transition {{ $statut === 'tous' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Tous
                    </a>
                    <a href="{{ route('employee.reclamations') }}?statut=non_traitee&search={{ $search }}" 
                       class="px-4 py-2 rounded-lg font-semibold transition {{ $statut === 'non_traitee' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Urgent
                    </a>
                    <a href="{{ route('employee.reclamations') }}?statut=en_cours&search={{ $search }}" 
                       class="px-4 py-2 rounded-lg font-semibold transition {{ $statut === 'en_cours' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        En cours
                    </a>
                    <a href="{{ route('employee.reclamations') }}?statut=resolue&search={{ $search }}" 
                       class="px-4 py-2 rounded-lg font-semibold transition {{ $statut === 'resolue' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Résolue
                    </a>
                </div>

                <button type="submit" class="px-6 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-semibold">
                    🔍 Rechercher
                </button>
            </form>
        </div>

        <!-- Liste des réclamations -->
        <div class="space-y-4">
            @forelse($reclamations as $reclamation)
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 shadow-lg border border-gray-200">
                <div class="flex items-start justify-between gap-4">
                    <!-- Icône et info -->
                    <div class="flex items-start gap-4 flex-1">
                        <!-- Icône de statut -->
                        <div class="w-16 h-16 rounded-full flex items-center justify-center text-3xl flex-shrink-0
                            {{ $reclamation->statut === 'non_traitee' ? 'bg-red-100' : '' }}
                            {{ $reclamation->statut === 'en_cours' ? 'bg-yellow-100' : '' }}
                            {{ $reclamation->statut === 'resolue' ? 'bg-green-100' : '' }}">
                            @if($reclamation->statut === 'non_traitee')
                                ⚠️
                            @elseif($reclamation->statut === 'en_cours')
                                ⏱️
                            @else
                                ✅
                            @endif
                        </div>

                        <!-- Contenu -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-xl font-bold text-gray-800">
                                    Réclamation #{{ $reclamation->numero_reclamation }}
                                </h3>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $reclamation->statut === 'non_traitee' ? 'bg-red-500 text-white' : '' }}
                                    {{ $reclamation->statut === 'en_cours' ? 'bg-yellow-500 text-white' : '' }}
                                    {{ $reclamation->statut === 'resolue' ? 'bg-green-500 text-white' : '' }}">
                                    @if($reclamation->statut === 'non_traitee')
                                        Urgent
                                    @elseif($reclamation->statut === 'en_cours')
                                        En cours
                                    @else
                                        Résolue
                                    @endif
                                </span>
                            </div>

                            <div class="mb-3">
                                @if($reclamation->commande)
                                <p class="text-sm text-gray-600">
                                    <span class="font-semibold">Commande #{{ $reclamation->commande->numero_commande }}</span>
                                </p>
                                @endif
                                <p class="text-sm text-gray-600">{{ $reclamation->temps_ecoule }}</p>
                            </div>

                            <div class="mb-3">
                                <p class="text-sm font-semibold text-gray-700">Problème:</p>
                                <p class="text-gray-800">{{ $reclamation->type_probleme }}</p>
                            </div>

                            <div class="bg-white rounded-lg p-3 mb-3">
                                <p class="text-sm text-gray-600 italic">{{ $reclamation->description }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                @if($reclamation->statut === 'non_traitee')
                                <button onclick="changerStatutReclamation({{ $reclamation->id }}, 'en_cours')" 
                                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-semibold text-sm">
                                    ✓ Valider
                                </button>
                                @endif

                                @if($reclamation->statut === 'en_cours')
                                <button onclick="changerStatutReclamation({{ $reclamation->id }}, 'resolue')" 
                                        class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold text-sm">
                                    ✓ Marquer comme résolue
                                </button>
                                @endif

                                @if($reclamation->statut !== 'non_traitee')
                                <button onclick="changerStatutReclamation({{ $reclamation->id }}, 'non_traitee')" 
                                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold text-sm">
                                    ✗ Rejeter
                                </button>
                                @endif

                                @if($reclamation->commande)
                                <a href="{{ route('employee.reclamations') }}?commande={{ $reclamation->commande->id }}" 
                                   class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-semibold text-sm">
                                    👁️ Voir commande
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-12 text-center shadow-lg">
                <div class="text-6xl mb-4">📋</div>
                <p class="text-xl text-gray-500">Aucune réclamation trouvée</p>
                @if($search || $statut !== 'tous')
                <a href="{{ route('employee.reclamations') }}" class="text-amber-600 hover:text-amber-700 mt-2 inline-block">
                    Réinitialiser les filtres
                </a>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reclamations->appends(['statut' => $statut, 'search' => $search])->links() }}
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-12 py-6">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-4">
                <img src="/images/logo.png" alt="Logo" class="h-12 mx-auto mb-2">
            </div>
            <p>Order.cm ©Copyright 2025, All Rights Reserved.</p>
            <div class="mt-2 space-x-4">
                <a href="#" class="hover:text-amber-400 transition">Privacy Policy</a>
                <a href="#" class="hover:text-amber-400 transition">Terms</a>
                <a href="#" class="hover:text-amber-400 transition">Pricing</a>
                <a href="#" class="hover:text-amber-400 transition">Do not share your personal information</a>
            </div>
        </div>
    </footer>

    <script>
        function changerStatutReclamation(reclamationId, nouveauStatut) {
            const messages = {
                'en_cours': 'Voulez-vous valider cette réclamation et la prendre en charge ?',
                'resolue': 'Confirmer que cette réclamation a été résolue ?',
                'non_traitee': 'Voulez-vous rejeter cette réclamation ?'
            };

            if (!confirm(messages[nouveauStatut])) {
                return;
            }

            fetch(`/employee/reclamations/${reclamationId}/statut`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ statut: nouveauStatut })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Statut mis à jour avec succès !');
                    location.reload();
                } else {
                    alert('Erreur lors de la mise à jour du statut');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la mise à jour du statut');
            });
        }

        // Auto-refresh pour les nouvelles réclamations
        setInterval(() => {
            // Recharger uniquement si on est sur "non_traitee" ou "tous"
            const params = new URLSearchParams(window.location.search);
            const statut = params.get('statut') || 'tous';
            if (statut === 'non_traitee' || statut === 'tous') {
                location.reload();
            }
        }, 60000); // Toutes les 60 secondes
    </script>
</body>
</html>