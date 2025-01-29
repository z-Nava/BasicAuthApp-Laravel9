<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h1 class="text-2xl font-bold text-center mb-6">Registrarse</h1>
        <!-- Mostrar mensaje de error si existe -->
        @if(isset($errorMessage))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ $errorMessage }}
        </div>
        @endif
                <form action="{{ route('register') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="name" id="name" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" value="{{ old('name') }}" pattern="[A-Za-z\s]+" title="El nombre solo puede contener letras y espacios">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                <input type="email" name="email" id="email" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" value="{{ old('email') }}" minlength="5">
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" name="password" id="password" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" minlength="8" pattern=".*[A-Z].*" title="La contraseña debe contener al menos una letra mayúscula, una minúscula, un número y un carácter especial">
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" minlength="8">
            </div>

            <div class="mb-4 grid place-content-center">
                {!! HCaptcha::renderJs() !!}
                {!! HCaptcha::display() !!}
            </div>  
            
            <!-- Submit Button -->
            <div class="mb-4">
                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-md hover:bg-green-600">Registrar</button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-500 hover:text-blue-700">¿Ya tienes cuenta? Inicia sesión aquí</a>
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
