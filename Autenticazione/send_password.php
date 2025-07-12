<?php
include '../Database/db_config.php';

function generaPasswordCasuale($length = 10) {
    return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&'), 0, $length);
}

$messaggio = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM CREDENZIALI WHERE email = ?");
    if (!$stmt) {
        die("Errore nella preparazione: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId);

    if ($stmt->fetch()) {
        $newPwd = generaPasswordCasuale();
        $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE CREDENZIALI SET password = ? WHERE email = ?");
        if (!$update) {
            die("Errore nell'update: " . $conn->error);
        }
        $update->bind_param("ss", $hashedPwd, $email);
        $update->execute();

        $intestazioni = "From: Recupero Password <no-reply@flaviovacca2025.altervista.org>\r\n";
        $intestazioni .= "Content-Type: text/plain; charset=UTF-8\r\n";

        mail($email, "Recupero Password", "La tua nuova password è: $newPwd", $intestazioni);
    }

    $messaggio = "Se l'email è registrata, riceverai un messaggio con una nuova password.";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Recupera Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="container-sm px-3">
  <div class="card p-4 shadow-sm rounded mx-auto" style="max-width: 400px;">
  <button type="button" class="btn btn-link mb-3 p-0" onclick="location.href='../index.php'">&larr; Indietro</button>
    <h3 class="text-center mb-4">Recupera Password</h3>
    <form method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Inserisci la tua email:</label>
        <input type="email" class="form-control" name="email" id="email" required />
      </div>
      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-primary">Invia</button>
      </div>
    </form>
    <p class="text-center"><?= htmlspecialchars($messaggio) ?></p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>