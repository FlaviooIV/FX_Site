<?php
include '../Database/db_config.php';
include '../Database/get_user_theme.php';
include '../Footer/footer.php';

$sql = "SELECT * FROM NEWS ORDER BY idN DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>News</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="<?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-light' ?>">

<div class="container py-4">
  <div class="mb-4">
    <button type="button" class="btn btn-secondary" onclick="location.href='../home.php'">&larr; Indietro</button>
  </div>

  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="mb-4 p-3 rounded shadow-sm <?= $_SESSION['theme'] === 'dark' ? 'bg-secondary text-white' : 'bg-white' ?>">
      <h3 class="h5 text-primary"><?php echo htmlspecialchars($row['titolo']); ?></h3>
      <p class="mb-0"><?php echo nl2br(htmlspecialchars($row['descrizione'])); ?></p>
    </div>
  <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>