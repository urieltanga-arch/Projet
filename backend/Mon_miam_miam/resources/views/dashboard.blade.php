<x-app-layout>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-12">
                <div class="flex items-center space-x-4 mb-2">
                    <h1 class="text-5xl font-bold text-black">
                        Bonjour {{ Auth::user()->name }}
                    </h1>
                    <span class="text-5xl">👋</span>
                </div>
                <p class="text-gray-700 text-lg">
                    Bienvenue sur votre espace personnel ZEDUC-SP@CE
                </p>
            </div>

            <!-- Cards Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Fidelity Card -->
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-3xl p-8 shadow-lg">
                    <div class="mb-6">
                        <h3 class="text-white text-xl font-semibold mb-1">
                            Points de Fidélité
                        </h3>
                        <p class="text-white/80 text-sm">Votre solde actuel</p>
                    </div>
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <div class="flex items-baseline">
                                <span class="text-white text-6xl font-bold">
                                    {{ auth()->user()->total_points  }}
                                </span>
                                <span class="text-white text-2xl font-semibold ml-2">pts</span>
                            </div>
                        </div>
                        <div class="relative">
                            <span class="text-7xl opacity-30">🪙</span>
                        </div>
                    </div>
                    <button class="w-full bg-black text-white py-4 rounded-full font-semibold text-lg hover:bg-gray-900 transition-colors shadow-lg">
                        Utiliser mes points
                    </button>
                </div>

                <!-- Referral Card -->
                <div class="bg-white rounded-3xl p-8 shadow-lg">
                    <h3 class="text-black text-2xl font-bold mb-4">
                        Code de Parrainage
                    </h3>
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-6 mb-4">
                        <p class="text-gray-600 text-sm mb-2">Votre code unique</p>
                        <p class="text-black text-3xl font-bold tracking-wider">
                            {{ $referralCode ?? strtoupper(explode('@', Auth::user()->email)[0]) . '2025' }}
                        </p>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Partagez votre code et gagnez 10 points par filleul !
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
<!-- Orders Card / Plats Disponibles -->
<div class="bg-white rounded-3xl p-8 shadow-lg">
    <h3 class="text-black text-2xl font-bold mb-6">
        Plats Disponibles
    </h3>
    <div class="space-y-4">
        @if(!empty($plats) && count($plats) > 0)
    @foreach($plats as $plat)
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center shadow-sm overflow-hidden">
                    @if(!empty($plat->image_url))
                        <img 
                            src="{{ $plat->image_url }}" 
                            alt="{{ $plat->name }}"
                            class="w-full h-full object-cover"
                            onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-3xl\'>🍽️</span>';"
                        >
                    @else
                        <span class="text-3xl">🍽️</span>
                    @endif
                </div>
                
                <div class="flex-1">
                    <h4 class="text-lg font-semibold text-gray-800">{{ $plat->name }}</h4>
                    @if($plat->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $plat->description }}</p>
                    @endif
                    <div class="flex items-center space-x-4 mt-2">
                        <span class="text-green-600 font-bold">{{ number_format($plat->price, 0, ',', ' ') }} FCFA</span>
                        @if($plat->points > 0)
                            <span class="text-blue-600 font-semibold">+{{ $plat->points }} pts</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <button 
                    onclick="commanderPlat({{ $plat->id }})"
                    class="bg-gradient-to-r from-yellow-500 to-amber-500 hover:from-yellow-600 hover:to-amber-600 text-black px-6 py-2 rounded-xl font-semibold transition-all shadow-md hover:shadow-lg"
                >
                    Commander
                </button>
            </div>
        </div>
    @endforeach
        @else
            <div class="text-center py-8">
                <div class="text-6xl mb-4">🍽️</div>
                <h4 class="text-xl font-semibold text-gray-600 mb-2">Aucun plat disponible</h4>
                <p class="text-gray-500">Revenez plus tard pour découvrir nos nouveaux plats.</p>
            </div>
        @endif
    </div>
