<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision des Réclamations - Zeduc Spaces</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        .stat-card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .reclamation-card {
            transition: all 0.3s;
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .reclamation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .status-badge {
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .action-btn {
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .filter-btn {
            padding: 0.5rem 1.5rem;
            border: 2px solid #d4a574;
            background: white;
            color: #1a1a1a;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .filter-btn.active {
            background: #d4a574;
            color: white;
        }

        .search-box {
            position: relative;
            margin-bottom: 2rem;
        }

        .search-box input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #d4a574;
            box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
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
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                        Dashboard
                    </a>
                    <a href="{{ route('gerant.employees.index') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                        Employés
                    </a>
                    <a href="{{ route('gerant.statistiques') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                        Statistiques
                    </a>
                    <a href="{{ route('gerant.reclamations.index') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors bg-yellow-500 text-black">
                        Réclamations
                    </a>
                </div>
                <div class="hidden md:flex items-center">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-yellow-500 hover:text-yellow-400 transition-colors focus:outline-none">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <div x-show="userMenuOpen"
                             @click.away="userMenuOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50"
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name ?? 'Gérant' }}</p>
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
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 2rem;">
        <!-- Header -->
        <div style="margin-bottom: 2rem;">
            <h1 style="color: #1a1a1a; font-size: 2.5rem; margin-bottom: 0.5rem; font-weight: bold;">
                Supervision des Réclamations
            </h1>
            <p style="color: #666; font-size: 1.1rem;">Contrôle et validation des réponses employés</p>
        </div>

        <!-- Période Selector -->
        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('gerant.reclamations.index', ['periode' => 'jour']) }}" 
               class="filter-btn {{ ($periode ?? 'jour') === 'jour' ? 'active' : '' }}">
                Aujourd'hui
            </a>
            <a href="{{ route('gerant.reclamations.index', ['periode' => 'semaine']) }}" 
               class="filter-btn {{ ($periode ?? 'jour') === 'semaine' ? 'active' : '' }}">
                Cette Semaine
            </a>
            <a href="{{ route('gerant.reclamations.index', ['periode' => 'mois']) }}" 
               class="filter-btn {{ ($periode ?? 'jour') === 'mois' ? 'active' : '' }}">
                Ce Mois
            </a>
        </div>

        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Total Réclamations -->
            <div class="stat-card" style="background: linear-gradient(135deg, #ff6b6b, #ff8787); color: white; padding: 1.5rem; border-radius: 15px;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⚠️</div>
                <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.3rem;">{{ $stats['total'] }}</div>
                <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.2rem;">Total</div>
                <div style="font-size: 1rem; font-weight: 600;">Réclamations Actives</div>
            </div>

            <!-- En Attente -->
            <div class="stat-card" style="background: linear-gradient(135deg, #ffd93d, #f4c430); padding: 1.5rem; border-radius: 15px;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">⏰</div>
                <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.3rem;">{{ $stats['en_attente'] }}</div>
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.2rem;">En attente</div>
                <div style="font-size: 1rem; font-weight: 600;">Validation Requise</div>
            </div>

            <!-- En Traitement -->
            <div class="stat-card" style="background: linear-gradient(135deg, #6c97d4, #5a87c2); color: white; padding: 1.5rem; border-radius: 15px;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👥</div>
                <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.3rem;">{{ $stats['en_traitement'] }}</div>
                <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.2rem;">Traitement</div>
                <div style="font-size: 1rem; font-weight: 600;">Réponses Employés</div>
            </div>

            <!-- Traitées -->
            <div class="stat-card" style="background: linear-gradient(135deg, #4a90e2, #357abd); color: white; padding: 1.5rem; border-radius: 15px;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">✅</div>
                <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.3rem;">{{ $stats['traitees'] }}</div>
                <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.2rem;">En service</div>
                <div style="font-size: 1rem; font-weight: 600;">Traitées</div>
            </div>

            <!-- Résolues -->
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50, #45a049); color: white; padding: 1.5rem; border-radius: 15px;">
                <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📈</div>
                <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.3rem;">{{ $stats['resolues'] }}</div>
                <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.2rem;">Ce mois</div>
                <div style="font-size: 1rem; font-weight: 600;">Résolues</div>
            </div>
        </div>

        <!-- Réclamations List -->
        <div>
            @forelse($reclamations ?? [] as $reclamation)
            <div class="reclamation-card">
                <!-- Header -->
                <div style="display: flex; align-items: start; justify-content: space-between; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ff6b6b, #ff8787); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                            📋
                        </div>
                        <div>
                            <h3 style="font-size: 1.3rem; font-weight: bold; margin-bottom: 0.3rem; color: #1a1a1a;">
                                {{ $reclamation->type_probleme ?? 'Réclamation' }}
                            </h3>
                            <p style="color: #666; font-size: 0.95rem;">
                                {{ $reclamation->commande->user->name ?? 'Client' }} · 
                                {{ $reclamation->created_at->format('d M, H:i') }}
                            </p>
                        </div>
                    </div>
                    <span class="status-badge" style="background: 
                        @if($reclamation->statut === 'non_traitee') #fff9e6; color: #f4c430;
                        @elseif($reclamation->statut === 'en_cours') #e6f3ff; color: #4a90e2;
                        @elseif($reclamation->statut === 'resolue') #e6ffe6; color: #4caf50;
                        @else #f0f0f0; color: #666;
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $reclamation->statut ?? 'Non traitée')) }}
                    </span>
                </div>

                <!-- Content -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <!-- Réclamation Client -->
                    <div>
                        <h4 style="font-weight: 600; margin-bottom: 1rem; color: #1a1a1a; font-size: 1.1rem;">
                            📝 Réclamation Client
                        </h4>
                        <div style="background: linear-gradient(135deg, #fff9e6, #fffbf0); padding: 1.2rem; border-radius: 12px; border: 2px solid #ffd93d;">
                            <div style="font-weight: 600; margin-bottom: 0.8rem; color: #1a1a1a;">
                                🔴 Réclamation envoyée
                            </div>
                            <p style="font-size: 0.95rem; margin-bottom: 0.8rem; line-height: 1.6; color: #333;">
                                {{ $reclamation->description ?? 'Aucune description disponible' }}
                            </p>
                            <p style="font-size: 0.85rem; color: #666;">
                                {{ $reclamation->commande->user->name ?? 'Client' }} - {{ $reclamation->created_at->format('d M, H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Réponse Employé -->
                    <div>
                        <h4 style="font-weight: 600; margin-bottom: 1rem; color: #1a1a1a; font-size: 1.1rem;">
                            💬 Réponse Employé
                        </h4>
                        <div style="background: linear-gradient(135deg, #e6f3ff, #f0f7ff); padding: 1.2rem; border-radius: 12px; border: 2px solid #6c97d4;">
                            <div style="font-weight: 600; margin-bottom: 0.8rem; color: #1a1a1a;">
                                @if($reclamation->reponse_employee)
                                    ✅ Réponse soumise
                                @else
                                    ⏳ En attente de réponse
                                @endif
                            </div>
                            <p style="font-size: 0.95rem; margin-bottom: 0.8rem; line-height: 1.6; color: #333;">
                                {{ $reclamation->reponse_employee ?? 'Aucune réponse pour le moment. L\'équipe analyse la situation...' }}
                            </p>
                            <p style="font-size: 0.85rem; color: #666;">
                                En attente d'assignation
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div style="display: flex; justify-content: flex-end; gap: 0.8rem;">
                    @if($reclamation->statut === 'non_traitee' || $reclamation->statut === 'en_cours')
                    <form method="POST" action="{{ route('gerant.reclamations.valider', $reclamation->id) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="action-btn" style="background: linear-gradient(135deg, #4a90e2, #357abd); color: white;">
                            ✅ Valider
                        </button>
                    </form>
                    @endif

                    @if($reclamation->statut === 'en_cours')
                    <form method="POST" action="{{ route('gerant.reclamations.resoudre', $reclamation->id) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="action-btn" style="background: linear-gradient(135deg, #4caf50, #45a049); color: white;">
                            🎉 Marquer comme résolue
                        </button>
                    </form>
                    @endif

                    @if($reclamation->statut === 'resolue')
                    <span style="padding: 0.6rem 1.5rem; background: #e6ffe6; color: #4caf50; border-radius: 25px; font-weight: 600;">
                        ✅ Résolue
                    </span>
                    @endif
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
                <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                <h3 style="font-size: 1.5rem; color: #1a1a1a; margin-bottom: 0.5rem;">Aucune réclamation</h3>
                <p style="color: #666;">Aucune réclamation à afficher pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <x-footer class="block h-12 w-auto fill-current text-yellow-500" />
</body>
</html>