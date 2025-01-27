document.addEventListener('DOMContentLoaded', function () {
    let countdown = 20; // 20 segundos
    const timerElement = document.getElementById('timer');

    // Actualiza el contador cada segundo
    const interval = setInterval(() => {
        countdown--;
        timerElement.innerText = countdown;

        // Cuando el contador llega a 0, redirige al registro
        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = "/register"; // Ruta al registro
        }
    }, 1000);
});
