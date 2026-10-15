<?php
require_once 'db_config.php';
require_once 'functions.php';

// Ako je korisnik već ulogovan, preusmeri
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

// Obrada forme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = "Sva polja su obavezna!";
    } else {
        // Pronađi korisnika prema korisniku
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch();

        if ($row) {
            // Proveri lozinku
            if (password_verify($password, $row['password'])) {
                // Prijava uspešna - postavi session
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $username;
                
                // Postavi cookie koji ističe za 30 dana
                setcookie("user_id", $row['id'], time() + (30 * 24 * 60 * 60), "/");
                setcookie("username", $username, time() + (30 * 24 * 60 * 60), "/");
                
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Lozinka nije tačna!";
            }
        } else {
            $error = "Korisničko ime ne postoji!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava - Horsle</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="container">
        <h1>Horsle</h1>
        <p class="subtitle">Nađi svoju bolju polovinu</p>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo safe_output($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Korisničko ime:</label>
                <input type="text" id="username" name="username" required value="<?php echo safe_output($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Lozinka:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Prijavi se</button>
        </form>
        
        <div class="register-link">
            Nemaš nalog? <a href="register.php">Registruj se</a>
        </div>
    </div>
</body>
</html>
