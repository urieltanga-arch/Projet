@extends('layouts.employee')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-5xl font-bold text-gray-900 mb-2">Gestion des Commandes</h1>
            <p class="text-gray-600 text-lg">Suivi et traitement des commandes</p>
        </div>

        <!-- Filtres et Recherche -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                    <input type="text" id="searchInput" placeholder="Numéro de commande ou nom du client..." 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>

                <!-- Filtre par date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" id="dateFilter" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>

                <!-- Filtre par montant -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Montant min.</label>
                    <input type="number" id="montantFilter" placeholder="0 FCFA" 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="flex gap-4 mt-4">
                <button onclick="applyFilters()" class="px-6 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">
                    Appliquer les filtres
                </button>
                <button onclick="resetFilters()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Réinitialiser
                </button>
            </div>
        </div>

        <!-- Colonnes de statuts -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
            
            <!-- Nouvelles -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-6 h-6 rounded-full bg-yellow-400"></div>
                    <h2 class="text-xl font-bold text-gray-900">Nouvelles</h2>
                    <span class="ml-auto bg-yellow-100 text-yellow-800 text-sm font-bold px-3 py-1 rounded-full">
                        {{ $commandes->where('statut', 'nouvelle')->count() }}
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($commandes->where('statut', 'nouvelle') as $commande)
                    <div class="bg-yellow-100 rounded-2xl p-4 cursor-pointer hover:shadow-md transition" 
                         onclick="openCommandeModal({{ $commande->id }})">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-lg font-bold text-gray-900">*{{ $commande->numero }}</span>
                            <span class="bg-yellow-200 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">
                                Livraison
                            </span>
                        </div>
                        <p class="text-gray-700 font-medium mb-1">{{ $commande->client_nom }}</p>
                        <p class="text-gray-600 text-sm mb-3">{{ $commande->items_resume }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">{{ $commande->heure }}</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($commande->montant, 0) }}F</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- En préparation -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-6 h-6 rounded-full bg-indigo-400"></div>
                    <h2 class="text-xl font-bold text-gray-900">En préparation</h2>
                    <span class="ml-auto bg-indigo-100 text-indigo-800 text-sm font-bold px-3 py-1 rounded-full">
                        {{ $commandes->where('statut', 'en_preparation')->count() }}
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($commandes->where('statut', 'en_preparation') as $commande)
                    <div class="bg-indigo-200 rounded-2xl p-4 cursor-pointer hover:shadow-md transition" 
                         onclick="openCommandeModal({{ $commande->id ?? 0}})">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-lg font-bold text-gray-900">*{{ $commande->numero ?? 0 }}</span>
                            <span class="bg-indigo-300 text-indigo-900 text-xs font-bold px-3 py-1 rounded-full">
                                Livraison
                            </span>
                        </div>
                        <p class="text-gray-700 font-medium mb-1">{{ $commande->client_nom ?? 0}}</p>
                        <p class="text-gray-600 text-sm mb-3">{{ $commande->items_resume ?? 0}}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">{{ $commande->heure ?? 0}}</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($commande->montant?? 0, 0 ) }}F</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Prêtes -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-6 h-6 rounded-full bg-green-400"></div>
                    <h2 class="text-xl font-bold text-gray-900">Prêtes</h2>
                    <span class="ml-auto bg-green-100 text-green-800 text-sm font-bold px-3 py-1 rounded-full">
                        {{ $commandes->where('statut', 'prete')->count() }}
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($commandes->where('statut', 'prete') as $commande)
                    <div class="bg-green-200 rounded-2xl p-4 cursor-pointer hover:shadow-md transition" 
                         onclick="openCommandeModal({{ $commande->id }})">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-lg font-bold text-gray-900">*{{ $commande->numero }}</span>
                            <span class="bg-green-300 text-green-900 text-xs font-bold px-3 py-1 rounded-full">
                                Livraison
                            </span>
                        </div>
                        <p class="text-gray-700 font-medium mb-1">{{ $commande->client_nom }}</p>
                        <p class="text-gray-600 text-sm mb-3">{{ $commande->items_resume }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">{{ $commande->heure }}</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($commande->montant, 0) }}F</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- En livraison -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-6 h-6 rounded-full bg-blue-400"></div>
                    <h2 class="text-xl font-bold text-gray-900">En livraison</h2>
                    <span class="ml-auto bg-blue-100 text-blue-800 text-sm font-bold px-3 py-1 rounded-full">
                        {{ $commandes->where('statut', 'en_livraison')->count() }}
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($commandes->where('statut', 'en_livraison') as $commande)
                    <div class="bg-blue-200 rounded-2xl p-4 cursor-pointer hover:shadow-md transition" 
                         onclick="openCommandeModal({{ $commande->id }})">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-lg font-bold text-gray-900">*{{ $commande->numero }}</span>
                            <span class="bg-blue-300 text-blue-900 text-xs font-bold px-3 py-1 rounded-full">
                                Livraison
                            </span>
                        </div>
                        <p class="text-gray-700 font-medium mb-1">{{ $commande->client_nom }}</p>
                        <p class="text-gray-600 text-sm mb-3">{{ $commande->items_resume }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">{{ $commande->heure }}</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($commande->montant, 0) }}F</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Livrées -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-6 h-6 rounded-full bg-gray-500"></div>
                    <h2 class="text-xl font-bold text-gray-900">Livrées</h2>
                    <span class="ml-auto bg-gray-100 text-gray-800 text-sm font-bold px-3 py-1 rounded-full">
                        {{ $commandes->where('statut', 'livree')->count() }}
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($commandes->where('statut', 'livree') as $commande)
                    <div class="bg-gray-300 rounded-2xl p-4 cursor-pointer hover:shadow-md transition" 
                         onclick="openCommandeModal({{ $commande->id }})">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-lg font-bold text-gray-900">*{{ $commande->numero }}</span>
                            <span class="bg-gray-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full">
                                Livraison
                            </span>
                        </div>
                        <p class="text-gray-700 font-medium mb-1">{{ $commande->client_nom }}</p>
                        <p class="text-gray-600 text-sm mb-3">{{ $commande->items_resume }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">{{ $commande->heure }}</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($commande->montant, 0) }}F</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Annulées -->
            <div class="bg-white rounded-3xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-6 h-6 rounded-full bg-red-400"></div>
                    <h2 class="text-xl font-bold text-gray-900">Annulées</h2>
                    <span class="ml-auto bg-red-100 text-red-800 text-sm font-bold px-3 py-1 rounded-full">
                        {{ $commandes->where('statut', 'annulee')->count() }}
                    </span>
                </div>
                <div class="space-y-4">
                    @foreach($commandes->where('statut', 'annulee') as $commande)
                    <div class="bg-red-200 rounded-2xl p-4 cursor-pointer hover:shadow-md transition" 
                         onclick="openCommandeModal({{ $commande->id }})">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-lg font-bold text-gray-900">*{{ $commande->numero }}</span>
                            <span class="bg-red-300 text-red-900 text-xs font-bold px-3 py-1 rounded-full">
                                Livraison
                            </span>
                        </div>
                        <p class="text-gray-700 font-medium mb-1">{{ $commande->client_nom }}</p>
                        <p class="text-gray-600 text-sm mb-3">{{ $commande->items_resume }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500 text-sm">{{ $commande->heure }}</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($commande->montant, 0) }}F</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Détails Commande -->
<div id="commandeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <!-- Header Modal -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Commande <span id="modalNumero"></span></h2>
                    <p class="text-gray-600">Détails et actions</p>
                </div>
                <button onclick="closeCommandeModal()" class="text-gray-400 hover:text-gray-600 text-3xl">×</button>
            </div>

            <!-- Informations Client -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informations Client</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nom du client</p>
                        <p class="font-bold text-gray-900" id="modalClientNom"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Téléphone</p>
                        <p class="font-bold text-gray-900" id="modalClientTel"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Adresse de livraison</p>
                        <p class="font-bold text-gray-900" id="modalAdresse"></p>
                    </div>
                </div>
            </div>

            <!-- Détails de la commande -->
            <div class="bg-gray-50 rounded-2xl p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Détails de la commande</h3>
                <div id="modalItems" class="space-y-3"></div>
                <div class="border-t border-gray-300 mt-4 pt-4">
                    <div class="flex justify-between items-center text-2xl font-bold">
                        <span>Total</span>
                        <span id="modalTotal"></span>
                    </div>
                </div>
            </div>

            <!-- Statut actuel -->
            <div class="bg-blue-50 rounded-2xl p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Statut actuel</h3>
                <div class="flex items-center gap-4">
                    <span id="modalStatutBadge" class="px-6 py-3 rounded-full font-bold text-lg"></span>
                    <span class="text-gray-600">Commandée le <span id="modalDate"></span> à <span id="modalHeure"></span></span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-4">
                <button onclick="changerStatut('en_preparation')" 
                        class="flex-1 bg-indigo-500 text-white px-6 py-3 rounded-xl hover:bg-indigo-600 transition font-bold">
                    ▶ Mettre en préparation
                </button>
                <button onclick="changerStatut('prete')" 
                        class="flex-1 bg-green-500 text-white px-6 py-3 rounded-xl hover:bg-green-600 transition font-bold">
                    ✓ Marquer comme prête
                </button>
                <button onclick="changerStatut('en_livraison')" 
                        class="flex-1 bg-blue-500 text-white px-6 py-3 rounded-xl hover:bg-blue-600 transition font-bold">
                    🚚 En livraison
                </button>
                <button onclick="changerStatut('livree')" 
                        class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition font-bold">
                    ✓ Marquer comme livrée
                </button>
                <button onclick="annulerCommande()" 
                        class="flex-1 bg-red-500 text-white px-6 py-3 rounded-xl hover:bg-red-600 transition font-bold">
                    ✕ Annuler la commande
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentCommandeId = null;
const commandesData = @json($commandes);

function openCommandeModal(commandeId) {
    const commande = commandesData.find(c => c.id === commandeId);
    if (!commande) return;
    
    currentCommandeId = commandeId;
    
    // Remplir les informations
    document.getElementById('modalNumero').textContent = '*' + commande.numero;
    document.getElementById('modalClientNom').textContent = commande.client_nom;
    document.getElementById('modalClientTel').textContent = commande.client_tel;
    document.getElementById('modalAdresse').textContent = commande.adresse;
    document.getElementById('modalTotal').textContent = commande.montant.toLocaleString() + ' FCFA';
    document.getElementById('modalDate').textContent = commande.date;
    document.getElementById('modalHeure').textContent = commande.heure;
    
    // Items
    const itemsHtml = commande.items.map(item => `
        <div class="flex justify-between items-center bg-white rounded-lg p-3">
            <div>
                <p class="font-bold text-gray-900">${item.nom}</p>
                <p class="text-sm text-gray-600">Quantité: ${item.quantite}</p>
            </div>
            <p class="font-bold text-gray-900">${item.prix.toLocaleString()} F</p>
        </div>
    `).join('');
    document.getElementById('modalItems').innerHTML = itemsHtml;
    
    // Statut badge
    const statutBadge = document.getElementById('modalStatutBadge');
    const statutClasses = {
        'nouvelle': 'bg-yellow-400 text-yellow-900',
        'en_preparation': 'bg-indigo-400 text-indigo-900',
        'prete': 'bg-green-400 text-green-900',
        'en_livraison': 'bg-blue-400 text-blue-900',
        'livree': 'bg-gray-500 text-white',
        'annulee': 'bg-red-400 text-red-900'
    };
    const statutTexts = {
        'nouvelle': 'Nouvelle',
        'en_preparation': 'En préparation',
        'prete': 'Prête',
        'en_livraison': 'En livraison',
        'livree': 'Livrée',
        'annulee': 'Annulée'
    };
    statutBadge.className = `px-6 py-3 rounded-full font-bold text-lg ${statutClasses[commande.statut]}`;
    statutBadge.textContent = statutTexts[commande.statut];
    
    // Afficher le modal
    document.getElementById('commandeModal').classList.remove('hidden');
}

function closeCommandeModal() {
    document.getElementById('commandeModal').classList.add('hidden');
    currentCommandeId = null;
}

function changerStatut(nouveauStatut) {
    if (!currentCommandeId) return;
    
    if (confirm('Êtes-vous sûr de vouloir changer le statut de cette commande ?')) {
        fetch(`/employee/commandes/${currentCommandeId}/statut`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ statut: nouveauStatut })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function annulerCommande() {
    if (!currentCommandeId) return;
    
    const raison = prompt('Raison de l\'annulation :');
    if (!raison) return;
    
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        fetch(`/employee/commandes/${currentCommandeId}/annuler`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ raison: raison })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}

function applyFilters() {
    const search = document.getElementById('searchInput').value;
    const date = document.getElementById('dateFilter').value;
    const montant = document.getElementById('montantFilter').value;
    
    const params = new URLSearchParams();
    if (search) params.append('search', search);
    if (date) params.append('date', date);
    if (montant) params.append('montant', montant);
    
    window.location.href = '{{ route("employee.commandes") }}?' + params.toString();
}

function resetFilters() {
    window.location.href = '{{ route("employee.commandes") }}';
}

// Fermer le modal en cliquant en dehors
document.getElementById('commandeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCommandeModal();
    }
});

// Rafraîchissement automatique toutes les 30 secondes
setInterval(() => {
    if (!document.getElementById('commandeModal').classList.contains('hidden')) return;
    location.reload();
}, 30000);
</script>
@endpush
@endsection