<?php

include 'connection.php';
$error = '';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $product_id = intval($_POST['product_id']);
    $nama_produk = trim($_POST['nama_produk']);
    $harga = floatval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $deskripsi = trim($_POST['deskripsi']);
    $kategori_id = intval($_POST['kategori_id']);
    $gambar = $_POST['gambar_lama'];

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
    }

    if ($product_id && $nama_produk && $harga && $stok && $kategori_id && !$error) {
        $conn = my_connectDB();
        $stmt = mysqli_prepare($conn, "UPDATE produk SET nama_produk=?, harga=?, stok=?, deskripsi=?, kategori_id=?, gambar=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sdisssi", $nama_produk, $harga, $stok, $deskripsi, $kategori_id, $gambar, $product_id);
        mysqli_stmt_execute($stmt);
        my_closeDB($conn);
        header("Location: read.php");
        exit();
    } else if (!$error) {
        $error = "Semua field harus diisi!";
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $product_id = intval($_POST['product_id']);
    if ($product_id) {
        $conn = my_connectDB();
        $stmt = mysqli_prepare($conn, "DELETE FROM produk WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        my_closeDB($conn);
        header("Location: read.php");
        exit();
    } else {
        $error = "Produk tidak valid!";
    }
}

// Fetch product data for edit form
$product = null;
if ($product_id) {
    $conn = my_connectDB();
    $result = mysqli_query($conn, "SELECT * FROM produk WHERE id=$product_id");
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    }
    my_closeDB($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - StepIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Edit Produk</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-red-600 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($product): ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
            <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($product['gambar']) ?>">
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="nama_produk">Nama Produk</label>
                <input type="text" name="nama_produk" id="nama_produk" required value="<?= htmlspecialchars($product['nama_produk']) ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="harga">Harga</label>
                <input type="number" name="harga" id="harga" required value="<?= htmlspecialchars($product['harga']) ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="stok">Stok</label>
                <input type="number" name="stok" id="stok" required value="<?= htmlspecialchars($product['stok']) ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="deskripsi">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($product['deskripsi']) ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="kategori_id">Kategori</label>
                <select name="kategori_id" id="kategori_id" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="1" <?= $product['kategori_id']==1?'selected':'' ?>>Sneakers</option>
                    <option value="2" <?= $product['kategori_id']==2?'selected':'' ?>>Boots</option>
                    <option value="3" <?= $product['kategori_id']==3?'selected':'' ?>>Running</option>
                    <option value="4" <?= $product['kategori_id']==4?'selected':'' ?>>Casual</option>
                    <option value="5" <?= $product['kategori_id']==5?'selected':'' ?>>Slip On</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="gambar">Gambar Produk</label>
                <?php if (!empty($product['gambar'])): ?>
                    <img src="<?= htmlspecialchars($product['gambar']) ?>" alt="Gambar Produk" class="mb-2 w-32 h-32 object-cover rounded">
                <?php endif; ?>
                <input type="file" name="gambar" id="gambar" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                <small class="text-gray-500">Kosongkan jika tidak ingin mengubah gambar. Format: jpg, jpeg, png, gif, webp.</small>
            </div>
            <div class="flex justify-between">
                <button type="submit" name="update" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Update</button>
                <a href="read.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Cancel</a>
            </div>
        </form>
        <?php else: ?>
            <div class="text-center text-gray-600">Produk tidak ditemukan.</div>
        <?php endif; ?>
    </div>
</body>
</html>