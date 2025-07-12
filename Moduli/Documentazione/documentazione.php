<?php
session_start();

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

include '../../Footer/footer.php';
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <title>Documentazione</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <style>
        code {
            background-color: #f1f1f1;
            padding: 2px 4px;
            border-radius: 4px;
        }
        body.bg-dark code {
            background-color: #2d2d2d;
            color: #ddd;
        }
    </style>
</head>
<body class="<?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-light text-dark' ?>">

<div class="container py-5">
    <div class="mb-3">
        <a href="../settings.php" class="btn btn-secondary">Indietro</a>
    </div>

    <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?> mb-4">
        <div class="card-body">
            <h1 class="fw-bold">Documentazione</h1>
        </div>
    </div>

    <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?> mb-4">
        <div class="card-body">
            <h2>1.0 - Informazioni Generali</h2>
            <p><strong>Hosting utilizzato:</strong> Altervista.<br />
            <strong>Database:</strong> MySQL Altervista.</p>
        </div>
    </div>

    <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?> mb-4">
        <div class="card-body">
            <h2>1.1 - Tecnologie utilizzate</h2>
            <p><strong>Front-end:</strong> HTML, CSS, JavaScript.<br />
            <strong>Back-end:</strong> PHP.<br />
            <strong>Database:</strong> MySQL.</p>

            <p><strong>API Esterne:</strong></p>
            <ul>
                <li>Grafico Candlestick: Financial Modeling Prep.</li>
                <li>Convertitore: Banca d'Italia.</li>
            </ul>
        </div>
    </div>

    <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?> mb-4">
        <div class="card-body">
            <h2>2.0 - Struttura del Database</h2>

            <h3>2.1 - CREDENZIALI</h3>
            <ul>
                <li>id (INT, PK, AUTO_INCREMENT)</li>
                <li>nome (VARCHAR - 50)</li>
                <li>cognome (VARCHAR - 50)</li>
                <li>email (VARCHAR, INDEX - 100)</li>
                <li>password (VARCHAR – 255)</li>
            </ul>

            <h3>2.2 - NEWS</h3>
            <ul>
                <li>idN (INT, PK, AUTO_INCREMENT)</li>
                <li>titolo (VARCHAR - 255)</li>
                <li>descrizione (TEXT)</li>
            </ul>

            <h3>2.3 - Tabelle Valute Volatilità</h3>
            <p>Es: <code>eurusd_data</code>, <code>usdjpy_data</code>, ecc.</p>
            <ul>
                <li>Id (INT, PK, AUTO_INCREMENT)</li>
                <li>date (DATE)</li>
                <li>close (DOUBLE)</li>
            </ul>
        </div>
    </div>

    <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?> mb-4">
        <div class="card-body">
            <h2>3.0 - Struttura delle Pagine</h2>

            <h3>3.1 - Login</h3>
            <ul>
                <li>Tecnologie: PHP + HTML</li>
                <li>Verifica credenziali hashate nella tabella CREDENZIALI</li>
            </ul>

            <h4>3.1.1 - Password dimenticata</h4>
            <ul>
                <li>Form per email</li>
                <li>Password generata automaticamente</li>
                <li>Invio con <code>mail()</code></li>
                <li>Update nel database</li>
            </ul>

            <h3>3.2 - Registrazione</h3>
            <ul>
                <li>Tecnologie: PHP + HTML</li>
                <li>Salvataggio nella tabella CREDENZIALI</li>
            </ul>

            <h3>3.3 - Home Page</h3>
            <ul>
                <li>Tecnologie: HTML + JavaScript (AJAX)</li>
            </ul>

            <h4>3.3.1 - Messaggio di benvenuto</h4>
            <p>Recuperato via AJAX con nome utente</p>

            <h4>3.3.2 - Grafico Candlestick</h4>
            <ul>
                <li>Dati da Financial Modeling Prep</li>
                <li>Scelte: coppia valutaria e timeframe</li>
            </ul>

            <h3>3.4 - Moduli</h3>

            <h4>3.4.1 - News</h4>
            <ul>
                <li>Query al DB tabella NEWS</li>
                <li>Visualizzazione: Titolo + Descrizione</li>
            </ul>

            <h4>3.4.2 - Volatilità</h4>
            <ul>
                <li>Dati da CSV di investing.com</li>
                <li>Selezione coppia valutaria</li>
                <li>Grafico con Chart.js</li>
            </ul>

            <h4>3.4.3 - Convertitore</h4>
            <ul>
                <li>Input Euro -> selezione valuta (USD, CHF, JPY, GBP)</li>
                <li>Conversione via API Banca d'Italia</li>
            </ul>

            <h4>3.4.4 - Impostazioni</h4>
            <ul>
                <li>Form per modificare la password</li>
                <li>Scelta tema chiaro/scuro</li>
                <li>Accesso a documentazione</li>
                <li>Collegamento a logout.php</li>
            </ul>
        </div>
    </div>

    <div class="card shadow <?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-white text-dark' ?>">
        <div class="card-body">
            <h2>4.0 - Possibili Estensioni Future</h2>
            <ul>
                <li>Autenticazione a due fattori (2FA)</li>
                <li>Inserimento automatico delle news via API</li>
                <li>Migliorare recupero password con token</li>
                <li>Nascondere API key</li>
                <li>Area personale con preferenze</li>
                <li>Migliorare grafica</li>
            </ul>
        </div>
    </div>
</div>

<div class="text-center mt-4">
    <img src="esame.JPG" class="img-fluid" style="max-width: 800px;">
    <p class="mt-2 fw-bold">Realizzato con App Diagrams</p>
</div><br><br>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>