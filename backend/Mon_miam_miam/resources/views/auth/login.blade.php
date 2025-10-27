<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Zeduc-sp@ce</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-h-screen bg-gradient-to-b from-amber-50 to-stone-200">
        <!-- HEADER -->
        <header class="flex items-center justify-between px-8 py-4 bg-black shadow-md">
            <div class="flex items-center">
                
                    <x-application-logo class="block h-12 w-auto fill-current text-yellow-500" />
                  
            </div>

            <button
                onclick="window.history.back()"
                class="flex items-center justify-center bg-amber-500 hover:bg-amber-600 transition-colors rounded p-2"
            >
                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </header>

        <!-- CONTENU -->
        <main class="flex flex-col items-center justify-center mt-20 w-full">
            <div class="flex items-center justify-center px-4 py-12">
                <div class="w-full max-w-xl">
                    
                    <!-- ONGLETS -->
                    <div class="bg-white rounded-2xl p-2 mb-6 shadow-sm">
                        <div class="flex gap-2">
                            <button
                                onclick="switchTab('login')"
                                id="tab-login"
                                class="flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all text-center bg-gradient-to-r from-yellow-500 to-amber-500 text-black shadow-md"
                            >
                                Connexion
                            </button>
                            
                            <button
                                onclick="switchTab('register')"
                                id="tab-register"
                                class="flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all text-center text-gray-600 hover:text-gray-800"
                            >
                                Inscription
                            </button>
                        </div>
                    </div>

                    <!-- FORMULAIRE UNIFIÉ -->
                    <div class="bg-white shadow-lg rounded-2xl p-10 border border-amber-100" style="min-height: 600px;">
                        <!-- TITRE DYNAMIQUE -->
                        <div class="text-center mb-8">
                            <h1 id="form-title" class="text-3xl font-bold text-black mb-3">
                                Bon Retour!
                            </h1>
                            <p id="form-subtitle" class="text-gray-700 text-lg">
                                Connectez-vous à votre compte Zeduc-sp@ce
                            </p>
                        </div>

                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- FORMULAIRE DE CONNEXION -->
                        <form id="form-login" method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Nom complet (inscription uniquement) -->
                            <div id="field-name" class="hidden overflow-hidden transition-all duration-300" style="max-height: 0;">
                                <label for="name" class="block text-black font-semibold mb-2">
                                    Nom complet
                                </label>
                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="Jean Dupont"
                                    autocomplete="name"
                                    class="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                />
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-black font-semibold mb-2">
                                    Adresse Email
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Votre@email.com"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    class="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                />
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Numéro de téléphone (inscription uniquement) -->
                            <div id="field-phone" class="hidden overflow-hidden transition-all duration-300" style="max-height: 0;">
                                <label for="phone" class="block text-black font-semibold mb-2">
                                    Numéro de téléphone
                                    <span class="text-gray-500 text-sm font-normal">(Optionnel)</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                        </svg>
                                    </div>
                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="+237 6 XX XX XX XX"
                                        autocomplete="tel"
                                        class="w-full pl-12 pr-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                    />
                                </div>
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: +237 6 XX XX XX XX
                                </p>
                            </div>

                            <!-- Mot de passe -->
                            <div>
                                <label for="password" class="block text-black font-semibold mb-2">
                                    Mot de Passe
                                </label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        placeholder="****************"
                                        required
                                        autocomplete="current-password"
                                        class="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                    />
                                    <button
                                        type="button"
                                        onclick="togglePassword('password', 'eye-icon-password')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors"
                                    >
                                        <svg id="eye-icon-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmation mot de passe (inscription uniquement) -->
                            <div id="field-password-confirm" class="hidden overflow-hidden transition-all duration-300" style="max-height: 0;">
                                <label for="password_confirmation" class="block text-black font-semibold mb-2">
                                    Confirmer le mot de passe
                                </label>
                                <div class="relative">
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Retapez votre mot de passe"
                                        autocomplete="new-password"
                                        class="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                    />
                                    <button
                                        type="button"
                                        onclick="togglePassword('password_confirmation', 'eye-icon-confirmation')"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 transition-colors"
                                    >
                                        <svg id="eye-icon-confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Options (connexion uniquement) -->
                            <div id="field-remember" class="flex items-center justify-between overflow-hidden transition-all duration-300">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        class="w-5 h-5 rounded border-2 border-gray-400 text-amber-500 focus:ring focus:ring-amber-400/50"
                                    />
                                    <span class="text-black font-medium">
                                        Se souvenir de moi
                                    </span>
                                </label>
                                
                                @if (Route::has('password.request'))
                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-amber-500 hover:text-amber-600 font-medium transition-colors"
                                    >
                                        Mot de passe oublié?
                                    </a>
                                @endif
                            </div>

                            <!-- Bouton de soumission -->
                            <button
                                type="submit"
                                id="submit-button"
                                class="w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02]"
                            >
                                Se connecter
                            </button>

                            <!-- Lien de basculement -->
                            <p class="text-center text-gray-700">
                                <span id="switch-text">Pas encore de compte ?</span>
                                <button
                                    type="button"
                                    id="switch-button"
                                    onclick="switchTab('register')"
                                    class="text-amber-500 hover:text-amber-600 font-semibold transition-colors"
                                >
                                    Créer un compte
                                </button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </main>

                <x-footer class="block h-12 w-auto fill-current text-yellow-500" />


    <script>
        let currentMode = 'login';

        function switchTab(tab) {
            if (currentMode === tab) return;
            
            currentMode = tab;
            const form = document.getElementById('form-login');
            const loginTab = document.getElementById('tab-login');
            const registerTab = document.getElementById('tab-register');
            const formTitle = document.getElementById('form-title');
            const formSubtitle = document.getElementById('form-subtitle');
            const submitButton = document.getElementById('submit-button');
            const switchText = document.getElementById('switch-text');
            const switchButton = document.getElementById('switch-button');
            
            // Champs spécifiques
            const nameField = document.getElementById('field-name');
            const phoneField = document.getElementById('field-phone');
            const passwordConfirmField = document.getElementById('field-password-confirm');
            const rememberField = document.getElementById('field-remember');
            
            // Champs inputs
            const nameInput = document.getElementById('name');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');

            if (tab === 'login') {
                // Mettre à jour l'action et les champs
                form.action = "{{ route('login') }}";
                passwordInput.setAttribute('autocomplete', 'current-password');
                passwordInput.setAttribute('placeholder', '****************');
                
                // Masquer les champs d'inscription avec animation
                nameField.style.maxHeight = '0';
                phoneField.style.maxHeight = '0';
                passwordConfirmField.style.maxHeight = '0';
                setTimeout(() => {
                    nameField.classList.add('hidden');
                    phoneField.classList.add('hidden');
                    passwordConfirmField.classList.add('hidden');
                    nameInput.removeAttribute('required');
                    passwordConfirmInput.removeAttribute('required');
                }, 300);
                
                // Afficher le champ "se souvenir"
                rememberField.style.maxHeight = '100px';
                rememberField.classList.remove('hidden');
                
                // Mettre à jour les textes
                formTitle.textContent = 'Bon Retour!';
                formSubtitle.textContent = 'Connectez-vous à votre compte Zeduc-sp@ce';
                submitButton.textContent = 'Se connecter';
                switchText.textContent = 'Pas encore de compte ?';
                switchButton.textContent = 'Créer un compte';
                switchButton.setAttribute('onclick', "switchTab('register')");
                
                // Style des onglets
                loginTab.classList.add('bg-gradient-to-r', 'from-yellow-500', 'to-amber-500', 'text-black', 'shadow-md');
                loginTab.classList.remove('text-gray-600', 'hover:text-gray-800');
                registerTab.classList.remove('bg-gradient-to-r', 'from-yellow-500', 'to-amber-500', 'text-black', 'shadow-md');
                registerTab.classList.add('text-gray-600', 'hover:text-gray-800');
            } else {
                // Mettre à jour l'action et les champs
                form.action = "{{ route('register') }}";
                passwordInput.setAttribute('autocomplete', 'new-password');
                passwordInput.setAttribute('placeholder', 'Minimum 8 caractères');
                
                // Afficher les champs d'inscription avec animation
                nameField.classList.remove('hidden');
                phoneField.classList.remove('hidden');
                passwordConfirmField.classList.remove('hidden');
                nameInput.setAttribute('required', 'required');
                passwordConfirmInput.setAttribute('required', 'required');
                
                setTimeout(() => {
                    nameField.style.maxHeight = '150px';
                    phoneField.style.maxHeight = '180px';
                    passwordConfirmField.style.maxHeight = '150px';
                }, 10);
                
                // Masquer le champ "se souvenir"
                rememberField.style.maxHeight = '0';
                setTimeout(() => {
                    rememberField.classList.add('hidden');
                }, 300);
                
                // Mettre à jour les textes
                formTitle.textContent = 'Rejoignez-Nous!';
                formSubtitle.textContent = 'Créez votre compte Zeduc-sp@ce';
                submitButton.textContent = "S'inscrire";
                switchText.textContent = 'Déjà un compte ?';
                switchButton.textContent = 'Se connecter';
                switchButton.setAttribute('onclick', "switchTab('login')");
                
                // Style des onglets
                registerTab.classList.add('bg-gradient-to-r', 'from-yellow-500', 'to-amber-500', 'text-black', 'shadow-md');
                registerTab.classList.remove('text-gray-600', 'hover:text-gray-800');
                loginTab.classList.remove('bg-gradient-to-r', 'from-yellow-500', 'to-amber-500', 'text-black', 'shadow-md');
                loginTab.classList.add('text-gray-600', 'hover:text-gray-800');
            }
        }

        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Afficher le bon formulaire au chargement si des erreurs existent
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->has('name') || $errors->has('phone') || $errors->has('password_confirmation'))
                switchTab('register');
            @endif
        });
    </script>

</body>
</html>