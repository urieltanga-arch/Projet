<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-black">Mon Panier</h1>
                <p class="text-gray-600 mt-2">Vérifiez votre commande avant de valider</p>
            </div>

            @if(empty($cart))
                <!-- Panier vide -->
                <div class="bg-white rounded-3xl shadow-lg p-12 text-center">
                    <div class="text-8xl mb-4">🛒</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Votre panier est vide</h2>
                    <p class="text-gray-600 mb-6">Ajoutez des plats délicieux à votre panier !</p>
                    <a href="{{ route('student.menu') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-8 py-3 rounded-full transition-colors">
                        Voir le menu
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Liste des articles -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cart as $item)
                            <div class="bg-white rounded-2xl shadow-lg p-6 flex items-center space-x-6">
                                <!-- Image -->
                                <div class="flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden bg-amber-50">
                                    @if($item['image_url'])
                                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-4xl">🍽️</div>
                                    @endif
                                </div>

                                <!-- Détails -->
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold text-black">{{ $item['name'] }}</h3>
                                    <p class="text-yellow-600 font-bold text-lg mt-1">
                                        {{ number_format($item['price'], 0, ',', ' ') }} FCFA
                                    </p>
                                    @if($item['points'] > 0)
                                        <p class="text-sm text-green-600 mt-1">
                                            +{{ $item['points'] }} pts / unité
                                        </p>
                                    @endif
                                </div>

                                <!-- Quantité -->
                                <div class="flex items-center space-x-3">
                                    <button 
                                        onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                        class="bg-gray-200 hover:bg-gray-300 text-black font-bold w-8 h-8 rounded-full transition-colors"
                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                    >
                                        -
                                    </button>
                                    <span class="text-xl font-bold w-8 text-center" id="quantity-{{ $item['id'] }}">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button 
                                        onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                        class="bg-gray-200 hover:bg-gray-300 text-black font-bold w-8 h-8 rounded-full transition-colors"
                                    >
                                        +
                                    </button>
                                </div>

                                <!-- Sous-total -->
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-black">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                                    </p>
                                </div>

                                <!-- Bouton supprimer -->
                                <button 
                                    onclick="removeFromCart({{ $item['id'] }})"
                                    class="text-red-500 hover:text-red-700 transition-colors"
                                >
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach

                        <!-- Bouton vider le panier -->
                        <div class="text-center pt-4">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vider le panier ?')">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 font-semibold transition-colors">
                                    Vider le panier
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Résumé de la commande -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-4">
                            <h2 class="text-2xl font-bold text-black mb-6">Résumé</h2>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span>Sous-total</span>
                                    <span class="font-semibold">{{ number_format($montant_total, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Frais de livraison</span>
                                    <span class="font-semibold">Gratuit</span>
                                </div>
                                <div class="border-t pt-3 flex justify-between text-black">
                                    <span class="text-xl font-bold">Total</span>
                                    <span class="text-2xl font-bold text-yellow-600">{{ number_format($montant_total, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>

                            <!-- Points de fidélité -->
                            @php
                                $totalPoints = array_sum(array_map(function($item) {
                                    return ($item['points'] ?? 0) * $item['quantity'];
                                }, $cart));
                            @endphp
                            @if($totalPoints > 0)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-green-800 font-semibold">
                                            Vous gagnerez {{ $totalPoints }} points de fidélité
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Bouton Valider la commande -->
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-full transition-colors shadow-lg">
                                    Valider la commande
                                </button>
                            </form>

                            <a href="{{ route('student.menu') }}" class="block text-center text-gray-600 hover:text-black font-semibold mt-4 transition-colors">
                                ← Continuer mes achats
                            </a>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Mettre à jour la quantité
        function updateQuantity(platId, newQuantity) {
            if (newQuantity < 1) return;

            fetch(`/panier/update/${platId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ quantity: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recharger la page pour mettre à jour les totaux
                    location.reload();
                }
            })
            .catch(error => console.error('Erreur:', error));
        }

        // Retirer du panier
        function removeFromCart(platId) {
            if (!confirm('Êtes-vous sûr de vouloir retirer cet article ?')) return;

            fetch(`/panier/remove/${platId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erreur:', error));
        }
    </script>

</x-app-layout>