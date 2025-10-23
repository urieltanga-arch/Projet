<x-admin-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-bold text-black">Dashboard Administrateur</h1>
                    <p class="text-gray-600 mt-2">Vue d'ensemble et gestion complète</p>
                </div>
                
                <!-- Filtres de période -->
                <div class="flex gap-2">
                    <a href="{{ route('admin.dashboard', ['periode' => 'jour']) }}" 
                       class="px-4 py-2 rounded-xl font-semibold transition-all {{ $periode === 'jour' ? 'bg-black text-white' : 'bg-white text-black hover:bg-gray-100' }}">
                        Jour
                    </a>
                    <a href="{{ route('admin.dashboard', ['periode' => 'semaine']) }}" 
                       class="px-4 py-2 rounded-xl font-semibold transition-all {{ $periode === 'semaine' ? 'bg-black text-white' : 'bg-white text-black hover:bg-gray-100' }}">
                        Semaine
                    </a>
                    <a href="{{ route('admin.dashboard', ['periode' => 'mois']) }}" 
                       class="px-4 py-2 rounded-xl font-semibold transition-all {{ $periode === 'mois' ? 'bg-black text-white' : 'bg-white text-black hover:bg-gray-100' }}">
                        Mois
                    </a>
                </div>
            </div>

            <!-- Cartes statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Vente d'Aujourd'hui -->
                <div class="bg-black rounded-3xl p-6 shadow-xl transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-6xl">💵</div>
                        <div class="text-right">
                            <div class="text-white text-4xl font-bold">{{ number_format($ventesAujourdhui, 0) }}</div>
                            <div class="text-white/70 text-sm">FCFA</div>
                        </div>
                    </div>
                    <div class="text-white font-semibold">Vente d'Aujourd'hui</div>
                </div>

                <!-- Commandes -->
                <div class="bg-gradient-to-br from-yellow-400 to-amber-500 rounded-3xl p-6 shadow-xl transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-6xl">🛒</div>
                        <div class="text-right">
                            <div class="text-black text-4xl font-bold">{{ $nombreCommandes }}</div>
                        </div>
                    </div>
                    <div class="text-black font-semibold">Commandes</div>
                </div>

                <!-- Clients Actifs -->
                <div class="bg-white rounded-3xl p-6 shadow-xl transform hover:scale-105 transition-all border-2 border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-6xl">👥</div>
                        <div class="text-right">
                            <div class="text-black text-4xl font-bold">{{ number_format($clientsActifs, 0, ',', ' ') }}</div>
                        </div>
                    </div>
                    <div class="text-black font-semibold">Clients Actifs</div>
                </div>

                <!-- Points Attribués -->
                <div class="bg-gradient-to-br from-green-400 to-emerald-500 rounded-3xl p-6 shadow-xl transform hover:scale-105 transition-all">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-6xl">✅</div>
                        <div class="text-right">
                            <div class="text-black text-4xl font-bold">{{ number_format($pointsAttribues, 0, ',', ' ') }}</div>
                        </div>
                    </div>
                    <div class="text-black font-semibold">Points Attribués</div>
                </div>

            </div>

            <!-- Grid 2 colonnes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                <!-- Graphique des ventes -->
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-black mb-6">Ventes</h2>
                    
                    <div class="relative">
                        <canvas id="ventesChart" class="w-full" style="height: 300px;"></canvas>
                    </div>
                </div>

                <!-- Activités Récentes -->
                <div class="bg-white rounded-3xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-black mb-6">Activités Récentes</h2>
                    
                    <div class="space-y-3">
                        @forelse($activitesRecentes as $activite)
                            @php
                                $bgColors = [
                                    'yellow' => 'bg-yellow-100',
                                    'blue' => 'bg-blue-100',
                                    'green' => 'bg-green-100',
                                    'red' => 'bg-red-100'
                                ];
                            @endphp
                            <div class="{{ $bgColors[$activite['couleur']] }} rounded-xl p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl">{{ $activite['icone'] }}</div>
                                    <div class="flex-1">
                                        <p class="font-bold text-black">{{ $activite['message'] }}</p>
                                        <p class="text-sm text-gray-600">{{ $activite['details'] }}</p>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $activite['temps'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-8">Aucune activité récente</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Alertes Système -->
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-black mb-6">Alertes Système</h2>
                
                @if(empty($alertes))
                    <div class="bg-green-100 rounded-2xl p-6 text-center">
                        <div class="text-4xl mb-2">✅</div>
                        <p class="text-green-800 font-semibold">Tout est en ordre !</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($alertes as $alerte)
                            @php
                                $bgColors = [
                                    'red' => 'bg-red-100 border-red-300',
                                    'yellow' => 'bg-yellow-100 border-yellow-300'
                                ];
                            @endphp
                            <div class="{{ $bgColors[$alerte['couleur']] }} rounded-2xl p-4 border-2 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="text-3xl">{{ $alerte['icone'] }}</div>
                                    <span class="font-bold text-black">{{ $alerte['message'] }}</span>
                                </div>
                                
                                @if($alerte['type'] === 'stock')
                                    <a href="{{ route('employee.menu.index') }}" 
                                       class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                                        Gérer le menu
                                    </a>
                                @elseif($alerte['type'] === 'retard')
                                    <a href="{{ route('employee.commandes.index') }}" 
                                       class="bg-black hover:bg-gray-800 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                                        Voir les commandes
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Graphique des ventes
        const ctx = document.getElementById('ventesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($graphiqueData['labels']),
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: @json($graphiqueData['ventes']),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#000',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        borderColor: '#f59e0b',
                        borderWidth: 2,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y.toLocaleString() + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' F';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>

</x-admin-app-layout>