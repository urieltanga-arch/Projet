<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-yellow-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h1 class="text-4xl font-bold text-black mb-8">📝 Finaliser ma commande</h1>

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('commandes.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Résumé de la commande -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-black mb-4">Récapitulatif</h2>
                    
                    <div class="space-y-3">
                        @foreach($panier as $item)
                            <div class="flex justify-between items-center py-2 border-b">
                                <div>
                                    <p class="font-semibold">{{ $item['name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $item['quantite'] }} × {{ number_format($item['prix'], 0, ',', ' ') }} FCFA</p>
                                </div>
                                <p class="font-bold text-yellow-600">{{ number_format($item['sous_total'], 0, ',', ' ') }} FCFA</p>
                            </div>
                        @endforeach

                        <div class="pt-4 flex justify-between text-xl font-bold text-black">
                            <span>Total</span>
                            <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                        </div>

                        @if($totalPoints > 0)
                            <div class="flex justify-between text-green-600 font-semibold">
                                <span>Points fidélité gagnés</span>
                                <span>+{{ $totalPoints }} pts</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-black mb-4">Informations de contact</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="telephone" class="block text-sm font-semibold text-gray-700 mb-2">
                                Téléphone
                            </label>
                            <input 
                                type="tel" 
                                name="telephone" 
                                id="telephone"
                                value="{{ old('telephone', auth()->user()->phone ?? '') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="Ex: +237 6XX XXX XXX"
                            >
                            @error('telephone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="adresse_livraison" class="block text-sm font-semibold text-gray-700 mb-2">
                                Adresse de livraison (optionnel)
                            </label>
                            <textarea 
                                name="adresse_livraison" 
                                id="adresse_livraison"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="Entrez votre adresse complète"
                            >{{ old('adresse_livraison') }}</textarea>
                            @error('adresse_livraison')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instructions" class="block text-sm font-semibold text-gray-700 mb-2">
                                Instructions spéciales (optionnel)
                            </label>
                            <textarea 
                                name="instructions" 
                                id="instructions"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                placeholder="Ex: Sans piment, bien cuit, etc."
                            >{{ old('instructions') }}</textarea>
                            @error('instructions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Mode de paiement -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-2xl font-bold text-black mb-4">Mode de paiement</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-500 transition">
                            <input 
                                type="radio" 
                                name="mode_paiement" 
                                value="especes" 
                                class="w-5 h-5 text-yellow-600"
                                {{ old('mode_paiement') == 'especes' ? 'checked' : '' }}
                            >
                            <div class="ml-4">
                                <p class="font-semibold text-black">💵 Espèces</p>
                                <p class="text-sm text-gray-600">Paiement à la livraison</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-500 transition">
                            <input 
                                type="radio" 
                                name="mode_paiement" 
                                value="mobile_money" 
                                class="w-5 h-5 text-yellow-600"
                                {{ old('mode_paiement') == 'mobile_money' ? 'checked' : '' }}
                            >
                            <div class="ml-4">
                                <p class="font-semibold text-black">📱 Mobile Money</p>
                                <p class="text-sm text-gray-600">Orange Money, MTN Mobile Money (Bientôt disponible)</p>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-500 transition">
                            <input 
                                type="radio" 
                                name="mode_paiement" 
                                value="carte" 
                                class="w-5 h-5 text-yellow-600"
                                {{ old('mode_paiement') == 'carte' ? 'checked' : '' }}
                            >
                            <div class="ml-4">
                                <p class="font-semibold text-black">💳 Carte bancaire</p>
                                <p class="text-sm text-gray-600">Visa, Mastercard (Bientôt disponible)</p>
                            </div>
                        </label>
                    </div>

                    @error('mode_paiement')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-4">
                    <a href="{{ route('panier.index') }}" class="flex-1 bg-gray-200 text-black text-center px-6 py-4 rounded-full font-bold hover:bg-gray-300 transition">
                        ← Retour au panier
                    </a>
                    
                    <button type="submit" class="flex-1 bg-black text-white px-6 py-4 rounded-full font-bold hover:bg-gray-800 transition">
                        Confirmer la commande →
                    </button>
                </div>

            </form>

        </div>
        
    </div>
            <x-footer class="block h-12 w-auto fill-current text-yellow-500" />

    
</x-app-layout>
