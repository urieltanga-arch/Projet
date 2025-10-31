
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervision des Réclamations - Zeduc Spaces</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <header>
        <nav x-data="{ mobileMenuOpen: false, userMenuOpen: false }" class="bg-black border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                <a href="{{ route('gerant.dashboard') }}" class="flex items-center">
                    <x-application-logo class="block h-10 w-auto fill-current text-yellow-500" />
                </a>
            </div>
                <div class="hidden md:flex space-x-1">
                    <a href="{{ route('gerant.dashboard') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('gerant.gerant.dashboard') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('gerant.employees.index') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                        Employé
                    </a>
                    <a href="{{ route('gerant.statistiques') }}"
                       class="px-4 py-2 text-base font-medium rounded-lg transition-colors text-white hover:bg-gray-800">
                        Statistiques
                    </a>
                     <a href="{{ route('gerant.reclamations.index') }}"
   class="px-4 py-2 text-base font-medium rounded-lg transition-colors {{ request()->routeIs('gerant.reclamations.index') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
    Réclamation
</a>
                </div>
                <div class="hidden md:flex items-center">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen" class="flex items-center text-yellow-500 hover:text-yellow-400 transition-colors focus:outline-none">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <div x-show="userMenuOpen"
                             @click.away="userMenuOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50"
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">Gérant</p>
                            </div>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                Mon Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-yellow-500 transition-colors p-2 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div x-show="mobileMenuOpen" class="md:hidden bg-gray-900" style="display: none;">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="{{ route('gerant.dashboard') }}" class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('gerant.gerant.dashboard') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">Dashboard</a>
                <a href="#" class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Employé</a>
                <a href="#" class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Statistiques</a>
<a href="{{ route('gerant.reclamations.index') }}" 
   class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('gerant.reclamations.index') ? 'bg-yellow-500 text-black' : 'text-white hover:bg-gray-800' }}">
    Réclamation
