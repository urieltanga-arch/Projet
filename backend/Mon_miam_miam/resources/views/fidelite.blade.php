<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- En-tête --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Programme de Fidélité</h1>
                <p class="text-gray-600 mt-2">Gagnez des points à chaque commande</p>
            </div>

            {{-- Points disponibles --}}
            <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-2xl shadow-xl p-8 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-white text-lg font-semibold mb-2">Points disponible</p>
                        <p class="text-white text-5xl font-bold">{{ number_format($totalPoints, 0, ',', ' ') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-full p-4">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

                {{-- Barre de progression --}}
                @if($nextReward)
                <div class="mt-6">
                    <div class="flex justify-between text-white text-sm mb-2">
                        <span>Prochain niveau: {{ $nextReward->points_required }} pts</span>
                        <span>{{ number_format($progressPercentage, 0) }}%</span>
                    </div>
                    <div class="w-full bg-white bg-opacity-30 rounded-full h-3 overflow-hidden">
                        <div class="bg-white h-full rounded-full transition-all duration-500" 
                             style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Règles du programme --}}
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Règles du programme</h2>
                    
                    <div class="space-y-4">
                        <div class="bg-gradient-to-r from-amber-100 to-orange-100 rounded-xl p-6 flex items-start gap-4">
                            <div class="bg-amber-500 rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">100pt = 1000 CFA</p>
                                <p class="text-sm text-gray-600 mt-1">Conversion automatique</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-amber-100 to-orange-100 rounded-xl p-6 flex items-start gap-4">
                            <div class="bg-amber-500 rounded-full p-3 flex-shrink-0">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">100points = 1000F CFA</p>
                                <p class="text-sm text-gray-600 mt-1">Réduction sur commande</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Prochaines récompenses --}}
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Prochaines Récompenses</h2>
                    
                    <div class="space-y-4">
                        @foreach($rewards->take(2) as $reward)
                        <div class="bg-black text-white rounded-xl p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg mb-1">
                                        @if($reward->type === 'free_drink')
                                            Boisson Gratuite
                                        @elseif($reward->type === 'main_dish')
                                            Plat Principal
                                        @else
                                            Réduction
                                        @endif
                                    </h3>
                                    <p class="text-sm text-gray-400">{{ $reward->description ?? 'Offre spéciale restaurant' }}</p>
                                </div>
                                <div class="bg-amber-500 text-white px-4 py-2 rounded-lg font-bold text-sm">
                                    {{ $reward->points_required }}pts
                                </div>
                            </div>
                            
                            @if($totalPoints >= $reward->points_required)
                                <button class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg transition">
                                    Échanger maintenant
                                </button>
                            @else
                                <div class="text-gray-400 text-sm">
                                    Encore {{ $reward->points_required - $totalPoints }} points nécessaires
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Historique des points --}}
            <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Historique des Points</h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                                <th class="text-left py-3 px-4 font-semibold text-gray-700">Description</th>
                                <th class="text-right py-3 px-4 font-semibold text-gray-700">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pointsHistory as $point)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 px-4 text-gray-600">
                                    {{ $point->created_at->format('d/m/Y') }}
                                </td>
                                <td class="py-4 px-4 text-gray-900">
                                    {{ $point->description }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <span class="font-bold {{ $point->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $point->points > 0 ? '+' : '' }}{{ $point->points }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-gray-500">
                                    Aucun historique de points disponible
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>