{{-- resources/views/admin/promotions/edit-promotion.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Promotion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f5e6d3;
            font-family: 'Georgia', serif;
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.promotions.index', ['filter' => 'promotion']) }}" 
               class="text-yellow-600 hover:underline">
                ← Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold mb-6">Modifier la Promotion</h1>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Nom *</label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $promotion->name) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Description</label>
                    <textarea name="description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">{{ old('description', $promotion->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Type *</label>
                        <select name="type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                required>
                            <option value="percentage" {{ old('type', $promotion->type) === 'percentage' ? 'selected' : '' }}>Pourcentage</option>
                            <option value="fixed_amount" {{ old('type', $promotion->type) === 'fixed_amount' ? 'selected' : '' }}>Montant fixe</option>
                            <option value="free_delivery" {{ old('type', $promotion->type) === 'free_delivery' ? 'selected' : '' }}>Livraison gratuite</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Valeur *</label>
                        <input type="number" 
                               name="value" 
                               value="{{ old('value', $promotion->value) }}"
                               step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                               required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Montant minimum</label>
                        <input type="number" 
                               name="min_amount" 
                               value="{{ old('min_amount', $promotion->min_amount) }}"
                               step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Code promo</label>
                        <input type="text" 
                               name="code" 
                               value="{{ old('code', $promotion->code) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">URL de l'image</label>
                    <input type="url" 
                           name="image_url" 
                           value="{{ old('image_url', $promotion->image_url) }}"
                           placeholder="https://example.com/image.jpg"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Date de début *</label>
                        <input type="datetime-local" 
                               name="start_date" 
                               value="{{ old('start_date', $promotion->start_date ? \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Date de fin *</label>
                        <input type="datetime-local" 
                               name="end_date" 
                               value="{{ old('end_date', $promotion->end_date ? \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500"
                               required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">Nombre maximum d'utilisations</label>
                    <input type="number" 
                           name="max_uses" 
                           value="{{ old('max_uses', $promotion->max_uses) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 text-yellow-500 border-gray-300 rounded focus:ring-yellow-500">
                        <span class="ml-2 text-gray-700 font-semibold">Promotion active</span>
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold py-3 px-8 rounded-lg">
                        Mettre à jour
                    </button>
                    <a href="{{ route('admin.promotions.index', ['filter' => 'promotion']) }}" 
                       class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-8 rounded-lg">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
        <x-footer class="block h-12 w-auto fill-current text-yellow-500" />

</body>
</html>