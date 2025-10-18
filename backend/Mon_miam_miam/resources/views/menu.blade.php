<x-app-layout>


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

                            <!-- Description (optionnelle) -->
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
                                    onclick="orderPlat({{ $plat->id }})"
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

    <!-- Footer -->
    <footer class="bg-black text-white mt-20">
        <div class="container mx-auto px-4 py-12">


            <!-- Ligne de séparation -->
            <div class="border-t border-gray-700 mb-8"></div>

            <!-- Contenu du footer -->
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="text-gray-400 text-sm">
                    Order.cm ©Copyright {{ date('Y') }}, All Rights Reserved.
                </div>
                <div class="flex items-center space-x-6 text-sm">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        Privacy Policy
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        Terms
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        Pricing
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        Do not share your personal information
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts JavaScript -->
    <script>
        // Filtrer par catégorie
        function filterCategory(category) {
            const dishes = document.querySelectorAll('.dish-card');
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
            dishes.forEach(dish => {
                if (category === 'all') {
                    dish.style.display = 'block';
                } else {
                    const dishCategory = dish.getAttribute('data-category');
                    if (dishCategory === category) {
                        dish.style.display = 'block';
                    } else {
                        dish.style.display = 'none';
                    }
                }
            });
        }

        // Commander un plat
        function orderDish(dishId) {
            // Rediriger vers la page de commande ou ouvrir un modal
            window.location.href = `/order/${dishId}`;
            
            // Ou afficher un message de confirmation
            // alert('Plat ajouté au panier !');
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

            document.querySelectorAll('.dish-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease-out';
                observer.observe(card);
            });
        });
    </script>
</x-app-layout>