<nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-black border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-10 w-auto fill-current text-yellow-500" />
                </a>
            </div>

            <div class="hidden md:flex space-x-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.employees.index') }}"
                   class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('admin.employees.*') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                    Employé
                </a>

                <a href="{{ route('employee.menu.index') }}"
                   class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('employee.menu.*') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                    Menu
                </a>

                 <a href="{{ route('reclamations.index') }}"
    class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('reclamations.*') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
    Réclamation
</a>

                <a href="{{ route('admin.promotions.index') }}"
                   class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                    Promotion
                </a>

                <a href="{{ route('admin.statistiques') }}"
                   class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                    Statistiques
                </a>

                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                    Configuration
                </a>

   
            </div>

            <div class="hidden md:flex items-center">
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-yellow-500 hover:text-yellow-400 transition-colors focus:outline-none">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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

                        <div class="px-4 py-2 border-b border-gray-200">
                            <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">Administrateur</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                            Mon Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-yellow-500 transition-colors p-2 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

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

            <div class="flex items-center gap-3 pb-3 mb-3 border-b border-gray-700">
                <div class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">Administrateur</p>
                </div>
            </div>

            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.employees.index') }}"
               class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('admin.employees.*') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                Employé
            </a>

            <a href="{{ route('employee.menu.index') }}"
               class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('employee.menu.*') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                Menu
            </a>
            
            <a href="{{ route('reclamations.index') }}"
   class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('reclamations.*') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
    Réclamation
</a>

            <a href="{{ route('admin.promotions.index') }}"
               class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">
                Promotion
            </a>

            <a href="{{ route('admin.statistiques') }}"
               class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">
                Statistiques
            </a>

            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">
                Configuration
            </a>

            

            <div class="border-t border-gray-700 pt-3 mt-3">
                <a href="{{ route('profile.edit') }}"
                   class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">
                    Mon Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>