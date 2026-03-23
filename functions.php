<?php
// Helper funkcije za XSS zaštitu

/**
 * Čisti user input od XSS napada
 * @param string $input Unos koji treba očistiti
 * @return string Očišćeni unos
 */
function sanitize_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Validira email
 * @param string $email Email koji treba validirati
 * @return bool True ako je validan
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validira lozinku (najmanje 6 karaktera, broj i slovo)
 * @param string $password Lozinka za validaciju
 * @return bool True ako je validna
 */
function validate_password($password) {
    return strlen($password) >= 6 && preg_match('/[0-9]/', $password) && preg_match('/[a-zA-Z]/', $password);
}

/**
 * Proverava da li je korisnik ulogovan
 * @return bool True ako je ulogovan
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Preusmeri na login stranicu ako korisnik nije ulogovan
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Bezbedno prikazuje HTML
 * @param string $text Tekst za prikaz
 * @return string Čist tekst prikladan za prikaz
 */
function safe_output($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Log out korisnika
 */
function logout_user() {
    // Obriši session varijable
    $_SESSION = array();
    
    // Obriši session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Unisti session
    session_destroy();
}
?>
