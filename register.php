<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'connection.php';
    $conn = my_connectDB();

    // Sanitize and validate input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Check if username already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");
    if (!$check) {
        $error = 'Database query failed: ' . mysqli_error($conn);
    } elseif (mysqli_fetch_assoc($check)) {
        $error = 'Username sudah terdaftar!';
    } else {
        // Hash the password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed', '$role')");
        if ($insert) {
            $success = 'Registrasi berhasil! Silakan login.';
        } else {
            $error = 'Registrasi gagal: ' . mysqli_error($conn);
        }
    }

    // Close the database connection
    my_closeDB($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up StepIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Sign Up StepIn</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-red-600 text-center"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="mb-4 text-green-600 text-center"><?= $success ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="username">Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="role">Role</label>
                <select name="role" id="role" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit"
                class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Sign Up</button>
        </form>
        <div class="mt-4 text-center">
            <a href="login.php" class="text-blue-600 hover:underline">Sudah punya akun? Login di sini</a>
        </div>
    </div>
</body>
</html>