<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            
            <!-- Bouton retour -->
            <a href="{{ route('employee.commandes.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-black mb-6 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux commandes
            </a>

            <!-- En-tête -->
            <div class="bg-white rounded-3xl shadow-xl p-8 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-4xl font-bold text-black">Commande #{{ $commande->id }}</h1>
                        <p class="text-gray-600 mt-2">
                            Passée le {{ $commande->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    
                    @php
                        $statusColors = [
                            'en_attente' => 'bg-yellow-500 text-black',
                            'en_preparation' => 'bg-blue-500 text-white',
                            'prete' => 'bg-green-500 text-white',
                            'livree' => 'bg-gray-500 text-white',
                            'annulee' => 'bg-red-500 text-white',
                        ];
                        $statusLabels = [
                            'en_attente' => 'En attente',
                            'en_preparation' => 'En préparation',
                            'prete' => 'Prête',
                            'livree' => 'Livrée',
                            'annulee' => 'Annulée',
                        ];
                    @endphp
                    <span class="px-6 py-3 rounded-full font-bold text-lg {{ $statusColors[$commande->status] }}">
                        {{ $statusLabels[$commande->status] }}
                    </span>
                </div>

                <!-- Informations client -->
                <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                    <h2 class="text-xl font-bold text-black mb-4">Informations client</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nom</p>
                            <p class="font-bold text-black">{{ $commande->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-bold text-black">{{ $commande->user->email }}</p>
                        </div>
                        @if($commande->user->phone)
                            <div>
                                <p class="text-sm text-gray-600">Téléphone</p>
                                <p class="font-bold text-black">{{ $commande->user->phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Articles commandés -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-black mb-4">Articles commandés</h2>
                    <div class="space-y-3">
                        @foreach($commande->items as $item)
                            <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center space-x-4">
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-amber-100">
                                        @if($item->plat->image_url)
                                            <img src="{{ $item->plat->image_url }}" 
                                                 alt="{{ $item->plat->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-2xl">🍽️</div>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-black">{{ $item->plat->name }}</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ number_format($item->price, 0, ',', ' ') }} FCFA × {{ $item->quantity }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-black">
                                        {{ number_format($item->subtotal, 0, ',', ' ') }} FCFA
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t-2 border-gray-200 pt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-black">Total</span>
                        <span class="text-3xl font-bold text-yellow-600">
                            {{ number_format($commande->total, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                @if($commande->status === 'en_attente')
                    <button 
                        onclick="changeStatus('en_preparation')"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 rounded-2xl transition-colors shadow-lg"
                    >
                        ▶️ Commencer la préparation
                    </button>
                @elseif($commande->status === 'en_preparation')
                    <button 
                        onclick="changeStatus('prete')"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 rounded-2xl transition-colors shadow-lg"
                    >
                        ✅ Marquer comme prête
                    </button>
                @elseif($commande->status === 'prete')
                    <button 
                        onclick="changeStatus('livree')"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 rounded-2xl transition-colors shadow-lg"
                    >
                        🚚 Marquer comme livrée
                    </button>
                @endif

                @if($commande->status !== 'annulee' && $commande->status !== 'livree')
                    <button 
                        onclick="cancelCommande()"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-4 rounded-2xl transition-colors shadow-lg"
                    >
                        ❌ Annuler la commande
                    </button>
                @endif

                <button 
                    onclick="window.print()"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-4 rounded-2xl transition-colors shadow-lg"
                >
                    🖨️ Imprimer le ticket
                </button>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <h2 class="text-xl font-bold text-black mb-4">Notes</h2>
                
                @if($commande->notes)
                    <div class="bg-gray-50 rounded-xl p-4 mb-4 whitespace-pre-line">
                        {{ $commande->notes }}
                    </div>
                @else
                    <p class="text-gray-500 italic mb-4">Aucune note pour cette commande</p>
                @endif

                <!-- Ajouter une note -->
                <form id="note-form" class="flex gap-3">
                    @csrf
                    <input 
                        type="text" 
                        id="note-input"
                        placeholder="Ajouter une note..."
                        class="flex-1 rounded-xl border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                    >
                    <button 
                        type="submit"
                        class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-6 py-3 rounded-xl transition-colors"
                    >
                        Ajouter
                    </button>
                </form>
            </div>

        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const commandeId = {{ $commande->id }};

        function changeStatus(newStatus) {
            if (!confirm('Êtes-vous sûr de vouloir changer le statut ?')) return;

            fetch(`/employee/commandes/${commandeId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
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

        function cancelCommande() {
            if (!confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) return;

            fetch(`/employee/commandes/${commandeId}/cancel`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => console.error('Erreur:', error));
        }

        // Ajouter une note
        document.getElementById('note-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const noteInput = document.getElementById('note-input');
            const note = noteInput.value.trim();
            
            if (!note) return;

            fetch(`/employee/commandes/${commandeId}/note`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ note: note })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    noteInput.value = '';
                    location.reload();
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    </script>

    <!-- Style pour l'impression -->
    <style media="print">
        @page {
            size: 80mm auto;
            margin: 5mm;
        }
        
        body * {
            visibility: hidden;
        }
        
        .bg-white, .bg-white * {
            visibility: visible;
        }
        
        .bg-white {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        button, .bg-gray-700 {
            display: none !important;
        }
    </style>

</x-app-layout>