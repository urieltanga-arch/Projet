<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Programme de Fidélité</h1>
                <p class="text-gray-600 mt-2">Gérez vos points de fidélité et suivez votre historique</p>
            </div>
            
            {{-- Carte des Points --}}
            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-3xl shadow-2xl p-8 mb-8 text-white">
                <h2 class="text-3xl font-bold mb-4">Points de Fidélité</h2>
                <div class="text-6xl font-bold mb-2">{{ number_format($user->total_points) }}</div>
                <p class="text-xl opacity-90">≈ {{ number_format(($user->total_points / 100) * 1000) }} CFA</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                {{-- Code de Parrainage --}}
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4">Code de Parrainage</h3>
                    <div class="bg-yellow-100 rounded-lg p-4 text-center mb-4">
                        <p class="text-3xl font-bold text-gray-800">{{ $user->referral_code }}</p>
                    </div>
                    <button onclick="copyCode('{{ $user->referral_code }}')" 
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition">
                        Copier le code
                    </button>
                </div>

                {{-- QR Code --}}
                <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                    <h3 class="text-xl font-bold mb-4">QR Code</h3>
                    <div class="bg-yellow-400 rounded-lg p-4 inline-block">
                        <div class="bg-white p-3 rounded">
                            {!! QrCode::size(150)->generate($user->referral_code) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Historique --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-bold mb-4">Historique</h3>
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
            alert('Code copié : ' + code);
        }
    </script>
</x-app-layout>