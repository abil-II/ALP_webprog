<?php
session_start();
include 'connection.php';
$conn = my_connectDB();

// Ambil data user dari session
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$email = '';
$role = '';
$success = '';
$error = '';

// Update akun
if (isset($_POST['update'])) {
    $new_nama = mysqli_real_escape_string($conn, $_POST['username']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = trim($_POST['password']);
    // Role tidak bisa diganti, gunakan $role lama
    if ($new_password !== '') {
        $update = mysqli_query($conn, "UPDATE users SET nama='$new_nama', email='$new_email', password='$new_password' WHERE nama='$nama'");
    } else {
        $update = mysqli_query($conn, "UPDATE users SET nama='$new_nama', email='$new_email' WHERE nama='$nama'");
    }
    if ($update) {
        $_SESSION['nama'] = $new_nama;
        $success = 'Akun berhasil diupdate!';
        $nama = $new_nama;
        $email = $new_email;
        // $role tidak berubah
    } else {
        $error = 'Gagal update akun: ' . mysqli_error($conn);
    }
}

// Hapus akun
if (isset($_POST['delete'])) {
    $delete = mysqli_query($conn, "DELETE FROM users WHERE nama='$nama'");
    if ($delete) {
        session_unset();
        session_destroy();
        header('Location: register.php');
        exit();
    } else {
        $error = 'Gagal menghapus akun: ' . mysqli_error($conn);
    }
}

// Ambil data user terbaru
if ($nama) {
    $query = "SELECT * FROM users WHERE nama = '$nama' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $email = $user['email'];
        $role = $user['role'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Info StepIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Account Info</h2>
        <?php if ($success): ?>
            <div class="mb-4 text-green-600 text-center"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="mb-4 text-red-600 text-center"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($nama): ?>
        <form method="POST" autocomplete="off">
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="username">Username</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($nama) ?>" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="password">Password (kosongkan jika tidak ingin ganti)</label>
                <input type="password" name="password" id="password"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="role">Role</label>
                <input type="text" name="role" id="role" value="<?= htmlspecialchars($role) ?>" readonly
                    class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100 cursor-not-allowed">
            </div>
            <div class="flex gap-2">
                <button type="submit" name="update"
                    class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Update Akun</button>
                <button type="submit" name="delete" onclick="return confirm('Yakin ingin menghapus akun?')"
                    class="w-full py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Hapus Akun</button>
            </div>
        </form>
        <?php else: ?>
        <div class="mb-4 text-red-600 text-center">Anda belum login.</div>
        <?php endif; ?>
        <div class="mt-4 text-center">
            <a href="index.php" class="text-blue-600 hover:underline">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>