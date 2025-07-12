window.addEventListener("DOMContentLoaded", () => { 
    fetch("Ajax/get_username.php")
        .then(res => res.text())
        .then(name => {
            document.getElementById("user-welcome").innerText = `Benvenuto ${name}`;
        })
        .catch(err => console.error("Errore nel recupero nome:", err));
});
