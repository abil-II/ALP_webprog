<?php
session_start();

include 'connection.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = trim($_POST['nama_produk']);
    $harga = floatval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $deskripsi = trim($_POST['deskripsi']);
    $kategori_id = intval($_POST['kategori_id']);
    $gambar = '';
    $admin_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;

    // Handle file upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'img/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = basename($_FILES['gambar']['name']);
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($ext, $allowed)) {
            $targetFile = $uploadDir . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
                $gambar = $targetFile;
            } else {
                $error = 'Gagal upload gambar.';
            }
        } else {
            $error = 'Format gambar tidak didukung.';
        }
    } else {
        $error = 'Gambar produk wajib diupload.';
    }

    // Debug admin_id
    if (!$admin_id) {
        $error = 'Anda belum login sebagai admin. admin_id kosong!';
    }

    // Perbaikan pengecekan agar nilai 0 pada harga/stok tetap valid
    if ($nama_produk !== '' && $deskripsi !== '' && $kategori_id && $gambar && $admin_id && !$error && is_numeric($harga) && is_numeric($stok)) {
        $conn = my_connectDB();
        $stmt = mysqli_prepare($conn, "INSERT INTO produk (nama_produk, harga, stok, deskripsi, kategori_id, gambar, admin_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sdisssi", $nama_produk, $harga, $stok, $deskripsi, $kategori_id, $gambar, $admin_id);
        mysqli_stmt_execute($stmt);
        my_closeDB($conn);
        header("Location: read.php");
        exit();
    } else if (!$error) {
        $error = "Semua field harus diisi! (admin_id: " . var_export($admin_id, true) . ")";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - StepIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Tambah Produk Baru</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-red-600 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="harga">Harga</label>
                <input type="number" name="harga" id="harga" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="stok">Stok</label>
                <input type="number" name="stok" id="stok" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="kategori_id">Kategori</label>
                <select name="kategori_id" id="kategori_id" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="1">Sneakers</option>
                    <option value="2">Boots</option>
                    <option value="3">Running</option>
                    <option value="4">Casual</option>
                    <option value="5">Slip On</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="gambar">Gambar Produk</label>
                <input type="file" name="gambar" id="gambar" accept="image/*" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                <small class="text-gray-500">Format: jpg, jpeg, png, gif, webp.</small>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="flex-1 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Tambah Produk</button>
                <a href="read.php" class="flex-1 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition text-center flex items-center justify-center">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>