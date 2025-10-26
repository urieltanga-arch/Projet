<nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-black border-b border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            <!-- Logo à gauche -->
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-12 w-auto fill-current text-yellow-500" />
                </a>
            </div>

            <!-- Navigation Links au centre -->
            <div class="hidden md:flex space-x-8 flex-1 justify-center">
                <a href="{{ route('dashboard') }}" 
                   class="text-white hover:text-yellow-500 px-3 py-2 text-lg font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-yellow-500' : '' }}">
                    Dashboard
                </a>
                
                <a href="{{ route('menu') }}" 
                   class="text-white hover:text-yellow-500 px-3 py-2 text-lg font-medium transition-colors {{ request()->routeIs('menu') ? 'text-yellow-500' : '' }}">
                    Menu
                </a>
                
                <a href="{{ route('loyalty.simple') }}" 
                   class="text-white hover:text-yellow-500 px-3 py-2 text-lg font-medium transition-colors {{ request()->routeIs('loyalty.simple') ? 'text-yellow-500' : '' }}">
                    Fidélité
                </a>

                <a href="{{ route('historique.index') }}" 
                   class="text-white hover:text-yellow-500 px-3 py-2 text-lg font-medium transition-colors {{ request()->routeIs('historique.index') ? 'text-yellow-500' : '' }}">
                    Historique
                </a>
                
                
                
                <a href="{{ route('games.index') }}" 
                   class="text-white hover:text-yellow-500 px-3 py-2 text-lg font-medium transition-colors {{ request()->routeIs('games.index') ? 'text-yellow-500' : '' }}">
                    Jeux
                </a>
                
                <a href="{{ route('top-clients') }}" 
                   class="text-white hover:text-yellow-500 px-3 py-2 text-lg font-medium transition-colors">
                    Top 10
                </a>
            </div>

            <!-- Points et Panier à droite -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Points -->
                <div class="flex items-center bg-yellow-500 text-black px-4 py-2 rounded-full font-bold">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ auth()->user()->total_points }}pts
                </div>
                
                <!-- Panier -->
                <a href="{{ route('cart.index') }}" class="relative">
                    <svg class="w-8 h-8 text-white hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @php
                        $cartCount = array_sum(array_column(session()->get('cart', []), 'quantity'));
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-yellow-500 text-black text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- User Dropdown -->
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-white hover:text-yellow-500 transition-colors">
                        <span class="text-lg font-medium">{{ Auth::user()->name }}</span>
                        <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div x-show="userMenuOpen" 
                         @click.away="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50"
                         style="display: none;">
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                            Profile
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-yellow-500 transition-colors p-2">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="md:hidden bg-gray-900"
         style="display: none;">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <!-- Points affichés en haut sur mobile -->
            <div class="flex items-center justify-center bg-yellow-500 text-black px-4 py-3 rounded-full font-bold mb-4">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                {{ auth()->user()->total_points }}pts
            </div>

            <a href="{{ route('dashboard') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-yellow-500' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('menu') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('menu') ? 'bg-gray-800 text-yellow-500' : '' }}">
                Menu
            </a>
            <a href="{{ route('cart.index') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('cart.index') ? 'bg-gray-800 text-yellow-500' : '' }}">
                <div class="flex items-center justify-between">
                    <span>Panier</span>
                    @if($cartCount > 0)
                        <span class="bg-yellow-500 text-black text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                </div>
            </a>
            <a href="{{ route('loyalty.simple') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('loyalty.simple') ? 'bg-gray-800 text-yellow-500' : '' }}">
                Fidélité
            </a>
            @if(Route::has('historique.index'))
                <a href="{{ route('historique.index') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg">
                    Historique
                </a>
            @endif
            <a href="{{ route('games.index') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('games.index') ? 'bg-gray-800 text-yellow-500' : '' }}">
                Jeux
            </a>
            <a href="{{ route('top-clients') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg">
                Top 10
            </a>
            
            @if(in_array(Auth::user()->role, ['employee', 'manager', 'admin']))
                <div class="border-t border-gray-700 my-2 pt-2">
                    <a href="{{ route('employee.menu.index') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg">
                        Gestion Menu
                    </a>
                    <a href="{{ route('employee.dashboard') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg">
                        Gestion Commandes
                    </a>
                </div>
            @endif
            
            <div class="border-t border-gray-700 pt-4 pb-1 mt-4">
                <div class="px-3 text-white font-medium text-lg">{{ Auth::user()->name }}</div>
                <div class="px-3 text-gray-400 text-sm mb-2">{{ Auth::user()->email }}</div>
                <a href="{{ route('profile.edit') }}" class="block text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg">
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-white hover:text-yellow-500 px-3 py-2 text-base font-medium rounded-lg">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>