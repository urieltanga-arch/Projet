<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Order.cm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<!-- Ajoutez la navbar ici -->
    @include('layouts.employee-navigation')
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('employee.dashboard') }}" class="text-amber-600 hover:text-amber-700 mb-2 inline-block">
                    ← Retour au dashboard
                </a>
                <h1 class="text-4xl font-bold text-gray-800">Statistiques</h1>
                <p class="text-gray-600 mt-2">Analyser les performances du restaurant</p>
            </div>
            <div class="flex items-center gap-4">
                <select id="periodeSelect" class="px-4 py-2 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-amber-500">
                    <option value="jour" {{ $periode === 'jour' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="semaine" {{ $periode === 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="mois" {{ $periode === 'mois' ? 'selected' : '' }}>Ce mois</option>
                    <option value="annee" {{ $periode === 'annee' ? 'selected' : '' }}>Cette année</option>
                </select>
                <a href="{{ route('employee.statistiques.export') }}?periode={{ $periode }}" 
                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold">
                    📥 Exporter
                </a>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Commandes totales -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl shadow-lg p-6 text-gray-800">
                <div class="flex items-center justify-between">
                    <div class="text-6xl">📊</div>
                    <div class="text-right">
                        <div class="text-5xl font-bold">{{ $commandesTotales }}</div>
                        <div class="text-sm mt-1 opacity-90">COMMANDES</div>
                    </div>
                </div>
                <div class="mt-4 text-lg font-semibold">Commandes totales</div>
            </div>

            <!-- Chiffre d'affaires -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="text-6xl">💰</div>
                    <div class="text-right">
                        <div class="text-5xl font-bold">{{ number_format($chiffreAffaires / 1000000, 1) }}M</div>
                        <div class="text-sm mt-1 opacity-90">FCFA</div>
                    </div>
                </div>
                <div class="mt-4 text-lg font-semibold">Chiffre d'Affaires</div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Évolution des ventes -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Évolution des Ventes</h2>
                <canvas id="evolutionChart" height="250"></canvas>
            </div>

            <!-- Commandes par jour -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Commandes par Jour</h2>
                <canvas id="commandesJourChart" height="250"></canvas>
            </div>
        </div>

        <!-- Top 5 des plats -->
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Top 5 des plats les plus vendus</h2>
            <div class="space-y-3">
                @forelse($topPlats as $index => $plat)
                <div class="bg-white rounded-xl p-4 flex items-center justify-between hover:shadow-lg transition">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gray-800 text-white rounded-lg flex items-center justify-center text-xl font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg flex items-center justify-center text-3xl">
                                🍽️
                            </div>
                            <div>
                                <div class="font-bold text-gray-800 text-lg">{{ $plat['nom'] }}</div>
                                <div class="text-sm text-gray-600">{{ number_format($plat['prix'], 0, ',', ' ') }} FCFA</div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold text-gray-800 text-lg">{{ $plat['quantite'] }} Vendus</div>
                        <div class="text-sm text-gray-600">{{ number_format($plat['revenus'], 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-700">
                    <p class="text-lg">Aucune donnée disponible pour cette période</p>
                </div>
                @endforelse
            </div>
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
        // Changement de période
        document.getElementById('periodeSelect').addEventListener('change', function() {
            window.location.href = '{{ route("employee.statistiques") }}?periode=' + this.value;
        });

        // Graphique évolution des ventes
        const ctxEvolution = document.getElementById('evolutionChart').getContext('2d');
        const evolutionChart = new Chart(ctxEvolution, {
            type: 'bar',
            data: {
                labels: @json($evolutionVentes['labels']),
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: @json($evolutionVentes['data']),
                    backgroundColor: '#eab308',
                    borderColor: '#ca8a04',
                    borderWidth: 2,
                    borderRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value >= 1000 ? (value / 1000) + 'K' : value;
                            }
                        }
                    }
                }
            }
        });

        // Graphique commandes par jour
        const ctxJour = document.getElementById('commandesJourChart').getContext('2d');
        const commandesJourChart = new Chart(ctxJour, {
            type: 'line',
            data: {
                labels: @json($commandesParJour['labels']),
                datasets: [{
                    label: 'Commandes',
                    data: @json($commandesParJour['data']),
                    borderColor: '#1f2937',
                    backgroundColor: 'rgba(31, 41, 55, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointBackgroundColor: '#1f2937',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>