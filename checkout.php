<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id'];
$conn = my_connectDB();

// Ambil data keranjang user
$cart_result = mysqli_query($conn, "SELECT k.*, p.*, k.jumlah as qty, p.harga as harga_produk FROM keranjang k JOIN produk p ON k.produk_id=p.id WHERE k.user_id=$user_id");
$cart_products = [];
$total = 0;
while ($row = mysqli_fetch_assoc($cart_result)) {
    $row['subtotal'] = $row['qty'] * $row['harga_produk'];
    $cart_products[] = $row;
    $total += $row['subtotal'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($cart_products)) {
    // 1. Insert ke tabel transaksi
    $tanggal = date('Y-m-d H:i:s');
    $status = 'diproses';
    mysqli_query($conn, "INSERT INTO transaksi (user_id, tanggal, total, status) VALUES ($user_id, '$tanggal', $total, '$status')");
    $transaksi_id = mysqli_insert_id($conn);

    // 2. Insert ke transaksi_detail & update stok produk
    foreach ($cart_products as $item) {
        $produk_id = $item['produk_id'];
        $jumlah = $item['qty'];
        $total_harga_beli = $item['subtotal'];
        mysqli_query($conn, "INSERT INTO transaksi_detail (transaksi_id, produk_id, jumlah, total_harga_beli) VALUES ($transaksi_id, $produk_id, $jumlah, $total_harga_beli)");
        // Update stok produk
        mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE id = $produk_id AND stok >= $jumlah");
    }

    // 3. Hapus keranjang user
    mysqli_query($conn, "DELETE FROM keranjang WHERE user_id=$user_id");

    // 4. Redirect ke halaman sukses
    header("Location: checkout.php?sukses=1&id=$transaksi_id");
    exit();
}

my_closeDB($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - StepIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="max-w-2xl mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6 text-center">Checkout</h2>
        <?php if (isset($_GET['sukses']) && isset($_GET['id'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 text-center">
                <b>Checkout Berhasil!</b><br>
                Nomor Transaksi: <span class="font-mono text-blue-700"><?=$_GET['id']?></span><br>
                Terima kasih, pesanan Anda telah diterima.<br>
                <a href="index.php" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kembali ke Beranda</a>
            </div>
        <?php elseif (empty($cart_products)): ?>
            <div class="text-center text-gray-500">Keranjang kosong. <a href="produk.php" class="text-blue-600 underline">Belanja sekarang</a></div>
        <?php else: ?>
            <form method="post">
                <table class="min-w-full bg-white border border-gray-200 mb-6">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Produk</th>
                            <th class="py-2 px-4 border-b">Jumlah</th>
                            <th class="py-2 px-4 border-b">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_products as $item): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($item['nama_produk']) ?></td>
                            <td class="py-2 px-4 border-b"><?= $item['qty'] ?></td>
                            <td class="py-2 px-4 border-b">Rp <?= number_format($item['subtotal'],0,',','.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="py-2 px-4 text-right font-bold">Total</td>
                            <td class="py-2 px-4 font-bold">Rp <?= number_format($total,0,',','.') ?></td>
                        </tr>
                    </tfoot>
                </table>
                <button type="submit" class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700 font-bold">Konfirmasi & Checkout</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
