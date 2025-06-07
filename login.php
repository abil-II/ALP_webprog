<?php
session_start();
include 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login StepIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php
    if(isset($_POST['nama'])){
        $nama = $_POST['nama'];
        $password = $_POST['password'];

        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$nama'and password='$password'");

        if(mysqli_num_rows($query) > 0){
            $data = mysqli_fetch_array($query);
            $_SESSION['user_id'] = $data;
            echo "<script>alert('Login Berhasil'); location.href='index.php';</script>";
        } else {
            echo "<script>alert('Login Gagal, Silahkan Cek Kembali Username dan Password Anda');</script>";
        } 
    }
?>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Login StepIn</h2>
        
        <form method="POST" autocomplete="off">
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
            <button type="submit"
                class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Login</button>
            <a href="register.php">register</a>
        </form>
    </div>
</body>

</html>