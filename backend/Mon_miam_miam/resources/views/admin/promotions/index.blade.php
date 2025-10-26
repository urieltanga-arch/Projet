<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promotions & Événements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f5e6d3; /* Couleur de fond beige de la maquette */
            font-family: 'Georgia', serif;
        }
    </style>
</head>
<x-admin-app-layout>
<body class="flex flex-col min-h-screen">
  

    {{-- Contenu principal --}}
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-6 md:py-8">
            {{-- Header --}}
            <div class="mb-6 md:mb-8">
                <h1 class="text-3xl md:text-4xl font-bold mb-2 text-gray-800">Promotions & Événements</h1>
                <p class="text-gray-600 text-sm md:text-base">Gérez les promotions, événements et mini-jeux</p>
            </div>

            {{-- Messages de succès --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Boutons d'ajout rapide (style maquette) --}}
            <div class="flex justify-end gap-3 mb-6">
                <a href="{{ route('admin.promotions.create') }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-5 py-2.5 rounded-full flex items-center gap-2 transition-colors duration-200 shadow-md hover:shadow-lg">
                    <span class="text-lg">+</span>
                    <span>Promotion</span>
                </a>
                <a href="{{ route('admin.events.create') }}" 
                   class="bg-black hover:bg-gray-800 text-white font-semibold px-5 py-2.5 rounded-full flex items-center gap-2 transition-colors duration-200 shadow-md hover:shadow-lg">
                    <span class="text-lg">📅</span>
                    <span>Événement</span>
                </a>
                <button disabled 
                        class="bg-yellow-100 text-gray-400 font-semibold px-5 py-2.5 rounded-full cursor-not-allowed flex items-center gap-2 opacity-80 shadow-md">
                    <span class="text-lg">🎮</span>
                    <span>Mini-jeux</span>
                </button>
            </div>

            {{-- Onglets de filtrage (style maquette) --}}
            <div class="bg-white rounded-2xl p-2 inline-flex gap-2 shadow-md mb-8">
                <a href="{{ route('admin.promotions.index', ['filter' => 'all']) }}" 
                   class="px-8 py-3 rounded-xl font-semibold transition-all duration-200 {{ (!isset($filter) || $filter === 'all') ? 'bg-black text-white' : 'bg-yellow-600 text-black hover:bg-yellow-700' }}">
                    Tous
                </a>
                <a href="{{ route('admin.promotions.index', ['filter' => 'promotion']) }}" 
                   class="px-8 py-3 rounded-xl font-semibold transition-all duration-200 {{ (isset($filter) && $filter === 'promotion') ? 'bg-black text-white' : 'bg-yellow-600 text-black hover:bg-yellow-700' }}">
                    Promotion
                </a>
                <a href="{{ route('admin.promotions.index', ['filter' => 'evenement']) }}" 
                   class="px-8 py-3 rounded-xl font-semibold transition-all duration-200 {{ (isset($filter) && $filter === 'evenement') ? 'bg-black text-white' : 'bg-yellow-600 text-black hover:bg-yellow-700' }}">
                    Événement
                </a>
                <button disabled 
                        class="px-8 py-3 rounded-xl font-semibold bg-yellow-600 text-gray-800 opacity-60 cursor-not-allowed">
                    Mini-jeux
                </button>
            </div>

            {{-- Grille de cartes (Logique de filtre corrigée) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
                {{-- 1. Affichage: TOUS --}}
                @if(!isset($filter) || $filter === 'all')
                    
                    {{-- Boucle des promotions --}}
                    @foreach($promotions as $promotion)
                        @include('admin.promotions.partials.promotion-card', ['promotion' => $promotion])
                    @endforeach

                    {{-- Boucle des événements --}}
                    @foreach($events as $event)
                        @include('admin.promotions.partials.event-card', ['event' => $event])
                    @endforeach

                    {{-- Cas où TOUT est vide --}}
                    @if($promotions->isEmpty() && $events->isEmpty())
                        <div class="col-span-full bg-white rounded-lg shadow-lg p-8 md:p-12 text-center">
                            <div class="text-6xl md:text-7xl mb-4">🤷</div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Rien à afficher</h3>
                            <p class="text-gray-600 mb-4">Aucune promotion ni événement n'est actuellement disponible.</p>
                            <a href="{{ route('admin.promotions.create') }}" 
                               class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 py-2 rounded-lg mt-4 transition-colors">
                                Créer une promotion
                            </a>
                        </div>
                    @endif

                {{-- 2. Affichage: PROMOTIONS UNIQUEMENT --}}
                @elseif($filter === 'promotion')
                    @forelse($promotions as $promotion)
                        @include('admin.promotions.partials.promotion-card', ['promotion' => $promotion])
                    @empty
                        <div class="col-span-full bg-white rounded-lg shadow-lg p-8 md:p-12 text-center">
                            <div class="text-6xl md:text-7xl mb-4">🎯</div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Aucune promotion disponible</h3>
                            <p class="text-gray-600 mb-4">Créez votre première promotion pour attirer plus de clients</p>
                            <a href="{{ route('admin.promotions.create') }}" 
                               class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-semibold px-6 py-2 rounded-lg mt-4 transition-colors">
                                Créer une promotion
                            </a>
                        </div>
                    @endforelse

                {{-- 3. Affichage: ÉVÉNEMENTS UNIQUEMENT --}}
                @elseif($filter === 'evenement')
                    @forelse($events as $event)
                        @include('admin.promotions.partials.event-card', ['event' => $event])
                    @empty
                        <div class="col-span-full bg-white rounded-lg shadow-lg p-8 md:p-12 text-center">
                            <div class="text-6xl md:text-7xl mb-4">📅</div>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">Aucun événement disponible</h3>
                            <p class="text-gray-600 mb-4">Créez votre premier événement pour engager votre communauté</p>
                            <a href="{{ route('admin.events.create') }}" 
                               class="inline-block bg-black hover:bg-gray-800 text-white font-semibold px-6 py-2 rounded-lg mt-4 transition-colors">
                                Créer un événement
                            </a>
                        </div>
                    @endforelse
                @endif
            </div>

            {{-- Pagination (Logique mise à jour) --}}
            @if((!isset($filter) || $filter === 'all' || $filter === 'promotion') && $promotions->hasPages())
                <div class="mt-6">
                    {{ $promotions->links() }}
                </div>
            @endif

            @if($filter === 'evenement' && $events->hasPages())
                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            @endif

            {{-- Statistiques rapides --}}
            @if(!isset($filter) || $filter === 'all')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-yellow-600">{{ $promotions->count() }}</div>
                                <div class="text-gray-600 mt-1">Promotions actives</div>
                            </div>
                            <div class="text-5xl">🎯</div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-3xl font-bold text-black">{{ $events->count() }}</div>
                                <div class="text-gray-600 mt-1">Événements à venir</div>
                            </div>
                            <div class="text-5xl">📅</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    
    <!-- Footer -->
        <x-footer class="block h-12 w-auto fill-current text-yellow-500" />

    
</body>
</html>

</x-admin-app-layout>