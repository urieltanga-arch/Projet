<x-app-layout>

@section('title', 'Mini-Jeux & Événements')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50 to-orange-50 py-12">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-6xl font-bold text-gray-900 mb-3">Mini-Jeux & Événements</h1>
            <p class="text-xl text-gray-600">Amusez vous et gagnez des points</p>
            
            <!-- Affichage des points -->
            <div class="mt-6 inline-flex items-center gap-3 bg-gradient-to-r from-amber-400 to-yellow-500 px-8 py-4 rounded-2xl shadow-lg">
                <span class="text-3xl">🏆</span>
                <div class="text-left">
                    <p class="text-sm font-semibold text-gray-800">Vos Points</p>
                    <p class="text-4xl font-bold text-white" id="userPoints">{{ Auth::user()->total_points ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Mini-jeux Disponibles -->
        <div class="mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-8">Mini-jeux Disponible</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Roue de Fortune -->
                <div class="bg-gradient-to-br from-amber-400 to-yellow-500 rounded-3xl shadow-xl p-8 text-center hover:scale-105 transition-transform">
                    <div class="text-7xl mb-4">🎲</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Roue de Fortune</h3>
                    <p class="text-gray-800 font-semibold mb-1">Coût: 10 pts</p>
                    <p class="text-white font-bold mb-4">Gain: 0-15 pts</p>
                    <button onclick="openRoueModal()"class="w-full bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition">
                        JOUEZ MAINTENANT
                    </button>
                </div>

                <!-- Quiz Cuisine -->
                <div class="bg-gradient-to-br from-gray-900 to-black rounded-3xl shadow-xl p-8 text-center hover:scale-105 transition-transform">
                    <div class="text-7xl mb-4">🧩</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Quiz Cuisine</h3>
                    <p class="text-amber-400 font-semibold mb-1">Coût: 20 pts</p>
                    <p class="text-white font-bold mb-4">Gain: max 20 pts</p>
                    <button onclick="openQuizModal()" 
                    class="w-full bg-amber-400 text-black px-6 py-3 rounded-xl font-bold hover:bg-amber-500 transition">
                        JOUEZ MAINTENANT
                    </button>
                </div>

                <!-- Défi Quotidien -->
                <div class="bg-white rounded-3xl shadow-xl p-8 text-center hover:scale-105 transition-transform border-4 border-gray-200">
                    <div class="text-7xl mb-4">🏆</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Défi Quotidien</h3>
                    <p class="text-gray-600 font-semibold mb-4">+ 150 pts</p>
                    <button onclick="showComingSoon()" 
                            class="w-full bg-black text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition">
                        JOUEZ MAINTENANT
                    </button>
                </div>

                <!-- Jeux de mémoire -->
                <div class="bg-gradient-to-br from-gray-800 to-black rounded-3xl shadow-xl p-8 text-center hover:scale-105 transition-transform">
                    <div class="text-7xl mb-4">🎮</div>
                    <h3 class="text-2xl font-bold text-white mb-2">Jeux de mémoire</h3>
                    <p class="text-amber-400 font-semibold mb-4">+ 75 pts</p>
                    <button onclick="showComingSoon()" 
                            class="w-full bg-amber-400 text-black px-6 py-3 rounded-xl font-bold hover:bg-amber-500 transition">
                        JOUEZ MAINTENANT
                    </button>
                </div>
            </div>
        </div>

        <!-- Événements à venir -->
        <div>
            <h2 class="text-4xl font-bold text-gray-900 mb-8">Événements a venir</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($events as $event)
                <div class="bg-white rounded-3xl shadow-xl p-8 flex items-center gap-6 hover:shadow-2xl transition">
                    <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-yellow-500 rounded-2xl flex items-center justify-center text-5xl flex-shrink-0">
                        @if($event->type === 'karaoke')
                            🎵
                        @elseif($event->type === 'football')
                            ⚽
                        @else
                            🎉
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $event->name }}</h3>
                        <p class="text-gray-600">{{ \Carbon\Carbon::parse($event->event_date)->locale('fr')->isoFormat('dddd D MMMM') }}</p>
                    </div>
                    
                    @php
                        $isParticipant = $event->participants->contains(Auth::id());
                        $isFull = $event->current_participants >= $event->max_participants;
                    @endphp
                    
                    @if($isParticipant)
                        <button class="bg-green-500 text-white px-8 py-4 rounded-2xl font-bold text-lg cursor-not-allowed" disabled>
                            ✓ Inscrit
                        </button>
                    @elseif($isFull)
                        <button class="bg-gray-400 text-white px-8 py-4 rounded-2xl font-bold text-lg cursor-not-allowed" disabled>
                            Complet
                        </button>
                    @else
                        <button onclick="participerEvent({{ $event->id }})" 
                                class="bg-gradient-to-r from-amber-400 to-yellow-500 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:shadow-lg transition">
                            {{ $event->type === 'football' ? 'Réserver place' : 'Participer' }}
                        </button>
                    @endif
                </div>
                @empty
                <div class="col-span-2 text-center py-12 text-gray-500">
                    <p class="text-2xl">Aucun événement à venir pour le moment</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Roue de Fortune -->
