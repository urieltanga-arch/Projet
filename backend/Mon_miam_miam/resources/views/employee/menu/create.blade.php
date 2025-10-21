<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            
            <!-- Bouton retour -->
            <a href="{{ route('employee.menu.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-black mb-6 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour au menu
            </a>

            <!-- Formulaire -->
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <h1 class="text-3xl font-bold text-black mb-6">Ajouter un nouveau plat</h1>

                <form action="{{ route('employee.menu.store') }}" method="POST">
                    @csrf

                    <!-- Nom du plat -->
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                            Nom du plat *
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            value="{{ old('name') }}"
                            required
                            class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                            placeholder="Ex: Poulet Pané"
                        >
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-bold text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea 
                            name="description" 
                            id="description"
                            rows="3"
                            class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                            placeholder="Description du plat (optionnelle)"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prix et Catégorie en ligne -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <!-- Prix -->
                        <div>
                            <label for="price" class="block text-sm font-bold text-gray-700 mb-2">
                                Prix (FCFA) *
                            </label>
                            <input 
                                type="number" 
                                name="price" 
                                id="price"
                                value="{{ old('price') }}"
                                required
                                min="0"
                                step="1"
                                class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                                placeholder="2500"
                            >
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category" class="block text-sm font-bold text-gray-700 mb-2">
                                Catégorie *
                            </label>
                            <select 
                                name="category" 
                                id="category"
                                required
                                class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                            >
                                <option value="">Choisir...</option>
                                <option value="plat" {{ old('category') === 'plat' ? 'selected' : '' }}>plat</option>
                                <option value="boisson" {{ old('category') === 'boisson' ? 'selected' : '' }}>boisson</option>
                                <option value="dessert" {{ old('category') === 'dessert' ? 'selected' : '' }}>dessert</option>
                            </select>
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- URL de l'image -->
                    <div class="mb-6">
                        <label for="image_url" class="block text-sm font-bold text-gray-700 mb-2">
                            URL de l'image
                        </label>
                        <input 
                            type="url" 
                            name="image_url" 
                            id="image_url"
                            value="{{ old('image_url') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                            placeholder="https://example.com/image.jpg"
                        >
                        <p class="text-xs text-gray-500 mt-1">URL complète de l'image du plat</p>
                        @error('image_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Disponibilité -->
                    <div class="mb-8">
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_available" 
                                value="1"
                                {{ old('is_available', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-green-500 focus:ring-green-500 focus:ring-offset-0 w-5 h-5"
                            >
                            <span class="ml-3 text-sm font-bold text-gray-700">
                                Plat disponible immédiatement
                            </span>
                        </label>
                    </div>

                    <!-- Boutons -->
                    <div class="flex gap-4">
                        <button 
                            type="submit"
                            class="flex-1 bg-black hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-full transition-colors"
                        >
                            ➕ Ajouter le plat
                        </button>
                        <a 
                            href="{{ route('employee.menu.index') }}"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-bold py-3 px-6 rounded-full transition-colors text-center"
                        >
                            Annuler
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>