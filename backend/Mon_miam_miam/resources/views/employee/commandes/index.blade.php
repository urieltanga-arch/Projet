<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-black mb-2">Gestion des Commandes</h1>
                <p class="text-gray-600">Suivi et traitement des commandes</p>
                <p class="text-sm text-gray-500 mt-2">
                    Dernière mise à jour : <span id="last-update">{{ now()->format('H:i:s') }}</span>
                </p>
            </div>

            <!-- Filtres -->
            <div class="flex gap-3 mb-8 overflow-x-auto pb-2">
                <a href="{{ route('employee.commandes.index') }}" 
                   class="px-6 py-2 rounded-full font-semibold text-sm transition-all whitespace-nowrap {{ $status === 'all' ? 'bg-black text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    Toutes ({{ $commandes->count() }})
                </a>
                <a href="{{ route('employee.commandes.index', ['status' => 'en_attente']) }}"
                   class="px-6 py-2 rounded-full font-semibold text-sm transition-all whitespace-nowrap {{ $status === 'en_attente' ? 'bg-yellow-500 text-black' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    Nouvelles ({{ $nouvelles->count() }})
                </a>
                <a href="{{ route('employee.commandes.index', ['status' => 'en_preparation']) }}"
                   class="px-6 py-2 rounded-full font-semibold text-sm transition-all whitespace-nowrap {{ $status === 'en_preparation' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    En préparation ({{ $enPreparation->count() }})
                </a>
                <a href="{{ route('employee.commandes.index', ['status' => 'prete']) }}"
                   class="px-6 py-2 rounded-full font-semibold text-sm transition-all whitespace-nowrap {{ $status === 'prete' ? 'bg-green-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    Prêtes ({{ $pretes->count() }})
                </a>
                <a href="{{ route('employee.commandes.index', ['status' => 'livree']) }}"
                   class="px-6 py-2 rounded-full font-semibold text-sm transition-all whitespace-nowrap {{ $status === 'livree' ? 'bg-purple-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    Livrées ({{ $livrees->count() }})
                </a>
            </div>

            <!-- Grid des commandes par statut -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Colonne Nouvelles -->
                <div>
                    <div class="bg-yellow-100 rounded-2xl p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-yellow-900">Nouvelles</h2>
                            <span class="bg-yellow-500 text-black text-sm font-bold px-3 py-1 rounded-full">
                                {{ $nouvelles->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($nouvelles as $commande)
                            @include('employee.commandes.partials.cart', ['commande' => $commande, 'color' => 'yellow'])
                        @empty
                            <div class="bg-white rounded-2xl p-6 text-center text-gray-500">
                                Aucune nouvelle commande
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne En préparation -->
                <div>
                    <div class="bg-blue-100 rounded-2xl p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-blue-900">En préparation</h2>
                            <span class="bg-blue-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                                {{ $enPreparation->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($enPreparation as $commande)
                            @include('employee.commandes.partials.cart', ['commande' => $commande, 'color' => 'blue'])
                        @empty
                            <div class="bg-white rounded-2xl p-6 text-center text-gray-500">
                                Aucune commande en préparation
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne Prêtes -->
                <div>
                    <div class="bg-green-100 rounded-2xl p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-green-900">Prêtes</h2>
                            <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                                {{ $pretes->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($pretes as $commande)
                            @include('employee.commandes.partials.cart', ['commande' => $commande, 'color' => 'green'])
                        @empty
                            <div class="bg-white rounded-2xl p-6 text-center text-gray-500">
                                Aucune commande prête
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne Livrées -->
                <div>
                    <div class="bg-gray-100 rounded-2xl p-4 mb-4">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-bold text-gray-900">Livrées</h2>
                            <span class="bg-gray-500 text-white text-sm font-bold px-3 py-1 rounded-full">
                                {{ $livrees->count() }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($livrees as $commande)
                            @include('employee.commandes.partials.cart', ['commande' => $commande, 'color' => 'gray'])
                        @empty
                            <div class="bg-white rounded-2xl p-6 text-center text-gray-500">
                                Aucune commande livrée
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Script pour le refresh automatique -->
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let lastUpdate = new Date().toISOString();

        // Rafraîchir toutes les 30 secondes
        setInterval(() => {
            fetch(`{{ route('employee.commandes.refresh') }}?last_update=${lastUpdate}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.commandes.length > 0) {
                    // Recharger la page si des changements sont détectés
                    location.reload();
                }
                lastUpdate = new Date().toISOString();
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString('fr-FR');
            })
            .catch(error => console.error('Erreur refresh:', error));
        }, 30000); // 30 secondes
    </script>

</x-app-layout>