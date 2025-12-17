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
    <title>Wąż - MiniGry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .game-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 30px auto;
            max-width: 700px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        #gameCanvas {
            border: 3px solid #667eea;
            border-radius: 10px;
            display: block;
            margin: 20px auto;
            background: #f0f0f0;
        }
        .controls {
            display: grid;
            grid-template-columns: repeat(3, 80px);
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }
        .control-btn {
            height: 60px;
            font-size: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-light">← Powrót</a>
        </div>
        
        <div class="game-container">
            <h2 class="text-center mb-4">🐍 Wąż</h2>
            
            <div class="row mb-3">
                <div class="col text-center">
                    <h5>Wynik: <span id="score">0</span></h5>
                </div>
                <div class="col text-center">
                    <h5>Najlepszy: <span id="bestScore">0</span></h5>
                </div>
            </div>
            
            <canvas id="gameCanvas" width="400" height="400"></canvas>
            
            <div class="text-center mb-3">
                <button class="btn btn-success" id="startBtn" onclick="startGame()">Start</button>
                <button class="btn btn-danger" id="pauseBtn" onclick="pauseGame()" disabled>Pauza</button>
            </div>
            
            <div class="alert alert-info text-center">
                Użyj strzałek lub przycisków aby sterować wężem
            </div>
            
            <div class="controls">
                <div></div>
                <button class="btn btn-primary control-btn" onclick="changeDirection('up')">▲</button>
                <div></div>
                <button class="btn btn-primary control-btn" onclick="changeDirection('left')">◄</button>
                <div></div>
                <button class="btn btn-primary control-btn" onclick="changeDirection('right')">►</button>
                <div></div>
                <button class="btn btn-primary control-btn" onclick="changeDirection('down')">▼</button>
            </div>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');
        const box = 20;
        const canvasSize = canvas.width / box;
        
        let snake, food, score, direction, game, bestScore = 0;
        
        function init() {
            snake = [{x: 10, y: 10}];
            food = generateFood();
            score = 0;
            direction = 'right';
            document.getElementById('score').textContent = score;
        }
        
        function generateFood() {
            return {
                x: Math.floor(Math.random() * canvasSize),
                y: Math.floor(Math.random() * canvasSize)
            };
        }
        
        function draw() {
            ctx.fillStyle = '#f0f0f0';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            ctx.fillStyle = '#ff6b6b';
            ctx.fillRect(food.x * box, food.y * box, box - 2, box - 2);

            snake.forEach((segment, index) => {
                ctx.fillStyle = index === 0 ? '#51cf66' : '#37b24d';
                ctx.fillRect(segment.x * box, segment.y * box, box - 2, box - 2);
            });
        }
        
        function update() {
            const head = {...snake[0]};
            
            if (direction === 'up') head.y--;
            if (direction === 'down') head.y++;
            if (direction === 'left') head.x--;
            if (direction === 'right') head.x++;
            
            if (head.x < 0 || head.x >= canvasSize || head.y < 0 || head.y >= canvasSize) {
                gameOver();
                return;
            }
            
            if (snake.some(segment => segment.x === head.x && segment.y === head.y)) {
                gameOver();
                return;
            }
            
            snake.unshift(head);
            
            if (head.x === food.x && head.y === food.y) {
                score++;
                document.getElementById('score').textContent = score;
                food = generateFood();
            } else {
                snake.pop();
            }
            
            draw();
        }
        
        function startGame() {
            init();
            document.getElementById('startBtn').disabled = true;
            document.getElementById('pauseBtn').disabled = false;
            game = setInterval(update, 150);
        }
        
        function pauseGame() {
            if (game) {
                clearInterval(game);
                game = null;
                document.getElementById('pauseBtn').textContent = 'Wznów';
            } else {
                game = setInterval(update, 150);
                document.getElementById('pauseBtn').textContent = 'Pauza';
            }
        }
        
        function gameOver() {
            clearInterval(game);
            document.getElementById('startBtn').disabled = false;
            document.getElementById('pauseBtn').disabled = true;
            
            if (score > bestScore) {
                bestScore = score;
                document.getElementById('bestScore').textContent = bestScore;
            }
            
            const points = score * 2;
            alert(`Koniec gry! Twój wynik: ${score}\nZdobyte punkty: ${points}`);
            
            fetch('save_result.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({game: 'snake', score: points})
            })
            .then(response => response.json())
            .then(data => {
                console.log('Save result:', data);
                if (data.success) {
                    alert(`Punkty zapisane! Twój całkowity wynik: ${data.newTotalScore}`);
                }
            })
            .catch(error => console.error('Fetch error:', error));
        }
        
        function changeDirection(newDir) {
            if (newDir === 'up' && direction !== 'down') direction = 'up';
            if (newDir === 'down' && direction !== 'up') direction = 'down';
            if (newDir === 'left' && direction !== 'right') direction = 'left';
            if (newDir === 'right' && direction !== 'left') direction = 'right';
        }
        
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowUp') changeDirection('up');
            if (e.key === 'ArrowDown') changeDirection('down');
            if (e.key === 'ArrowLeft') changeDirection('left');
            if (e.key === 'ArrowRight') changeDirection('right');
        });
        
        init();
        draw();
    </script>
</body>
</html>