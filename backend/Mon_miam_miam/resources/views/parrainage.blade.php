<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- En-tête --}}
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">Programme de Parrainage</h1>
                <p class="text-gray-600 mt-2">Invitez vos amis et gagnez des points</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Carte de parrainage --}}
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Votre code personnel</h2>
                    
                    <div class="bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl p-8 text-center mb-6">
                        <p class="text-sm text-gray-600 mb-2">Code Parrainage</p>
                        <p class="text-4xl font-bold text-gray-900 tracking-wider">{{ $user->referral_code }}</p>
                    </div>

                    {{-- QR Code --}}
                    <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl p-8 flex items-center justify-center mb-6">
                        <div class="bg-white p-4 rounded-lg">
                            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" class="w-48 h-48">
                        </div>
                    </div>

                    {{-- Boutons de partage --}}
                    <div class="grid grid-cols-2 gap-4">
                        <a href="https://wa.me/?text=Rejoins-moi sur notre restaurant avec mon code de parrainage: {{ $user->referral_code }}%20{{ route('register', ['ref' => $user->referral_code]) }}" 
                           target="_blank"
                           class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center gap-2 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            WhatsApp
                        </a>
                        <button onclick="copyCode()" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg transition">
                            Copier le code
                        </button>
                    </div>
                </div>

                {{-- Statistiques --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Vos statistiques</h2>
                        
                        <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-xl p-6 mb-6">
                            <p class="text-white text-sm mb-1">Filleuls</p>
                            <p class="text-white text-4xl font-bold">{{ $activeReferrals }}</p>
                        </div>

                        <div class="bg-gradient-to-r from-blue-400 to-indigo-500 rounded-xl p-6">
                            <p class="text-white text-sm mb-1">Points gagnés</p>
                            <p class="text-white text-4xl font-bold">{{ $totalPointsEarned }}</p>
                        </div>
                    </div>

                    {{-- Liste des filleuls --}}
                    <div class="bg-white rounded-2xl shadow-lg p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Vos filleuls</h3>
                        <div class="space-y-3">
                            @forelse($referrals->where('status', 'active') as $referral)
                                <div class="bg-black text-white rounded-lg p-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold">{{ $referral->referred->name ?? 'Utilisateur' }}</p>
                                        <p class="text-sm text-gray-400">+{{ $referral->points_earned }}pts</p>
                                    </div>
                                    <span class="text-green-400 text-sm font-semibold">Actif</span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Aucun filleul pour le moment</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyCode() {
            const code = "{{ $user->referral_code }}";
            navigator.clipboard.writeText(code).then(() => {
                alert('Code copié dans le presse-papiers !');
            });
        }
    </script>
</x-app-layout>