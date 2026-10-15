<?php

// Konfiguracija baze (PostgreSQL preko PDO).
// Na Render-u vrednosti dolaze iz environment varijabli.
// Lokalne fallback vrednosti se koriste ako promenljive nisu postavljene.

$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: '5432';
$db_user = getenv('DB_USER') ?: 'postgres';
$db_password = getenv('DB_PASSWORD') ?: '';
$db_name = getenv('DB_NAME') ?: 'tinder_db';
$db_sslmode = getenv('DB_SSLMODE') ?: 'prefer';

$dsn = "pgsql:host={$db_host};port={$db_port};dbname={$db_name};sslmode={$db_sslmode}";

// Kreiraj konekciju
try {
    $conn = new PDO($dsn, $db_user, $db_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Greška pri konekciji na bazu: " . $e->getMessage());
}

// Sesije se cuvaju u bazi umesto u fajlovima,
// pa korisnici ostaju ulogovani i posle restarta/redeploy-a.
require_once __DIR__ . '/session_handler.php';

$session_handler = new PdoSessionHandler($conn);
session_set_save_handler($session_handler, true);

// Bezbedna podesavanja session cookie-ja
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'secure' => $is_https,
    'samesite' => 'Lax',
]);

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
