<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kliknij Cele - MiniGry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow: hidden;
        }
        .game-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 30px auto;
            max-width: 900px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .game-area {
            width: 100%;
            height: 500px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border: 3px solid #667eea;
            border-radius: 15px;
            position: relative;
            margin: 20px 0;
            cursor: crosshair;
            overflow: hidden;
        }
        .target {
            position: absolute;
            width: 60px;
            height: 60px;
            background: radial-gradient(circle, #ff6b6b 0%, #ee5a6f 50%, #c92a2a 100%);
            border-radius: 50%;
            cursor: pointer;
            animation: pulse 0.5s ease-in-out;
            box-shadow: 0 0 20px rgba(255, 107, 107, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        .target::before {
            content: '🎯';
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }
        .hit-effect {
            position: absolute;
            font-size: 2rem;
            animation: hitAnim 0.8s ease-out;
            pointer-events: none;
        }
        @keyframes hitAnim {
            0% { transform: scale(1) translateY(0); opacity: 1; }
            100% { transform: scale(1.5) translateY(-50px); opacity: 0; }
        }
        .stats-box {
            display: inline-block;
            background: #f8f9fa;
            padding: 15px 25px;
            border-radius: 10px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-light">← Powrót</a>
        </div>
        
        <div class="game-container">
            <h2 class="text-center mb-4">🎯 Kliknij Cele</h2>
            
            <div class="text-center mb-3">
                <div class="stats-box">
                    <h5 class="mb-0">Wynik: <span id="score">0</span></h5>
                </div>
                <div class="stats-box">
                    <h5 class="mb-0">Trafienia: <span id="hits">0</span></h5>
                </div>
                <div class="stats-box">
                    <h5 class="mb-0">Chybione: <span id="misses">0</span></h5>
                </div>
                <div class="stats-box">
                    <h5 class="mb-0">Czas: <span id="timer">30</span>s</h5>
                </div>
            </div>
            
            <div class="game-area" id="gameArea" onclick="handleMiss(event)"></div>
            
            <div class="text-center">
                <button class="btn btn-success btn-lg" id="startBtn" onclick="startGame()">
                    🎮 Start Gry
                </button>
            </div>
            
            <div class="alert alert-info mt-3 text-center">
                <strong>Zasady:</strong> Klikaj cele jak najszybciej! Masz 30 sekund. 
                Szybkie trafienia dają więcej punktów. Chybienie odbiera punkt.
            </div>
        </div>
    </div>

    <script>
        let score = 0;
        let hits = 0;
        let misses = 0;
        let timeLeft = 30;
        let gameActive = false;
        let currentTarget = null;
        let targetTimer = null;
        let gameTimer = null;
        let targetStartTime = 0;
        
        const gameArea = document.getElementById('gameArea');
        const scoreEl = document.getElementById('score');
        const hitsEl = document.getElementById('hits');
        const missesEl = document.getElementById('misses');
        const timerEl = document.getElementById('timer');
        const startBtn = document.getElementById('startBtn');
        
        function startGame() {
            score = 0;
            hits = 0;
            misses = 0;
            timeLeft = 30;
            gameActive = true;
            
            updateDisplay();
            startBtn.disabled = true;
            startBtn.textContent = '⏸️ Gra w toku...';
            
            gameTimer = setInterval(() => {
                timeLeft--;
                timerEl.textContent = timeLeft;
                
                if (timeLeft <= 0) {
                    endGame();
                }
            }, 1000);
            
            spawnTarget();
        }
        
        function spawnTarget() {
            if (!gameActive) return;
            
            if (currentTarget) {
                currentTarget.remove();
                misses++;
                score = Math.max(0, score - 5);
                updateDisplay();
            }
            
            const target = document.createElement('div');
            target.className = 'target';
            
            const maxX = gameArea.offsetWidth - 60;
            const maxY = gameArea.offsetHeight - 60;
            
            target.style.left = Math.random() * maxX + 'px';
            target.style.top = Math.random() * maxY + 'px';
            
            target.onclick = (e) => {
                e.stopPropagation();
                hitTarget();
            };
            
            gameArea.appendChild(target);
            currentTarget = target;
            targetStartTime = Date.now();
            
            targetTimer = setTimeout(() => {
                if (gameActive && currentTarget === target) {
                    spawnTarget();
                }
            }, 1000);
        }
        
        function hitTarget() {
            if (!gameActive || !currentTarget) return;
            
            const reactionTime = Date.now() - targetStartTime;
            
            let points = 10;
            if (reactionTime < 300) points = 20; 
            else if (reactionTime < 500) points = 15; 
            else if (reactionTime < 700) points = 12; 
            
            score += points;
            hits++;
            
            showHitEffect(currentTarget.style.left, currentTarget.style.top, `+${points}`);
            
            updateDisplay();
            
            currentTarget.remove();
            currentTarget = null;
            clearTimeout(targetTimer);
            
            setTimeout(spawnTarget, 300);
        }
        
        function handleMiss(e) {
            if (!gameActive || e.target.classList.contains('target')) return;
            
            misses++;
            score = Math.max(0, score - 5);
            
            showHitEffect(e.clientX - gameArea.offsetLeft + 'px', 
                         e.clientY - gameArea.offsetTop + 'px', '-5');
            
            updateDisplay();
        }
        
        function showHitEffect(x, y, text) {
            const effect = document.createElement('div');
            effect.className = 'hit-effect';
            effect.textContent = text;
            effect.style.left = x;
            effect.style.top = y;
            effect.style.color = text.includes('-') ? '#dc3545' : '#28a745';
            
            gameArea.appendChild(effect);
            setTimeout(() => effect.remove(), 800);
        }
        
        function updateDisplay() {
            scoreEl.textContent = score;
            hitsEl.textContent = hits;
            missesEl.textContent = misses;
        }
        
        function endGame() {
            gameActive = false;
            clearInterval(gameTimer);
            clearTimeout(targetTimer);
            
            if (currentTarget) {
                currentTarget.remove();
                currentTarget = null;
            }
            
            startBtn.disabled = false;
            startBtn.textContent = '🎮 Start Gry';
            
            const accuracy = hits > 0 ? ((hits / (hits + misses)) * 100).toFixed(1) : 0;
            
            alert(`🎯 Koniec Gry!\n\nWynik: ${score} punktów\nTrafienia: ${hits}\nChybienia: ${misses}\nCelność: ${accuracy}%`);
            
            fetch('save_result.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({game: 'target', score: score})
            })
            .then(response => response.json())
            .then(data => {
                console.log('Save result:', data);
            })
            .catch(error => console.error('Fetch error:', error));
        }
    </script>
</body>
</html>