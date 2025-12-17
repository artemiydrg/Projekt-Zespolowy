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
    <title>Clicker - MiniGry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .game-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin: 30px auto;
            max-width: 600px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        #clickButton {
            width: 250px;
            height: 250px;
            font-size: 4rem;
            border-radius: 50%;
            border: 5px solid #667eea;
            transition: all 0.1s;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        #clickButton:active {
            transform: scale(0.95);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.5);
        }
        #clickButton:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .timer {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
        .click-animation {
            position: absolute;
            font-size: 2rem;
            animation: float 1s ease-out;
            pointer-events: none;
        }
        @keyframes float {
            0% { transform: translateY(0); opacity: 1; }
            100% { transform: translateY(-100px); opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-light">← Powrót</a>
        </div>
        
        <div class="game-container">
            <h2 class="text-center mb-4">🖱️ Test Szybkości Klikania</h2>
            
            <div class="text-center mb-4">
                <div class="timer" id="timer">10.0</div>
                <p class="text-muted">sekund</p>
            </div>
            
            <div class="text-center mb-4">
                <h1 class="display-3" id="clicks">0</h1>
                <p class="text-muted">kliknięć</p>
            </div>
            
            <div class="text-center mb-4" style="position: relative;">
                <button class="btn btn-primary" id="clickButton" onclick="handleClick(event)">
                    👆
                </button>
            </div>
            
            <div class="text-center">
                <button class="btn btn-success" id="startBtn" onclick="startGame()">Start Gry</button>
                <button class="btn btn-secondary" id="resetBtn" onclick="resetGame()" disabled>Reset</button>
            </div>
            
            <div id="results" class="mt-4" style="display: none;">
                <div class="alert alert-success text-center">
                    <h4>🎉 Koniec!</h4>
                    <p class="mb-0">Twój wynik: <strong id="finalScore">0</strong> kliknięć</p>
                    <p class="mb-0">CPS: <strong id="cps">0</strong></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let clicks = 0;
        let timeLeft = 10;
        let gameActive = false;
        let timer;
        
        const clickButton = document.getElementById('clickButton');
        const clicksDisplay = document.getElementById('clicks');
        const timerDisplay = document.getElementById('timer');
        const startBtn = document.getElementById('startBtn');
        const resetBtn = document.getElementById('resetBtn');
        const results = document.getElementById('results');
        
        function startGame() {
            clicks = 0;
            timeLeft = 10;
            gameActive = true;
            
            clicksDisplay.textContent = clicks;
            timerDisplay.textContent = timeLeft.toFixed(1);
            
            clickButton.disabled = false;
            startBtn.disabled = true;
            resetBtn.disabled = false;
            results.style.display = 'none';
            
            timer = setInterval(() => {
                timeLeft -= 0.1;
                timerDisplay.textContent = timeLeft.toFixed(1);
                
                if (timeLeft <= 0) {
                    endGame();
                }
            }, 100);
        }
        
        function handleClick(event) {
            if (!gameActive) return;
            
            clicks++;
            clicksDisplay.textContent = clicks;
            
            const animation = document.createElement('div');
            animation.className = 'click-animation';
            animation.textContent = '+1';
            animation.style.left = event.clientX + 'px';
            animation.style.top = event.clientY + 'px';
            document.body.appendChild(animation);
            
            setTimeout(() => animation.remove(), 1000);
        }
        
        function endGame() {
            clearInterval(timer);
            gameActive = false;
            clickButton.disabled = true;
            startBtn.disabled = false;
            
            const cps = (clicks / 10).toFixed(2);
            const points = clicks;
            
            document.getElementById('finalScore').textContent = clicks;
            document.getElementById('cps').textContent = cps;
            results.style.display = 'block';
            
            fetch('save_result.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({game: 'clicker', score: points})
            })
            .then(response => response.json())
            .then(data => {
                console.log('Save result:', data);
            })
            .catch(error => console.error('Fetch error:', error));
        }
        
        function resetGame() {
            clearInterval(timer);
            clicks = 0;
            timeLeft = 10;
            gameActive = false;
            
            clicksDisplay.textContent = clicks;
            timerDisplay.textContent = timeLeft.toFixed(1);
            clickButton.disabled = true;
            startBtn.disabled = false;
            resetBtn.disabled = true;
            results.style.display = 'none';
        }
    </script>
</body>
</html>