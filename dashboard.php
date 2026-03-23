<?php
require_once 'db_config.php';
require_once 'functions.php';

// Zahtevaj login
require_login();

$user_id = $_SESSION['user_id'];
$error = "";
$message = "";

// Pronađi pol trenutnog korisnika
$user_stmt = $conn->prepare("SELECT gender FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$current_user = $user_result->fetch_assoc();
$user_gender = $current_user['gender'] ?? 0;
$user_stmt->close();

// Ako je korisnik ženski (0), prikaži muške (1) i ostalo (2)
// Ako je korisnik muški (1), prikaži ženske (0) i ostalo (2)
$allowed_genders = ($user_gender == 0) ? [1, 2] : [0, 2];
$gender_filter = "AND u.gender IN (" . implode(",", $allowed_genders) . ")";

// Pronađi sve korisnike koje trenutni korisnik nije obradio (like/dislike)
$query = "SELECT u.* FROM users u 
          WHERE u.id != ? 
          AND u.id NOT IN (
              SELECT liked_user_id FROM likes WHERE user_id = ?
          )
          $gender_filter
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$current_profile = null;
if ($result->num_rows > 0) {
    $current_profile = $result->fetch_assoc();
}
$stmt->close();

// Obrada like/dislike akcija
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = sanitize_input($_POST['action']); // 'like' ili 'dislike'
    $liked_user_id = intval($_POST['liked_user_id'] ?? 0);
    
    if ($liked_user_id > 0 && ($action === 'like' || $action === 'dislike')) {
        // Ubaci u tabelu likes
        $stmt = $conn->prepare("INSERT INTO likes (user_id, liked_user_id, action) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $liked_user_id, $action);
        
        if ($stmt->execute()) {
            // Ako je like akcija, proveri da li postoji mutual like
            if ($action === 'like') {
                // Proveri da li drugi korisnik lajkuje ovog korisnika
                $mutual_check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND liked_user_id = ? AND action = 'like'");
                $mutual_check->bind_param("ii", $liked_user_id, $user_id);
                $mutual_check->execute();
                $mutual_result = $mutual_check->get_result();
                
                if ($mutual_result->num_rows > 0) {
                    // Mutual like! Kreiraj match
                    // Prvo proverimo da li match već postoji
                    $match_check = $conn->prepare("SELECT id FROM matches WHERE (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)");
                    $match_check->bind_param("iiii", $user_id, $liked_user_id, $liked_user_id, $user_id);
                    $match_check->execute();
                    $match_check_result = $match_check->get_result();
                    
                    if ($match_check_result->num_rows === 0) {
                        // Kreiraj novi match
                        $user_1 = min($user_id, $liked_user_id);
                        $user_2 = max($user_id, $liked_user_id);
                        
                        $match_stmt = $conn->prepare("INSERT INTO matches (user_id_1, user_id_2) VALUES (?, ?)");
                        $match_stmt->bind_param("ii", $user_1, $user_2);
                        $match_stmt->execute();
                        $match_stmt->close();
                    }
                    $match_check->close();
                }
                $mutual_check->close();
            }
            
            // Loguj da je korisnik pogledao profil
            $log_stmt = $conn->prepare("INSERT INTO profiles_viewed (user_id, viewed_user_id) VALUES (?, ?)");
            $log_stmt->bind_param("ii", $user_id, $liked_user_id);
            $log_stmt->execute();
            $log_stmt->close();
            
            // Preusmeri na istu stranicu da se učita sledeći profil
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Greška pri obradi: " . $conn->error;
        }
        $stmt->close();
    }
}

// Pronađi trenutnog korisnika za prikaz njegovog profila
$current_user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$current_user_stmt->bind_param("i", $user_id);
$current_user_stmt->execute();
$current_user_result = $current_user_stmt->get_result();
$current_user = $current_user_result->fetch_assoc();
$current_user_stmt->close();

// Pronađi sve like-ove trenutnog korisnika
$likes_query = "SELECT COUNT(*) as count FROM likes WHERE user_id = ? AND action = 'like'";
$likes_stmt = $conn->prepare($likes_query);
$likes_stmt->bind_param("i", $user_id);
$likes_stmt->execute();
$likes_result = $likes_stmt->get_result();
$likes_count = $likes_result->fetch_assoc()['count'];
$likes_stmt->close();

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
    <title>Dashboard - Horsle</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <?php if (!empty($error)): ?>
        <div style="max-width: 500px; margin: 0 auto;">
            <div class="error-message"><?php echo safe_output($error); ?></div>
        </div>
    <?php endif; ?>
    
    <div class="stats">
        <p>Svidelo ti se: <?php echo intval($likes_count); ?> profila</p>
    </div>
    
    <div class="container">
        <?php if ($current_profile): ?>
            <div class="profile-card">
                <div class="profile-header">
                    <h3><?php echo safe_output($current_profile['first_name']); ?>, <?php echo intval($current_profile['age']); ?></h3>
                    <div class="profile-info"><?php echo safe_output($current_profile['city'] ?? 'Nepoznat grad'); ?></div>
                </div>
                
                <div class="profile-body">
                    <?php if (!empty($current_profile['bio'])): ?>
                        <div class="bio"><?php echo safe_output($current_profile['bio']); ?></div>
                    <?php endif; ?>
                    
                    <div class="info-row">
                        <span class="info-label">Pol:</span>
                        <span class="info-value"><?php echo get_gender_text($current_profile['gender']); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Traži:</span>
                        <span class="info-value"><?php echo safe_output($current_profile['looking_for'] ?? 'Nije navedeno'); ?></span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Korisničko ime:</span>
                        <span class="info-value">@<?php echo safe_output($current_profile['username']); ?></span>
                    </div>
                    
                    <form method="POST" action="" style="margin-top: 20px;">
                        <input type="hidden" name="liked_user_id" value="<?php echo intval($current_profile['id']); ?>">
                        
                        <div class="actions">
                            <button type="submit" name="action" value="dislike" class="btn dislike-btn">Nije za mene ✕</button>
                            <button type="submit" name="action" value="like" class="btn like-btn">Svidja mi se ♥</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="no-profile">
                <h3>Nema više profila za prikaz</h3>
                <p>Pregledao si sve dostupne profile. Proveri kasnije za nove korisnike!</p>
                <p style="color: #667eea; font-size: 24px; margin-top: 20px;">♥</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