</a>               <div class="border-t border-gray-700 pt-3 mt-3">
                     <div class="flex items-center gap-3 pb-3 mb-3">
                        <div>
                            <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">Gérant</p>
                        </div>
                    </div>
                    <a href="#" class="block px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Mon Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-base font-medium rounded-lg text-white hover:bg-gray-800">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    </header>
    <div class="py-12 bg-gradient-to-br from-amber-50 to-orange-50 min-h-screen">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- En-tête -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-5xl font-bold text-black">Gestion des Employés</h1>
                    <p class="text-gray-600 mt-2">Gérez les employés, rôles et permissions</p>
                </div>
                <button onclick="openAddModal()" class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold px-6 py-3 rounded-full transition-colors shadow-lg">
                    + Ajouter Employé
                </button>
            </div>

            <!-- Message de succès -->
            <div id="successMessage" class="hidden mb-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-2xl">
                <p class="font-semibold" id="successText"></p>
            </div>

            <!-- Tableau des employés -->
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
                <!-- Header du tableau -->
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 px-6 py-4">
                    <div class="grid grid-cols-12 gap-4 font-bold text-black">
                        <div class="col-span-3">Employé</div>
                        <div class="col-span-2">Rôle</div>
                        <div class="col-span-2">Email</div>
                        <div class="col-span-2">Statut</div>
                        <div class="col-span-3 text-center">Actions</div>
                    </div>
                </div>

                <!-- Liste des employés -->
                <div class="divide-y divide-gray-200">
                    @forelse($employees as $employee)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors" id="employee-{{ $employee->id }}">
                            <div class="grid grid-cols-12 gap-4 items-center">
                                
                                <!-- Nom et Email -->
                                <div class="col-span-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-xl font-bold text-white">
                                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-black">{{ $employee->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rôle -->
                                <div class="col-span-2">
                                    @php
                                        $roleConfig = [
                                            'employee' => ['label' => 'Employé', 'class' => 'bg-blue-100 text-blue-800'],
                                            'manager' => ['label' => 'Gérant', 'class' => 'bg-purple-100 text-purple-800'],
                                            'admin' => ['label' => 'Admin', 'class' => 'bg-red-100 text-red-800'],
                                        ];
                                        $role = $roleConfig[$employee->role] ?? ['label' => $employee->role, 'class' => 'bg-gray-100 text-gray-800'];
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $role['class'] }}">
                                        {{ $role['label'] }}
                                    </span>
                                </div>

                                <!-- Email -->
                                <div class="col-span-2">
                                    <p class="text-gray-700 text-sm">{{ $employee->email }}</p>
                                </div>

                                <!-- Statut -->
                                <div class="col-span-2">
                                    @if($employee->is_suspended ?? false)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                            Suspendu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                            Actif
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="col-span-3 flex justify-center gap-2">
                                    <!-- Modifier -->
                                    <button 
                                        onclick="openEditModal({{ $employee->id }}, '{{ $employee->name }}', '{{ $employee->email }}', '{{ $employee->role }}')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors"
                                        title="Modifier"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>

                                    <!-- Suspendre/Activer -->
                                    <button 
                                        onclick="toggleStatus({{ $employee->id }})"
                                        class="bg-orange-500 hover:bg-orange-600 text-white p-2 rounded-lg transition-colors"
                                        title="{{ $employee->is_suspended ? 'Réactiver' : 'Suspendre' }}"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>

                                    <!-- Supprimer -->
                                    <button 
                                        onclick="deleteEmployee({{ $employee->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors"
                                        title="Supprimer"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <div class="text-6xl mb-4">👥</div>
                            <p class="text-gray-600 text-lg">Aucun employé trouvé</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Ajouter Employé -->
    <div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-black">Ajouter un Employé</h2>
                <button onclick="closeAddModal()" class="text-gray-500 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="addForm" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Nom complet</label>
                    <input type="text" name="name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Rôle</label>
                    <select name="role" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                        <option value="">Sélectionner...</option>
                        <option value="employee">Employé</option>
                        <option value="manager">Gérant</option>
                    </select>
                </div>

                <div id="addMessage" class="hidden"></div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeAddModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-bold py-3 rounded-full transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-full transition-colors">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Modifier Employé -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-black">Modifier l'Employé</h2>
                <button onclick="closeEditModal()" class="text-gray-500 hover:text-black">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editForm" class="space-y-4">
                @csrf
                <input type="hidden" id="edit_employee_id" name="employee_id">

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Nom complet</label>
                    <input type="text" id="edit_name" name="name" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Email</label>
                    <input type="email" id="edit_email" name="email" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Rôle</label>
                    <select id="edit_role" name="role" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200">
                        <option value="employee">Employé</option>
                        <option value="manager">Gérant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div id="editMessage" class="hidden"></div>

                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-black font-bold py-3 rounded-full transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 bg-black hover:bg-gray-800 text-white font-bold py-3 rounded-full transition-colors">
                        Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Ouvrir modal ajout
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('addForm').reset();
            document.getElementById('addMessage').classList.add('hidden');
        }

        // Ouvrir modal modification
        function openEditModal(id, name, email, role) {
            document.getElementById('edit_employee_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('editModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('editMessage').classList.add('hidden');
        }

        // Ajouter employé
        document.getElementById('addForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('addMessage');

            try {
                const response = await fetch('/admin/employees', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    closeAddModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    messageDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl';
                    messageDiv.textContent = data.message || 'Une erreur est survenue';
                    messageDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Erreur:', error);
                messageDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl';
                messageDiv.textContent = 'Une erreur est survenue';
                messageDiv.classList.remove('hidden');
            }
        });

        // Modifier employé
        document.getElementById('editForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const employeeId = document.getElementById('edit_employee_id').value;
            const formData = new FormData(this);
            const messageDiv = document.getElementById('editMessage');

            try {
                const response = await fetch(`/admin/employees/${employeeId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    closeEditModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    messageDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl';
                    messageDiv.textContent = data.message || 'Une erreur est survenue';
                    messageDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        });

        // Suspendre/Activer
        async function toggleStatus(employeeId) {
            if (!confirm('Voulez-vous changer le statut de cet employé ?')) return;

            try {
                const response = await fetch(`/admin/employees/${employeeId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => location.reload(), 1000);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            }
        }

        // Supprimer
        async function deleteEmployee(employeeId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet employé ? Cette action est irréversible.')) return;

            try {
                const response = await fetch(`/admin/employees/${employeeId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showSuccess(data.message);
                    document.getElementById(`employee-${employeeId}`).remove();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue');
            }
        }

        // Afficher message de succès
        function showSuccess(message) {
            const successDiv = document.getElementById('successMessage');
            document.getElementById('successText').textContent = message;
            successDiv.classList.remove('hidden');
            setTimeout(() => successDiv.classList.add('hidden'), 5000);
        }

        // Fermer modals en cliquant à l'extérieur
        document.getElementById('addModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddModal();
        });
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
    </script>
        <x-footer class="block h-12 w-auto fill-current text-yellow-500" />

</body>
</html>