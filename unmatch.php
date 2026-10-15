<?php
require_once 'db_config.php';
require_once 'functions.php';

// Zahtevaj login
require_login();

$user_id = $_SESSION['user_id'];
$match_id = intval($_POST['match_id'] ?? 0);

if ($match_id <= 0) {
    header("Location: messages.php");
    exit();
}

// Proveri da li je korisnik deo ovog match-a
$verify_stmt = $conn->prepare("SELECT id FROM matches WHERE id = ? AND (user_id_1 = ? OR user_id_2 = ?)");
$verify_stmt->execute([$match_id, $user_id, $user_id]);

if (!$verify_stmt->fetch()) {
    header("Location: messages.php");
    exit();
}

// Obriši match (što će automatski obrisati i sve poruke zbog CASCADE)
$delete_stmt = $conn->prepare("DELETE FROM matches WHERE id = ?");

try {
    $delete_stmt->execute([$match_id]);
    // Vrati korisnika na messages stranicu
    header("Location: messages.php");
    exit();
} catch (PDOException $e) {
    header("Location: messages.php");
    exit();
}
?>
