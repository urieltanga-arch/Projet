@extends('layouts.app')

@section('content')
<div class="min-h-screen" style="background-color: #E8DCC8;">
    <div class="container mx-auto px-4 py-8">
        <!-- Bouton retour -->
        <a href="{{ route('games.index') }}" class="inline-flex items-center mb-4 text-gray-700 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour aux jeux
        </a>

        <!-- Titre du jeu -->
        <h1 class="text-3xl font-bold mb-6 text-center" style="color: #1a1a1a;">{{ $game->title }}</h1>

        <!-- Conteneur du jeu -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <iframe 
                src="{{ asset($game->game_path) }}" 
                class="w-full" 
                style="height: 80vh; border: none;"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>
@endsection