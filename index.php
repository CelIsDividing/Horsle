<?php
require_once 'db_config.php';
require_once 'functions.php';

// Ako je korisnik ulogovan, preusmeri ga na dashboard
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horsle - Nađi svoju bolju polovinu</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <div class="logo">♥</div>
        <h1>Horsle</h1>
        <p>Pronađi svoju bolju polovinu u svega nekoliko klikova!</p>
        
        <div class="features">
            <div class="feature">Sigurna i laka registracija</div>
            <div class="feature">Pregledaj buduće partnere</div>
            <div class="feature">Lajkuj one koji ti se sviđaju</div>
            <div class="feature">Upravljaj svim iz svog profila</div>
        </div>
        
        <p style="margin-top: 20px; font-weight: 500;">
            Spreman/Spremna da nađeš ljubav? Kreni sada!
        </p>
        
        <div class="buttons">
            <a href="register.php" class="btn-register">Registruj se</a>
            <a href="login.php" class="btn-login">Prijavi se</a>
        </div>
    </div>
</body>
</html>
