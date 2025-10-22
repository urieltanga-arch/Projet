<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Top 10 clients</h1>
                <p class="text-gray-600">Classements des meilleurs clients</p>
            </div>

            <!-- Filtres de période -->
            <div class="bg-gradient-to-r from-amber-400 to-yellow-500 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('top-clients', ['periode' => 'semaine']) }}" 
                       class="px-8 py-3 rounded-full font-semibold text-lg transition-all {{ $periode === 'semaine' ? 'bg-black text-white' : 'bg-white text-black hover:bg-gray-100' }}">
                        Cette semaine
                    </a>
                    <a href="{{ route('top-clients', ['periode' => 'mois']) }}" 
                       class="px-8 py-3 rounded-full font-semibold text-lg transition-all {{ $periode === 'mois' ? 'bg-black text-white' : 'bg-white text-black hover:bg-gray-100' }}">
                        Ce mois
                    </a>
                </div>
            </div>

            <!-- Podium (Top 3) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @foreach($topClients->take(3) as $index => $client)
                    @if($index === 1)
                        <!-- 1er place (au milieu) -->
                        <div class="md:order-2 bg-gradient-to-br from-yellow-400 to-amber-500 rounded-3xl p-8 text-center shadow-2xl transform hover:scale-105 transition-transform">
                            <div class="text-6xl mb-4">🏆</div>
                            <div class="text-4xl font-bold text-black mb-2">1er</div>
                            <h3 class="text-2xl font-bold text-black mb-1">{{ $client->name }}</h3>
                            <p class="text-black/80 mb-4">{{ $client->commandes_count }} commandes</p>
                            <div class="text-3xl font-bold text-black">{{ number_format($client->total_depense, 0, ',', ' ') }} FCFA</div>
                        </div>
                    @elseif($index === 0)
                        <!-- 2ème place (à gauche) -->
                        <div class="md:order-1 bg-white rounded-3xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                            <div class="text-5xl mb-4">🥈</div>
                            <div class="text-3xl font-bold text-amber-600 mb-2">2 ème</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $client->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $client->commandes_count }} commandes</p>
                            <div class="text-2xl font-bold text-amber-600">{{ number_format($client->total_depense, 0, ',', ' ') }} FCFA</div>
                        </div>
                    @else
                        <!-- 3ème place (à droite) -->
                        <div class="md:order-3 bg-white rounded-3xl p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                            <div class="text-5xl mb-4">🥉</div>
                            <div class="text-3xl font-bold text-amber-600 mb-2">3 ème</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $client->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $client->commandes_count }} commandes</p>
                            <div class="text-2xl font-bold text-amber-600">{{ number_format($client->total_depense, 0, ',', ' ') }} FCFA</div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Classement complet (4-10) -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gray-900 text-white px-6 py-4">
                    <h2 class="text-2xl font-bold">Classement complet</h2>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @forelse($topClients->skip(3) as $index => $client)
                        <div class="flex items-center justify-between p-6 hover:bg-amber-50 transition-colors">
                            <div class="flex items-center space-x-6">
                                <!-- Position -->
                                <div class="bg-black text-white w-12 h-12 rounded-xl flex items-center justify-center font-bold text-xl">
                                    {{ $index + 4 }}
                                </div>
                                
                                <!-- Info client -->
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $client->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $client->commandes_count }} commandes</p>
                                </div>
                            </div>
                            
                            <!-- Montant -->
                            <div class="text-right">
                                <div class="text-2xl font-bold text-amber-600">
                                    {{ number_format($client->total_depense, 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </div>
                    @empty
                        @if($topClients->count() <= 3)
                            <div class="p-8 text-center text-gray-500">
                                <p>Pas d'autres clients dans le classement pour cette période</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

            @if($topClients->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">📊</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Aucune commande pour cette période</h3>
                    <p class="text-gray-600">Le classement sera disponible dès qu'il y aura des commandes.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>