<div id="roueModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl shadow-2xl max-w-2xl w-full p-8 relative">
        <button onclick="closeRoueModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-4xl">×</button>
        
        <h2 class="text-4xl font-bold text-center text-gray-900 mb-6">🎡 Roue de Fortune</h2>
        <p class="text-center text-gray-600 mb-8 text-lg">Coût : <span class="font-bold text-red-600">10 points</span></p>
        
        <div class="flex justify-center mb-8">
            <div class="relative">
                <canvas id="roueCanvas" width="400" height="400"></canvas>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                    <div class="w-6 h-20 bg-red-600 transform -translate-y-[200px] shadow-lg" style="clip-path: polygon(50% 100%, 0 0, 100% 0);"></div>
                </div>
            </div>
        </div>
        
        <button id="spinButton" onclick="spinRoue()" 
                class="w-full bg-gradient-to-r from-amber-400 to-yellow-500 text-white px-8 py-4 rounded-2xl font-bold text-2xl hover:shadow-xl transition">
            TOURNER LA ROUE
        </button>
    </div>
</div>

<!-- Modal Quiz Cuisine -->
<div id="quizModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-3xl w-full p-8 relative max-h-[90vh] overflow-y-auto">
        <button onclick="closeQuizModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 text-4xl">×</button>
        
        <h2 class="text-4xl font-bold text-center text-gray-900 mb-2">🧩 Quiz Cuisine Africaine</h2>
        <p class="text-center text-gray-600 mb-8">Répondez correctement pour gagner <span class="font-bold text-green-600">100 points</span></p>
        
        <div id="quizContent"></div>
    </div>
</div>

<!-- Notification Toast -->
<div id="notification" class="hidden fixed top-4 right-4 z-50 bg-white rounded-2xl shadow-2xl p-6 min-w-[300px] transform transition-all">
    <div class="flex items-center gap-4">
        <span id="notifIcon" class="text-5xl"></span>
        <div class="flex-1">
            <p id="notifTitle" class="font-bold text-xl text-gray-900"></p>
            <p id="notifMessage" class="text-gray-600"></p>
        </div>
    </div>
</div>

@push('scripts')
<script>
const API_BASE = '{{ url("/") }}';
const CSRF_TOKEN = '{{ csrf_token() }}';
let currentRotation = 0;

// Questions du quiz
const quizQuestions = [
    {
        question: "Quel est l'ingrédient principal du Ndolé camerounais ?",
        options: ["Feuilles d'arachide", "Feuilles de ndolé", "Feuilles de manioc", "Épinards"],
        correct: 1
    },
    {
        question: "Le Jollof rice est originaire de quelle région ?",
        options: ["Afrique de l'Est", "Afrique du Nord", "Afrique de l'Ouest", "Afrique Centrale"],
        correct: 2
    },
    {
        question: "Quel plat camerounais est fait à base de haricots pilés ?",
        options: ["Koki", "Achu", "Sanga", "Kwem"],
        correct: 0
    },
    {
        question: "Le Fufu est traditionnellement accompagné de ?",
        options: ["Riz", "Soupe", "Pain", "Pâtes"],
        correct: 1
    },
    {
        question: "Quel est le plat national du Sénégal ?",
        options: ["Couscous", "Thiéboudienne", "Yassa", "Mafé"],
        correct: 1
    }
];

let currentQuestion = 0;
let quizScore = 0;

// Roue de Fortune
const segments = [
    {label: "0", color: "#EF4444", points: 0},
    {label: "15", color: "#F59E0B", points: 15},
    {label: "30", color: "#10B981", points: 30},
    {label: "45", color: "#3B82F6", points: 45},
    {label: "60", color: "#8B5CF6", points: 60},
    {label: "75", color: "#EC4899", points: 75},
    {label: "0", color: "#EF4444", points: 0},
    {label: "15", color: "#F59E0B", points: 15},
    {label: "30", color: "#10B981", points: 30},
    {label: "45", color: "#3B82F6", points: 45},
    {label: "60", color: "#8B5CF6", points: 60},
    {label: "75", color: "#EC4899", points: 75}
];

function drawRoue() {
    const canvas = document.getElementById('roueCanvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    const radius = 180;
    const anglePerSegment = (2 * Math.PI) / segments.length;
    
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    segments.forEach((segment, index) => {
        const startAngle = index * anglePerSegment + currentRotation;
        const endAngle = startAngle + anglePerSegment;
        
        // Dessiner le segment
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, startAngle, endAngle);
        ctx.lineTo(centerX, centerY);
        ctx.fillStyle = segment.color;
        ctx.fill();
        ctx.strokeStyle = "#fff";
        ctx.lineWidth = 3;
        ctx.stroke();
        
        // Ajouter le texte
        ctx.save();
        ctx.translate(centerX, centerY);
        ctx.rotate(startAngle + anglePerSegment / 2);
        ctx.textAlign = "center";
        ctx.fillStyle = "#fff";
        ctx.font = "bold 24px Arial";
        ctx.fillText(segment.label, radius - 50, 10);
        ctx.restore();
    });
    
    // Cercle central
    ctx.beginPath();
    ctx.arc(centerX, centerY, 40, 0, 2 * Math.PI);
    ctx.fillStyle = "#1F2937";
    ctx.fill();
}

