<?php
include '../Database/get_user_theme.php';
include '../Footer/footer.php';
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Convertitore Valuta</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> 
</head>

<body class="<?= $_SESSION['theme'] === 'dark' ? 'bg-dark text-white' : 'bg-light' ?>">

<div class="container py-4" style="max-width: 480px;">
  <div class="mb-4">
    <button type="button" class="btn btn-secondary" onclick="location.href='../home.php'">&larr; Indietro</button>
  </div>

  <h1 class="mb-4 text-center">Convertitore Valuta</h1>

  <div class="mb-3">
    <label for="amount" class="form-label">Importo in Euro (€):</label>
    <input type="number" class="form-control" id="amount" placeholder="Inserisci importo in euro" />
  </div>

  <div class="mb-3">
    <label for="valuta" class="form-label">Scegli la valuta:</label>
    <select class="form-select" id="valuta"></select>
  </div>

  <button class="btn btn-primary w-100 mb-3" onclick="convertiValuta()">Converti</button>

  <div id="result" class="alert alert-info text-center d-none"></div>
</div>

<script>
  function loadCurrencies() {
    const allowedCurrencies = ['USD', 'GBP', 'JPY', 'CHF'];

    fetch('https://tassidicambio.bancaditalia.it/terzevalute-wf-web/rest/v1.0/currencies') 
      .then(response => response.json())
      .then(data => {
        const selectElement = document.getElementById('valuta');
        allowedCurrencies.forEach(code => {
          const currency = data.currencies.find(c => c.isoCode === code);
          if(currency){
            const option = document.createElement('option');
            option.value = currency.isoCode;
            option.text = currency.name;
            selectElement.add(option); 
          }
        });
      })
      .catch(error => {
        console.error("Errore durante il fetch delle valute:", error);
        alert('Impossibile caricare la lista delle valute.');
      });
  }

  function convertiValuta() {
    const amount = parseFloat(document.getElementById('amount').value); // Legge l'importo inserito
    const currency = document.getElementById('valuta').value; // Legge la valuta selezionata
    const resultDiv = document.getElementById('result');

    if (isNaN(amount) || amount <= 0) {
      alert('Inserisci un importo valido.');
      resultDiv.classList.add('d-none');
      return;
    }

    const exchangeRates = {
      'USD': 1.1,
      'GBP': 0.85,
      'JPY': 145,
      'CHF': 1.05
    };

    const rate = exchangeRates[currency];
    if (rate) {
      const convertedAmount = (amount * rate).toFixed(2);
      let symbol = '';
      if (currency === 'USD') symbol = '$ ';
      else if (currency === 'GBP') symbol = '£ ';
      else if (currency === 'JPY') symbol = '¥ ';
      else if (currency === 'CHF') symbol = 'CHF ';

      resultDiv.textContent = L'importo convertito è: ${symbol}${convertedAmount}; // Mostra il risultato
      resultDiv.classList.remove('d-none');
    } 
    else {
      alert('Valuta non supportata.');
      resultDiv.classList.add('d-none');
    }
  }

  window.onload = loadCurrencies; // Caricamento valute all'apertura della pagina
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>