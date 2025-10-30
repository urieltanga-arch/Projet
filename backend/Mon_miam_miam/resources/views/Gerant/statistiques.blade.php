<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques Complètes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    
    {{-- CORRECTION : Ajout des classes de style au header --}}
       <nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-black border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                <a href="{{ route('employee.dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-10 w-auto fill-current text-yellow-500" />
                </a>
            </div>
                <div class="hidden md:flex space-x-1">
                    <a href="{{ route('gerant.dashboard') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('gerant.gerant.dashboard') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                        Dashboard
                    </a>
                    <a href="#"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                        Employé
                    </a>
                    <a href="{{ route('gerant.statistiques') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                        Statistiques
                    </a>
                     <a href="{{ route('gerant.reclamations.index') }}"
   class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('gerant.reclamations.index') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
    Réclamation
</a>
                </div>
                <div class="hidden md:flex items-center">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-yellow-500 hover:text-yellow-400 transition-colors focus:outline-none">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <div x-show="userMenuOpen"
                             @click.away="userMenuOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50"
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Gérant</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                Mon Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-yellow-500 transition-colors p-2 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div x-show="mobileMenuOpen" class="md:hidden bg-gray-900" style="display: none;">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="{{ route('gerant.dashboard') }}" class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('gerant.gerant.dashboard') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">Dashboard</a>
                <a href="#" class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Employé</a>
                <a href="#" class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Statistiques</a>
<a href="{{ route('gerant.reclamations.index') }}" 
   class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('gerant.reclamations.index') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
    Réclamation
</a>               <div class="border-t border-gray-700 pt-3 mt-3">
                     <div class="flex items-center gap-3 pb-3 mb-3">
                        <div>
                            <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">Gérant</p>
                        </div>
                    </div>
                    <a href="#" class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Mon Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>


    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Statistiques Complètes</h1>
            <p class="text-gray-600">Analyse détaillée des ventes, fidélité et parrainage</p>
        </div>

        <!-- Filtres de date -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('admin.statistiques') }}" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de début</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date de fin</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                </div>
                <button type="submit" class="px-6 py-2 bg-yellow-500 text-black font-semibold rounded-lg hover:bg-yellow-600 transition-colors">
                    Filtrer
                </button>
            </form>
        </div>

        <!-- Grille des graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Évolution des Ventes -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Évolution des Ventes</h2>
                <canvas id="evolutionVentesChart"></canvas>
            </div>

            <!-- Répartition par Catégorie -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Répartition par Catégorie</h2>
                <canvas id="repartitionCategorieChart"></canvas>
            </div>

            <!-- Programme de Fidélité -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Programme de Fidélité</h2>
                <canvas id="programmeFideliteChart"></canvas>
            </div>

            <!-- Système de Parrainage -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Système de Parrainage</h2>
                <canvas id="parrainageChart"></canvas>
            </div>
        </div>

        <!-- Analyse Fidélité (Cartes KPI) -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Analyse Fidélité</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-4xl font-bold text-yellow-600 mb-2">{{ number_format($clientsFideles) }}</div>
                    <div class="text-gray-600 font-medium">Clients Fidèles</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ number_format($pointsDistribues) }}</div>
                    <div class="text-gray-600 font-medium">Points distribués</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">{{ $tauxRetentionPourcent }}%</div>
                    <div class="text-gray-600 font-medium">Taux de rétention</div>
                </div>
            </div>
        </div>

        <!-- Clients les plus fidèles -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Clients les plus fidèles</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-yellow-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">👤 Clients</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Points</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Commandes</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total dépensé</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topClients as $client)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 flex items-center gap-2">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr($client->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $client->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">{{ number_format($client->total_points) }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $client->nombre_commandes }}</td>
                            <td class="px-6 py-4 text-gray-700 font-semibold">{{ number_format($client->total_depense, 0, ',', ' ') }} FCFA</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Données PHP vers JavaScript
        const evolutionVentesData = @json($evolutionVentes);
        const repartitionCategorieData = @json($repartitionCategorie);
        const parrainageDataRaw = @json($parrainageData);

        // 1. Graphique Évolution des Ventes
        const ctxEvolution = document.getElementById('evolutionVentesChart').getContext('2d');
        new Chart(ctxEvolution, {
            type: 'line',
            data: {
                labels: evolutionVentesData.map(item => item.date),
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: evolutionVentesData.map(item => item.total_ventes),
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }, {
                    label: 'Nombre de commandes',
                    data: evolutionVentesData.map(item => item.nombre_commandes),
                    borderColor: '#000000',
                    backgroundColor: 'rgba(0, 0, 0, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Graphique Répartition par Catégorie
        const ctxCategorie = document.getElementById('repartitionCategorieChart').getContext('2d');
        const categoryLabels = {
            'plat': 'Plats Principaux',
            'boisson': 'Boissons',
            'dessert': 'Desserts'
        };
        new Chart(ctxCategorie, {
            type: 'doughnut',
            data: {
                labels: repartitionCategorieData.map(item => categoryLabels[item.category] || item.category),
                datasets: [{
                    data: repartitionCategorieData.map(item => item.total_ventes),
                    backgroundColor: ['#eab308', '#000000', '#d97706'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // 3. Programme de Fidélité
        const ctxFidelite = document.getElementById('programmeFideliteChart').getContext('2d');
        new Chart(ctxFidelite, {
            type: 'bar',
            data: {
                labels: ['Points Gagnés', 'Points Utilisés', 'Bonus Parrainage'],
                datasets: [{
                    data: [{{ $pointsGagnes }}, {{ $pointsUtilises }}, {{ $bonusParrainage }}],
                    backgroundColor: ['#eab308', '#000000', '#22c55e'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 4. Système de Parrainage
        const ctxParrainage = document.getElementById('parrainageChart').getContext('2d');
        new Chart(ctxParrainage, {
            type: 'line',
            data: {
                labels: parrainageDataRaw.map(item => item.mois),
                datasets: [{
                    label: 'Parrainages Réussis',
                    data: parrainageDataRaw.map(item => item.total_parrainages),
                    borderColor: '#eab308',
                    backgroundColor: 'rgba(234, 179, 8, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }, {
                    label: 'Parrains Actifs',
                    data: parrainageDataRaw.map(item => item.parrains_actifs),
                    borderColor: '#000000',
                    backgroundColor: 'rgba(0, 0, 0, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <x-footer class="block h-12 w-auto fill-current text-yellow-500" />
</body>
</html>

