<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Portal Hak Integrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<form action="proses_login.php" method="POST" class="bg-white p-8 rounded-xl shadow w-96">
    <h2 class="text-2xl font-bold text-center mb-6">Login Portal</h2>

    <input type="text" name="username" placeholder="Username" required
        class="w-full border p-3 rounded mb-4">

    <input type="password" name="password" placeholder="Password" required
        class="w-full border p-3 rounded mb-4">

    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded">
        Login
    </button>
</form>

</body>
</html>
