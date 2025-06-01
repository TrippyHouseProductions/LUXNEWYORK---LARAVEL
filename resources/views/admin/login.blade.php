<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto bg-white rounded-lg p-8 shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>
        <form method="POST" action="/admin/login">
            @csrf
            <label class="block mb-4">
                <span>Email</span>
                <input type="email" name="email" class="w-full border px-3 py-2 rounded" required>
            </label>
            <label class="block mb-6">
                <span>Password</span>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded" required>
            </label>
            <button class="bg-indigo-700 text-white w-full px-6 py-2 rounded-xl hover:bg-indigo-800 transition-colors">
                Login
            </button>
        </form>
    </div>
</body>
</html>
