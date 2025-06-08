<?php
include 'connection.php';

// Fetch products from the database
$conn = my_connectDB();
$query = "SELECT * FROM produk"; // Assuming you have a 'products' table
$result = mysqli_query($conn, $query);
$products = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
}
my_closeDB($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - StepIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
            <h1 class="text-xl font-bold text-blue-600 tracking-tight">StepIn</h1>
            <ul class="flex space-x-4 text-sm font-medium">
                <li><a href="index.php" class="hover:text-blue-600 transition-colors">Beranda</a></li>
                <li><a href="product.php" class="hover:text-blue-600 transition-colors">Produk</a></li>
                <li><a href="kategori.php" class="hover:text-blue-600 transition-colors">Kategori</a></li>
                <li><a href="register.php" class="hover:text-blue-600 transition-colors">Sign Up</a></li>
                <li><a href="login.php" class="hover:text-blue-600 transition-colors">Login</a></li>
            </ul>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-2xl font-bold mb-6">Daftar Produk</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <img src="<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" class="w-full h-56 object-cover">
                    <div class="p-4">
                        <h4 class="text-lg font-semibold"><?= htmlspecialchars($product['nama_produk']) ?></h4>
                        <p class="text-gray-600 mt-1 mb-2"><?= htmlspecialchars($product['harga']) ?></p>
                        <a href="product_detail.php?id=<?= $product['id'] ?>" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <footer class="bg-white shadow-inner py-6 mt-10">
        <div class="text-center text-sm text-gray-500">
            &copy; 2025 StepIn. All rights reserved.
        </div>
    </footer>

</body>

</html>