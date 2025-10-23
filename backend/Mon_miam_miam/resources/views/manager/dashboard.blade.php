@extends('layout')

@section('title', 'Dashboard Gérant - Zebus Space')

@section('content')
<div class="max-w-[1400px] mx-auto px-8 py-8">
    <div class="mb-8">
        <h1 class="text-4xl mb-2">Dashboard Gérant</h1>
        <p class="text-gray-600">Vue d'ensemble en temp réel du restaurant</p>
    </div>

    <!-- Alertes importantes -->
    <div class="bg-[#EE9A9A] rounded-2xl p-6 mb-8 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="bg-[#E57373] rounded-full p-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-xl mb-1">Alertes importantes</h3>
                <p class="text-sm">3 réclamations urgentes en attente . Stock faible : Tiramisu</p>
            </div>
        </div>
        <button class="bg-white text-gray-800 px-6 py-2 rounded-full hover:bg-gray-100 transition-colors">
            Voir Détails
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-6 mb-8">
        <div class="bg-[#D4AF37] rounded-2xl p-6 text-black">
            <svg class="w-10 h-10 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <div class="text-4xl mb-2">{{ $stats['commandes_actives'] ?? 12 }}</div>
            <div class="text-sm">En Cours</div>
            <div class="mt-2">Commandes Actives</div>
        </div>
        <div class="bg-black rounded-2xl p-6 text-white">
            <svg class="w-10 h-10 mb-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
            </svg>
            <div class="text-4xl mb-2">{{ $stats['revenus_jour'] ?? '485k' }}</div>
            <div class="text-sm">FCFA</div>
            <div class="mt-2">Revenus du jour</div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <svg class="w-10 h-10 mb-4 text-[#6B7FFF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <div class="text-4xl mb-2">{{ $stats['clients_actifs'] ?? 28 }}</div>
            <div class="text-sm">Connectés</div>
            <div class="mt-2">Clients Actifs</div>
        </div>
        <div class="bg-white rounded-2xl p-6 border border-gray-200">
            <svg class="w-10 h-10 mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            <div class="text-4xl mb-2">{{ $stats['performance'] ?? '94%' }}</div>
            <div class="text-sm">Efficacité</div>
            <div class="mt-2">Performance Équipe</div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-8">
        <!-- Commandes en Temps Réel -->
        <div class="bg-white rounded-2xl p-6">
            <h3 class="text-xl mb-6">Commandes en Temps Réel</h3>
            <div class="space-y-3">
                @foreach($orders ?? [] as $order)
                <div class="flex items-center justify-between p-4 bg-[rgba(242,242,10,0.13)] rounded-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 {{ $order['color'] }} rounded-full"></div>
                        <div>
                            <div>{{ $order['name'] }}</div>
                            <div class="text-sm text-gray-500">{{ $order['description'] }}</div>
                        </div>
                    </div>
                    <div>
                        <div>{{ $order['price'] }}</div>
                        <div class="text-xs text-gray-500">en preparation</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Performances Journaliere -->
        <div class="bg-white rounded-2xl p-6">
            <h3 class="text-xl mb-6">Performances Journaliere</h3>
            <canvas id="performanceChart" width="400" height="300"></canvas>
        </div>
    </div>

    <!-- Équipe en Service -->
    <div>
        <h3 class="text-xl mb-6">Équipe en Service</h3>
        <div class="grid grid-cols-4 gap-6">
            @foreach($employees ?? [] as $employee)
            <div class="bg-white rounded-xl p-6 border border-gray-200">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-12 h-12 bg-[#1E1E3F] rounded-full flex items-center justify-center text-white text-xl">
                        👤
                    </div>
                    <div>
                        <div>{{ $employee['name'] }}</div>
                        <div class="text-sm text-gray-500">{{ $employee['role'] }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">{{ $employee['status'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Graphique de performance
const ctx = document.getElementById('performanceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['8h', '9h', '10h', '11h', '12h', '13h', '14h', '15h', '16h', '17h', '18h', '19h', '20h'],
        datasets: [{
            label: 'Commandes',
            data: [20, 40, 60, 120, 180, 160, 100, 80, 90, 140, 180, 160, 120],
            borderColor: '#000000',
            borderWidth: 2,
            fill: false,
            tension: 0.4
        }, {
            label: 'Revenus (x1000 FCFA)',
            data: [15, 25, 35, 45, 55, 52, 48, 42, 45, 50, 54, 52, 48],
            borderColor: '#D4AF37',
            borderWidth: 2,
            fill: false,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
