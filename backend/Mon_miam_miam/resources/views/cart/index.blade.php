<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            
            <!-- Message de succès -->
            <div id="successMessage" class="hidden mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl relative">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">✅</span>
                    <div>
                        <p class="font-bold text-lg" id="successText"></p>
                        <p class="text-sm">Votre commande a été enregistrée avec succès !</p>
                    </div>
                </div>
                <button onclick="document.getElementById('successMessage').classList.add('hidden')" class="absolute top-4 right-4 text-green-700 hover:text-green-900">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>

            <div class="mb-8">
                <h1 class="text-5xl font-bold text-black">Mon Panier</h1>
                <p class="text-gray-600 mt-2">Vérifiez votre commande avant de valider</p>
            </div>

            @if(empty($cart))
                <!-- Panier vide -->
                <div class="bg-white rounded-3xl shadow-lg p-12 text-center">
                    <div class="text-8xl mb-4">🛒</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Votre panier est vide</h2>
                    <p class="text-gray-600 mb-6">Ajoutez des plats délicieux à votre panier !</p>
                    <a href="{{ route('menu') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-8 py-3 rounded-full transition-colors">
                        Voir le menu
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Liste des articles -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cart as $item)
                            <div class="bg-white rounded-3xl shadow-lg p-6">
                                <div class="flex items-center gap-6">
                                    <!-- Image -->
                                    <div class="flex-shrink-0 w-32 h-32 rounded-2xl overflow-hidden bg-amber-50">
                                        @if($item['image_url'])
                                            <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-5xl">🍽️</div>
                                        @endif
                                    </div>

                                    <!-- Détails -->
                                    <div class="flex-1">
                                        <h3 class="text-2xl font-bold text-black mb-2">{{ $item['name'] }}</h3>
                                        <p class="text-yellow-600 font-bold text-xl">
                                            {{ number_format($item['price'], 0, ',', ' ') }} FCFA
                                        </p>
                                        @if(isset($item['points']) && $item['points'] > 0)
                                            <p class="text-sm text-green-600 mt-1">
                                                +{{ $item['points'] }} pts / unité
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Contrôles quantité -->
                                    <div class="flex items-center gap-4">
                                        <button 
                                            onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                            class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 text-black font-bold text-xl transition-colors flex items-center justify-center"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}
                                        >
                                            -
                                        </button>
                                        <span class="text-2xl font-bold w-12 text-center" id="quantity-{{ $item['id'] }}">
                                            {{ $item['quantity'] }}
                                        </span>
                                        <button 
                                            onclick="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                            class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 text-black font-bold text-xl transition-colors flex items-center justify-center"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <!-- Prix total de l'article -->
                                    <div class="text-2xl font-bold text-black">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                                    </div>

                                    <!-- Bouton supprimer -->
                                    <button 
                                        onclick="removeFromCart({{ $item['id'] }})"
                                        class="text-red-500 hover:text-red-700 transition-colors ml-4"
                                    >
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <!-- Bouton vider le panier -->
                        <div class="text-center pt-4">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vider le panier ?')">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-lg transition-colors">
                                    Vider le panier
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Résumé de la commande -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-3xl shadow-lg p-8 sticky top-4">
                            <h2 class="text-3xl font-bold text-black mb-8">Résumé</h2>

                            <div class="space-y-6 mb-8">
                                <div class="flex justify-between items-center text-lg">
                                    <span class="text-gray-600">Sous-total</span>
                                    <span class="font-bold text-black">{{ number_format($montant_total, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="flex justify-between items-center text-lg">
                                    <span class="text-gray-600">Frais de livraison</span>
                                    <span class="font-bold text-black">Gratuit</span>
                                </div>
                                <div class="border-t-2 border-gray-200 pt-6 flex justify-between items-center">
                                    <span class="text-2xl font-bold text-black">Total</span>
                                    <span class="text-3xl font-bold text-yellow-600">{{ number_format($montant_total, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>

                            <!-- Points de fidélité -->
                            @php
                                $montant_total = array_sum(array_map(function($item) {
                                    return ($item['points'] ?? 0) * $item['quantity'];
                                }, $cart));
                            @endphp
                            @if($montant_total > 0)
                                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        <span class="text-green-800 font-semibold">
                                            Vous gagnerez {{ $montant_total }} points de fidélité
                                        </span>
                                    </div>
                                </div>
                            @endif

                            <!-- Bouton Valider la commande -->
                            <button onclick="openCheckoutModal()" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-full transition-colors shadow-lg text-lg mb-4">
                                Valider la commande
                            </button>

                            <a href="{{ route('menu') }}" class="block text-center text-gray-600 hover:text-black font-semibold transition-colors">
                                ← Continuer mes achats
                            </a>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>

    <!-- Modal de checkout -->
    <div id="checkoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-black">Finaliser la commande</h2>
                    <button onclick="closeCheckoutModal()" class="text-gray-500 hover:text-black">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Formulaire -->
                <form id="checkoutForm" class="space-y-6">
                    @csrf

                    <!-- Adresse de livraison -->
                    <div>
                        <label class="block text-lg font-bold text-black mb-2">
                            📍 Adresse de livraison *
                        </label>
                        <input 
                            type="text" 
                            name="adresse_livraison" 
                            id="adresse_livraison"
                            required
                            placeholder="Ex: Quartier Mimboman, près de la pharmacie"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all"
                        >
                        <p class="text-sm text-red-500 mt-1 hidden" id="error_adresse"></p>
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block text-lg font-bold text-black mb-2">
                            📱 Numéro de téléphone *
                        </label>
                        <input 
                            type="tel" 
                            name="telephone" 
                            id="telephone"
                            required
                            placeholder="Ex: 6 XX XX XX XX"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all"
                        >
                        <p class="text-sm text-red-500 mt-1 hidden" id="error_telephone"></p>
                    </div>

                    <!-- Mode de paiement -->
                    <div>
                        <label class="block text-lg font-bold text-black mb-3">
                            💳 Mode de paiement *
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-yellow-500 transition-all">
                                <input type="radio" name="mode_paiement" value="cash" checked class="w-5 h-5 text-yellow-500">
                                <span class="ml-3 flex items-center gap-2">
                                    <span class="text-2xl">💵</span>
                                    <span class="font-semibold">Paiement à la livraison (Cash)</span>
                                </span>
                            </label>
                            
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-yellow-500 transition-all">
                                <input type="radio" name="mode_paiement" value="mobile_money" class="w-5 h-5 text-yellow-500">
                                <span class="ml-3 flex items-center gap-2">
                                    <span class="text-2xl">📱</span>
                                    <span class="font-semibold">Mobile Money (Orange Money, MTN)</span>
                                </span>
                            </label>
                            
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-xl cursor-pointer hover:border-yellow-500 transition-all">
                                <input type="radio" name="mode_paiement" value="carte" class="w-5 h-5 text-yellow-500">
                                <span class="ml-3 flex items-center gap-2">
                                    <span class="text-2xl">💳</span>
                                    <span class="font-semibold">Carte bancaire</span>
                                </span>
                            </label>
                        </div>
                        <p class="text-sm text-red-500 mt-1 hidden" id="error_paiement"></p>
                    </div>

                    <!-- Instructions spéciales -->
                    <div>
                        <label class="block text-lg font-bold text-black mb-2">
                            📝 Instructions spéciales (optionnel)
                        </label>
                        <textarea 
                            name="instructions" 
                            id="instructions"
                            rows="3"
                            placeholder="Ex: Sonner deux fois, livrer au 2ème étage..."
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition-all resize-none"
                        ></textarea>
                    </div>

 

                    <!-- Message d'erreur général -->
                    <div id="formError" class="hidden bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
                        <p class="font-semibold" id="formErrorMessage"></p>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4">
                        <button 
                            type="button" 
                            onclick="closeCheckoutModal()"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-bold py-4 rounded-full transition-colors"
                        >
                            Annuler
                        </button>
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="flex-1 bg-black hover:bg-gray-800 text-white font-bold py-4 rounded-full transition-colors shadow-lg"
                        >
                            Confirmer la commande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Ouvrir le modal
        function openCheckoutModal() {
            document.getElementById('checkoutModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fermer le modal
        function closeCheckoutModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            // Réinitialiser le formulaire
            document.getElementById('checkoutForm').reset();
            // Cacher les messages d'erreur
            document.querySelectorAll('[id^="error_"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('formError').classList.add('hidden');
        }

        // Soumettre le formulaire de checkout
        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const formError = document.getElementById('formError');
            
            // Désactiver le bouton
            submitBtn.disabled = true;
            submitBtn.textContent = 'Traitement en cours...';
            formError.classList.add('hidden');
            
            // Cacher les erreurs précédentes
            document.querySelectorAll('[id^="error_"]').forEach(el => el.classList.add('hidden'));
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("cart.checkout") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Fermer le modal
                    closeCheckoutModal();
                    
                    // Afficher le message de succès
                    const successMessage = document.getElementById('successMessage');
                    const successText = document.getElementById('successText');
                    successText.textContent = data.message;
                    successMessage.classList.remove('hidden');
                    
                    // Faire défiler vers le haut
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Recharger la page après 2 secondes
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    // Afficher les erreurs de validation
                    if (data.errors) {
                        for (const [field, messages] of Object.entries(data.errors)) {
                            const errorElement = document.getElementById(`error_${field}`);
                            if (errorElement) {
                                errorElement.textContent = messages[0];
                                errorElement.classList.remove('hidden');
                            }
                        }
                    } else {
                        formError.classList.remove('hidden');
                        document.getElementById('formErrorMessage').textContent = data.message || 'Une erreur est survenue';
                    }
                    
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Confirmer la commande';
                }
            } catch (error) {
                console.error('Erreur:', error);
                formError.classList.remove('hidden');
                document.getElementById('formErrorMessage').textContent = 'Une erreur est survenue. Veuillez réessayer.';
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirmer la commande';
            }
        });

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

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('checkoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCheckoutModal();
            }
        });
    </script>

</x-app-layout>