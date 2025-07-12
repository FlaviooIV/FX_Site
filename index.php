<?php
session_start();
if (isset($_SESSION['id'])) {
    header("Location: home.php");
    exit();
}
include 'Database/db_config.php';
include 'Footer/footer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, nome, password FROM CREDENZIALI WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nome, $hashed);

    if ($stmt->fetch() && password_verify($password, $hashed)) {
        $_SESSION['id'] = $id;
        $_SESSION['nome'] = $nome; // ESSENZIALE per il Benvenuto
        header("Location: home.php");
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Credenziali errate.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; }
  </style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="container-sm px-3">
  <div class="card p-4 shadow-sm rounded mx-auto" style="max-width: 400px;">
    <h3 class="text-center mb-4">Login</h3>
    <form method="POST">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="showPassword">
        <label class="form-check-label" for="showPassword">Mostra password</label>
      </div>
      <div class="d-grid mb-3">
        <input type="submit" class="btn btn-primary" value="Login">
      </div>
      <div class="text-center small">
        <a href="Autenticazione/send_password.php" class="d-block">Password dimenticata?</a>
        <a href="register.php" class="d-block">Registrati</a>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('showPassword').addEventListener('change', function () {
    const pwdField = document.getElementById('password');
    pwdField.type = this.checked ? 'text' : 'password';
  });
</script>
</body>
</html>