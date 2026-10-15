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
$user_stmt->execute([$user_id]);
$current_user = $user_stmt->fetch();
$user_gender = (string)($current_user['gender'] ?? '');

// Prikaži suprotan pol i "Ostalo".
// Vrednosti mogu biti sačuvane kao tekst ('Muški'/'Ženski'/'Ostalo')
// ili kao brojevi ('0'/'1'/'2'), pa pokrivamo oba oblika.
$female_values = ['Ženski', 'Žensko', '0'];
$male_values   = ['Muški', 'Muško', '1'];
$other_values  = ['Ostalo', 'Drugo', '2'];

if (in_array($user_gender, $female_values, true)) {
    $allowed_genders = array_merge($male_values, $other_values);
} elseif (in_array($user_gender, $male_values, true)) {
    $allowed_genders = array_merge($female_values, $other_values);
} else {
    $allowed_genders = array_merge($female_values, $male_values, $other_values);
}

$placeholders = implode(',', array_fill(0, count($allowed_genders), '?'));

// Pronađi sve korisnike koje trenutni korisnik nije obradio (like/dislike)
$query = "SELECT u.* FROM users u 
          WHERE u.id != ? 
          AND u.id NOT IN (
              SELECT liked_user_id FROM likes WHERE user_id = ?
          )
          AND u.gender IN ($placeholders)
          LIMIT 1";

$stmt = $conn->prepare($query);
$stmt->execute(array_merge([$user_id, $user_id], $allowed_genders));

$current_profile = $stmt->fetch() ?: null;

// Obrada like/dislike akcija
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = sanitize_input($_POST['action']); // 'like' ili 'dislike'
    $liked_user_id = intval($_POST['liked_user_id'] ?? 0);
    
    if ($liked_user_id > 0 && ($action === 'like' || $action === 'dislike')) {
        try {
            // Ubaci u tabelu likes
            $stmt = $conn->prepare("INSERT INTO likes (user_id, liked_user_id, action) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $liked_user_id, $action]);

            // Ako je like akcija, proveri da li postoji mutual like
            if ($action === 'like') {
                // Proveri da li drugi korisnik lajkuje ovog korisnika
                $mutual_check = $conn->prepare("SELECT id FROM likes WHERE user_id = ? AND liked_user_id = ? AND action = 'like'");
                $mutual_check->execute([$liked_user_id, $user_id]);

                if ($mutual_check->fetch()) {
                    // Mutual like! Kreiraj match
                    // Prvo proverimo da li match već postoji
                    $match_check = $conn->prepare("SELECT id FROM matches WHERE (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)");
                    $match_check->execute([$user_id, $liked_user_id, $liked_user_id, $user_id]);

                    if (!$match_check->fetch()) {
                        // Kreiraj novi match
                        $user_1 = min($user_id, $liked_user_id);
                        $user_2 = max($user_id, $liked_user_id);

                        $match_stmt = $conn->prepare("INSERT INTO matches (user_id_1, user_id_2) VALUES (?, ?)");
                        $match_stmt->execute([$user_1, $user_2]);
                    }
                }
            }

            // Loguj da je korisnik pogledao profil
            $log_stmt = $conn->prepare("INSERT INTO profiles_viewed (user_id, viewed_user_id) VALUES (?, ?)");
            $log_stmt->execute([$user_id, $liked_user_id]);

            // Preusmeri na istu stranicu da se učita sledeći profil
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $error = "Greška pri obradi: " . $e->getMessage();
        }
    }
}

// Pronađi trenutnog korisnika za prikaz njegovog profila
$current_user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$current_user_stmt->execute([$user_id]);
$current_user = $current_user_stmt->fetch();

// Pronađi sve like-ove trenutnog korisnika
$likes_query = "SELECT COUNT(*) as count FROM likes WHERE user_id = ? AND action = 'like'";
$likes_stmt = $conn->prepare($likes_query);
$likes_stmt->execute([$user_id]);
$likes_count = $likes_stmt->fetch()['count'];

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
