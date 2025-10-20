<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            💳 Finaliser ma commande
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('orders.store') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf

                <!-- Formulaire de commande -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Informations de livraison -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">📍 Informations de livraison</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Adresse de livraison (optionnel)
                                </label>
                                <input 
                                    type="text" 
                                    id="delivery_address" 
                                    name="delivery_address" 
                                    value="{{ old('delivery_address') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                    placeholder="Ex: Quartier Bastos, Yaoundé"
                                >
                                @error('delivery_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Téléphone (optionnel)
                                </label>
                                <input 
                                    type="tel" 
                                    id="phone" 
                                    name="phone" 
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                    placeholder="Ex: +237 6XX XX XX XX"
                                >
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Instructions spéciales (optionnel)
                                </label>
                                <textarea 
                                    id="notes" 
                                    name="notes" 
                                    rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent"
                                    placeholder="Allergies, préférences de cuisson, etc."
                                >{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Récapitulatif des articles -->
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">🛒 Récapitulatif de la commande</h3>
                        
                        <div class="space-y-3">
                            @foreach($cart as $item)
                                <div class="flex items-center justify-between py-3 border-b">
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-amber-50 flex-shrink-0">
                                            @if($item['image_url'])
                                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="flex items-center justify-center h-full text-2xl">🍽️</div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
                                            <p class="text-sm text-gray-600">Quantité: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <p class="font-bold text-gray-800">
                                        {{ number_format($item['price'] * $item['quantity'], 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Résumé et paiement -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">💰 Résumé</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Sous-total</span>
                                <span class="font-semibold">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                            </div>
                            
                            @if($totalPoints > 0)
                                <div class="flex justify-between text-green-600 text-sm">
                                    <span>Points fidélité</span>
                                    <span class="font-semibold">+{{ $totalPoints }} pts</span>
                                </div>
                            @endif

                            <div class="border-t pt-3">
                                <div class="flex justify-between text-2xl font-bold text-gray-800">
                                    <span>Total</span>
                                    <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Méthode de paiement (factice) -->
                        <div class="bg-gray-50 rounded-lg p-4 mb-6">
                            <p class="text-sm text-gray-600 mb-2">💳 Mode de paiement</p>
                            <p class="text-xs text-gray-500 italic">
                                Le système de paiement sera disponible prochainement. 
                                Votre commande sera enregistrée et vous pourrez payer à la livraison.
                            </p>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full bg-black text-white px-6 py-4 rounded-full font-semibold hover:bg-gray-800 transition-colors mb-3"
                        >
                            Confirmer la commande
                        </button>

                        <a 
                            href="{{ route('cart.index') }}" 
                            class="block w-full bg-gray-100 text-gray-800 text-center px-6 py-3 rounded-full font-semibold hover:bg-gray-200 transition-colors"
                        >
                            Retour au panier
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>