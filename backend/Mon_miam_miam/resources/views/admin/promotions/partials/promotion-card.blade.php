<div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-black rounded-2xl p-6 relative shadow-lg hover:shadow-2xl transition-all duration-300">
    {{-- Nom de la promotion --}}
    <h3 class="text-2xl font-bold mb-4">{{ $promotion->name }}</h3>
    
    {{-- Description --}}
    @if($promotion->description)
        <p class="mb-3 text-gray-900 text-base leading-relaxed">{{ $promotion->description }}</p>
    @endif
    
    {{-- Valeur de la promotion --}}
    <div class="mb-4">
        @if($promotion->type === 'percentage')
            <p class="text-lg font-semibold">-{{ $promotion->value }}% sur tous les produits</p>
        @elseif($promotion->type === 'fixed_amount')
            <p class="text-lg font-semibold">Réduction de {{ number_format($promotion->value, 0, ',', ' ') }} FCFA</p>
        @else
            <p class="text-lg font-semibold">🚚 Livraison gratuite</p>
        @endif
    </div>

    {{-- Code promo --}}
    @if($promotion->code)
        <div class="mb-4">
            <span class="bg-black text-white px-3 py-1 rounded text-sm font-mono">
                CODE: {{ $promotion->code }}
            </span>
        </div>
    @endif

    {{-- Dates --}}
    <p class="text-sm text-gray-800 mb-6">
        Du {{ \Carbon\Carbon::parse($promotion->start_date)->translatedFormat('l') }} au {{ \Carbon\Carbon::parse($promotion->end_date)->translatedFormat('l') }}
    </p>

    {{-- Informations supplémentaires --}}
    @if($promotion->min_amount)
        <p class="text-xs text-gray-800 mb-4">
            Montant minimum: {{ number_format($promotion->min_amount, 0, ',', ' ') }} FCFA
        </p>
    @endif

    {{-- Badge de statut (en haut à droite) --}}
    <div class="absolute top-4 right-4">
        @if($promotion->is_active)
            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                Actif
            </span>
        @else
            <span class="bg-gray-400 text-white text-xs px-2 py-1 rounded-full font-semibold">
                Inactif
            </span>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 mt-6">
        <a href="{{ route('admin.promotions.edit', $promotion) }}" 
           class="bg-black hover:bg-gray-800 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center gap-2 shadow-md">
            <span>✏️</span>
            <span>Modifier</span>
        </a>
        <form action="{{ route('admin.promotions.destroy', $promotion) }}" 
              method="POST" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 shadow-md">
                🗑️
            </button>
        </form>
    </div>
</div>
