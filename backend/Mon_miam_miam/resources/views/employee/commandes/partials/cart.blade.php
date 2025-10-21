@php
    $bgColors = [
        'yellow' => 'bg-yellow-50 border-yellow-200',
        'blue' => 'bg-blue-50 border-blue-200',
        'green' => 'bg-green-50 border-green-200',
        'gray' => 'bg-gray-50 border-gray-200',
    ];
    $badgeColors = [
        'yellow' => 'bg-yellow-500 text-black',
        'blue' => 'bg-blue-500 text-white',
        'green' => 'bg-green-500 text-white',
        'gray' => 'bg-gray-500 text-white',
    ];
@endphp

<div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all border-2 {{ $bgColors[$color] }} cursor-pointer"
     onclick="showCommandeDetails({{ $commande->id }})">
    
    <!-- En-tête de la carte -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xl font-bold text-black">#{{ $commande->id }}</span>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeColors[$color] }}">
                {{ strtoupper($commande->status) }}
            </span>
        </div>
        
        <div class="text-sm text-gray-600">
            <p class="font-semibold text-black">{{ $commande->user->name }}</p>
            <p>{{ $commande->created_at->format('H:i') }}</p>
        </div>
    </div>

    <!-- Liste des plats -->
    <div class="p-4 space-y-2">
        @foreach($commande->items->take(3) as $item)
            <div class="flex justify-between text-sm">
                <span class="text-gray-700">{{ $item->plat->name }} x{{ $item->quantity }}</span>
            </div>
        @endforeach
        
        @if($commande->items->count() > 3)
            <p class="text-xs text-gray-500 italic">
                +{{ $commande->items->count() - 3 }} autre(s) plat(s)
            </p>
        @endif
    </div>

    <!-- Footer avec le total -->
    <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
        <div class="flex items-center justify-between">
            <span class="text-sm font-semibold text-gray-700">Total</span>
            <span class="text-lg font-bold text-black">
                {{ number_format($commande->total, 0, ',', ' ') }} FCFA
            </span>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="p-4 border-t border-gray-200 flex gap-2">
        @if($commande->status === 'en_attente')
            <button 
                onclick="event.stopPropagation(); changeStatus({{ $commande->id }}, 'en_preparation')"
                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2 rounded-lg transition-colors"
            >
                Préparer
            </button>
        @elseif($commande->status === 'en_preparation')
            <button 
                onclick="event.stopPropagation(); changeStatus({{ $commande->id }}, 'prete')"
                class="flex-1 bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 rounded-lg transition-colors"
            >
                Marquer prête
            </button>
        @elseif($commande->status === 'prete')
            <button 
                onclick="event.stopPropagation(); changeStatus({{ $commande->id }}, 'livree')"
                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white text-xs font-bold py-2 rounded-lg transition-colors"
            >
                Livrer
            </button>
        @endif
        
        <button 
            onclick="event.stopPropagation(); window.location.href='{{ route('employee.commandes.show', $commande) }}'"
            class="bg-gray-200 hover:bg-gray-300 text-black text-xs font-bold px-4 py-2 rounded-lg transition-colors"
        >
            Détails
        </button>
    </div>

</div>

<script>
    function changeStatus(commandeId, newStatus) {
        if (!confirm('Changer le statut de cette commande ?')) return;
        
        fetch(`/employee/commandes/${commandeId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Erreur:', error));
    }

    function showCommandeDetails(commandeId) {
        window.location.href = `/employee/commandes/${commandeId}`;
    }
</script>