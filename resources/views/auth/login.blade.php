<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold text-center mb-6">Iniciar sesión</h1>

            <form action="{{ route('login') }}" method="POST">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
            <input type="email" name="email" id="email" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" value="{{ old('email') }}" minlength="5">
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" name="password" id="password" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" minlength="8">
        </div>

        <!-- 2FA Code -->
        <div class="mb-4">
            <label for="2fa_code" class="block text-sm font-medium text-gray-700">Código 2FA</label>
            <input type="text" name="2fa_code" id="2fa_code" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" placeholder="123456" pattern="\d{6}" title="El código debe ser de 6 dígitos">
        </div>

        <div class="mb-4 grid place-content-center">
            {!! HCaptcha::renderJs() !!}
            {!! HCaptcha::display() !!}
        </div>  
        
        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">Iniciar sesión</button>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}" class="text-sm text-blue-500 hover:text-blue-700">¿No tienes cuenta? Regístrate aquí</a>
        </div>

        <!-- Errors -->
        @if($errors->any())
            <div class="mt-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>

    </div>

</body>
</html>

