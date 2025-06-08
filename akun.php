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

// Ambil histori transaksi user
$histori = [];
if ($nama) {
    // Ambil user_id
    $user_id = null;
    $q = mysqli_query($conn, "SELECT user_id FROM users WHERE nama = '" . mysqli_real_escape_string($conn, $nama) . "' LIMIT 1");
    if ($row = mysqli_fetch_assoc($q)) {
        $user_id = $row['user_id'];
    }
    if ($user_id) {
        $q = mysqli_query($conn, "SELECT * FROM transaksi WHERE user_id = $user_id ORDER BY tanggal DESC");
        while ($row = mysqli_fetch_assoc($q)) {
            $row['detail'] = [];
            $qd = mysqli_query($conn, "SELECT td.*, p.nama_produk FROM transaksi_detail td JOIN produk p ON td.produk_id=p.id WHERE td.transaksi_id=" . $row['transaksi_id']);
            while ($d = mysqli_fetch_assoc($qd)) {
                $row['detail'][] = $d;
            }
            $histori[] = $row;
        }
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
        <?php if (!empty($histori)): ?>
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-4 text-blue-700">Histori Pembelian</h3>
        <?php foreach ($histori as $trx): ?>
            <div class="mb-6 border rounded-lg p-4 bg-gray-50">
                <div class="mb-2 font-semibold">No. Transaksi: <span class="font-mono text-blue-700"><?= $trx['transaksi_id'] ?></span></div>
                <div class="mb-2 text-sm text-gray-600">Tanggal: <?= $trx['tanggal'] ?> | Status: <span class="font-semibold <?= $trx['status']==='selesai' ? 'text-green-600' : ($trx['status']==='dibatalkan' ? 'text-red-600' : 'text-yellow-600') ?>"><?= ucfirst($trx['status']) ?></span></div>
                <table class="w-full text-sm mb-2">
                    <thead>
                        <tr class="text-gray-700">
                            <th class="py-1 px-2 text-left">Produk</th>
                            <th class="py-1 px-2">Jumlah</th>
                            <th class="py-1 px-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($trx['detail'] as $d): ?>
                        <tr>
                            <td class="py-1 px-2"><?= htmlspecialchars($d['nama_produk']) ?></td>
                            <td class="py-1 px-2 text-center"><?= $d['jumlah'] ?></td>
                            <td class="py-1 px-2 text-right">Rp <?= number_format($d['total_harga_beli'],0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-right font-bold">Total: Rp <?= number_format($trx['total'],0,',','.') ?></div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
        <div class="mt-4 text-center">
            <a href="index.php" class="text-blue-600 hover:underline">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>