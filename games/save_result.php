<?php
error_reporting(0);
require_once '../config.php';

header('Content-Type: application/json');

file_put_contents('debug.log', date('Y-m-d H:i:s') . " - Request received\n", FILE_APPEND);

if (!isLoggedIn()) {
    file_put_contents('debug.log', "Not logged in\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Nie jesteś zalogowany']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('debug.log', "Data: " . print_r($data, true) . "\n", FILE_APPEND);

if (!$data || !isset($data['game']) || !isset($data['score'])) {
    file_put_contents('debug.log', "Invalid data\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe dane']);
    exit;
}

$userId = $_SESSION['user_id'];
$game = $data['game'];
$score = (int)$data['score'];
$time = isset($data['time']) ? (int)$data['time'] : null;

try {
    $stmt = $pdo->prepare("
        INSERT INTO games_results (user_id, game, score, time) 
        VALUES (?, ?, ?, ?)
    ");
    $result = $stmt->execute([$userId, $game, $score, $time]);
    
    file_put_contents('debug.log', "Insert result: " . ($result ? 'success' : 'failed') . "\n", FILE_APPEND);
    
    $stmt = $pdo->prepare("
        UPDATE users 
        SET score = score + ? 
        WHERE id = ?
    ");
    $updateResult = $stmt->execute([$score, $userId]);
    
    file_put_contents('debug.log', "Update result: " . ($updateResult ? 'success' : 'failed') . "\n", FILE_APPEND);
    
    $stmt = $pdo->prepare("SELECT score FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $newScore = $stmt->fetchColumn();
    
    file_put_contents('debug.log', "New score: " . $newScore . "\n", FILE_APPEND);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Wynik zapisany',
        'newTotalScore' => $newScore
    ]);
    
} catch (PDOException $e) {
    file_put_contents('debug.log', "Error: " . $e->getMessage() . "\n", FILE_APPEND);
    echo json_encode([
        'success' => false, 
        'message' => 'Błąd zapisu: ' . $e->getMessage()
    ]);
}
?>