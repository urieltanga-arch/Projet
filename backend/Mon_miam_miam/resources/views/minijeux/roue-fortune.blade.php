<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Roue de Fortune - Order.cm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes confetti {
            0% { transform: translateY(0) rotateZ(0deg); opacity: 1; }
            100% { transform: translateY(1000px) rotateZ(720deg); opacity: 0; }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            animation: confetti 3s linear forwards;
        }
        
        #roueCanvas {
            transition: transform 0.1s;
            cursor: pointer;
        }
        
        .pulse-ring {
            animation: pulse-ring 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse-ring {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('minijeux.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux mini-jeux
            </a>
            
            <h1 class="text-6xl font-bold text-gray-900 mb-4 float-animation">🎡 Roue de Fortune</h1>
            <p class="text-2xl text-gray-600 mb-6">Tentez votre chance et gagnez des points !</p>
            
            <!-- Stats utilisateur -->
            <div class="flex justify-center gap-6 mb-8">
                <div class="bg-white rounded-2xl shadow-xl px-8 py-4">
                    <p class="text-sm text-gray-600 mb-1">Vos points</p>
                    <p class="text-4xl font-bold text-amber-600" id="userPoints">{{ Auth::user()->total_points ?? 0 }}</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl px-8 py-4">
                    <p class="text-sm text-gray-600 mb-1">Tours joués</p>
                    <p class="text-4xl font-bold text-blue-600" id="toursJoues">0</p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl px-8 py-4">
                    <p class="text-sm text-gray-600 mb-1">Gain total</p>
                    <p class="text-4xl font-bold text-green-600" id="gainTotal">0</p>
                </div>
            </div>
        </div>

        <!-- Zone principale -->
        <div class="max-w-4xl mx-auto">
            <!-- Roue -->
            <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
                <div class="text-center mb-6">
                    <p class="text-2xl font-bold text-gray-900 mb-2">Coût par tour: <span class="text-red-600">10 points</span></p>
                    <p class="text-gray-600">Gains possibles: 0, 15, 30, 45, 60, 75 points</p>
                </div>
                
                <div class="relative flex justify-center items-center" style="height: 500px;">
                    <!-- Indicateur/Flèche -->
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 z-20" style="margin-top: -20px;">
                        <div class="w-0 h-0 border-l-[30px] border-l-transparent border-r-[30px] border-r-transparent border-t-[60px] border-t-red-600 drop-shadow-2xl"></div>
                    </div>
                    
                    <!-- Canvas de la roue -->
                    <canvas id="roueCanvas" width="450" height="450" class="drop-shadow-2xl"></canvas>
                    
                    <!-- Bouton central -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <button id="spinButton" onclick="tournerRoue()" 
                                class="relative w-32 h-32 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-full shadow-2xl hover:shadow-3xl transform hover:scale-110 transition-all duration-300 border-8 border-white">
                            <span class="text-3xl font-bold text-white">SPIN</span>
                            <div class="absolute inset-0 rounded-full bg-amber-300 opacity-30 pulse-ring"></div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Légende des gains -->
            <div class="bg-white rounded-3xl shadow-xl p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">🎯 Table des gains</h3>
                <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                    <div class="text-center p-4 rounded-xl bg-red-500 text-white">
                        <p class="text-3xl font-bold">0</p>
                        <p class="text-sm">Perdu</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-orange-500 text-white">
                        <p class="text-3xl font-bold">15</p>
                        <p class="text-sm">pts</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-green-500 text-white">
                        <p class="text-3xl font-bold">30</p>
                        <p class="text-sm">pts</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-blue-500 text-white">
                        <p class="text-3xl font-bold">45</p>
                        <p class="text-sm">pts</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-purple-500 text-white">
                        <p class="text-3xl font-bold">60</p>
                        <p class="text-sm">pts</p>
                    </div>
                    <div class="text-center p-4 rounded-xl bg-pink-500 text-white">
                        <p class="text-3xl font-bold">75</p>
                        <p class="text-sm">pts</p>
                    </div>
                </div>
            </div>

            <!-- Historique des derniers tours -->
            <div class="mt-8 bg-white rounded-3xl shadow-xl p-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">📜 Derniers résultats</h3>
                <div id="historique" class="flex flex-wrap gap-3">
                    <p class="text-gray-400 italic">Aucun tour joué pour le moment</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de résultat -->
    <div id="resultModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full text-center transform scale-95 transition-transform">
            <div id="resultIcon" class="text-9xl mb-4"></div>
            <h2 id="resultTitle" class="text-4xl font-bold text-gray-900 mb-4"></h2>
            <p id="resultMessage" class="text-2xl text-gray-600 mb-6"></p>
            <div class="bg-gradient-to-r from-amber-400 to-yellow-500 rounded-2xl p-4 mb-6">
                <p class="text-white text-lg">Nouveau solde</p>
                <p id="newBalance" class="text-5xl font-bold text-white"></p>
            </div>
            <button onclick="closeResultModal()" 
                    class="w-full bg-gradient-to-r from-gray-800 to-black text-white px-8 py-4 rounded-xl font-bold text-xl hover:shadow-xl transition">
                Continuer
            </button>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        const API_BASE = '{{ url("/") }}';
        
        let currentRotation = 0;
        let isSpinning = false;
        let toursJoues = 0;
        let gainTotal = 0;
        let historique = [];

        // Configuration des segments (12 segments) - Gains réduits de 80%
        const segments = [
            {value: 0, color: '#EF4444', label: '0'},     // Rouge
            {value: 3, color: '#F59E0B', label: '3'},     // Orange
            {value: 6, color: '#10B981', label: '6'},     // Vert
            {value: 9, color: '#3B82F6', label: '9'},     // Bleu
            {value: 12, color: '#8B5CF6', label: '12'},   // Violet
            {value: 15, color: '#EC4899', label: '15'},   // Rose
            {value: 0, color: '#EF4444', label: '0'},     // Rouge
            {value: 3, color: '#F59E0B', label: '3'},     // Orange
            {value: 6, color: '#10B981', label: '6'},     // Vert
            {value: 9, color: '#3B82F6', label: '9'},     // Bleu
            {value: 12, color: '#8B5CF6', label: '12'},   // Violet
            {value: 15, color: '#EC4899', label: '15'}    // Rose
        ];

        const canvas = document.getElementById('roueCanvas');
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 210;

        // Dessiner la roue
        function dessinerRoue() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            const angleParSegment = (2 * Math.PI) / segments.length;
            
            segments.forEach((segment, index) => {
                const angleDebut = index * angleParSegment + currentRotation;
                const angleFin = angleDebut + angleParSegment;
                
                // Dessiner le segment
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, angleDebut, angleFin);
                ctx.lineTo(centerX, centerY);
                ctx.fillStyle = segment.color;
                ctx.fill();
                
                // Bordure
                ctx.strokeStyle = '#ffffff';
                ctx.lineWidth = 4;
                ctx.stroke();
                
                // Texte
                ctx.save();
                ctx.translate(centerX, centerY);
                ctx.rotate(angleDebut + angleParSegment / 2);
                ctx.textAlign = 'center';
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 28px Arial';
                ctx.fillText(segment.label, radius - 60, 10);
                ctx.restore();
            });
            
            // Cercle central décoratif
            ctx.beginPath();
            ctx.arc(centerX, centerY, 70, 0, 2 * Math.PI);
            ctx.fillStyle = '#1F2937';
            ctx.fill();
            ctx.strokeStyle = '#FCD34D';
            ctx.lineWidth = 6;
            ctx.stroke();
        }

        // Tourner la roue
        async function tournerRoue() {
            if (isSpinning) return;
            
            const userPoints = parseInt(document.getElementById('userPoints').textContent);
            
            if (userPoints < 10) {
                alert('❌ Vous n\'avez pas assez de points ! (10 points requis)');
                return;
            }
            
            isSpinning = true;
            document.getElementById('spinButton').disabled = true;
            document.getElementById('spinButton').classList.add('opacity-50');
            
            try {
                const response = await fetch(`${API_BASE}/minijeux/roue/spin`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    await animerRoue(data.segment, data.points);
                    afficherResultat(data.points, data.total_points);
                    mettreAJourStats(data.points);
                    ajouterHistorique(data.points);
                } else {
                    alert('❌ ' + data.message);
                    isSpinning = false;
                    document.getElementById('spinButton').disabled = false;
                    document.getElementById('spinButton').classList.remove('opacity-50');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('❌ Une erreur est survenue');
                isSpinning = false;
                document.getElementById('spinButton').disabled = false;
                document.getElementById('spinButton').classList.remove('opacity-50');
            }
        }

        // Animer la rotation
        function animerRoue(segmentGagnant, points) {
            return new Promise((resolve) => {
                const angleParSegment = (2 * Math.PI) / segments.length;
                const angleSegment = segmentGagnant * angleParSegment;
                
                // 5 tours complets + angle du segment gagnant + offset pour pointer vers le haut
                const toursComplets = 5;
                const offsetHaut = Math.PI / 2; // 90 degrés pour pointer vers le haut
                const rotationFinale = (2 * Math.PI * toursComplets) - angleSegment + offsetHaut;
                
                const duree = 5000; // 5 secondes
                const debut = Date.now();
                const rotationDepart = currentRotation;
                
                function animer() {
                    const maintenant = Date.now();
                    const progression = Math.min((maintenant - debut) / duree, 1);
                    
                    // Courbe d'accélération (easing out cubic)
                    const ease = 1 - Math.pow(1 - progression, 3);
                    
                    currentRotation = rotationDepart + (rotationFinale * ease);
                    dessinerRoue();
                    
                    if (progression < 1) {
                        requestAnimationFrame(animer);
                    } else {
                        currentRotation = currentRotation % (2 * Math.PI);
                        isSpinning = false;
                        document.getElementById('spinButton').disabled = false;
                        document.getElementById('spinButton').classList.remove('opacity-50');
                        
                        if (points > 0) {
                            lancerConfettis();
                        }
                        
                        resolve();
                    }
                }
                
                animer();
            });
        }

        // Afficher le résultat
        function afficherResultat(points, nouveauSolde) {
            const modal = document.getElementById('resultModal');
            const icon = document.getElementById('resultIcon');
            const title = document.getElementById('resultTitle');
            const message = document.getElementById('resultMessage');
            const balance = document.getElementById('newBalance');
            
            if (points === 0) {
                icon.textContent = '😢';
                title.textContent = 'Dommage !';
                message.textContent = 'Vous n\'avez rien gagné cette fois';
            } else if (points <= 30) {
                icon.textContent = '😊';
                title.textContent = 'Bien joué !';
                message.textContent = `Vous avez gagné ${points} points !`;
            } else if (points <= 60) {
                icon.textContent = '🎉';
                title.textContent = 'Excellent !';
                message.textContent = `Vous avez gagné ${points} points !`;
            } else {
                icon.textContent = '🏆';
                title.textContent = 'JACKPOT !';
                message.textContent = `Incroyable ! ${points} points !`;
            }
            
            balance.textContent = nouveauSolde + ' pts';
            document.getElementById('userPoints').textContent = nouveauSolde;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeResultModal() {
            const modal = document.getElementById('resultModal');
            modal.querySelector('div').classList.remove('scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Lancer des confettis
        function lancerConfettis() {
            const couleurs = ['#FCD34D', '#F59E0B', '#EF4444', '#10B981', '#3B82F6', '#8B5CF6', '#EC4899'];
            
            for (let i = 0; i < 50; i++) {
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

        // Mettre à jour les stats
        function mettreAJourStats(points) {
            toursJoues++;
            gainTotal += points;
            
            document.getElementById('toursJoues').textContent = toursJoues;
            document.getElementById('gainTotal').textContent = gainTotal;
        }

        // Ajouter à l'historique
        function ajouterHistorique(points) {
            historique.unshift(points);
            if (historique.length > 10) historique.pop();
            
            const historiqueDiv = document.getElementById('historique');
            historiqueDiv.innerHTML = historique.map(p => {
                const couleur = p === 0 ? 'bg-red-500' : p <= 30 ? 'bg-orange-500' : p <= 60 ? 'bg-green-500' : 'bg-purple-500';
                return `<span class="${couleur} text-white px-4 py-2 rounded-lg font-bold">${p}</span>`;
            }).join('');
        }

        // Initialiser
        dessinerRoue();
    </script>
</body>
</html>