<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-white">
            Mini-Jeux & Événements
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: #E8DCC8;">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="text-center mb-8">
                <p class="text-lg text-gray-700">Amusez-vous et gagnez des points</p>
            </div>

            <!-- Section Mini-jeux -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Mini-jeux Disponible</h2>
                
                @if($games->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($games as $game)
                        <div class="rounded-3xl shadow-lg overflow-hidden transition-transform hover:scale-105 cursor-pointer" 
                             style="background: {{ $loop->index % 4 == 0 ? 'linear-gradient(135deg, #D4AF37 0%, #C19A2E 100%)' : ($loop->index % 4 == 1 ? '#000000' : ($loop->index % 4 == 2 ? '#FFFFFF' : '#1a1a1a')) }};">
                            
                            <div class="p-6 text-center h-full flex flex-col justify-between" style="min-height: 220px;">
                                <!-- Icône du jeu -->
                                <div class="mb-4">
                                    @if($game->thumbnail)
                                    <img src="{{ asset('storage/' . $game->thumbnail) }}" alt="{{ $game->title }}" class="w-20 h-20 mx-auto object-contain rounded-lg">
                                    @else
                                    <div class="w-20 h-20 mx-auto bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10" style="color: {{ $loop->index % 4 == 2 ? '#000' : '#fff' }};" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                                        </svg>
                                    </div>
                                    @endif
                                </div>

                                <!-- Titre -->
                                <h3 class="text-xl font-bold mb-2" style="color: {{ $loop->index % 4 == 2 ? '#000000' : '#FFFFFF' }};">
                                    {{ $game->title }}
                                </h3>

                                <!-- Points -->
                                <p class="text-sm mb-4" style="color: {{ $loop->index % 4 == 2 ? '#666666' : 'rgba(255,255,255,0.8)' }};">
                                    + {{ $game->points ?? 100 }} pts
                                </p>

                                <!-- Bouton -->
                                <a href="{{ route('games.show', $game->id) }}" 
                                   class="block py-3 px-6 rounded-full font-semibold text-sm uppercase transition-all hover:opacity-90"
                                   style="background-color: {{ $loop->index % 4 == 2 ? '#D4AF37' : ($loop->index % 4 == 0 ? '#000000' : '#D4AF37') }}; color: {{ $loop->index % 4 == 1 ? '#000000' : '#FFFFFF' }};">
                                    Jouez Maintenant
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-3xl shadow-lg p-12 text-center">
                        <svg class="w-24 h-24 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun jeu disponible</h3>
                        <p class="text-gray-500">Les jeux seront bientôt disponibles. Revenez plus tard !</p>
                    </div>
                @endif
            </div>

            <!-- Section Événements à venir (optionnel) -->
            <div class="mb-12">
                <h2 class="text-2xl font-bold mb-6 text-gray-900">Événements à venir</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Exemple d'événement 1 -->
                    <div class="bg-white rounded-3xl shadow-lg p-6 flex items-center hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-1 text-gray-900">Soirée Karaoké</h3>
                            <p class="text-sm text-gray-600">Vendredi dès 20H</p>
                        </div>
                        <button class="py-2 px-6 rounded-full font-semibold text-sm transition-all hover:opacity-90" style="background-color: #D4AF37; color: #000000;">
                            Participer
                        </button>
                    </div>

                    <!-- Exemple d'événement 2 -->
                    <div class="bg-white rounded-3xl shadow-lg p-6 flex items-center hover:shadow-xl transition-shadow">
                        <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg mb-1 text-gray-900">Match de Foot</h3>
                            <p class="text-sm text-gray-600">Samedi 22 Mars</p>
                        </div>
                        <button class="py-2 px-6 rounded-full font-semibold text-sm transition-all hover:opacity-90" style="background-color: #D4AF37; color: #000000;">
                            Réserver place
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black text-white py-8">
        <div class="mx-auto max-w-7xl px-4">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm">Order.cm ©Copyright 2025, All Rights Reserved.</p>
                <div class="flex gap-6 text-sm">
                    <a href="#" class="hover:text-gray-300 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Terms</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Pricing</a>
                    <a href="#" class="hover:text-gray-300 transition-colors">Do not share your personal information</a>
                </div>
            </div>
        </div>
    </footer>
</x-app-layout>