function spinRoue() {
    const userPoints = parseInt(document.getElementById('userPoints').textContent);
    
    if (userPoints < 10) {
        showNotification('❌', 'Points insuffisants', 'Vous avez besoin de 10 points pour jouer');
        return;
    }
    
    document.getElementById('spinButton').disabled = true;
    
    fetch(`${API_BASE}/minijeux/roue/spin`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            animateRoue(data.segment, data.points);
        } else {
            showNotification('❌', 'Erreur', data.message);
            document.getElementById('spinButton').disabled = false;
        }
    });
}

function animateRoue(winningSegment, points) {
    const targetRotation = (2 * Math.PI * 5) + (winningSegment * (2 * Math.PI / segments.length));
    const duration = 4000;
    const start = Date.now();
    const startRotation = currentRotation;
    
    function animate() {
        const now = Date.now();
        const progress = Math.min((now - start) / duration, 1);
        const easeOut = 1 - Math.pow(1 - progress, 3);
        
        currentRotation = startRotation + (targetRotation * easeOut);
        drawRoue();
        
        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            currentRotation = targetRotation % (2 * Math.PI);
            setTimeout(() => {
                updateUserPoints();
                const icon = points > 0 ? '🎉' : '😢';
                const title = points > 0 ? 'Félicitations!' : 'Dommage!';
                const message = points > 0 ? `Vous avez gagné ${points} points!` : 'Réessayez!';
                showNotification(icon, title, message);
                document.getElementById('spinButton').disabled = false;
                closeRoueModal();
            }, 1000);
        }
    }
    
    animate();
}

// Quiz Functions
function openQuizModal() {
    currentQuestion = 0;
    quizScore = 0;
    document.getElementById('quizModal').classList.remove('hidden');
    showQuestion();
}

function closeQuizModal() {
    document.getElementById('quizModal').classList.add('hidden');
}

function showQuestion() {
    const question = quizQuestions[currentQuestion];
    const content = document.getElementById('quizContent');
    
    content.innerHTML = `
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <span class="text-lg font-semibold text-gray-600">Question ${currentQuestion + 1}/${quizQuestions.length}</span>
                <span class="text-lg font-semibold text-amber-600">Score: ${quizScore}</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-6">${question.question}</h3>
            <div class="space-y-4">
                ${question.options.map((option, index) => `
                    <button onclick="checkAnswer(${index})" 
                            class="quiz-option w-full text-left px-6 py-4 rounded-xl border-2 border-gray-300 hover:border-amber-400 hover:bg-amber-50 transition font-semibold text-lg">
                        ${option}
                    </button>
                `).join('')}
            </div>
        </div>
    `;
}

function checkAnswer(selected) {
    const question = quizQuestions[currentQuestion];
    const options = document.querySelectorAll('.quiz-option');
    
    options.forEach((option, index) => {
        option.disabled = true;
        if (index === question.correct) {
            option.classList.add('bg-green-500', 'text-white', 'border-green-500');
        } else if (index === selected && selected !== question.correct) {
            option.classList.add('bg-red-500', 'text-white', 'border-red-500');
        }
    });
    
    if (selected === question.correct) {
        quizScore += 20;
    }
    
    setTimeout(() => {
        currentQuestion++;
        if (currentQuestion < quizQuestions.length) {
            showQuestion();
        } else {
            finishQuiz();
        }
    }, 1500);
}

function finishQuiz() {
    fetch(`${API_BASE}/minijeux/quiz/finish`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({score: quizScore})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            updateUserPoints();
            showNotification('🎉', 'Quiz terminé!', `Vous avez gagné ${data.points} points!`);
            closeQuizModal();
        }
    });
}

// Event Participation
function participerEvent(eventId) {
    fetch(`${API_BASE}/events/${eventId}/participate`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showNotification('✓', 'Inscription réussie!', data.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            showNotification('❌', 'Erreur', data.message);
        }
    });
}

// Utilities
function openRoueModal() {
    document.getElementById('roueModal').classList.remove('hidden');
    drawRoue();
}

function closeRoueModal() {
    document.getElementById('roueModal').classList.add('hidden');
}

function showComingSoon() {
    showNotification('🚧', 'Bientôt disponible', 'Ce jeu sera disponible prochainement!');
}

function updateUserPoints() {
    fetch(`${API_BASE}/user/points`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('userPoints').textContent = data.points;
        });
}

function showNotification(icon, title, message) {
    const notif = document.getElementById('notification');
    document.getElementById('notifIcon').textContent = icon;
    document.getElementById('notifTitle').textContent = title;
    document.getElementById('notifMessage').textContent = message;
    
    notif.classList.remove('hidden');
    setTimeout(() => {
        notif.classList.add('hidden');
    }, 4000);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    drawRoue();
});
</script>
@endpush
</x-app-layout>