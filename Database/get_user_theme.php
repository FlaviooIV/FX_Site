<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['id'])) {
    $_SESSION['theme'] = 'light';
    return;
}
$user_id = $_SESSION['id'];

// Query per ottenere il tema salvato dall'utente
$stmt = $conn->prepare("SELECT theme FROM CREDENZIALI WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($theme);
$stmt->fetch();
$stmt->close();

// Imposta il tema nella sessione (default = 'light')
if ($theme) {
    $_SESSION['theme'] = $theme;
} else {
    $_SESSION['theme'] = 'light';
}
?>