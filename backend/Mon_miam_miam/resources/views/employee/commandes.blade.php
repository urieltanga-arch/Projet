<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Commandes - Order.cm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('employee.dashboard') }}" class="text-amber-600 hover:text-amber-700 mb-2 inline-block">
                    ← Retour au dashboard
                </a>
                <h1 class="text-4xl font-bold text-gray-800">Gestion des Commandes</h1>
                <p class="text-gray-600 mt-2">Gérez et suivez toutes les commandes</p>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('employee.commandes') }}?statut=tous" 
                   class="px-6 py-2 rounded-lg font-semibold transition {{ $statut === 'tous' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Toutes ({{ \App\Models\Commande::count() }})
                </a>
                <a href="{{ route('employee.commandes') }}?statut=en_attente" 
                   class="px-6 py-2 rounded-lg font-semibold transition {{ $statut === 'en_attente' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    En attente ({{ \App\Models\Commande::where('statut', 'en_attente')->count() }})
                </a>
                <a href="{{ route('employee.commandes') }}?statut=en_preparation" 
                   class="px-6 py-2 rounded-lg font-semibold transition {{ $statut === 'en_preparation' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    En préparation ({{ \App\Models\Commande::where('statut', 'en_preparation')->count() }})
                </a>
                <a href="{{ route('employee.commandes') }}?statut=prete" 
                   class="px-6 py-2 rounded-lg font-semibold transition {{ $statut === 'prete' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Prêtes ({{ \App\Models\Commande::where('statut', 'prete')->count() }})
                </a>
                <a href="{{ route('employee.commandes') }}?statut=en_livraison" 
                   class="px-6 py-2 rounded-lg font-semibold transition {{ $statut === 'en_livraison' ? 'bg-purple-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    En livraison ({{ \App\Models\Commande::where('statut', 'en_livraison')->count() }})
                </a>
                <a href="{{ route('employee.commandes') }}?statut=livree" 
                   class="px-6 py-2 rounded-lg font-semibold transition {{ $statut === 'livree' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Livrées ({{ \App\Models\Commande::where('statut', 'livree')->count() }})
                </a>
            </div>
        </div>

        <!-- Liste des commandes -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @forelse($commandes as $commande)
            <div class="border-b border-gray-200 p-6 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between gap-4">
                    <!-- Info commande -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-gray-800">Commande #{{ $commande->numero_commande }}</h3>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $commande->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $commande->statut === 'en_preparation' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $commande->statut === 'prete' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $commande->statut === 'en_livraison' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $commande->statut === 'livree' ? 'bg-green-200 text-green-900' : '' }}
                                {{ $commande->statut === 'annulee' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-3">
                            <div>
                                <p class="text-sm text-gray-500">Client</p>
                                <p class="font-semibold">{{ $commande->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Téléphone</p>
                                <p class="font-semibold">{{ $commande->telephone_contact }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Montant</p>
                                <p class="font-semibold text-amber-600">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Date</p>
                                <p class="font-semibold">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if($commande->adresse_livraison)
                        <div class="mb-3">
                            <p class="text-sm text-gray-500">Adresse de livraison</p>
                            <p class="font-semibold">{{ $commande->adresse_livraison }}</p>
                        </div>
                        @endif

                        @if($commande->notes)
                        <div class="mb-3">
                            <p class="text-sm text-gray-500">Notes</p>
                            <p class="text-gray-700 italic">{{ $commande->notes }}</p>
                        </div>
                        @endif

                        <!-- Items commandés -->
                        <div class="mt-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">Articles commandés:</p>
                            <div class="space-y-1">
                                @foreach($commande->items as $item)
                                <div class="flex justify-between text-sm">
                                    <span>{{ $item['quantite'] }}x {{ $item['nom'] }}</span>
                                    <span class="font-semibold">{{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} FCFA</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-2 min-w-[200px]">
                        <select onchange="changerStatut({{ $commande->id }}, this.value)" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 font-semibold">
                            <option value="">Changer le statut</option>
                            <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'disabled' : '' }}>En attente</option>
                            <option value="en_preparation" {{ $commande->statut === 'en_preparation' ? 'disabled' : '' }}>En préparation</option>
                            <option value="prete" {{ $commande->statut === 'prete' ? 'disabled' : '' }}>Prête</option>
                            <option value="en_livraison" {{ $commande->statut === 'en_livraison' ? 'disabled' : '' }}>En livraison</option>
                            <option value="livree" {{ $commande->statut === 'livree' ? 'disabled' : '' }}>Livrée</option>
                            <option value="annulee" {{ $commande->statut === 'annulee' ? 'disabled' : '' }}>Annuler</option>
                        </select>
                        
                        <button onclick="imprimerCommande({{ $commande->id }})" 
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-semibold">
                            🖨️ Imprimer
                        </button>
                        
                        <button onclick="voirDetails({{ $commande->id }})" 
                                class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-semibold">
                            👁️ Détails
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="text-6xl mb-4">📦</div>
                <p class="text-xl text-gray-500">Aucune commande trouvée</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $commandes->links() }}
        </div>
    </div>

    <script>
        function changerStatut(commandeId, nouveauStatut) {
            if (!nouveauStatut) return;
            
            if (!confirm('Voulez-vous vraiment changer le statut de cette commande ?')) {
                event.target.value = '';
                return;
            }

            fetch(`/employee/commandes/${commandeId}/statut`, {
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

        function imprimerCommande(commandeId) {
            window.print();
        }

        function voirDetails(commandeId) {
            alert('Fonctionnalité à implémenter : voir les détails complets de la commande #' + commandeId);
        }
    </script>
</body>
</html>