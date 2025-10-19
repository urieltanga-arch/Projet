<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            
            {{-- Messages de succès/erreur --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Carte des Points --}}
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-3xl shadow-2xl p-8 mb-8 text-white">
                <h2 class="text-3xl font-bold mb-4">Mes Points de Fidélité</h2>
                <div class="text-6xl font-bold mb-2">{{ number_format($user->total_points) }}</div>
                <p class="text-xl opacity-90">≈ {{ number_format(($user->total_points / 100) * 1000) }} CFA</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                {{-- MON Code de Parrainage --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">🎁 Mon Code de Parrainage</h3>
                    <div class="bg-gradient-to-br from-amber-100 to-orange-100 rounded-lg p-4 text-center mb-4">
                        <p class="text-sm text-gray-600 mb-1">Partagez ce code</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $user->referral_code }}</p>
                    </div>
                    
                    <button onclick="copyCode('{{ $user->referral_code }}')" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition mb-3">
                        📋 Copier le code
                    </button>
                    
                    <a href="https://wa.me/?text=Rejoins-moi avec mon code: {{ $user->referral_code }}" 
                       target="_blank"
                       class="block w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-lg transition text-center">
                        📱 Partager sur WhatsApp
                    </a>
                    
                    <div class="mt-4 text-center text-sm text-gray-600">
                        <p class="font-semibold">Filleuls actifs : {{ $user->active_referrals_count }}</p>
                    </div>
                </div>

                {{-- UTILISER un Code de Parrainage --}}
                @if(!$user->referred_by)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">✨ Utiliser un Code</h3>
                    <p class="text-sm text-gray-600 mb-4">Vous avez un code de parrainage ? Gagnez 50 points !</p>
                    
                    <form action="{{ route('referral.validate') }}" method="POST">
                        @csrf
                        <input type="text" 
                               name="referral_code" 
                               placeholder="Entrez le code" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg mb-3 uppercase"
                               required>
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold py-3 rounded-lg transition">
                            Valider le code
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">✅ Code Utilisé</h3>
                    <div class="bg-green-100 rounded-lg p-4 text-center">
                        <p class="text-sm text-gray-600 mb-1">Vous avez été parrainé</p>
                        <p class="text-green-600 font-bold text-2xl">✓ Activé</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- Liste des Filleuls --}}
            @if($referrals->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4">👥 Mes Filleuls ({{ $referrals->count() }})</h3>
                <div class="space-y-3">
                    @foreach($referrals as $referral)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold">{{ $referral->referred->name ?? 'En attente' }}</p>
                                <p class="text-sm text-gray-500">{{ $referral->created_at->format('d/m/Y') }}</p>
                            </div>
                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm font-semibold">
                                +{{ $referral->points_earned }} pts
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Historique des Points --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold mb-4">📊 Historique</h3>
                <div class="space-y-3">
                    @forelse($history as $point)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold">{{ $point->description }}</p>
                                <p class="text-sm text-gray-500">{{ $point->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <span class="text-2xl font-bold {{ $point->points > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $point->points > 0 ? '+' : '' }}{{ $point->points }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Aucun historique</p>
                    @endforelse
                </div>
                
                <div class="mt-4">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyCode(code) {
            navigator.clipboard.writeText(code);
            alert('✅ Code copié : ' + code);
        }
    </script>
</x-app-layout>