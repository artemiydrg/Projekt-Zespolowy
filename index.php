<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user = getUserData();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minigry - Strona Główna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .game-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            height: 100%;
        }
        .game-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .game-icon {
            font-size: 4rem;
            margin: 20px 0;
        }
        .welcome-section {
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
        <div class="welcome-section text-center">
            <h1 class="display-4">👋 Witaj, <?php echo htmlspecialchars($user['username']); ?>!</h1>
            <p class="lead">Twój wynik: <strong><?php echo $user['score']; ?></strong> punktów 🏆</p>
            <p class="text-muted">Wybierz grę i zacznij zdobywać punkty!</p>
        </div>

        <h2 class="text-white text-center mb-4">🎮 Dostępne Gry</h2>
        
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card game-card" onclick="location.href='games/tictactoe.php'">
                    <div class="card-body text-center">
                        <div class="game-icon">❌⭕</div>
                        <h3 class="card-title">Kółko i Krzyżyk</h3>
                        <p class="card-text">Graj przeciwko komputerowi</p>
                        <span class="badge bg-success">+10 punktów</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card game-card" onclick="location.href='games/target.php'">
                    <div class="card-body text-center">
                        <div class="game-icon">🎯</div>
                        <h3 class="card-title">Kliknij Cele</h3>
                        <p class="card-text">Klikaj cele jak najszybciej!</p>
                        <span class="badge bg-info">Punkty za celność</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card game-card" onclick="location.href='games/snake.php'">
                    <div class="card-body text-center">
                        <div class="game-icon">🐍</div>
                        <h3 class="card-title">Wąż</h3>
                        <p class="card-text">Klasyczna gra Snake</p>
                        <span class="badge bg-info">Punkty za wynik</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card game-card" onclick="location.href='games/clicker.php'">
                    <div class="card-body text-center">
                        <div class="game-icon">🖱️</div>
                        <h3 class="card-title">Clicker</h3>
                        <p class="card-text">Klikaj jak najszybciej!</p>
                        <span class="badge bg-warning">10 sekund</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>