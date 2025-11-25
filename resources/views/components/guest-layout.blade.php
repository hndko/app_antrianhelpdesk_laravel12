<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-200">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Admin Login</h1>
            <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk mengelola antrian</p>
        </div>

        {{ $slot }}
    </div>

</body>

</html>