<x-admin-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-bold text-black">Mise à jour Menu</h1>
                    <p class="text-gray-600 mt-2">Gestion de la disponibilité et des prix</p>
                </div>
                
                <!-- Bouton Ajouter -->
                <a href="{{ route('employee.menu.create') }}" 
                   class="bg-black hover:bg-gray-800 text-white font-bold px-6 py-3 rounded-full transition-colors shadow-lg">
                    ➕ Ajouter un plat
                </a>
            </div>

            <!-- Messages de succès -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Grid à 2 colonnes -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Colonne Plats Principaux -->
                <div>
                    <div class="bg-gradient-to-r from-yellow-400 to-amber-500 rounded-3xl p-6 mb-6 shadow-xl">
                        <h2 class="text-2xl font-bold text-black">Plats Principaux</h2>
                    </div>

                    <div class="space-y-4">
                        @forelse($platsPrincipaux as $plat)
                            <div class="bg-amber-50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-shadow border-2 border-amber-200">
                                <div class="flex items-center gap-4">
                                    <!-- Image -->
                                    <div class="w-20 h-20 rounded-xl overflow-hidden bg-white flex-shrink-0">
                                        @if($plat->image_url)
                                            <img src="{{ $plat->image_url }}" 
                                                 alt="{{ $plat->name }}" 
                                                 class="w-full h-full object-cover"
                                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-3xl\'>🍽️</div>';">
                                        @else
                                            <div class="flex items-center justify-center h-full text-3xl">🍽️</div>
                                        @endif
                                    </div>

                                    <!-- Infos -->
                                    <div class="flex-grow">
                                        <h3 class="font-bold text-xl text-black">{{ $plat->name }}</h3>
                                        <p class="text-lg font-semibold text-gray-700">{{ number_format($plat->price, 0) }}F</p>
                                    </div>

                                    <!-- Toggle disponibilité -->
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            {{ $plat->is_available ? 'checked' : '' }}
                                            class="sr-only peer"
                                            onchange="toggleAvailability({{ $plat->id }}, this)"
                                        >
                                        <div class="w-14 h-8 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500"></div>
                                    </label>

                                    <!-- Boutons actions -->
                                    <div class="flex gap-2">
                                        <a href="{{ route('employee.menu.edit', $plat) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors">
                                            ✏️
                                        </a>
                                        <form action="{{ route('employee.menu.destroy', $plat) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plat ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-2xl p-8 text-center text-gray-500">
                                Aucun plat principal
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne Boissons & Desserts -->
                <div class="space-y-8">
                    
                    <!-- Boissons -->
                    <div>
                        <div class="bg-gradient-to-r from-yellow-400 to-amber-500 rounded-3xl p-6 mb-6 shadow-xl">
                            <h2 class="text-2xl font-bold text-black">Boissons & Desserts</h2>
                        </div>

                        <div class="space-y-4">
                            @forelse($boissons->concat($desserts) as $plat)
                                <div class="bg-amber-50 rounded-2xl p-6 shadow-md hover:shadow-xl transition-shadow border-2 border-amber-200">
                                    <div class="flex items-center gap-4">
                                        <!-- Image -->
                                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-white flex-shrink-0">
                                            @if($plat->image_url)
                                                <img src="{{ $plat->image_url }}" 
                                                     alt="{{ $plat->name }}" 
                                                     class="w-full h-full object-cover"
                                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-3xl\'>🍽️</div>';">
                                            @else
                                                <div class="flex items-center justify-center h-full text-3xl">
                                                    @if($plat->category === 'boisson') 🥤 @else 🍰 @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Infos -->
                                        <div class="flex-grow">
                                            <h3 class="font-bold text-xl text-black">{{ $plat->name }}</h3>
                                            <p class="text-lg font-semibold text-gray-700">{{ number_format($plat->price, 0) }}F</p>
                                            @if(!$plat->is_available)
                                                <span class="text-xs text-red-600 font-semibold">Épuisé</span>
                                            @endif
                                        </div>

                                        <!-- Toggle disponibilité -->
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input 
                                                type="checkbox" 
                                                {{ $plat->is_available ? 'checked' : '' }}
                                                class="sr-only peer"
                                                onchange="toggleAvailability({{ $plat->id }}, this)"
                                            >
                                            <div class="w-14 h-8 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500"></div>
                                        </label>

                                        <!-- Boutons actions -->
                                        <div class="flex gap-2">
                                            <a href="{{ route('employee.menu.edit', $plat) }}" 
                                               class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors">
                                                ✏️
                                            </a>
                                            <form action="{{ route('employee.menu.destroy', $plat) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce plat ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white rounded-2xl p-8 text-center text-gray-500">
                                    Aucune boisson ou dessert
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function toggleAvailability(platId, checkbox) {
            fetch(`/employee/menu/${platId}/toggle`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    checkbox.checked = !checkbox.checked;
                    alert('Erreur lors de la mise à jour');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                checkbox.checked = !checkbox.checked;
                alert('Erreur lors de la mise à jour');
            });
        }
    </script>

</x-admin-app-layout>