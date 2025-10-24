<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gérant</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5e6d3 0%, #e8d5c4 100%);
            min-height: 100vh;
        }

        .navbar {
            background-color: #1a1a1a;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .navbar .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .navbar .nav-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .navbar .nav-links a:hover {
            color: #d4a574;
        }

        .navbar .user-icon {
            width: 40px;
            height: 40px;
            background: #d4a574;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            color: #1a1a1a;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #666;
        }

        .periode-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .periode-btn {
            padding: 0.5rem 1.5rem;
            border: 2px solid #d4a574;
            background: white;
            color: #1a1a1a;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .periode-btn.active {
            background: #d4a574;
            color: white;
        }

        .alert-box {
            background: linear-gradient(135deg, #ff6b6b, #ff8787);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-icon {
            font-size: 2rem;
        }

        .alert-details p {
            margin-bottom: 0.3rem;
        }

        .voir-details-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .voir-details-btn:hover {
            background: white;
            color: #ff6b6b;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            padding: 2rem;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 160px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.commandes {
            background: linear-gradient(135deg, #ffd93d 0%, #f4c430 100%);
        }

        .stat-card.revenu {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
        }

        .stat-card.clients {
            background: linear-gradient(135deg, #6c97d4 0%, #5a87c2 100%);
            color: white;
        }

        .stat-card.performance {
            background: linear-gradient(135deg, #4a4a4a 0%, #5a5a5a 100%);
            color: white;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #1a1a1a;
            margin-bottom: 1.5rem;
            font-size: 1.3rem;
        }

        .commande-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .commande-item:hover {
            transform: translateX(5px);
        }

        .commande-item.en_attente {
            background: #fff9e6;
        }

        .commande-item.en_preparation {
            background: #e6f3ff;
        }

        .commande-item.prete {
            background: #e6ffe6;
        }

        .commande-item.annulee {
            background: #ffe6e6;
        }

        .commande-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .commande-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .commande-details h4 {
            margin-bottom: 0.3rem;
            color: #1a1a1a;
        }

        .commande-details p {
            font-size: 0.9rem;
            color: #666;
        }

        .commande-montant {
            text-align: right;
        }

        .montant {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1a1a1a;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
            border-radius: 12px;
            margin-top: 0.3rem;
            display: inline-block;
        }

        .equipe-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }

        .membre-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 10px;
            background: #f8f9fa;
            transition: transform 0.3s;
        }

        .membre-card:hover {
            transform: translateY(-5px);
        }

        .membre-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #1a1a1a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .membre-name {
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 0.3rem;
        }

        .membre-role {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }

        .status-indicator.en-ligne {
            background: #4caf50;
        }

        .status-indicator.hors-ligne {
            background: #999;
        }

        .footer {
            background: #1a1a1a;
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .content-grid {
                grid-template-columns: 1fr;
            }
            .equipe-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .equipe-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <span>🍽️</span>
            <span>SEEDUC SPACES</span>
        </div>
        <ul class="nav-links">
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="#">Employé</a></li>
            <li><a href="#">Statistiques</a></li>
            <li><a href="#">Réclamation</a></li>
        </ul>
        <div class="user-icon">👤</div>
    </nav>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Dashboard Gérant</h1>
            <p>Vue d'ensemble en temps réel du restaurant</p>
        </div>

<!-- Sélecteur de période -->
<div class="periode-selector">
    <a href="{{ route('gerant.gerant.dashboard', ['periode' => 'jour']) }}" 
       class="periode-btn {{ ($periode ?? 'jour') === 'jour' ? 'active' : '' }}">
        Jour
    </a>
    <a href="{{ route('gerant.gerant.dashboard', ['periode' => 'semaine']) }}" 
       class="periode-btn {{ ($periode ?? 'jour') === 'semaine' ? 'active' : '' }}">
        Semaine
    </a>
    <a href="{{ route('gerant.gerant.dashboard', ['periode' => 'mois']) }}" 
       class="periode-btn {{ ($periode ?? 'jour') === 'mois' ? 'active' : '' }}">
        Mois
    </a>
</div>

        <!-- Alertes importantes -->
        @if($nombreAlertes > 0)
        <div class="alert-box">
            <div class="alert-content">
                <div class="alert-icon">⚠️</div>
                <div class="alert-details">
                    <p><strong>Alertes importantes</strong></p>
                    <p>{{ $nombreAlertes }} réclamation(s) nécessite(nt) attention - {{ $alertes->first()->type_probleme }}</p>
                </div>
            </div>
            <button class="voir-details-btn" onclick="window.location.href='#'">Voir Détails</button>
        </div>
        @endif

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card commandes">
                <div>
                    <div class="stat-icon">🛒</div>
                    <div class="stat-value">{{ $commandesActives }}</div>
                    <div class="stat-label">En Cours</div>
                </div>
                <div class="stat-label" style="opacity: 0.7;">Commandes Actives</div>
            </div>

            <div class="stat-card revenu">
                <div>
                    <div class="stat-icon">💰</div>
                    <div class="stat-value">{{ number_format($revenuPeriode, 0, ',', ' ') }}</div>
                    <div class="stat-label">FCFA</div>
                </div>
                <div class="stat-label">Revenu du {{ $periode === 'jour' ? 'Jour' : ($periode === 'semaine' ? 'Semaine' : 'Mois') }}</div>
            </div>

            <div class="stat-card clients">
                <div>
                    <div class="stat-icon">👥</div>
                    <div class="stat-value">{{ $clientsActifs }}</div>
                </div>
                <div class="stat-label">Clients Actifs</div>
            </div>

            <div class="stat-card performance">
                <div>
                    <div class="stat-icon">📊</div>
                    <div class="stat-value">{{ $performanceEquipe }}%</div>
                    <div class="stat-label">Efficacité</div>
                </div>
                <div class="stat-label">Performance Équipe</div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Commandes en temps réel -->
            <div class="card">
                <h3>Commandes en Temps Réel</h3>
                @forelse($commandesTempsReel as $commande)
                    <div class="commande-item {{ $commande->status }}">
                        <div class="commande-info">
                            <div class="commande-avatar" style="background: {{ ['#ffd93d', '#6c97d4', '#4caf50', '#ff6b6b'][array_rand([0,1,2,3])] }}">
                                {{ substr($commande->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="commande-details">
                                <h4>{{ $commande->user->name ?? 'Client' }}</h4>
                                <p>{{ $commande->numero_commande }}</p>
                            </div>
                        </div>
                        <div class="commande-montant">
                            <div class="montant">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</div>
                            <span class="status-badge" style="background: 
                                @if($commande->status === 'en_attente') #fff3cd
                                @elseif($commande->status === 'en_preparation') #cfe2ff
                                @elseif($commande->status === 'prete') #d1e7dd
                                @else #f8d7da @endif;">
                                {{ ucfirst(str_replace('_', ' ', $commande->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; color: #999; padding: 2rem;">Aucune commande active</p>
                @endforelse
            </div>

            <!-- Performances Journalières -->
            <div class="card">
                <h3>Performances {{ $periode === 'jour' ? 'Journalières' : ($periode === 'semaine' ? 'Hebdomadaires' : 'Mensuelles') }}</h3>
                <div class="chart-container">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Équipe en Service -->
        <div class="card">
            <h3>Équipe en Service</h3>
            <div class="equipe-grid">
                @foreach($equipeEnService as $membre)
                    <div class="membre-card">
                        <div class="membre-avatar">{{ substr($membre->name, 0, 1) }}</div>
                        <div class="membre-name">{{ $membre->name }}</div>
                        <div class="membre-role">{{ ucfirst($membre->role) }}</div>
                        <div>
                            <span class="status-indicator en-ligne"></span>
                            <span style="font-size: 0.9rem; color: #4caf50;">En ligne</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Order.cm ©Copyright 2025, All Rights Reserved. | Privacy Policy | Terms | Pricing | Do not share your personal information</p>
    </div>

    <script>
        // Graphique de performances
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceData = @json($performancesJournalieres);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: performanceData.labels,
                datasets: [
                    {
                        label: 'Commandes',
                        data: performanceData.commandes,
                        borderColor: '#ffd93d',
                        backgroundColor: 'rgba(255, 217, 61, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Revenus (FCFA)',
                        data: performanceData.revenus,
                        borderColor: '#1a1a1a',
                        backgroundColor: 'rgba(26, 26, 26, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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
    </script>
</body>
</html>