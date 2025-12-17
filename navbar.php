<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <strong>🎮 MiniGry</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-house-door"></i> Główna
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="gamesDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-controller"></i> Gry
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="games/tictactoe.php">❌⭕ Kółko i Krzyżyk</a></li>
                        <li><a class="dropdown-item" href="games/target.php">🎯 Kliknij Cele</a></li>
                        <li><a class="dropdown-item" href="games/snake.php">🐍 Wąż</a></li>
                        <li><a class="dropdown-item" href="games/clicker.php">🖱️ Clicker</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="leaderboard.php">
                        <i class="bi bi-trophy"></i> Ranking
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="bi bi-person-circle"></i> Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Wyloguj
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>