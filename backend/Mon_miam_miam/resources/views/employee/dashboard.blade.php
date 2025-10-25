
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Employé - Order.cm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
    <!-- Ajoutez la navbar ici -->
    @include('layouts.employee-navigation')
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
             <h1 class="text-5xl font-bold text-black">
                Bonjour {{ Auth::user()->name }}👋
            </h1>
            <div class="flex items-center gap-4">
                <select id="periodeSelect" class="px-4 py-2 rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-amber-500">
                    <option value="semaine" {{ $periode === 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="mois" {{ $periode === 'mois' ? 'selected' : '' }}>Ce mois</option>
                    <option value="annee" {{ $periode === 'annee' ? 'selected' : '' }}>Cette année</option>
                </select>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                  
                </form>
            </div>
            
        </div>
        

        <!-- Cartes statistiques -->
        <div class="flex items-center space-x-12 ">
            
                </div>
                
                
                
         <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
           
            <!-- Commandes en attente -->
            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-6xl">⏱️</div>
                    <div class="text-right">
                        <div class="text-4xl font-bold" data-stat="commandes_attente">{{ $commandesEnAttente }}</div>
                        <div class="text-sm opacity-90">En attente</div>
                    </div>
                </div>
                <div class="text-lg font-semibold"><a href="commandes">Commandes en attente</a></div>
            </div>

            <!-- Commandes du jour -->
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-6xl">📋</div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">{{ $commandesAujourdhui }}</div>
                        <div class="text-sm opacity-90">Aujourd'hui</div>
                    </div>
                </div>
                <div class="text-lg font-semibold"><a href="commandes">Commandes du jour</a></div>
            </div>

            <!-- Réclamations -->
            <div class="bg-gradient-to-br from-red-400 to-red-500 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-6xl">🚨</div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">{{ $reclamationsNonTraitees }}</div>
                        <div class="text-sm opacity-90">Non traitées</div>
                    </div>
                </div>
                <div class="text-lg font-semibold"><a href="reclamations">Réclamation</a></div>
            </div>

            <!-- Revenu du jour -->
            <div class="bg-gradient-to-br from-amber-400 to-yellow-500 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-6xl">💰</div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">{{ number_format($revenuJour, 0) }}</div>
                        <div class="text-sm opacity-90">FCFA</div>
                    </div>
                </div>
                <div class="text-lg font-semibold">Revenue du jour</div>
            </div>
        </div>

        <!-- Graphiques et Activité -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Statistiques de la période -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Statistiques de la {{ $periode }}</h2>
                <canvas id="statsChart" height="100"></canvas>
            </div>

            <!-- Activité Récente -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Activité Récente</h2>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($activiteRecente as $activite)
                    <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-gray-50 transition cursor-pointer" 
                         onclick="window.location.href='{{ route('employee.commandes.index') }}?commande={{ $activite['id'] }}'">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-2xl
                            {{ $activite['couleur'] === 'green' ? 'bg-green-100' : '' }}
                            {{ $activite['couleur'] === 'yellow' ? 'bg-yellow-100' : '' }}
                            {{ $activite['couleur'] === 'blue' ? 'bg-blue-100' : '' }}
                            {{ $activite['couleur'] === 'red' ? 'bg-red-100' : '' }}
                            {{ $activite['couleur'] === 'purple' ? 'bg-purple-100' : '' }}">
                            {{ $activite['icone'] }}
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800">{{ $activite['type'] }} #{{ $activite['numero'] }}</div>
                            <div class="text-sm text-gray-500">{{ $activite['temps'] }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-gray-400 py-8">
                        Aucune activité récente
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Liens rapides -->
        <div class="mt-8 flex gap-4">
            <a href="{{ route('employee.commandes.index') }}" class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6 hover:shadow-xl transition">
                <div class="text-4xl mb-2">📦</div>
                <div class="text-xl font-bold">Gérer les Commandes</div>
                <div class="text-sm opacity-90">Voir toutes les commandes</div>
            </a>
            <a href="statistiques" class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-6 hover:shadow-xl transition">
                <div class="text-4xl mb-2">📊</div>
                <div class="text-xl font-bold">Statistiques Détaillées</div>
                <div class="text-sm opacity-90">Analyses approfondies</div>
            </a>
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
            </div>
        </div>
    </footer>

    <script>
        // Changement de période
        document.getElementById('periodeSelect').addEventListener('change', function() {
            window.location.href = '{{ route("employee.dashboard") }}?periode=' + this.value;
        });

        // Graphique
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($statistiques['labels']),
                datasets: [{
                    label: 'Commandes',
                    data: Object.values(@json($statistiques['commandes'])),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true,
                }, {
                    label: 'Revenus (x1000 FCFA)',
                    data: Object.values(@json($statistiques['revenus'])).map(v => v / 1000),
                    borderColor: '#1f2937',
                    backgroundColor: 'rgba(31, 41, 55, 0.1)',
                    tension: 0.4,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Auto-refresh toutes les 30 secondes
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>