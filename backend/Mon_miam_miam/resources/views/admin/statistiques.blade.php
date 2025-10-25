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
<x-admin-app-layout>
<body class="bg-[#f5e6d3] font-sans">
    
    {{-- CORRECTION : Ajout des classes de style au header --}}
    <header class="bg-yellow-500 shadow-md">
        @include('components.admin-app-layout')
    </header>

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
</body>
</html>
</x-admin-app-layout>
