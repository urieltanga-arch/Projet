<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Zeduc-SP@CE - Restaurant & Terrasse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .menu-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .promo-card {
            transition: transform 0.3s ease;
        }
        
        .promo-card:hover {
            transform: scale(1.02);
        }

        .smooth-scroll {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-gray-50 smooth-scroll">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-black/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center">
                         <x-application-logo class="block h-12 w-auto fill-current text-yellow-500" />
                    </div>
                    <span class="ml-3 text-white text-lg font-semibold"></span>
                </div>
                
                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#accueil" class="text-white hover:text-amber-500 transition">Accueil</a>
                    <a href="#menu" class="text-white hover:text-amber-500 transition">Menu</a>
                    <a href="#evenements" class="text-white hover:text-amber-500 transition">Événements</a>
                    <a href="#contact" class="text-white hover:text-amber-500 transition">Contact</a>
                </div>

                <!-- Boutons Login/Register -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-amber-500 transition font-medium">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-amber-500 text-black px-6 py-2 rounded-full font-semibold hover:bg-amber-600 transition">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-amber-500 transition font-medium">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-amber-500 text-black px-6 py-2 rounded-full font-semibold hover:bg-amber-600 transition">
                            Inscription
                        </a>
                    @endauth
                </div>
                
                <!-- Menu Mobile Button -->
                <button id="mobile-menu-button" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-black/95 pb-4">
            <div class="px-4 space-y-2">
                <a href="#accueil" class="block text-white hover:text-amber-500 py-2 transition">Accueil</a>
                <a href="#menu" class="block text-white hover:text-amber-500 py-2 transition">Menu</a>
                <a href="#evenements" class="block text-white hover:text-amber-500 py-2 transition">Événements</a>
                <a href="#contact" class="block text-white hover:text-amber-500 py-2 transition">Contact</a>
                
                <div class="pt-4 border-t border-gray-700 space-y-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-white hover:text-amber-500 py-2 transition">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full bg-amber-500 text-black px-6 py-2 rounded-full font-semibold hover:bg-amber-600 transition text-center">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block text-white hover:text-amber-500 py-2 transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="block bg-amber-500 text-black px-6 py-2 rounded-full font-semibold hover:bg-amber-600 transition text-center">
                            Inscription
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
<section id="accueil" class="hero-section relative flex items-center justify-center text-white px-4 bg-cover bg-center bg-no-repeat min-h-screen" style="background-image: url('https://pokaa.fr/wp-content/uploads/2021/07/chez-ani-cuisine-africaine-8.jpeg');">
    <!-- Overlay pour meilleure lisibilité -->
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    
    <div class="text-center max-w-4xl relative z-10">
        <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in">
            BIENVENUE CHEZ ZEDUC-SP@CE
        </h1>
        <p class="text-xl md:text-2xl mb-8 text-gray-200">
            Restaurant & Terrasse avec vue sur la Dizimba
        </p>
        <a href="#menu" class="inline-block bg-amber-500 text-black px-8 py-4 rounded-full text-lg font-semibold hover:bg-amber-600 transition transform hover:scale-105">
            Découvrir le Menu
        </a>
    </div>
</section>

    <!-- Menu du Jour - 3 plats seulement -->
    <section id="menu" class="py-20 bg-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-4 text-gray-900">Menu du Jour</h2>
            <p class="text-center text-gray-600 mb-12 text-lg">Découvrez nos spécialités camerounaises</p>
            
            <!-- Grid des 3 plats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($plats->take(3) as $plat)
                    <div class="menu-card bg-white rounded-2xl overflow-hidden shadow-lg">
                        <!-- Image du plat -->
                        <div class="h-56 overflow-hidden bg-gradient-to-br {{ getCategoryGradient($plat->category) }}">
                            @if($plat->image_url)
                                <img 
                                    src="{{ $plat->image_url }}" 
                                    alt="{{ $plat->name }}"
                                    class="w-full h-full object-cover"
                                    onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="hidden w-full h-full items-center justify-center text-6xl">
                                    {{ getCategoryEmoji($plat->category) }}
                                </div>
                            @else
                                <div class="flex items-center justify-center h-full text-6xl">
                                    {{ getCategoryEmoji($plat->category) }}
                                </div>
                            @endif
                        </div>
                        
                        <!-- Contenu -->
                        <div class="p-6">
                            <h3 class="text-2xl font-bold mb-2 uppercase">{{ $plat->name }}</h3>
                            
                            @if($plat->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $plat->description }}
                                </p>
                            @endif
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-amber-500 text-2xl font-bold">
                                    {{ number_format($plat->price, 0, ',', ' ') }} FCFA
                                </span>
                                <div class="flex text-amber-500">
                                    ★★★★★
                                </div>
                            </div>
                            
                            @auth
                                <button 
                                    onclick="addToCart({{ $plat->id }})"
                                    class="w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition"
                                >
                                    Commander
                                </button>
                            @else
                                <a 
                                    href="{{ route('login') }}"
                                    class="block w-full bg-black text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition text-center"
                                >
                                    Commander
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <div class="text-6xl mb-4">🍽️</div>
                        <p class="text-gray-500 text-xl">Aucun plat disponible pour le moment</p>
                    </div>
                @endforelse
            </div>

            <!-- Bouton Voir tout le menu -->
            @if($plats->count() > 3)
                <div class="text-center mt-12">
                    <a href="#" class="inline-block bg-black text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-800 transition">
                        Voir tout le menu
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- Promotions -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-12 text-gray-900">Promotions en Cours</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Happy Hour -->
                <div class="promo-card bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl p-8 text-black">
                    <h3 class="text-3xl font-bold mb-4">Happy Hour</h3>
                    <p class="text-lg mb-2">-50% sur toutes les boissons de 17h à 19h</p>
                    <p class="text-sm opacity-80">Du lundi au vendredi</p>
                </div>

                <!-- Menu Famille -->
                <div class="promo-card bg-gradient-to-br from-gray-900 to-black rounded-2xl p-8 text-white">
                    <h3 class="text-3xl font-bold mb-4">Menu Famille</h3>
                    <p class="text-lg mb-2">4 plats + 4 boissons = 25.000 FCFA</p>
                    <p class="text-sm opacity-80">Valable ce weekend</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Événements à Venir -->
    <section id="evenements" class="py-20 bg-amber-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl md:text-5xl font-bold text-center mb-12 text-gray-900">Événements à Venir</h2>
            
            <div class="space-y-6">
                <!-- Événement 1 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg flex flex-col md:flex-row items-center justify-between hover:shadow-xl transition gap-4">
                    <div class="flex items-center space-x-6">
                        <div class="bg-amber-500 text-black rounded-xl p-4 text-center min-w-[80px]">
                            <div class="text-3xl font-bold">15</div>
                            <div class="text-sm">NOV</div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Soirée Karaoké</h3>
                            <p class="text-gray-600">Venez chanter vos tubes préférés dès 20h ce soir</p>
                            <p class="text-sm text-amber-600 mt-1">Entrée gratuite · 20h00</p>
                        </div>
                    </div>
                    <button class="bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition whitespace-nowrap">
                        Réserver
                    </button>
                </div>

                <!-- Événement 2 -->
                <div class="bg-white rounded-2xl p-6 shadow-lg flex flex-col md:flex-row items-center justify-between hover:shadow-xl transition gap-4">
                    <div class="flex items-center space-x-6">
                        <div class="bg-amber-500 text-black rounded-xl p-4 text-center min-w-[80px]">
                            <div class="text-3xl font-bold">22</div>
                            <div class="text-sm">NOV</div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Match de Football</h3>
                            <p class="text-gray-600">Regardez le match en grand écran avec vos amis</p>
                            <p class="text-sm text-amber-600 mt-1">Entrée : 1.000 F</p>
                        </div>
                    </div>
                    <button class="bg-black text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition whitespace-nowrap">
                        Réserver
                    </button>
                </div>
            </div>
        </div>
    </section>


                   
        <x-footer class="block h-12 w-auto fill-current text-yellow-500" />

    <!-- Toast notification -->
    @auth
    <div id="toast" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg transform translate-y-full transition-transform duration-300 z-50">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span id="toast-message">Plat ajouté au panier !</span>
        </div>
    </div>
    @endauth

    <!-- Scripts JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        @auth
        // CSRF Token
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
                    showToast(data.message);
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
        @endauth

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

            document.querySelectorAll('.menu-card').forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.5s ease-out';
                observer.observe(card);
            });
        });
    </script>
</body>
</html>

@php
// Fonctions helper pour les catégories
function getCategoryGradient($category) {
    $gradients = [
        'plats' => 'from-green-700 to-green-900',
        'boissons' => 'from-blue-600 to-blue-800',
        'desserts' => 'from-pink-500 to-pink-700',
    ];
    return $gradients[strtolower($category)] ?? 'from-amber-700 to-amber-900';
}

function getCategoryEmoji($category) {
    $emojis = [
        'plats' => '🍽️',
        'boissons' => '🥤',
        'desserts' => '🍰',
    ];
    return $emojis[strtolower($category)] ?? '🍽️';
}
@endphp