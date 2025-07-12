<?php
session_start();

header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit();
}
include '../Database/db_config.php';
include '../Database/get_user_theme.php';
include '../Footer/footer.php';

$user_id = $_SESSION['id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pw = $_POST['current_pw'];
    $new_pw = $_POST['new_pw'];
    $conf_pw = $_POST['conf_pw'];

    $stmt = $conn->prepare("SELECT password FROM CREDENZIALI WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_pw);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_pw, $hashed_pw)) {
        $message = "<div class='alert alert-danger text-center'>Password attuale errata.</div>";
    } 
    elseif ($current_pw === $new_pw) {
        $message = "<div class='alert alert-warning text-center'>La nuova password deve essere diversa da quella attuale.</div>";
    } 
    elseif ($new_pw !== $conf_pw) {
        $message = "<div class='alert alert-warning text-center'>Le due password non coincidono.</div>";
    } 
    else {
        $new_hashed_pw = password_hash($new_pw, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE CREDENZIALI SET password = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_hashed_pw, $user_id);
        if ($update_stmt->execute()) {
            $message = "<div class='alert alert-success text-center'>Password aggiornata con successo.</div>";
        } 
        else {
            $message = "<div class='alert alert-danger text-center'>Errore durante l'aggiornamento della password.</div>";
        }
        $update_stmt->close();
    }
}
if (isset($_POST['change_theme']) && in_array($_POST['theme'], ['light', 'dark'])) {
    $new_theme = $_POST['theme'];
    $stmt = $conn->prepare("UPDATE CREDENZIALI SET theme = ? WHERE id = ?");
    $stmt->bind_param("si", $new_theme, $user_id);
    $stmt->execute();
    $_SESSION['theme'] = $new_theme;
    header("Location: settings.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <title>Impostazioni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="<?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-light' ?>">

<div class="container py-5 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
    <div class="mb-4">
        <a href="../home.php" class="btn btn-secondary">Indietro</a>
    </div>

    <div class="card shadow mb-4 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
        <div class="card-body <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
            <h3 class="card-title text-center mb-4">Cambia Password</h3>

            <?php if ($message) echo $message; ?>

            <form method="POST">
                <div class="mb-3 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                    <label for="current_pw" class="form-label">Password Attuale</label>
                    <input type="password" class="form-control" name="current_pw" id="current_pw" required>
                </div>

                <div class="mb-3 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                    <label for="new_pw" class="form-label">Nuova Password</label>
                    <input type="password" class="form-control" name="new_pw" id="new_pw" required>
                </div>

                <div class="mb-3 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                    <label for="conf_pw" class="form-label">Conferma Password</label>
                    <input type="password" class="form-control" name="conf_pw" id="conf_pw" required>
                </div>

                <div class="d-grid <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                    <button type="submit" class="btn btn-primary">Cambia Password</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card shadow mt-4 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
    	<div class="card-body text-center <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
        	<h4 class="card-title mb-3">Tema</h4>
        	<form method="POST">
            	<input type="hidden" name="change_theme" value="1">
            	<button type="submit" name="theme" value="light" class="btn btn-outline-secondary <?= $_SESSION['theme'] === 'light' ? 'active' : '' ?>">Chiaro</button>
            	<button type="submit" name="theme" value="dark" class="btn btn-outline-dark <?= $_SESSION['theme'] === 'dark' ? 'active' : '' ?>">Scuro</button>
        	</form>
    	</div>
	</div>

    <div class="row g-3 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
        <div class="col-md-6 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
            <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                <div class="card-body text-center <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                    <a href="Documentazione/documentazione.php" class="btn btn-outline-primary">Documentazione</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
            <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                <div class="card-body text-center <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
                    <a href="../Autenticazione/logout.php" class="btn btn-outline-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>