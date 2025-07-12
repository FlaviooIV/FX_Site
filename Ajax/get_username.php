<?php
session_start();
if (isset($_SESSION['nome'])) {
    echo $_SESSION['nome'];
} else {
    echo 'Utente';
}
?>