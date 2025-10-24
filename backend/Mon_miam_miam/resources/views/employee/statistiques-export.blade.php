<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Statistiques - Order.cm</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f9fafb;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #eab308;
        }
        .header h1 {
            color: #1f2937;
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header p {
            color: #6b7280;
            font-size: 16px;
        }
        .info-box {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
            padding: 30px;
            border-radius: 12px;
            color: #1f2937;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        .stat-card .value {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-card .unit {
            font-size: 14px;
            opacity: 0.9;
        }
        .section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .section h2 {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eab308;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background: #eab308;
            color: #1f2937;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        table tr:hover {
            background: #fef3c7;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        @media print {
            body {
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Rapport de Statistiques</h1>
        <p>Order.cm - Restaurant</p>
        <p style="margin-top: 10px; font-weight: bold;">
            Période: 
            @if($periode === 'jour')
                Aujourd'hui ({{ $dateDebut->format('d/m/Y') }})
            @elseif($periode === 'semaine')
                Cette semaine (du {{ $dateDebut->format('d/m/Y') }} au {{ now()->format('d/m/Y') }})
            @elseif($periode === 'mois')
                Ce mois ({{ $dateDebut->format('F Y') }})
            @else
                Cette année ({{ $dateDebut->format('Y') }})
            @endif
        </p>
    </div>

    <div class="info-box">
        <strong>Rapport généré le:</strong> {{ now()->format('d/m/Y à H:i') }}
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>COMMANDES TOTALES</h3>
            <div class="value">{{ $commandesTotales }}</div>
            <div class="unit">commandes</div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); color: white;">
            <h3>CHIFFRE D'AFFAIRES</h3>
            <div class="value">{{ number_format($chiffreAffaires / 1000000, 1) }}M</div>
            <div class="unit">FCFA</div>
        </div>
    </div>

    <div class="section">
        <h2>🍽️ Top 5 des Plats les Plus Vendus</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom du Plat</th>
                    <th>Prix Unitaire</th>
                    <th>Quantité Vendue</th>
                    <th>Revenus Générés</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topPlats as $index => $plat)
                <tr>
                    <td><strong>{{ $index + 1 }}</strong></td>
                    <td>{{ $plat['nom'] }}</td>
                    <td>{{ number_format($plat['prix'], 0, ',', ' ') }} FCFA</td>
                    <td><strong>{{ $plat['quantite'] }}</strong></td>
                    <td><strong>{{ number_format($plat['revenus'], 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px;">
                        Aucune donnée disponible pour cette période
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>📈 Résumé des Performances</h2>
        <table>
            <tr>
                <td><strong>Commande moyenne</strong></td>
                <td style="text-align: right;">
                    @if($commandesTotales > 0)
                        {{ number_format($chiffreAffaires / $commandesTotales, 0, ',', ' ') }} FCFA
                    @else
                        0 FCFA
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Chiffre d'affaires total</strong></td>
                <td style="text-align: right;"><strong>{{ number_format($chiffreAffaires, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
            <tr>
                <td><strong>Nombre de commandes</strong></td>
                <td style="text-align: right;"><strong>{{ $commandesTotales }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p><strong>Order.cm</strong> - Système de Gestion de Restaurant</p>
        <p>© Copyright 2025, All Rights Reserved.</p>
        <p style="margin-top: 10px;">
            Ce rapport est confidentiel et destiné uniquement à un usage interne.
        </p>
    </div>

    <script>
        // Auto-print au chargement
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>