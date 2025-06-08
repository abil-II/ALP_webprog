<?php
session_start();

include 'connection.php';
$conn = my_connectDB();
$result = mysqli_query($conn, "SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.kategori_id = k.id");
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
            <h1 class="text-xl font-bold text-blue-600 tracking-tight">Manajemen Produk</h1>
            <ul class="flex space-x-4 text-sm font-medium">
                <li><a href="index.php" class="hover:text-blue-600 transition-colors">Kembali</a></li>
                <li><a href="create.php" class="hover:text-blue-600 transition-colors">Tambah Produk</a></li>
            </ul>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-2xl font-bold mb-6">Daftar Produk</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">No</th>
                    <th class="py-2 px-4 border-b">Nama Produk</th>
                    <th class="py-2 px-4 border-b">Harga</th>
                    <th class="py-2 px-4 border-b">Stok</th>
                    <th class="py-2 px-4 border-b">Kategori</th>
                    <th class="py-2 px-4 border-b">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($row = mysqli_fetch_assoc($result)): 
                    // Hanya tampilkan produk milik admin yang login, atau tampilkan semua jika ingin admin bisa lihat semua
                    if (isset($_SESSION['id']) && isset($row['admin_id']) && $_SESSION['id'] != $row['admin_id']) {
                        continue; // skip baris ini jika bukan milik admin yang login
                    }
                ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= $no++ ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['nama_produk']) ?></td>
                    <td class="py-2 px-4 border-b">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['stok']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="update.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a> |
                        <a href="update.php?id=<?= $row['id'] ?>&delete=1" class="text-red-600 hover:underline" onclick="return confirm('Hapus produk ini?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; my_closeDB($conn); ?>
            </tbody>
        </table>
    </section>

    <footer class="bg-white shadow-inner py-6 mt-10">
        <div class="text-center text-sm text-gray-500">
            &copy; 2025 Manajemen Produk. All rights reserved.
        </div>
    </footer>

</body>
</html>