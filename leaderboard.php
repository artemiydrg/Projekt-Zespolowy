<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$stmt = $pdo->query("
    SELECT id, username, score 
    FROM users 
    ORDER BY score DESC 
    LIMIT 10
");
$leaderboard = $stmt->fetchAll();

$gameRankings = [];
$games = ['tictactoe', 'target', 'snake', 'clicker'];
$gameNames = [
    'tictactoe' => 'Kółko i Krzyżyk',
    'target' => 'Kliknij Cele',
    'snake' => 'Wąż',
    'clicker' => 'Clicker'
];

foreach ($games as $game) {
    $stmt = $pdo->prepare("
        SELECT u.username, MAX(gr.score) as best_score
        FROM games_results gr
        JOIN users u ON gr.user_id = u.id
        WHERE gr.game = ?
        GROUP BY u.id, u.username
        ORDER BY best_score DESC
        LIMIT 5
    ");
    $stmt->execute([$game]);
    $gameRankings[$game] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ranking - MiniGry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .leaderboard-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .rank-1 { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); }
        .rank-2 { background: linear-gradient(135deg, #C0C0C0 0%, #808080 100%); }
        .rank-3 { background: linear-gradient(135deg, #CD7F32 0%, #8B4513 100%); }
        .rank-badge {
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <div class="leaderboard-card">
            <h2 class="text-center mb-4">🏆 TOP 10 Graczy</h2>
            
            <?php if ($leaderboard): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80">Miejsce</th>
                                <th>Gracz</th>
                                <th class="text-end">Punkty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($leaderboard as $index => $player): ?>
                                <tr <?php if ($player['id'] == $_SESSION['user_id']) echo 'class="table-primary"'; ?>>
                                    <td>
                                        <?php if ($index < 3): ?>
                                            <span class="rank-badge rank-<?php echo $index + 1; ?>">
                                                <?php 
                                                echo $index == 0 ? '🥇' : ($index == 1 ? '🥈' : '🥉');
                                                echo ' #' . ($index + 1);
                                                ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">#<?php echo $index + 1; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($player['username']); ?></strong>
                                        <?php if ($player['id'] == $_SESSION['user_id']) echo '<span class="badge bg-info ms-2">To Ty!</span>'; ?>
                                    </td>
                                    <td class="text-end">
                                        <h5 class="mb-0"><?php echo $player['score']; ?> pkt</h5>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">Brak danych w rankingu</p>
            <?php endif; ?>
        </div>

        <h3 class="text-white text-center mb-4">🎮 Rankingi według gier</h3>
        
        <div class="row">
            <?php foreach ($games as $game): ?>
                <div class="col-md-6 mb-4">
                    <div class="leaderboard-card">
                        <h5 class="mb-3"><?php echo $gameNames[$game]; ?></h5>
                        <?php if (!empty($gameRankings[$game])): ?>
                            <table class="table table-sm">
                                <tbody>
                                    <?php foreach ($gameRankings[$game] as $idx => $player): ?>
                                        <tr>
                                            <td width="50"><?php echo $idx + 1; ?>.</td>
                                            <td><?php echo htmlspecialchars($player['username']); ?></td>
                                            <td class="text-end"><strong><?php echo $player['best_score']; ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted small">Brak wyników</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>