<nav x-data="{ open: false }" class="bg-black border-b border-gray-800 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('employee.dashboard') }}" class="flex items-center space-x-3">
                    <div class="w-14 h-14 rounded-full border-2 border-amber-500 flex items-center justify-center bg-gradient-to-br from-amber-500 to-yellow-600">
                        <span class="text-black font-bold text-xl">O</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-white text-xl font-bold tracking-wide">Order.cm</span>
                        <span class="text-xs bg-blue-600 text-white px-2 py-0.5 rounded-full w-fit">Employé</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links - Desktop -->
            <div class="hidden md:flex items-center space-x-1">
                <a href="{{ route('employee.dashboard') }}" class="px-6 py-2 text-white hover:text-amber-500 transition-colors duration-200 text-sm font-medium {{ request()->routeIs('employee.dashboard') ? 'text-amber-500 border-b-2 border-amber-500' : '' }}">
                    Dashboard
                </a>
                
                <a href="{{ route('employee.dashboard') }}" class="px-6 py-2 text-white hover:text-amber-500 transition-colors duration-200 text-sm font-medium relative {{ request()->routeIs('employee.commandes') ? 'text-amber-500 border-b-2 border-amber-500' : '' }}">
                    Commandes
                    @if(isset($commandesEnAttente) && $commandesEnAttente > 0)
                        <span class="absolute -top-1 -right-1 bg-yellow-400 text-black text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $commandesEnAttente }}
                        </span>
                    @endif
                </a>
                
                <a href="{{ route('employee.menu.index') }}" class="px-6 py-2 text-white hover:text-amber-500 transition-colors duration-200 text-sm font-medium {{ request()->routeIs('employee.menu.index') ? 'text-amber-500 border-b-2 border-amber-500' : '' }}">
                    Menu
                </a>
                
                <a href="{{ route('employee.dashboard') }}" class="px-6 py-2 text-white hover:text-amber-500 transition-colors duration-200 text-sm font-medium relative {{ request()->routeIs('employee.reclamations') ? 'text-amber-500 border-b-2 border-amber-500' : '' }}">
                    Réclamation
                    @if(isset($reclamationsNonTraitees) && $reclamationsNonTraitees > 0)
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $reclamationsNonTraitees }}
                        </span>
                    @endif
                </a>
                
                <a href="{{ route('employee.dashboard') }}" class="px-6 py-2 text-white hover:text-amber-500 transition-colors duration-200 text-sm font-medium {{ request()->routeIs('employee.statistiques') ? 'text-amber-500 border-b-2 border-amber-500' : '' }}">
                    Statistiques
                </a>
            </div>

            <!-- User Profile Dropdown - Desktop -->
            <div class="hidden md:flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-2 px-4 py-2 rounded-full bg-gray-800 hover:bg-gray-700 transition-colors duration-200">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-white text-sm font-medium">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-600">{{ Auth::user()->email }}</p>
                        </div>
                        
                        <x-dropdown-link :href="route('profile.edit')">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Mon Profil
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('employee.dashboard')">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Paramètres
                        </x-dropdown-link>

                        <div class="border-t border-gray-200"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <svg class="w-4 h-4 mr-2 inline text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="text-red-600">Déconnexion</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button - Mobile -->
            <div class="flex md:hidden">
                <button @click="open = ! open" class="text-white hover:text-amber-500 focus:outline-none p-2">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden bg-gray-900">
        <div class="px-4 pt-2 pb-3 space-y-1">
            <a href="{{ route('employee.dashboard') }}" class="block px-4 py-3 text-white hover:bg-gray-800 rounded-lg {{ request()->routeIs('employee.dashboard') ? 'bg-gray-800 text-amber-500' : '' }}">
                📊 Dashboard
            </a>
            
            <a href="{{ route('employee.dashboard') }}" class="block px-4 py-3 text-white hover:bg-gray-800 rounded-lg {{ request()->routeIs('employee.commandes') ? 'bg-gray-800 text-amber-500' : '' }}">
                📦 Commandes
                @if(isset($commandesEnAttente) && $commandesEnAttente > 0)
                    <span class="ml-2 bg-yellow-400 text-black text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $commandesEnAttente }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('employee.menu.index') }}" class="block px-4 py-3 text-white hover:bg-gray-800 rounded-lg {{ request()->routeIs('employee.menu') ? 'bg-gray-800 text-amber-500' : '' }}">
                📖 Menu
            </a>
            
            <a href="{{ route('employee.dashboard') }}" class="block px-4 py-3 text-white hover:bg-gray-800 rounded-lg {{ request()->routeIs('employee.reclamations') ? 'bg-gray-800 text-amber-500' : '' }}">
                🚨 Réclamations
                @if(isset($reclamationsNonTraitees) && $reclamationsNonTraitees > 0)
                    <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $reclamationsNonTraitees }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('employee.dashboard') }}" class="block px-4 py-3 text-white hover:bg-gray-800 rounded-lg {{ request()->routeIs('employee.statistiques') ? 'bg-gray-800 text-amber-500' : '' }}">
                📈 Statistiques
            </a>
        </div>

        <!-- Mobile User Menu -->
        <div class="pt-4 pb-3 border-t border-gray-800">
            <div class="px-4 mb-3">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-yellow-600 flex items-center justify-center">
                        <svg class="w-6 h-6 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="px-4 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-white hover:bg-gray-800 rounded-lg">
                    Mon Profil
                </a>
                
                <a href="{{ route('employee.dashboard') }}" class="block px-4 py-2 text-white hover:bg-gray-800 rounded-lg">
                    Paramètres
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-gray-800 rounded-lg">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>