<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirect('../login.php');
}

$user = getUserData();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kółko i Krzyżyk - MiniGry</title>
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
            max-width: 600px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .board {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 20px 0;
        }
        .cell {
            aspect-ratio: 1;
            background: #f0f0f0;
            border: 3px solid #667eea;
            border-radius: 10px;
            font-size: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .cell:hover:not(.filled) {
            background: #e0e0ff;
            transform: scale(1.05);
        }
        .cell.filled {
            cursor: not-allowed;
        }
        .cell.win {
            background: #90EE90;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mt-3">
            <a href="../index.php" class="btn btn-light">← Powrót</a>
        </div>
        
        <div class="game-container">
            <h2 class="text-center mb-4">❌⭕ Kółko i Krzyżyk</h2>
            
            <div class="alert alert-info text-center" id="status">
                Twoja kolej! Grasz jako ❌
            </div>
            
            <div class="board" id="board">
                <div class="cell" data-cell="0"></div>
                <div class="cell" data-cell="1"></div>
                <div class="cell" data-cell="2"></div>
                <div class="cell" data-cell="3"></div>
                <div class="cell" data-cell="4"></div>
                <div class="cell" data-cell="5"></div>
                <div class="cell" data-cell="6"></div>
                <div class="cell" data-cell="7"></div>
                <div class="cell" data-cell="8"></div>
            </div>
            
            <div class="text-center">
                <button class="btn btn-primary" onclick="resetGame()">Nowa Gra</button>
            </div>
        </div>
    </div>

    <script>
        let board = ['', '', '', '', '', '', '', '', ''];
        let currentPlayer = 'X';
        let gameActive = true;
        
        const winPatterns = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]
        ];
        
        document.querySelectorAll('.cell').forEach(cell => {
            cell.addEventListener('click', handleCellClick);
        });
        
        function handleCellClick(e) {
            const cell = e.target;
            const index = parseInt(cell.dataset.cell);
            
            if (board[index] || !gameActive || currentPlayer === 'O') return;
            
            makeMove(index, 'X');
            
            if (gameActive) {
                setTimeout(computerMove, 500);
            }
        }
        
        function makeMove(index, player) {
            board[index] = player;
            const cell = document.querySelector(`[data-cell="${index}"]`);
            cell.textContent = player === 'X' ? '❌' : '⭕';
            cell.classList.add('filled');
            
            checkWinner();
        }
        
        function computerMove() {
            const empty = board.map((val, idx) => val === '' ? idx : null).filter(val => val !== null);
            if (empty.length === 0 || !gameActive) return;
            
            const move = empty[Math.floor(Math.random() * empty.length)];
            makeMove(move, 'O');
            currentPlayer = 'X';
        }
        
        function checkWinner() {
            let winner = null;
            
            for (let pattern of winPatterns) {
                const [a, b, c] = pattern;
                if (board[a] && board[a] === board[b] && board[a] === board[c]) {
                    winner = board[a];
                    pattern.forEach(i => {
                        document.querySelector(`[data-cell="${i}"]`).classList.add('win');
                    });
                    break;
                }
            }
            
            if (winner) {
                gameActive = false;
                const status = document.getElementById('status');
                
                if (winner === 'X') {
                    status.className = 'alert alert-success text-center';
                    status.textContent = '🎉 Wygrałeś! +10 punktów';
                    saveResult(10);
                } else {
                    status.className = 'alert alert-danger text-center';
                    status.textContent = '😔 Przegrałeś! Spróbuj ponownie';
                    saveResult(0);
                }
            } else if (!board.includes('')) {
                gameActive = false;
                document.getElementById('status').className = 'alert alert-warning text-center';
                document.getElementById('status').textContent = '🤝 Remis!';
                saveResult(5);
            }
        }
        
        function saveResult(score) {
            fetch('save_result.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({game: 'tictactoe', score: score})
            })
            .then(response => response.json())
            .then(data => {
                console.log('Save result:', data);
                if (data.success) {
                    console.log('New total score:', data.newTotalScore);
                } else {
                    console.error('Error saving:', data.message);
                }
            })
            .catch(error => console.error('Fetch error:', error));
        }
        
        function resetGame() {
            board = ['', '', '', '', '', '', '', '', ''];
            currentPlayer = 'X';
            gameActive = true;
            
            document.querySelectorAll('.cell').forEach(cell => {
                cell.textContent = '';
                cell.classList.remove('filled', 'win');
            });
            
            document.getElementById('status').className = 'alert alert-info text-center';
            document.getElementById('status').textContent = 'Twoja kolej! Grasz jako ❌';
        }
    </script>
</body>
</html>