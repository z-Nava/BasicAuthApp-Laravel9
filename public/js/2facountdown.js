document.addEventListener('DOMContentLoaded', function () {
    let countdown = 20; // Segundos para el temporizador
    const timerElement = document.getElementById('timer');
    const progressBar = document.getElementById('progress-bar');

    // Actualizar el temporizador cada segundo
    const interval = setInterval(() => {
        countdown--;
        timerElement.innerText = countdown;

        // Reducir la barra de progreso
        if (progressBar) {
            progressBar.style.width = `${(countdown / 20) * 100}%`;
        }

        // Redirigir al expirar el temporizador
        if (countdown <= 0) {
            clearInterval(interval);

            // Enviar solicitud para invalidar el usuario
            const userId = document.body.getAttribute('data-user-id');
            fetch('/invalidate-2fa', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ user_id: userId }),
            }).then(() => {
                // Redirigir al registro con un mensaje de error
                window.location.href = "/register?error=2fa_invalid";
            });
        }
    }, 1000);
});
