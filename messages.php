<?php
require_once 'db_config.php';
require_once 'functions.php';

// Zahtevaj login
require_login();

$user_id = $_SESSION['user_id'];

// Pronađi sve matches za trenutnog korisnika
$query = "SELECT m.id as match_id, u.id, u.username, u.first_name, u.age, u.gender, m.created_at,
          (SELECT message FROM messages WHERE match_id = m.id ORDER BY sent_at DESC LIMIT 1) as last_message,
          (SELECT sent_at FROM messages WHERE match_id = m.id ORDER BY sent_at DESC LIMIT 1) as last_message_time,
          (SELECT COUNT(*) FROM messages WHERE match_id = m.id AND receiver_id = ? AND is_read = FALSE) as unread_count
          FROM matches m
          JOIN users u ON (m.user_id_1 = u.id OR m.user_id_2 = u.id)
          WHERE (m.user_id_1 = ? OR m.user_id_2 = ?)
          AND u.id != ?
          ORDER BY last_message_time DESC NULLS LAST";

$stmt = $conn->prepare($query);
$stmt->execute([$user_id, $user_id, $user_id, $user_id]);
$matches = $stmt->fetchAll();

// Pronađi trenutnog korisnika
$current_user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$current_user_stmt->execute([$user_id]);
$current_user = $current_user_stmt->fetch();
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poruke - Horsle</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/messages.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="header">
            <h1>💬 Poruke</h1>
        </div>

        <div class="matches-list">
            <?php if (count($matches) > 0): ?>
                <?php foreach ($matches as $match): ?>
                    <div class="match-item">
                        <div class="match-info">
                            <div class="match-name"><?php echo safe_output($match['first_name']); ?> (<?php echo intval($match['age']); ?>)</div>
                            <div class="match-details">@<?php echo safe_output($match['username']); ?> • <?php echo safe_output($match['gender']); ?></div>
                            <?php if ($match['last_message']): ?>
                                <div class="last-message"><?php echo safe_output(substr($match['last_message'], 0, 50)); ?></div>
                            <?php else: ?>
                                <div class="last-message"><em>Nema poruka</em></div>
                            <?php endif; ?>
                        </div>
                        <div class="match-action">
                            <?php if ($match['unread_count'] > 0): ?>
                                <div class="unread-badge"><?php echo intval($match['unread_count']); ?></div>
                            <?php endif; ?>
                            <a href="chat.php?match_id=<?php echo intval($match['match_id']); ?>&user_id=<?php echo intval($match['id']); ?>" class="btn-chat">Otvori</a>
                            <form method="POST" action="unmatch.php" style="display: inline;" onsubmit="return confirm('Sigurno žeš obrisati ovu vezu?');">
                                <input type="hidden" name="match_id" value="<?php echo intval($match['match_id']); ?>">
                                <button type="submit" class="btn-unmatch">Obriši</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-message">
                    <h3>Nema matches-eva</h3>
                    <p>Lajkuj profile da nađeš nekoga koga se i tebi sviđaš!</p>
                    <a href="dashboard.php" style="color: #667eea; text-decoration: none; font-weight: 600;">Pregledaj profile →</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