</div>

                <!-- Promotion Card -->
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-3xl p-8 shadow-lg">
                    <h3 class="text-black text-2xl font-bold mb-6">
                        Promotion Activé
                    </h3>
                    <div class="bg-white rounded-2xl p-6 mb-4 relative overflow-hidden">
                        <div class="absolute top-4 right-4 bg-yellow-400 text-black px-3 py-1 rounded-full text-sm font-bold">
                            -30%
                        </div>
                        <h4 class="text-black font-bold text-xl mb-1">Happy Hour</h4>
                        <p class="text-gray-600 text-sm">17H-19H tous les jours</p>
                    </div>
                    <div class="bg-black rounded-2xl p-6">
                        <h4 class="text-white font-bold text-xl mb-2">Ménu Etudiant</h4>
                        <p class="text-gray-300 text-base">
                            Plat + Boisson à <span class="text-yellow-400 font-bold">3500F</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Games Section -->
            <div class="mt-16">
                <h2 class="text-black text-3xl font-bold mb-8">
                    Mini Jeux Disponibles
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $games = [
                            [
                                'id' => 1,
                                'name' => 'Roue de Fortune',
                                'points' => '+ 50-200 pts',
                                'icon' => '🎲',
                                'bgColor' => 'from-yellow-500 to-yellow-600',
                                'textColor' => 'text-white',
                                'buttonBg' => 'bg-black',
                                'buttonText' => 'text-white'
                            ],
                            [
                                'id' => 2,
                                'name' => 'Quiz Cusine',
                                'points' => '+ 100 pts',
                                'icon' => '🧩',
                                'bgColor' => 'from-black to-gray-900',
                                'textColor' => 'text-white',
                                'buttonBg' => 'bg-yellow-500',
                                'buttonText' => 'text-black'
                            ],
                            [
                                'id' => 3,
                                'name' => 'Défi Quotidien',
                                'points' => '+ 150 pts',
                                'icon' => '🏆',
                                'bgColor' => 'from-gray-100 to-gray-200',
                                'textColor' => 'text-black',
                                'buttonBg' => 'bg-black',
                                'buttonText' => 'text-white'
                            ],
                            [
                                'id' => 4,
                                'name' => 'Jeux de mémoire',
                                'points' => '+ 75 pts',
                                'icon' => '🎮',
                                'bgColor' => 'from-black to-gray-900',
                                'textColor' => 'text-white',
                                'buttonBg' => 'bg-yellow-500',
                                'buttonText' => 'text-black'
                            ]
                        ];
                    @endphp

                    @foreach($games as $game)
                        <div class="bg-gradient-to-br {{ $game['bgColor'] }} rounded-3xl p-8 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1 cursor-pointer">
                            <div class="mb-6 flex justify-center">
                                <div class="w-24 h-24 rounded-full bg-white/10 backdrop-blur-sm shadow-lg flex items-center justify-center text-5xl">
                                    {{ $game['icon'] }}
                                </div>
                            </div>
                            <h3 class="{{ $game['textColor'] }} text-xl font-bold text-center mb-2">
                                {{ $game['name'] }}
                            </h3>
                            <button class="w-full {{ $game['buttonBg'] }} {{ $game['buttonText'] }} py-3 rounded-full font-semibold hover:opacity-90 transition-opacity">
                                {{ $game['points'] }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

     <!-- Footer -->
    <footer id="contact" class="bg-black text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">Zeduc-SP@CE</h3>
                    <p class="text-gray-400">Restaurant & Terrasse avec vue sur la Dizimba</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400">📍 Yaoundé, Cameroun</p>
                    <p class="text-gray-400">📞 +237 XXX XXX XXX</p>
                    <p class="text-gray-400">✉️ contact@zeduc-space.cm</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Horaires</h4>
                    <p class="text-gray-400">Lun - Ven : 10h - 23h</p>
                    <p class="text-gray-400">Sam - Dim : 9h - 00h</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 Zeduc-SP@CE. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</x-app-layout>