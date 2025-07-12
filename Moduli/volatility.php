<?php
include '../Database/db_config.php'; 
include '../Database/get_user_theme.php'; 
include '../Footer/footer.php';
?>
<button type="button" class="btn btn-secondary mb-4" onclick="location.href='../home.php'">Indietro</button>
<?php
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$files = [
    "eurusd" => "CSV/eurusd.csv",
    "gbpjpy" => "CSV/gbpjpy.csv",
    "eurgbp" => "CSV/eurgbp.csv",
    "usdjpy" => "CSV/usdjpy.csv",
    "usdchf" => "CSV/usdchf.csv"
];

foreach ($files as $pair => $filename) {
    $path = __DIR__ . "/" . $filename;
    if (!file_exists($path)) continue;

    $csv = fopen($path, "r");
    fgetcsv($csv);

    while (($row = fgetcsv($csv)) !== false) {
        $date = date("Y-m-d", strtotime($row[0]));
        $price = floatval(str_replace(",", "", $row[1]));
        $stmt = $conn->prepare("INSERT INTO {$pair}_data (date, close) VALUES (?, ?) ON DUPLICATE KEY UPDATE close = VALUES(close)");
        $stmt->bind_param("sd", $date, $price);
        $stmt->execute();
    }
    fclose($csv);
}

$selectedPair = $_GET['pair'] ?? 'eurusd';
$table = $selectedPair . "_data";

$volatilita = [];
$query = $conn->query("SELECT date, close FROM $table ORDER BY date ASC");
$prev = null;
while ($row = $query->fetch_assoc()) {
    if ($prev !== null) {
        $vol = abs($row['close'] - $prev['close']);
        $volatilita[] = [
            'date' => $row['date'],
            'vol' => round($vol, 5)
        ];
    }
    $prev = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <title>Volatilità</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #chart-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            height: 450px; /* altezza fissa */
            margin: 0 auto;
        }

        @media (max-width: 600px) {
            #chart-container {
                height: 320px;
            }
        }
    </style>
</head>
<body class="<?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-light' ?>">

<div class="container py-4" style="max-width: 900px;">
    <h2 class="mb-4 text-center">Volatilità Giornaliera</h2>

    <form method="get" class="mb-4 d-flex justify-content-center align-items-center gap-3 flex-wrap">
        <label for="pair" class="form-label mb-0">Scegli la coppia:</label>
        <select id="pair" name="pair" class="form-select w-auto" onchange="this.form.submit()">
            <option value="eurusd" <?= $selectedPair == 'eurusd' ? 'selected' : '' ?>>EUR/USD</option>
            <option value="gbpjpy" <?= $selectedPair == 'gbpjpy' ? 'selected' : '' ?>>GBP/JPY</option>
            <option value="eurgbp" <?= $selectedPair == 'eurgbp' ? 'selected' : '' ?>>EUR/GBP</option>
            <option value="usdjpy" <?= $selectedPair == 'usdjpy' ? 'selected' : '' ?>>USD/JPY</option>
            <option value="usdchf" <?= $selectedPair == 'usdchf' ? 'selected' : '' ?>>USD/CHF</option>
        </select>
    </form>

    <div id="chart-container">
        <canvas id="chart"></canvas>
    </div>
</div>

<script>
    const data = <?= json_encode($volatilita); ?>;
    const labels = data.map(item => item.date);
    const values = data.map(item => item.vol);

    new Chart(document.getElementById('chart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Volatilità Giornaliera <?= strtoupper($selectedPair) ?>',
                data: values,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Volatilità Giornaliera - <?= strtoupper($selectedPair) ?>',
                    font: { size: 18 }
                },
                legend: { display: true }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Data',
                        font: { size: 14 }
                    },
                    ticks: { maxRotation: 45, minRotation: 45 }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Variazione Assoluta',
                        font: { size: 14 }
                    },
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>