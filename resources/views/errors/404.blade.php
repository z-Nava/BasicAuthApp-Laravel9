<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-500">404</h1>
        <p class="text-xl text-gray-700 mt-4">¡Oops! No pudimos encontrar esta página.</p>
        <a href="{{ route('login') }}" class="mt-6 inline-block px-6 py-2 text-white bg-blue-500 rounded-lg">Ir al inicio</a>
    </div>
</body>
</html>
