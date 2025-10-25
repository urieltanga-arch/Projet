<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $game->title }}</title>
    <style>
        body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #0066cc; text-decoration: none; }
        iframe { width: 100%; height: 600px; border: 2px solid #ddd; border-radius: 8px; }
    </style>
</head>
<body>
    <a href="{{ route('games.index') }}" class="back-link">← Retour aux jeux</a>
    <h1>{{ $game->title }}</h1>
    <p>{{ $game->description }}</p>
    
    <iframe src="{{ asset('games/' . $game->folder_name . '/index.html') }}" title="{{ $game->title }}"></iframe>
</body>
</html>