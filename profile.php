<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user = getUserData();

$stmt = $pdo->prepare("
    SELECT game, COUNT(*) as plays, MAX(score) as best_score, AVG(score) as avg_score
    FROM games_results 
    WHERE user_id = ? 
    GROUP BY game
");
$stmt->execute([$user['id']]);
$stats = $stmt->fetchAll();

$stmt = $pdo->prepare("
    SELECT * FROM games_results 
    WHERE user_id = ? 
    ORDER BY date DESC 
    LIMIT 10
");
$stmt->execute([$user['id']]);
$recent = $stmt->fetchAll();

$gameNames = [
    'tictactoe' => 'Kółko i Krzyżyk',
    'target' => 'Kliknij Cele',
    'snake' => 'Wąż',
    'clicker' => 'Clicker'
];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mój Profil - MiniGry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="profile-card">
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-person-circle" style="font-size: 120px; color: #667eea;"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p class="text-muted">Członek od: <?php echo date('d.m.Y', strtotime($user['created_at'])); ?></p>
                </div>
                
                <div class="col-md-8">
                    <h4 class="mb-4">📊 Twoje Statystyki</h4>
                    
                    <div class="alert alert-success">
                        <h5>🏆 Łączny Wynik: <?php echo $user['score']; ?> punktów</h5>
                    </div>
                    
                    <?php if ($stats): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Gra</th>
                                        <th>Rozegrane</th>
                                        <th>Najlepszy wynik</th>
                                        <th>Średni wynik</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats as $stat): ?>
                                        <tr>
                                            <td><?php echo $gameNames[$stat['game']] ?? $stat['game']; ?></td>
                                            <td><?php echo $stat['plays']; ?></td>
                                            <td><strong><?php echo $stat['best_score']; ?></strong></td>
                                            <td><?php echo round($stat['avg_score'], 1); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Jeszcze nie zagrałeś w żadną grę. Zacznij teraz!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($recent): ?>
        <div class="profile-card">
            <h4 class="mb-4">🎮 Ostatnie Gry</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Gra</th>
                            <th>Wynik</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent as $game): ?>
                            <tr>
                                <td><?php echo $gameNames[$game['game']] ?? $game['game']; ?></td>
                                <td><span class="badge bg-primary"><?php echo $game['score']; ?> pkt</span></td>
                                <td><?php echo date('d.m.Y H:i', strtotime($game['date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>