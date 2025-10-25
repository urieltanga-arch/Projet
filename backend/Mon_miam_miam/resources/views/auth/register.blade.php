
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription - Zeduc-sp@ce</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="min-h-screen bg-gradient-to-b from-amber-50 to-stone-200">
            <!-- HEADER -->
            <header class="flex items-center justify-between px-8 py-4 bg-black shadow-md">
                <div class="flex items-center">
                    <img
                        src="{{ asset('image 11.svg') }}"
                        alt="Zeduc Logo"
                        class="h-10 w-auto"
                        onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-amber-500 text-2xl font-bold\'>Zeduc-sp@ce</span>';"
                    />
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
                                <a
                                    href="{{ route('login') }}"
                                    class="flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all text-center text-gray-600 hover:text-gray-800"
                                >
                                    Connexion
                                </a>
                                
                                <a
                                    href="{{ route('register') }}"
                                    class="flex-1 py-3 px-6 rounded-xl font-semibold text-lg transition-all text-center bg-gradient-to-r from-yellow-500 to-amber-500 text-black shadow-md"
                                >
                                    Inscription
                                </a>
                            </div>
                        </div>

                        <!-- FORMULAIRE D'INSCRIPTION -->
                        <div class="bg-white shadow-lg rounded-2xl p-10 border border-amber-100">
                            <div class="text-center mb-8">
                                <h1 class="text-3xl font-bold text-black mb-3">
                                    Rejoignez-Nous!
                                </h1>
                                <p class="text-gray-700 text-lg">
                                    Créez votre compte Zeduc-sp@ce
                                </p>
                            </div>

                            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                                @csrf

                                <!-- Nom complet -->
                                <div>
                                    <label for="name" class="block text-black font-semibold mb-2">
                                        Nom complet
                                    </label>
                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="Jean Dupont"
                                        required
                                        autofocus
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
                                        placeholder="votre@email.com"
                                        required
                                        autocomplete="username"
                                        class="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                    />
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Numéro de téléphone (optionnel) -->
                                <div>
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
                                            placeholder="Minimum 8 caractères"
                                            required
                                            autocomplete="new-password"
                                            class="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                        />
                                        <button
                                            type="button"
                                            onclick="togglePassword('password')"
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

                                <!-- Confirmation mot de passe -->
                                <div>
                                    <label for="password_confirmation" class="block text-black font-semibold mb-2">
                                        Confirmer le mot de passe
                                    </label>
                                    <div class="relative">
                                        <input
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            placeholder="Retapez votre mot de passe"
                                            required
                                            autocomplete="new-password"
                                            class="w-full px-4 py-4 rounded-xl bg-amber-50 border-none text-gray-700 placeholder-gray-400 focus:outline-none focus:ring focus:ring-amber-400/50"
                                        />
                                        <button
                                            type="button"
                                            onclick="togglePassword('password_confirmation')"
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

                                <!-- Bouton inscription -->
                                <button
                                    type="submit"
                                    class="w-full py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-black font-bold text-lg rounded-2xl hover:shadow-lg transition-all transform hover:scale-[1.02]"
                                >
                                    S'inscrire
                                </button>

                                <!-- Lien connexion -->
                                <p class="text-center text-gray-700">
                                    Déjà un compte ?
                                    <a
                                        href="{{ route('login') }}"
                                        class="text-amber-500 hover:text-amber-600 font-semibold transition-colors"
                                    >
                                        Se connecter
                                    </a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <!-- FOOTER -->
            <footer class="mt-auto pt-8 pb-6">
                <div class="max-w-4xl mx-auto px-4">
                    <div class="border-t border-gray-300 mb-6"></div>
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-600">
                        <p>© {{ date('Y') }} Zeduc-sp@ce. Tous droits réservés.</p>
                        <div class="flex gap-6">
                            <a href="#" class="hover:text-amber-600 transition-colors">
                                Conditions d'utilisation
                            </a>
                            <a href="#" class="hover:text-amber-600 transition-colors">
                                Politique de confidentialité
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <script>
            function togglePassword(fieldId) {
                const passwordInput = document.getElementById(fieldId);
                const eyeIcon = document.getElementById(`eye-icon-${fieldId === 'password' ? 'password' : 'confirmation'}`);
                
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
        </script>
    </body>
    </html>
