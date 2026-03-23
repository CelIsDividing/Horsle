<?php
require_once 'db_config.php';
require_once 'functions.php';

// Zahtevaj login
require_login();

$user_id = $_SESSION['user_id'];
$error = "";
$success = "";

// Pronađi informacije o korisniku
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Obrada forme za ažuriranje profila
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = sanitize_input($_POST['first_name'] ?? '');
    $bio = sanitize_input($_POST['bio'] ?? '');
    $city = sanitize_input($_POST['city'] ?? '');
    $looking_for = sanitize_input($_POST['looking_for'] ?? '');
    
    if (empty($first_name)) {
        $error = "Ime je obavezno!";
    } else {
        $stmt = $conn->prepare("UPDATE users SET first_name = ?, bio = ?, city = ?, looking_for = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $first_name, $bio, $city, $looking_for, $user_id);
        
        if ($stmt->execute()) {
            $success = "Profil je uspešno ažuriran!";
            // Osveži podatke
            $user['first_name'] = $first_name;
            $user['bio'] = $bio;
            $user['city'] = $city;
            $user['looking_for'] = $looking_for;
        } else {
            $error = "Greška pri ažuriranju: " . $conn->error;
        }
        $stmt->close();
    }
}

// Pronađi statistiku
$likes_stmt = $conn->prepare("SELECT COUNT(*) as count FROM likes WHERE user_id = ? AND action = 'like'");
$likes_stmt->bind_param("i", $user_id);
$likes_stmt->execute();
$likes_result = $likes_stmt->get_result();
$likes_count = $likes_result->fetch_assoc()['count'];
$likes_stmt->close();

$views_stmt = $conn->prepare("SELECT COUNT(*) as count FROM profiles_viewed WHERE user_id = ?");
$views_stmt->bind_param("i", $user_id);
$views_stmt->execute();
$views_result = $views_stmt->get_result();
$views_count = $views_result->fetch_assoc()['count'];
$views_stmt->close();

// Funkcija za konverziju pola
function get_gender_text($gender) {
    $gender_map = [
        '0' => 'Žensko',
        '1' => 'Muško',
        '2' => 'Drugo',
        'Muško' => 'Muško',
        'Žensko' => 'Žensko',
        'Drugo' => 'Drugo'
    ];
    return $gender_map[$gender] ?? $gender;
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moj profil - Horsle</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container">
        <h1>Moj profil</h1>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo safe_output($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo safe_output($success); ?></div>
        <?php endif; ?>
        
        <div class="profile-details">
            <div class="detail-item">
                <strong>Korisničko ime:</strong> @<?php echo safe_output($user['username']); ?>
            </div>
            <div class="detail-item">
                <strong>Email:</strong> <?php echo safe_output($user['email']); ?>
            </div>
            <div class="detail-item">
                <strong>Ime:</strong> <?php echo safe_output($user['first_name']); ?>
            </div>
            <div class="detail-item">
                <strong>Starost:</strong> <?php echo intval($user['age']); ?> godina
            </div>
            <div class="detail-item">
                <strong>Pol:</strong> <?php echo get_gender_text($user['gender']); ?>
            </div>
        </div>
        
        <h2>Uredi profil</h2>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="first_name">Ime:</label>
                <input type="text" id="first_name" name="first_name" required value="<?php echo safe_output($user['first_name']); ?>">
            </div>
            
            <div class="form-group">
                <label for="city">Grad:</label>
                <input type="text" id="city" name="city" value="<?php echo safe_output($user['city'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="bio">Biografija:</label>
                <textarea id="bio" name="bio"><?php echo safe_output($user['bio'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="looking_for">Tražim:</label>
                <select id="looking_for" name="looking_for">
                    <option value="">Nije navedeno</option>
                    <option value="Vezu" <?php echo (isset($user['looking_for']) && $user['looking_for'] === 'Vezu') ? 'selected' : ''; ?>>Vezu</option>
                    <option value="Prijateljstvo" <?php echo (isset($user['looking_for']) && $user['looking_for'] === 'Prijateljstvo') ? 'selected' : ''; ?>>Prijateljstvo</option>
                    <option value="Zabavu" <?php echo (isset($user['looking_for']) && $user['looking_for'] === 'Zabavu') ? 'selected' : ''; ?>>Zabavu</option>
                </select>
            </div>
            
            <button type="submit">Sačuvaj promene</button>
        </form>
        
        <div class="stats">
            <div class="stat">
                <div class="stat-number"><?php echo intval($likes_count); ?></div>
                <div class="stat-label">Sviđanja</div>
            </div>
            <div class="stat">
                <div class="stat-number"><?php echo intval($views_count); ?></div>
                <div class="stat-label">Pregledani profili</div>
            </div>
        </div>
    </div>
</body>
</html>
