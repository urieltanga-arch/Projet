<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="mb-8">
                <a href="{{ route('commandes.index') }}" class="text-gray-600 hover:text-black transition-colors inline-flex items-center mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Retour à mes commandes
                </a>
                
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-bold text-black">Commande #{{ $commande->id }}</h1>
                        <p class="text-gray-600 mt-2">Passée le {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    
                    <!-- Statut -->
                    <div>
                        @php
                            $statusColors = [
                                'en_attente' => 'bg-yellow-100 text-yellow-800',
                                'en_preparation' => 'bg-blue-100 text-blue-800',
                                'prete' => 'bg-green-100 text-green-800',
                                'livree' => 'bg-purple-100 text-purple-800',
                                'annulee' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'en_attente' => 'En attente',
                                'en_preparation' => 'En préparation',
                                'prete' => 'Prête',
                                'livree' => 'Livrée',
                                'annulee' => 'Annulée',
                            ];
                        @endphp
                        <span class="px-6 py-3 rounded-full font-semibold text-sm {{ $statusColors[$commande->status] }}">
                            {{ $statusLabels[$commande->status] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Message de succès -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Détails de la commande -->
            <div class="bg-white rounded-3xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-black mb-6">Articles commandés</h2>
                
                <div class="space-y-4">
                    @foreach($commande->items as $item)
                        <div class="flex items-center space-x-6 pb-4 border-b border-gray-200 last:border-0">
                            <!-- Image -->
                            <div class="flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden bg-amber-50">
                                @if($item->plat->image_url)
                                    <img src="{{ $item->plat->image_url }}" alt="{{ $item->plat->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full text-3xl">🍽️</div>
                                @endif
                            </div>

                            <!-- Détails -->
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-black">{{ $item->plat->name }}</h3>
                                <p class="text-gray-600">Quantité : {{ $item->quantity }}</p>
                                <p class="text-yellow-600 font-semibold">
                                    {{ number_format($item->price, 0, ',', ' ') }} FCFA / unité
                                </p>
                            </div>

                            <!-- Sous-total -->
                            <div class="text-right">
                                <p class="text-xl font-bold text-black">
                                    {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="mt-8 pt-6 border-t-2 border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-black">Total</span>
                        <span class="text-3xl font-bold text-yellow-600">
                            {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    @if($commande->points_earned > 0)
                        <div class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-green-800 font-semibold">
                                    Vous avez gagné {{ $commande->points_earned }} points de fidélité
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($commande->status === 'en_attente')
                <div class="flex justify-center">
                    <form action="{{ route('commandes.cancel', $commande) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold px-8 py-3 rounded-full transition-colors">
                            Annuler la commande
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>