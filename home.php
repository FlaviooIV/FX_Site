<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Se la sessione non esiste, reindirizza a login
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

session_regenerate_id(true);

include 'Database/get_user_theme.php';
include 'Footer/footer.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <title>Home</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        window.addEventListener("pageshow", function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-financial"></script>
    <script src="Javascript/ajax_user.js"></script>
</head>
<body class="<?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'bg-dark text-white' : 'bg-light' ?>">

<div class="container py-4">
    <h2 id="user-welcome" class="text-center mb-4 fw-bold">Benvenuto</h2>

    <nav class="nav justify-content-center mb-4">
        <?php
        $links = [
            'News' => 'Moduli/news.php',
            'Volatilità' => 'Moduli/volatility.php',
            'Convertitore' => 'Moduli/converter.php',
            'Impostazioni' => 'Moduli/settings.php'
        ];
        foreach ($links as $label => $href) {
            $colorClass = ($_SESSION['theme'] ?? 'light') === 'dark' ? 'text-light' : 'text-primary';
            echo "<a class='nav-link {$colorClass} fw-semibold' href='{$href}'>{$label}</a>";
        }
        ?>
    </nav>

    <div class="controls d-flex flex-wrap justify-content-center align-items-center gap-3 mb-4">
        <div>
            <label for="pair" class="form-label mb-0">Coppia:</label>
            <select id="pair" class="form-select" onchange="mostraGrafico()">
                <option value="EURUSD">EUR/USD</option>
                <option value="USDJPY">USD/JPY</option>
                <option value="GBPUSD">GBP/USD</option>
                <option value="EURGBP">EUR/GBP</option>
            </select>
        </div>

        <div>
            <label for="timeframe" class="form-label mb-0">Timeframe:</label>
            <select id="timeframe" class="form-select" onchange="mostraGrafico()">
                <option value="1min">1 Minuto</option>
                <option value="5min" selected>5 Minuti</option>
                <option value="15min">15 Minuti</option>
                <option value="30min">30 Minuti</option>
                <option value="1hour">1 Ora</option>
                <option value="4hour">4 Ore</option>
                <option value="full">1 Giorno</option>
            </select>
        </div>
    </div>

    <div class="mb-4 position-relative" style="height: min(70vw, 500px);">
        <canvas id="grafico" class="position-absolute top-0 start-0 w-100 h-100 border border-1 rounded shadow-sm <?= ($_SESSION['theme'] ?? 'light') === 'dark' ? 'bg-dark' : 'bg-white' ?>"></canvas>
    </div>

    <div id="content">
        <?php include 'moduli/news.php'; ?>
    </div>
</div>

<script>
    const apiKey = "hGHDSypy8mIhm6rXBrST5r7YfbNfIo2C";
    let chart;

    function getTimeUnit(tf) {
        if (tf === '1min' || tf === '5min') return 'minute';
        if (tf === '15min' || tf === '30min' || tf === '1hour') return 'hour';
        return 'day';
    }

    async function mostraGrafico() {
        const pair = document.getElementById('pair').value;
        const tf = document.getElementById('timeframe').value;
        const url = tf === "full"
            ? `https://financialmodelingprep.com/api/v3/historical-price-full/${pair}?apikey=${apiKey}`
            : `https://financialmodelingprep.com/api/v3/historical-chart/${tf}/${pair}?apikey=${apiKey}`;

        try {
            const res = await fetch(url);
            const json = await res.json();
            const rawData = tf === "full" ? json.historical : json;

            if (!Array.isArray(rawData) || rawData.length === 0) {
                alert("Nessun dato disponibile.");
                return;
            }

            const dati = rawData.slice(0, 50).reverse().map(p => ({
                x: new Date(p.date),
                o: p.open,
                h: p.high,
                l: p.low,
                c: p.close
            }));

            const ctx = document.getElementById('grafico').getContext('2d');
            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'candlestick',
                data: {
                    datasets: [{
                        label: `${pair} (${tf})`,
                        data: dati,
                        barPercentage: 0.11,
                        categoryPercentage: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                tooltipFormat: 'dd/MM/yyyy HH:mm',
                                unit: getTimeUnit(tf)
                            },
                            ticks: { autoSkip: true, maxRotation: 0 }
                        },
                        y: { beginAtZero: false }
                    }
                }
            });

        } catch (err) {
            console.error("Errore:", err);
            alert("Errore durante il recupero dei dati.");
        }
    }

    window.onload = mostraGrafico;
</script>
</body>
</html>