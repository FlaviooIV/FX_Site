# 💱 Forex Dashboard – Piattaforma Web per Monitoraggio e Analisi Valutaria che consente di:

- 📈 Visualizzare grafici candlestick in tempo reale per coppie valutarie
- 💬 Leggere news aggiornate e personalizzabili
- 🔍 Analizzare la volatilità storica di specifiche valute
- 💸 Convertire valute con dati ufficiali della Banca d’Italia

Il tutto con un'interfaccia semplice, responsive e alimentata da API esterne e database MySQL.

---

## 🚀 Tecnologie Utilizzate

### Front-End
- HTML
- Bootrstrap 5 
- JavaScript (AJAX + Chart.js)

### Back-End
- PHP

### Database
- MySQL (hostato su Altervista)

### API Esterne
- Financial Modeling Prep (candlestick chart)
- Banca d’Italia (tassi di cambio)

---

## 🧠 Funzionalità Chiave

### 🔐 Login & Registrazione
- Autenticazione con email e password hashata
- Recupero password automatizzato via email

### 🏠 Home Page
- Benvenuto personalizzato con il nome utente
- Grafico candlestick con selezione di:
  - Coppia valutaria (EUR/USD, USD/JPY, GBP/USD, EUR/GBP)
  - Timeframe (1m, 5m, 15m, 30m, 1h, 4h, 1d)
  
### 📰 News
- Query dinamica al database
- Visualizzazione di titolo e descrizione delle notizie

### 📊 Volatilità
- Lettura dati da CSV (investing.com), salvati nel DB
- Grafici di volatilità per coppie come EUR/USD, USD/CHF, GBP/JPY

### 🔄 Convertitore Valutario
- Input in Euro
- Conversione in USD, CHF, JPY, GBP
- Dati ufficiali in tempo reale (Banca d’Italia)

### ⚙️ Impostazioni
- Logout
- Accesso alla documentazione del progetto

---

## 🗃️ Database

- **CREDENZIALI**: Gestione utenti
- **NEWS**: Archivio notizie
- **VALUTE**: Tabelle separate per ogni coppia valutaria (es. `eurusd_data`)

---

## 🔮 Estensioni Future

- Autenticazione a due fattori (2FA)
- Importazione automatica delle news da API
- Statistiche attività utente
- Area personale con modifiche credenziali/preferenze

---

## 📍 Hosting

- Il progetto è attualmente hostato su **Altervista**, con database MySQL integrato.

---

## 🔍 Come Vederlo?
- Link: flaviovacca2025.altervista.org/Esame

