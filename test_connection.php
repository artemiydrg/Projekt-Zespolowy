<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test połączenia z bazą danych</h2>";

require_once 'config.php';

echo "<p>✅ Config załadowany</p>";

if (isLoggedIn()) {
    echo "<p>✅ Użytkownik zalogowany: ID = " . $_SESSION['user_id'] . "</p>";
    
    $user = getUserData();
    echo "<p>✅ Dane użytkownika: " . htmlspecialchars($user['username']) . "</p>";
    echo "<p>✅ Aktualny wynik: " . $user['score'] . "</p>";
  
    $stmt = $pdo->prepare("SELECT * FROM games_results WHERE user_id = ? ORDER BY date DESC LIMIT 5");
    $stmt->execute([$_SESSION['user_id']]);
    $results = $stmt->fetchAll();
    
    echo "<h3>Ostatnie wyniki:</h3>";
    if ($results) {
        echo "<ul>";
        foreach ($results as $r) {
            echo "<li>Gra: {$r['game']}, Wynik: {$r['score']}, Data: {$r['date']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>⚠️ Brak wyników w bazie</p>";
    }
    
    echo "<h3>Test zapisu:</h3>";
    try {
        $stmt = $pdo->prepare("INSERT INTO games_results (user_id, game, score) VALUES (?, 'test', 999)");
        $stmt->execute([$_SESSION['user_id']]);
        echo "<p>✅ Test zapisu zakończony sukcesem</p>";
        
        $pdo->exec("DELETE FROM games_results WHERE game = 'test'");
    } catch (Exception $e) {
        echo "<p>❌ Błąd zapisu: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<p>❌ Użytkownik niezalogowany</p>";
    echo "<p><a href='login.php'>Zaloguj się</a></p>";
}

echo "<hr>";
echo "<p><a href='index.php'>Powrót do strony głównej</a></p>";
?>