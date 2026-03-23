<?php
require_once 'db_config.php';
require_once 'functions.php';

// Log out korisnika
logout_user();

// Preusmeri na login stranicu
header("Location: login.php");
exit();
?>
