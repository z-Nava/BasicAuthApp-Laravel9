<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificacion 2FA</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Verificacion 2FA</h2>
        <form action="{{ route('verify.2fa') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="2fa_code" class="block text-gray-700 text-sm font-bold mb-2">Ingresa el codigo de 6 digitos</label>
                <input type="text" name="2fa_code" id="2fa_code" required class="mt-2 p-2 w-full border border-gray-300 rounded-md" placeholder="123456" pattern="\d{6}" title="El código debe ser de 6 dígitos">
            </div>
            <div class="flex items-center justify-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Verificar
                </button>
            </div>
        </form>
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
</body>
</html>