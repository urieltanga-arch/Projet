<x-app-layout>
    <!-- Icône Panier dans la navigation (à ajouter dans ton layout principal) -->
    <div class="fixed top-4 right-20 z-50">
        <a href="{{ route('cart.index') }}" class="relative">
            <div class="bg-yellow-500 hover:bg-yellow-600 text-black rounded-full p-3 shadow-lg transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span id="cart-count" class="absolute -top-2 -right-2 bg-black text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                    {{ array_sum(array_column(session()->get('cart', []), 'quantity')) }}
                </span>
            </div>
        </a>
    </div>

    <div class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- Tabs de catégories -->
            <div class="flex flex-wrap gap-3 mb-8 justify-center">
                <button 
                    onclick="filterCategory('all')" 
                    id="tab-all"
                    class="category-tab active px-8 py-3 rounded-full font-semibold text-lg transition-all bg-black text-white"
                >
                    TOUS
                </button>
                <button 
                    onclick="filterCategory('plats')" 
                    id="tab-plats"
                    class="category-tab px-8 py-3 rounded-full font-semibold text-lg transition-all bg-gradient-to-r from-yellow-500 to-amber-500 text-black"
                >
                    Plats
                </button>
                <button 
                    onclick="filterCategory('boissons')" 
                    id="tab-boissons"
                    class="category-tab px-8 py-3 rounded-full font-semibold text-lg transition-all bg-gradient-to-r from-yellow-500 to-amber-500 text-black"
                >
                    Boissons
                </button>
                <button 
                    onclick="filterCategory('desserts')" 
                    id="tab-desserts"
                    class="category-tab px-8 py-3 rounded-full font-semibold text-lg transition-all bg-gradient-to-r from-yellow-500 to-amber-500 text-black"
                >
                    Desserts
                </button>
            </div>

            <!-- Grid des plats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="dishes-grid">
                @forelse($plats as $plat)
                    <div class="plat-card bg-white rounded-3xl shadow-lg overflow-hidden transform transition-all hover:scale-105 hover:shadow-2xl" data-category="{{ strtolower($plat->category) }}">
                        <!-- Image du plat -->
                        <div class="relative h-56 overflow-hidden bg-amber-50">
                            @if($plat->image_url)
                                <img 
                                    src="{{ $plat->image_url }}" 
                                    alt="{{ $plat->name }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-6xl\'>🍽️</div>';"
                                >
                            @else
                                <div class="flex items-center justify-center h-full text-6xl">🍽️</div>
                            @endif
                        </div>

                        <!-- Contenu de la carte -->
                        <div class="p-6">
                            <!-- Nom du plat -->
                            <h3 class="text-2xl font-bold text-black mb-2 uppercase">
                                {{ $plat->name }}
                            </h3>

                            <!-- Description -->
                            @if($plat->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $plat->description }}
                                </p>
                            @endif

                            <!-- Prix et bouton -->
                            <div class="flex items-center justify-between mt-4">
                                <div>
                                    <p class="text-3xl font-bold text-yellow-600">
                                        {{ number_format($plat->price, 0, ',', ' ') }} FCFA
                                    </p>
                                    @if($plat->points > 0)
                                        <p class="text-sm text-green-600 font-semibold mt-1">
                                            +{{ $plat->points }} pts fidélité
                                        </p>
                                    @endif
                                </div>

                                <!-- Bouton Commander -->
                                <button 
                                    onclick="addToCart({{ $plat->id }})"
                                    class="bg-black text-white px-6 py-3 rounded-full font-semibold hover:bg-gray-800 transition-colors"
                                >
                                    Commander
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="text-6xl mb-4">🍽️</div>
                        <p class="text-gray-500 text-xl">Aucun plat disponible pour le moment</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform translate-y-full transition-transform duration-300 z-50">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span id="toast-message">Plat ajouté au panier !</span>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        // CSRF Token pour les requêtes AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Ajouter au panier
        function addToCart(platId) {
            fetch(`/panier/add/${platId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le compteur du panier
                    document.getElementById('cart-count').textContent = data.cartCount;
                    
                    // Afficher le toast
                    showToast(data.message);
                } else {
                    // Afficher l'erreur
                    showToast(data.message, 'error');
                    
                    // Optionnel: masquer le plat du menu s'il n'est plus disponible
                    const platCard = document.querySelector(`[onclick="addToCart(${platId})"]`).closest('.plat-card');
                    if (platCard) {
                        platCard.style.opacity = '0.5';
                        platCard.style.pointerEvents = 'none';
                        const button = platCard.querySelector('button');
                        button.textContent = 'Indisponible';
                        button.classList.remove('bg-black', 'hover:bg-gray-800');
                        button.classList.add('bg-gray-400', 'cursor-not-allowed');
                    }
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showToast('Erreur lors de l\'ajout au panier', 'error');
            });
        }

        // Afficher le toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            
            if (type === 'error') {
                toast.classList.remove('bg-green-500');
                toast.classList.add('bg-red-500');
            } else {
                toast.classList.remove('bg-red-500');
                toast.classList.add('bg-green-500');
            }
            
            toast.style.transform = 'translateY(0)';
            
            setTimeout(() => {
                toast.style.transform = 'translateY(150%)';
            }, 3000);
        }

        // Filtrer par catégorie
        function filterCategory(category) {
            const plats = document.querySelectorAll('.plat-card');
            const tabs = document.querySelectorAll('.category-tab');

            // Mettre à jour les onglets
            tabs.forEach(tab => {
                tab.classList.remove('active', 'bg-black', 'text-white');
                tab.classList.add('bg-gradient-to-r', 'from-yellow-500', 'to-amber-500', 'text-black');
            });

            const activeTab = document.getElementById(`tab-${category}`);
            activeTab.classList.remove('bg-gradient-to-r', 'from-yellow-500', 'to-amber-500', 'text-black');
            activeTab.classList.add('active', 'bg-black', 'text-white');

            // Filtrer les plats
            plats.forEach(plat => {
                if (category === 'all') {
                    plat.style.display = 'block';
                } else {
                    const platCategory = plat.getAttribute('category');
                    if (platCategory === category) {
                        plat.style.display = 'block';
                    } else {
                        plat.style.display = 'none';
                    }
                }
            });
        }

        // Animation au scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            document.querySelectorAll('.plat-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease-out';
                observer.observe(card);
            });
        });
    </script>
        <!-- Footer -->
            <x-footer class="block h-12 w-auto fill-current text-yellow-500" />

    
</x-app-layout>