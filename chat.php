<?php
require_once 'db_config.php';
require_once 'functions.php';

// Zahtevaj login
require_login();

$user_id = $_SESSION['user_id'];
$match_id = intval($_GET['match_id'] ?? 0);
$other_user_id = intval($_GET['user_id'] ?? 0);

if ($match_id <= 0 || $other_user_id <= 0) {
    header("Location: messages.php");
    exit();
}

// Proveri da li je match validan
$match_stmt = $conn->prepare("SELECT id FROM matches WHERE id = ? AND (user_id_1 = ? OR user_id_2 = ?)");
$match_stmt->bind_param("iii", $match_id, $user_id, $user_id);
$match_stmt->execute();
$match_result = $match_stmt->get_result();

if ($match_result->num_rows === 0) {
    header("Location: messages.php");
    exit();
}
$match_stmt->close();

// Pronađi drugog korisnika
$other_user_stmt = $conn->prepare("SELECT id, username, first_name, age, gender FROM users WHERE id = ?");
$other_user_stmt->bind_param("i", $other_user_id);
$other_user_stmt->execute();
$other_user_result = $other_user_stmt->get_result();
$other_user = $other_user_result->fetch_assoc();
$other_user_stmt->close();

// Označi sve poruke kao pročitane
$read_stmt = $conn->prepare("UPDATE messages SET is_read = TRUE WHERE match_id = ? AND receiver_id = ?");
$read_stmt->bind_param("ii", $match_id, $user_id);
$read_stmt->execute();
$read_stmt->close();

// Obrada slanja poruke
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = sanitize_input($_POST['message'] ?? '');
    
    if (empty($message)) {
        $error = "Poruka ne može biti prazna!";
    } else {
        $send_stmt = $conn->prepare("INSERT INTO messages (match_id, sender_id, receiver_id, message) VALUES (?, ?, ?, ?)");
        $send_stmt->bind_param("iiis", $match_id, $user_id, $other_user_id, $message);
        
        if ($send_stmt->execute()) {
            // Refresh stranice da vidiš novu poruku
            header("Refresh: 0");
            exit();
        } else {
            $error = "Greška pri slanju poruke";
        }
        $send_stmt->close();
    }
}

// Pronađi sve poruke za ovaj match
$messages_stmt = $conn->prepare("SELECT m.id, m.sender_id, m.message, m.sent_at, u.username, u.first_name
                                 FROM messages m
                                 JOIN users u ON m.sender_id = u.id
                                 WHERE m.match_id = ?
                                 ORDER BY m.sent_at ASC");
$messages_stmt->bind_param("i", $match_id);
$messages_stmt->execute();
$messages_result = $messages_stmt->get_result();
$messages = $messages_result->fetch_all(MYSQLI_ASSOC);
$messages_stmt->close();

// Pronađi trenutnog korisnika
$current_user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$current_user_stmt->bind_param("i", $user_id);
$current_user_stmt->execute();
$current_user_result = $current_user_stmt->get_result();
$current_user = $current_user_result->fetch_assoc();
$current_user_stmt->close();
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Horsle</title>
    <link rel="stylesheet" href="css/common.css">
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <div class="chat-header">
            <div>
                <h1><?php echo safe_output($other_user['first_name']); ?></h1>
                <div class="chat-header-info">@<?php echo safe_output($other_user['username']); ?> • <?php echo intval($other_user['age']); ?> godina</div>
            </div>
            <form method="POST" action="unmatch.php" style="display: inline;" onsubmit="return confirm('Sigurno želiš obrisati ovu vezu?');">
                <input type="hidden" name="match_id" value="<?php echo intval($match_id); ?>">
                <button type="submit" class="unmatch-btn">Unmatch</button>
            </form>
        </div>

        <div class="chat-messages">
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo safe_output($error); ?></div>
            <?php endif; ?>

            <?php if (count($messages) === 0): ?>
                <div style="text-align: center; color: #999; padding: 40px 20px;">
                    <p>Nema poruka. Započni razgovor! 💬</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?php echo ($msg['sender_id'] === $user_id) ? 'sent' : 'received'; ?>">
                        <div>
                            <div class="message-bubble"><?php echo safe_output($msg['message']); ?></div>
                            <div class="message-time"><?php echo date('H:i', strtotime($msg['sent_at'])); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="chat-input-area">
            <form method="POST" action="" class="input-form">
                <textarea name="message" placeholder="Napiši poruku..." required></textarea>
                <button type="submit">Pošalji</button>
            </form>
        </div>
    </div>

    <script>
        // Auto scroll na dno
        const chatMessages = document.querySelector('.chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>
</body>
</html>
