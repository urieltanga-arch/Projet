<x-app-layout>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="mb-8">
                <h1 class="text-5xl font-bold text-black">Historique des commandes</h1>
                <p class="text-gray-600 mt-2">Retrouvez toutes vos commandes</p>
            </div>

            <!-- Filtres -->
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-3xl shadow-lg p-6 mb-8">
                <form method="GET" action="{{ route('historique.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- Filtre par date -->
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">Toutes les dates</label>
                        <select name="date" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl border-0 text-black font-medium focus:ring-2 focus:ring-black">
                            <option value="all" {{ $dateFilter == 'all' ? 'selected' : '' }}>Toutes les dates</option>
                            <option value="today" {{ $dateFilter == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="week" {{ $dateFilter == 'week' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="month" {{ $dateFilter == 'month' ? 'selected' : '' }}>Ce mois</option>
                        </select>
                    </div>

                    <!-- Filtre par statut -->
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">Tous les Statut</label>
                        <select name="status" onchange="this.form.submit()" class="w-full px-4 py-3 rounded-xl border-0 text-black font-medium focus:ring-2 focus:ring-black">
                            <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                            <option value="en_attente" {{ $statusFilter == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="en_preparation" {{ $statusFilter == 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                            <option value="prete" {{ $statusFilter == 'prete' ? 'selected' : '' }}>Prête</option>
                            <option value="en_livraison" {{ $statusFilter == 'en_livraison' ? 'selected' : '' }}>En livraison</option>
                            <option value="livree" {{ $statusFilter == 'livree' ? 'selected' : '' }}>Livrée</option>
                            <option value="annulee" {{ $statusFilter == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>

                    <!-- Bouton Filtrer -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-xl transition-colors">
                            Filtrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Liste des commandes -->
            @if($commandes->isEmpty())
                <div class="bg-white rounded-3xl shadow-lg p-12 text-center">
                    <div class="text-8xl mb-4">📦</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Aucune commande trouvée</h2>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande</p>
                    <a href="{{ route('menu') }}" class="inline-block bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-8 py-3 rounded-full transition-colors">
                        Voir le menu
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($commandes as $commande)
                        <div class="bg-white rounded-3xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                
                                <!-- Informations commande -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <h3 class="text-2xl font-bold text-black">
                                            Commande #{{ $commande->numero_commande ?? str_pad($commande->id, 4, '0', STR_PAD_LEFT) }}
                                        </h3>
                                        
                                        <!-- Badge Statut -->
                                        @php
                                            $statusConfig = [
                                                'en_attente' => ['label' => 'En attente', 'class' => 'bg-yellow-100 text-yellow-800'],
                                                'en_preparation' => ['label' => 'En préparation', 'class' => 'bg-blue-100 text-blue-800'],
                                                'prete' => ['label' => 'Prête', 'class' => 'bg-green-100 text-green-800'],
                                                'en_livraison' => ['label' => 'En livraison', 'class' => 'bg-purple-100 text-purple-800'],
                                                'livree' => ['label' => 'Livrée', 'class' => 'bg-green-600 text-white'],
                                                'annulee' => ['label' => 'Annulée', 'class' => 'bg-red-100 text-red-800'],
                                            ];
                                            $currentStatus = $statusConfig[$commande->status] ?? ['label' => $commande->status, 'class' => 'bg-gray-100 text-gray-800'];
                                        @endphp
                                        <span class="px-4 py-1 rounded-full text-sm font-bold {{ $currentStatus['class'] }}">
                                            {{ $currentStatus['label'] }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-3">
                                        {{ $commande->created_at->format('d/m/Y à H:i') }}
                                    </p>

                                    <!-- Liste des articles -->
                                    <ul class="space-y-1 mb-3">
                                        @foreach($commande->items as $item)
                                            <li class="text-gray-700">
                                                • {{ $item->quantity }} {{ $item->plat->name ?? 'Plat' }}
                                            </li>
                                        @endforeach
                                    </ul>

                                    <!-- Bouton Signaler un problème -->
                                    @if($commande->status !== 'annulee')
                                        <button 
                                            onclick="openProblemModal({{ $commande->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-6 py-2 rounded-full transition-colors text-sm"
                                        >
                                            Signaler un problème
                                        </button>
                                    @endif
                                </div>

                                <!-- Montant et Action -->
                                <div class="flex flex-col items-end gap-3">
                                    <div class="flex justify-between items-center py-2 border-b">
                                <div>
                                    <p class="font-semibold">{{ $item['name'] }}</p>
                                    <p class="font-bold text-yellow-600">{{ number_format($commandes['mantant_total'], 0, ',', ' ') }} FCFA</p>
                                </div>
                                
                            </div>

                                    @if($commande->status === 'en_livraison')
                                        <button 
                                            onclick="confirmerLivraison({{ $commande->id }})"
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-full transition-colors shadow-lg"
                                        >
                                            Livrée
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $commandes->appends(['date' => $dateFilter, 'status' => $statusFilter])->links() }}
                </div>
            @endif

        </div>
    </div>

    <!-- Modal Signaler un problème -->
    <div id="problemModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-black">Signaler un problème</h2>
                <button onclick="closeProblemModal()" class="text-gray-500 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Formulaire -->
            <form id="problemForm" class="space-y-4">
                @csrf
                <input type="hidden" id="commande_id" name="commande_id">

                <!-- Type de problème -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Type de problème</label>
                    <select name="type_probleme" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                        <option value="">Sélectionner...</option>
                        <option value="Problème de qualité">Problème de qualité</option>
                        <option value="Quantité incorrecte">Quantité incorrecte</option>
                        <option value="Retard de livraison">Retard de livraison</option>
                        <option value="Article manquant">Article manquant</option>
                        <option value="Autre">Autre</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Description du problème</label>
                    <textarea 
                        name="description" 
                        required
                        rows="4"
                        placeholder="Décrivez votre problème..."
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 resize-none"
                    ></textarea>
                </div>

                <!-- Message de succès/erreur -->
                <div id="problemMessage" class="hidden"></div>

                <!-- Boutons -->
                <div class="flex gap-4">
                    <button 
                        type="button" 
                        onclick="closeProblemModal()"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-bold py-3 rounded-full transition-colors"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-full transition-colors"
                    >
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Ouvrir le modal de signalement
        function openProblemModal(commandeId) {
            document.getElementById('commande_id').value = commandeId;
            document.getElementById('problemModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Fermer le modal
        function closeProblemModal() {
            document.getElementById('problemModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('problemForm').reset();
            document.getElementById('problemMessage').classList.add('hidden');
        }

        // Soumettre le formulaire de signalement
        document.getElementById('problemForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const commandeId = document.getElementById('commande_id').value;
            const formData = new FormData(this);
            const messageDiv = document.getElementById('problemMessage');
            
            try {
                const response = await fetch(`/historique/${commandeId}/signaler`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    messageDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl';
                    messageDiv.textContent = data.message;
                    messageDiv.classList.remove('hidden');
                    
                    setTimeout(() => {
                        closeProblemModal();
                    }, 2000);
                } else {
                    messageDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl';
                    messageDiv.textContent = data.message || 'Une erreur est survenue';
                    messageDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Erreur:', error);
                messageDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl';
                messageDiv.textContent = 'Une erreur est survenue. Veuillez réessayer.';
                messageDiv.classList.remove('hidden');
            }
        });

        // Confirmer la livraison
        async function confirmerLivraison(commandeId) {
            if (!confirm('Confirmez-vous que votre commande a été livrée ?')) return;
            
            try {
                const response = await fetch(`/historique/${commandeId}/confirmer-livraison`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Une erreur est survenue');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
        }

        // Fermer le modal en cliquant à l'extérieur
        document.getElementById('problemModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeProblemModal();
            }
        });
    </script>

</x-app-layout>