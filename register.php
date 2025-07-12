<?php
include 'Database/db_config.php';
include 'Footer/footer.php';

$errorPassword = '';
$errorEmail = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = $_POST['nome']     ?? '';
    $cognome  = $_POST['cognome']  ?? '';
    $email    = $_POST['email']    ?? '';
    $password = $_POST['password'] ?? '';

    $checkEmail = $conn->prepare("SELECT email FROM CREDENZIALI WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $errorEmail = "Questa email è già registrata.";
    } 
    elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[^\w\d]).{8,}$/', $password)) {
        $errorPassword = "La password deve contenere almeno 8 caratteri, una lettera maiuscola, un numero e un carattere speciale.";
    } 
    else {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO CREDENZIALI (nome, cognome, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $cognome, $email, $hashed);
        $stmt->execute();

        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Registrazione</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
  </style>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">

<div class="container-sm px-3">
  <div class="card p-4 shadow-sm rounded mx-auto" style="max-width: 450px;">
    <button type="button" class="btn btn-link mb-3 p-0" onclick="location.href='index.php'">&larr; Indietro</button>

    <h3 class="text-center mb-4">Registrazione</h3>

    <form method="POST">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" maxlength="50"
               value="<?php echo htmlspecialchars($nome ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="cognome" class="form-label">Cognome</label>
        <input type="text" class="form-control" name="cognome" id="cognome" maxlength="50"
               value="<?php echo htmlspecialchars($cognome ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" name="email" id="email" maxlength="100"
               value="<?php echo htmlspecialchars($email ?? '') ?>">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" required>
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="showPassword">
        <label class="form-check-label" for="showPassword">Mostra password</label>
      </div>

      <ul class="list-unstyled small mb-3">
        <li>• Almeno 8 caratteri</li>
        <li>• Almeno una lettera maiuscola</li>
        <li>• Almeno un numero</li>
        <li>• Almeno un carattere speciale (es. !@#$%)</li>
      </ul>

      <?php if ($errorEmail): ?>
        <div class="alert alert-danger py-2"><?php echo $errorEmail; ?></div>
      <?php endif; ?>

      <?php if ($errorPassword): ?>
        <div class="alert alert-danger py-2"><?php echo $errorPassword; ?></div>
      <?php endif; ?>

      <div class="d-grid">
        <input type="submit" class="btn btn-primary" value="Registrati">
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