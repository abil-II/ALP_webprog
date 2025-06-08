<?php
session_start();

include 'connection.php';
$conn = my_connectDB();

// Cek login user
if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

// Ambil user_id dari session
$user_id = $_SESSION['id'];

// Tambah ke keranjang (DB)
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $product_id = (int)$_GET['add'];
    $qty = isset($_GET['qty']) && is_numeric($_GET['qty']) && $_GET['qty'] > 0 ? (int)$_GET['qty'] : 1;
    // Cek apakah produk sudah ada di keranjang user
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE user_id=$user_id AND produk_id=$product_id");
    if (mysqli_num_rows($cek) > 0) {
        // Update jumlah
        mysqli_query($conn, "UPDATE keranjang SET jumlah=$qty WHERE user_id=$user_id AND produk_id=$product_id");
    } else {
        // Insert baru
        mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, jumlah) VALUES ($user_id, $product_id, $qty)");
    }
    // Jika request dari AJAX, jangan redirect
    if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        header('Location: keranjang.php');
        exit();
    } else {
        exit('OK');
    }
}

// Hapus dari keranjang (DB)
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    mysqli_query($conn, "DELETE FROM keranjang WHERE user_id=$user_id AND produk_id=$product_id");
    header('Location: keranjang.php');
    exit();
}

// Update jumlah (DB)
if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        if ($qty > 0) {
            mysqli_query($conn, "UPDATE keranjang SET jumlah=$qty WHERE user_id=$user_id AND produk_id=$id");
        } else {
            mysqli_query($conn, "DELETE FROM keranjang WHERE user_id=$user_id AND produk_id=$id");
        }
    }
    header('Location: keranjang.php');
    exit();
}

// Ambil data produk di keranjang (DB)
$cart_products = [];
$total = 0;
$cart_result = mysqli_query($conn, "SELECT k.*, p.*, k.jumlah as qty, k.keranjang_id, k.user_id as keranjang_user_id, k.produk_id as keranjang_produk_id, k.jumlah as keranjang_jumlah, kat.nama_kategori FROM keranjang k JOIN produk p ON k.produk_id=p.id LEFT JOIN kategori kat ON p.kategori_id=kat.id WHERE k.user_id=$user_id");
while ($row = mysqli_fetch_assoc($cart_result)) {
    $row['subtotal'] = $row['qty'] * $row['harga'];
    $cart_products[] = $row;
    $total += $row['subtotal'];
}
my_closeDB($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
            <h1 class="text-xl font-bold text-blue-600 tracking-tight">Keranjang</h1>
            <ul class="flex space-x-4 text-sm font-medium">
                <li><a href="index.php" class="hover:text-blue-600 transition-colors">Kembali</a></li>
            </ul>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-2xl font-bold mb-6">Keranjang Belanja</h2>
        <?php if (empty($cart_products)): ?>
            <div class="text-center text-gray-500">Keranjang kosong.</div>
        <?php else: ?>
        <form method="post">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">No</th>
                    <th class="py-2 px-4 border-b">Nama Produk</th>
                    <th class="py-2 px-4 border-b">Harga</th>
                    <th class="py-2 px-4 border-b">Jumlah</th>
                    <th class="py-2 px-4 border-b">Subtotal</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($cart_products as $row): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= $no++ ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td class="py-2 px-4 border-b">Rp <?= number_format($row['harga'],0,',','.') ?></td>
                    <td class="py-2 px-4 border-b">
                        <input type="number" name="qty[<?= $row['id'] ?>]" value="<?= $row['qty'] ?>" min="1" max="<?= $row['stok'] ?>" class="w-16 px-2 py-1 border rounded">
                    </td>
                    <td class="py-2 px-4 border-b">Rp <?= number_format($row['subtotal'],0,',','.') ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="keranjang.php?remove=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Hapus produk ini dari keranjang?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="py-2 px-4 text-right font-bold">Total</td>
                    <td colspan="2" class="py-2 px-4 font-bold">Rp <?= number_format($total,0,',','.') ?></td>
                </tr>
            </tfoot>
        </table>
        <div class="mt-4 flex justify-between">
            <a href="checkout.php" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Checkout</a>
        </div>
        </form>
        <?php endif; ?>
    </section>

    <footer class="bg-white shadow-inner py-6 mt-10">
        <div class="text-center text-sm text-gray-500">
            &copy; 2025 Manajemen Produk. All rights reserved.
        </div>
    </footer>

</body>
</html>