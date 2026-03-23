<?php
require_once 'db_config.php';
require_once 'functions.php';

// Ako je korisnik već ulogovan, preusmeri ga
if (is_logged_in()) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

// Obrada forme ako je POST zahtev
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Čiščenje input podataka od XSS napada
    $username = sanitize_input($_POST['username'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $gender = sanitize_input($_POST['gender'] ?? '');
    
    // Validacija
    if (empty($username) || empty($email) || empty($password) || empty($first_name) || $age <= 0) {
        $error = "Sva polja su obavezna i starost mora biti pozitivan broj!";
    } elseif (!validate_email($email)) {
        $error = "Email nije validan!";
    } elseif ($password !== $password_confirm) {
        $error = "Lozinke se ne poklapaju!";
    } elseif (!validate_password($password)) {
        $error = "Lozinka mora imati najmanje 6 karaktera i sadržavati broj i slovo!";
    } else {
        // Proveri da li korisnik već postoji
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Korisničko ime ili email već postoje!";
        } else {
            // Heširaj lozinku
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Ubaci novog korisnika
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, first_name, age, gender) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $username, $email, $hashed_password, $first_name, $age, $gender);
            
            if ($stmt->execute()) {
                $success = "Registracija uspešna! Sada se možete prijavi.";
                // Očisti formu
                $_POST = array();
            } else {
                $error = "Greška pri registraciji: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija - Horsle</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/auth.css">

</head>
<body>
    <div class="container">
        <h1>Horsle</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo safe_output($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo safe_output($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Korisničko ime:</label>
                <input type="text" id="username" name="username" required value="<?php echo safe_output($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?php echo safe_output($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="first_name">Ime:</label>
                <input type="text" id="first_name" name="first_name" required value="<?php echo safe_output($_POST['first_name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="age">Starost:</label>
                <input type="number" id="age" name="age" min="18" max="120" required value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="gender">Pol:</label>
                <select id="gender" name="gender" required>
                    <option value="">Izaberite pol</option>
                    <option value="Muški" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Muški') ? 'selected' : ''; ?>>Muški</option>
                    <option value="Ženski" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Ženski') ? 'selected' : ''; ?>>Ženski</option>
                    <option value="Ostalo" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Ostalo') ? 'selected' : ''; ?>>Ostalo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="password">Lozinka:</label>
                <input type="password" id="password" name="password" required>
                <small style="color: #b0b0b0;">Min. 6 karaktera, broj i slovo</small>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Potvrdi lozinku:</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            
            <button type="submit">Registruj se</button>
        </form>
        
        <div class="login-link">
            Već imaš nalog? <a href="login.php">Prijavi se</a>
        </div>
    </div>
</body>
</html>
