<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz Cuisine Africaine - Order.cm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        .slide-in {
            animation: slideIn 0.5s ease-out;
        }
        
        .bounce-animation {
            animation: bounce 2s ease-in-out infinite;
        }
        
        .option-button {
            transition: all 0.3s ease;
        }
        
        .option-button:hover:not(:disabled) {
            transform: translateX(10px);
        }
        
        @keyframes confetti {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            animation: confetti 3s linear forwards;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-black min-h-screen text-white">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('minijeux.index') }}" class="inline-flex items-center text-amber-400 hover:text-amber-300 mb-4">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux mini-jeux
            </a>
            
            <h1 class="text-6xl font-bold mb-4 bounce-animation">🧩 Quiz Cuisine Africaine</h1>
            <p class="text-2xl text-gray-300 mb-6">Testez vos connaissances sur la cuisine camerounaise et africaine</p>
            
            <!-- Coût du quiz -->
            <div class="inline-block bg-red-500 text-white px-8 py-3 rounded-2xl mb-6">
                <p class="text-xl font-bold">⚠️ Coût: 20 points pour jouer</p>
            </div>
            
            <!-- Stats -->
            <div class="flex justify-center gap-6 mb-8">
                <div class="bg-gradient-to-br from-amber-500 to-yellow-600 rounded-2xl shadow-xl px-8 py-4">
                    <p class="text-sm text-amber-100 mb-1">Vos points</p>
                    <p class="text-4xl font-bold text-white" id="userPoints">{{ Auth::user()->total_points ?? 0 }}</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl px-8 py-4">
                    <p class="text-sm text-green-100 mb-1">Score actuel</p>
                    <p class="text-4xl font-bold text-white" id="currentScore">0</p>
                </div>
            </div>
        </div>

        <!-- Zone de jeu -->
        <div class="max-w-4xl mx-auto">
            <!-- Écran de démarrage -->
            <div id="startScreen" class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl shadow-2xl p-12 text-center">
                <div class="text-8xl mb-8">🍲</div>
                <h2 class="text-4xl font-bold mb-6">Prêt à jouer ?</h2>
                <p class="text-xl text-gray-300 mb-8">10 questions sur la cuisine africaine et camerounaise</p>
                
                <div class="bg-gray-700 rounded-2xl p-6 mb-8">
                    <h3 class="text-2xl font-bold text-amber-400 mb-4">Règles du jeu</h3>
                    <ul class="text-left space-y-3 text-lg">
                        <li class="flex items-center gap-3">
                            <span class="text-2xl">✅</span>
                            <span>10 questions à choix multiples</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-2xl">⏱️</span>
                            <span>30 secondes par question</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-2xl">🏆</span>
                            <span>10 points par bonne réponse</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-2xl">💯</span>
                            <span>Maximum: 100 points</span>
                        </li>
                    </ul>
                </div>
                
                <button onclick="demarrerQuiz()" 
                        class="bg-gradient-to-r from-amber-500 to-yellow-600 text-white px-12 py-5 rounded-2xl font-bold text-2xl hover:shadow-2xl transform hover:scale-105 transition-all">
                    COMMENCER LE QUIZ
                </button>
            </div>

            <!-- Écran de question -->
            <div id="questionScreen" class="hidden">
                <!-- Barre de progression -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-3">
                        <span class="text-lg font-semibold">Question <span id="questionNumber">1</span>/10</span>
                        <span class="text-lg font-semibold">Temps: <span id="timer" class="text-amber-400">30</span>s</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-4">
                        <div id="progressBar" class="bg-gradient-to-r from-amber-500 to-yellow-600 h-4 rounded-full transition-all duration-500" style="width: 10%"></div>
                    </div>
                </div>

                <!-- Question -->
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl shadow-2xl p-8 mb-6 slide-in">
                    <div class="flex items-start gap-6 mb-8">
                        <div class="text-6xl">🍽️</div>
                        <div class="flex-1">
                            <h3 id="questionText" class="text-3xl font-bold mb-4"></h3>
                        </div>
                    </div>

                    <!-- Options de réponse -->
                    <div id="optionsContainer" class="space-y-4"></div>
                </div>

                <!-- Indicateur de réponse -->
                <div id="answerFeedback" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 bg-white rounded-3xl shadow-2xl p-8 text-center">
                    <div id="feedbackIcon" class="text-9xl mb-4"></div>
                    <h3 id="feedbackTitle" class="text-4xl font-bold mb-2"></h3>
                    <p id="feedbackMessage" class="text-xl text-gray-600"></p>
                </div>
            </div>

            <!-- Écran de résultat final -->
            <div id="resultScreen" class="hidden bg-gradient-to-br from-gray-800 to-gray-900 rounded-3xl shadow-2xl p-12 text-center">
                <div id="resultIcon" class="text-9xl mb-6"></div>
                <h2 id="resultTitle" class="text-5xl font-bold mb-4"></h2>
                <p id="resultMessage" class="text-2xl text-gray-300 mb-8"></p>
                
                <div class="bg-gradient-to-r from-amber-500 to-yellow-600 rounded-2xl p-8 mb-8">
                    <p class="text-white text-2xl mb-2">Points gagnés</p>
                    <p id="pointsGagnes" class="text-7xl font-bold text-white"></p>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-700 rounded-2xl p-6">
                        <p class="text-gray-300 mb-2">Bonnes réponses</p>
                        <p id="bonnesReponses" class="text-4xl font-bold text-green-400"></p>
                    </div>
                    <div class="bg-gray-700 rounded-2xl p-6">
                        <p class="text-gray-300 mb-2">Mauvaises réponses</p>
                        <p id="mauvaisesReponses" class="text-4xl font-bold text-red-400"></p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button onclick="rejouer()" 
                            class="flex-1 bg-gradient-to-r from-amber-500 to-yellow-600 text-white px-8 py-4 rounded-xl font-bold text-xl hover:shadow-xl transition">
                        REJOUER
                    </button>
                    <a href="{{ route('minijeux.index') }}" 
                       class="flex-1 bg-gray-700 text-white px-8 py-4 rounded-xl font-bold text-xl hover:bg-gray-600 transition text-center">
                        RETOUR
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        const API_BASE = '{{ url("/") }}';

        // Questions du quiz
        const questions = [
            {
                question: "Quel est l'ingrédient principal du Ndolé camerounais ?",
                options: ["Feuilles d'arachide", "Feuilles de ndolé", "Feuilles de manioc", "Épinards"],
                correct: 1,
                explication: "Le Ndolé est fait à base de feuilles de ndolé, une plante amère typique du Cameroun."
            },
            {
                question: "Le Jollof rice est originaire de quelle région africaine ?",
                options: ["Afrique de l'Est", "Afrique du Nord", "Afrique de l'Ouest", "Afrique Centrale"],
                correct: 2,
                explication: "Le Jollof rice est un plat emblématique d'Afrique de l'Ouest, notamment du Sénégal et du Nigeria."
            },
            {
                question: "Quel plat camerounais est fait à base de haricots pilés ?",
                options: ["Koki", "Achu", "Sanga", "Kwem"],
                correct: 0,
                explication: "Le Koki (ou Koki beans) est fait de haricots pilés cuits à la vapeur dans des feuilles de bananier."
            },
            {
                question: "Le Fufu est traditionnellement accompagné de ?",
                options: ["Riz", "Soupe ou sauce", "Pain", "Pâtes"],
                correct: 1,
                explication: "Le Fufu se mange avec des soupes comme l'Eru, le Ndolé ou la sauce d'arachide."
            },
            {
                question: "Quel est le plat national du Sénégal ?",
                options: ["Couscous", "Thiéboudienne", "Yassa", "Mafé"],
                correct: 1,
                explication: "Le Thiéboudienne (riz au poisson) est considéré comme le plat national du Sénégal."
            },
            {
                question: "Qu'est-ce que le Poulet DG au Cameroun ?",
                options: ["Poulet grillé", "Poulet Directeur Général", "Poulet fumé", "Poulet braisé"],
                correct: 1,
                explication: "Le Poulet DG signifie 'Directeur Général', c'est un plat festif avec poulet, plantains et légumes."
            },
            {
                question: "Le Suya est une brochette épicée originaire de ?",
                options: ["Côte d'Ivoire", "Ghana", "Nigeria", "Cameroun"],
                correct: 2,
                explication: "Le Suya est une brochette épicée nigériane, marinée dans un mélange d'épices appelé 'yaji'."
            },
            {
                question: "Quel est l'ingrédient de base du Garri ?",
                options: ["Maïs", "Manioc", "Igname", "Patate douce"],
                correct: 1,
                explication: "Le Garri est une semoule faite à partir de manioc fermenté et grillé."
            },
            {
                question: "Le Puff-Puff est ?",
                options: ["Un gâteau frit", "Une boisson", "Une sauce", "Un poisson"],
                correct: 0,
                explication: "Le Puff-Puff est un beignet sucré et moelleux, très populaire en Afrique de l'Ouest."
            },
            {
                question: "Quelle boisson est faite à partir de fleurs d'hibiscus ?",
                options: ["Bissap", "Gnamakoudji", "Chapalo", "Zobo"],
                correct: 0,
                explication: "Le Bissap (ou Zobo au Nigeria) est une boisson rouge faite avec des fleurs d'hibiscus séchées."
            }
        ];

        let currentQuestion = 0;
        let score = 0;
        let bonnesReponses = 0;
        let timer;
        let timeLeft = 30;

        function demarrerQuiz() {
            document.getElementById('startScreen').classList.add('hidden');
            document.getElementById('questionScreen').classList.remove('hidden');
            currentQuestion = 0;
            score = 0;
            bonnesReponses = 0;
            afficherQuestion();
        }

        function afficherQuestion() {
            if (currentQuestion >= questions.length) {
                terminerQuiz();
                return;
            }

            const question = questions[currentQuestion];
            timeLeft = 30;
            
            // Mettre à jour les éléments
            document.getElementById('questionNumber').textContent = currentQuestion + 1;
            document.getElementById('questionText').textContent = question.question;
            document.getElementById('progressBar').style.width = ((currentQuestion + 1) / questions.length * 100) + '%';
            
            // Créer les options
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '';
            
            question.options.forEach((option, index) => {
                const button = document.createElement('button');
                button.className = 'option-button w-full text-left px-8 py-5 rounded-xl bg-gray-700 hover:bg-gray-600 transition text-xl font-semibold border-4 border-transparent';
                button.innerHTML = `
                    <span class="inline-flex items-center gap-4">
                        <span class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold">${String.fromCharCode(65 + index)}</span>
                        <span>${option}</span>
                    </span>
                `;
                button.onclick = () => verifierReponse(index);
                container.appendChild(button);
            });

            // Démarrer le timer
            clearInterval(timer);
            timer = setInterval(() => {
                timeLeft--;
                document.getElementById('timer').textContent = timeLeft;
                
                if (timeLeft <= 10) {
                    document.getElementById('timer').classList.add('text-red-500');
                }
                
                if (timeLeft <= 0) {
                    clearInterval(timer);
                    verifierReponse(-1); // Temps écoulé
                }
            }, 1000);
        }

        function verifierReponse(selected) {
            clearInterval(timer);
            
            const question = questions[currentQuestion];
            const buttons = document.querySelectorAll('.option-button');
            const isCorrect = selected === question.correct;
            
            // Désactiver tous les boutons
            buttons.forEach((button, index) => {
                button.disabled = true;
                
                if (index === question.correct) {
                    button.classList.add('bg-green-500', 'border-green-400');
                } else if (index === selected) {
                    button.classList.add('bg-red-500', 'border-red-400');
                }
            });
            
            // Afficher le feedback
            afficherFeedback(isCorrect, question.explication);
            
            if (isCorrect) {
                score += 10;
                bonnesReponses++;
                document.getElementById('currentScore').textContent = score;
            }
            
            // Passer à la question suivante
            setTimeout(() => {
                document.getElementById('answerFeedback').classList.add('hidden');
                currentQuestion++;
                afficherQuestion();
            }, 3000);
        }

        function afficherFeedback(isCorrect, explication) {
            const feedback = document.getElementById('answerFeedback');
            const icon = document.getElementById('feedbackIcon');
            const title = document.getElementById('feedbackTitle');
            const message = document.getElementById('feedbackMessage');
            
            if (isCorrect) {
                icon.textContent = '🎉';
                title.textContent = 'Bravo !';
                title.className = 'text-4xl font-bold mb-2 text-green-600';
                lancerConfettis();
            } else {
                icon.textContent = '😢';
                title.textContent = 'Dommage !';
                title.className = 'text-4xl font-bold mb-2 text-red-600';
            }
            
            message.textContent = explication;
            feedback.classList.remove('hidden');
        }

        async function terminerQuiz() {
            document.getElementById('questionScreen').classList.add('hidden');
            
            // Envoyer le score au serveur
            try {
                const response = await fetch(`${API_BASE}/minijeux/quiz/finish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({score: score})
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('userPoints').textContent = data.total_points;
                    afficherResultatFinal(data.points);
                }
            } catch (error) {
                console.error('Erreur:', error);
                afficherResultatFinal(score);
            }
        }

        function afficherResultatFinal(pointsGagnes) {
            const resultScreen = document.getElementById('resultScreen');
            const icon = document.getElementById('resultIcon');
            const title = document.getElementById('resultTitle');
            const message = document.getElementById('resultMessage');
            
            const pourcentage = (bonnesReponses / questions.length) * 100;
            
            if (pourcentage === 100) {
                icon.textContent = '🏆';
                title.textContent = 'PARFAIT !';
                message.textContent = 'Vous êtes un expert de la cuisine africaine !';
                lancerConfettis();
            } else if (pourcentage >= 70) {
                icon.textContent = '🎉';
                title.textContent = 'Excellent !';
                message.textContent = 'Très bonne connaissance de la cuisine africaine !';
            } else if (pourcentage >= 50) {
                icon.textContent = '😊';
                title.textContent = 'Pas mal !';
                message.textContent = 'Vous connaissez bien la cuisine africaine !';
            } else {
                icon.textContent = '📚';
                title.textContent = 'Continuez à apprendre !';
                message.textContent = 'Il y a encore beaucoup à découvrir !';
            }
            
            document.getElementById('pointsGagnes').textContent = pointsGagnes;
            document.getElementById('bonnesReponses').textContent = bonnesReponses + '/10';
            document.getElementById('mauvaisesReponses').textContent = (questions.length - bonnesReponses) + '/10';
            
            resultScreen.classList.remove('hidden');
        }

        function rejouer() {
            document.getElementById('resultScreen').classList.add('hidden');
            document.getElementById('startScreen').classList.remove('hidden');
            document.getElementById('currentScore').textContent = '0';
        }

        function lancerConfettis() {
            const couleurs = ['#FCD34D', '#F59E0B', '#EF4444', '#10B981', '#3B82F6', '#8B5CF6'];
            
            for (let i = 0; i < 40; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.className = 'confetti';
                    confetti.style.left = Math.random() * window.innerWidth + 'px';
                    confetti.style.backgroundColor = couleurs[Math.floor(Math.random() * couleurs.length)];
                    confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => confetti.remove(), 3000);
                }, i * 30);
            }
        }
    </script>
</body>
</html>