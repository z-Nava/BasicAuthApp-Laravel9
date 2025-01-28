document.addEventListener('DOMContentLoaded', function () {
    let countdown = 20; // 20 segundos
    const timerElement = document.getElementById('timer');

    // Obtén el ID del usuario desde un atributo data (enviado desde el backend)
    const userId = document.body.getAttribute('data-user-id');

    // Actualiza el contador cada segundo
    const interval = setInterval(() => {
        countdown--;
        timerElement.innerText = countdown;

        // Cuando el contador llega a 0, envía la solicitud para invalidar el usuario
        if (countdown <= 0) {
            clearInterval(interval);

            // Enviar solicitud POST para invalidar al usuario
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
