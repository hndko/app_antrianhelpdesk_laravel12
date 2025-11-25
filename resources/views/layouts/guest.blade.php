<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-bg-main min-h-screen flex items-center justify-center p-4">

    <div class="bg-card-bg p-8 rounded-2xl shadow-lg w-full max-w-md border border-border">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-text-primary">Admin Login</h2>
            <p class="text-text-secondary text-sm mt-1">Silakan masuk untuk mengelola antrian</p>
        </div>

        {{ $slot }}
    </div>

</body>

</html>