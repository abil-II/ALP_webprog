<?php
session_start();
include 'connection.php';
$conn = my_connectDB();
//done
if (isset($_POST['submit'])) {
    $nama = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE nama = '$nama' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Debug aktif: cek isi password
        echo "Input: '" . $password . "' | DB: '" . $user['password'] . "'";

        if (trim($password) === trim($user['password'])) {
            $_SESSION['id'] = $user['user_id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            echo "<script>alert('Login Berhasil'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Nama tidak ditemukan!'); window.location.href='login.php';</script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login StepIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Login StepIn</h2>

        <form method="POST" >
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="username">Username</label>
                <input type="text" name="username" id="username"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="password">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit" name="submit"
                class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Login</button>
            <a href="register.php">register</a>
        </form>
    </div>
</body>

</html>