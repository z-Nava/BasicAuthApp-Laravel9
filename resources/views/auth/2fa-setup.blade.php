<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar 2FA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/2facountdown.js') }}" defer></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen" data-user-id="{{ $userId }}">


    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold text-center mb-6">Configura tu autenticación en dos pasos</h1>
        <p class="text-gray-700 mb-4 text-center">
            Escanea el siguiente código QR con Google Authenticator o una aplicación similar.
        </p>
        
        <div class="flex justify-center mb-4">
            {!! $qrCode !!}
        </div>

        <p class="text-gray-700 text-center">
            Si prefieres, puedes ingresar manualmente el siguiente código:
        </p>
        <div class="bg-gray-100 text-center p-2 mt-2 text-gray-900 rounded-md">
            <code>{{ $secretKey }}</code>
        </div>

        <div class="relative w-full bg-gray-200 h-2 rounded mt-4">
            <div id="progress-bar" class="absolute bg-blue-500 h-2 rounded transition-all duration-1000" style="width: 100%;"></div>
        </div>
        <p class="mt-4 text-sm text-gray-600 text-center">
            Esta página se redirigirá automáticamente en <span id="timer">20</span> segundos.
        </p>

        <p class="mt-4 text-sm text-gray-600 text-center">
            Una vez configurado, utiliza la aplicación para generar códigos cada vez que inicies sesión.
        </p>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="block bg-blue-500 text-white py-2 rounded-md text-center hover:bg-blue-600">
                Iniciar sesión
            </a>
        </div>
    </div>

</body>
</html>

