<div class="bg-black text-white rounded-2xl p-6 relative shadow-lg hover:shadow-2xl transition-all duration-300">
    {{-- Nom de l'événement --}}
    <h3 class="text-2xl font-bold mb-4 text-yellow-400">{{ $event->name }}</h3>
    
    {{-- Description --}}
    @if($event->description)
        <p class="mb-3 text-gray-300 text-base leading-relaxed">{{ $event->description }}</p>
    @endif
    
    {{-- Date et heure --}}
    <div class="mb-4">
        <p class="text-lg font-semibold text-white">
            {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d/m/Y') }}
        </p>
    </div>

    {{-- Lieu --}}
    @if($event->location)
        <p class="text-sm text-gray-300 mb-4">
            📍 {{ $event->location }}
        </p>
    @endif

    {{-- Validité --}}
    <p class="text-sm text-gray-400 mb-6">
        Valable {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l') }}
    </p>

    {{-- Participants --}}
    @if($event->max_participants)
        <div class="mb-4">
            <p class="text-xs text-gray-400 mb-2">
                Participants: {{ $event->current_participants ?? 0 }}/{{ $event->max_participants }}
            </p>
            <div class="w-full bg-gray-700 rounded-full h-1.5">
                @php
                    $percentage = $event->max_participants > 0 
                        ? min(100, (($event->current_participants ?? 0) / $event->max_participants) * 100)
                        : 0;
                @endphp
                <div class="bg-yellow-500 h-1.5 rounded-full transition-all duration-300" 
                     style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    @endif

    {{-- Prix d'entrée --}}
    @if(isset($event->entry_price) && $event->entry_price > 0)
        <p class="text-sm text-gray-300 mb-4">
            💰 {{ number_format($event->entry_price, 0, ',', ' ') }} FCFA
        </p>
    @elseif(isset($event->entry_price))
        <p class="text-sm text-green-400 mb-4">
            ✨ Gratuit
        </p>
    @endif

    {{-- Badge de statut (en haut à droite) --}}
    <div class="absolute top-4 right-4">
        @php
            $eventDate = \Carbon\Carbon::parse($event->event_date);
            $now = \Carbon\Carbon::now();
        @endphp
        
        @if($eventDate->isFuture())
            <span class="bg-blue-500 text-white text-xs px-2 py-1 rounded-full font-semibold">
                À venir
            </span>
        @elseif($eventDate->isToday())
            <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full font-semibold animate-pulse">
                Aujourd'hui
            </span>
        @else
            <span class="bg-gray-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                Terminé
            </span>
        @endif
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 mt-6">
        <a href="{{ route('admin.events.edit', $event) }}" 
           class="bg-yellow-500 hover:bg-yellow-600 text-black px-6 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center gap-2 shadow-md">
            <span>✏️</span>
            <span>Modifier</span>
        </a>
        <form action="{{ route('admin.events.destroy', $event) }}" 
              method="POST" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 shadow-md">
                🗑️
            </button>
        </form>
    </div>
</div>
