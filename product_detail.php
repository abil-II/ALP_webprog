<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/ALP_WEBPROG/product_detail.php -->
<?php
include 'connection.php';

// Check if the product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Product ID is missing.');
}

$conn = my_connectDB();
$product_id = intval($_GET['id']);

// Fetch product details from the database
$query = "SELECT p.*, k.nama_kategori 
          FROM produk p 
          LEFT JOIN kategori k ON p.kategori_id = k.id 
          WHERE p.id = $product_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    die('Product not found.');
}

$product = mysqli_fetch_assoc($result);
my_closeDB($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['nama_produk']) ?> - StepIn</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200">

    <nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
            <h1 class="text-xl font-bold text-blue-600 tracking-tight">StepIn</h1>
            <ul class="flex space-x-4 text-sm font-medium">
                <li><a href="index.php" class="hover:text-blue-600 transition-colors">Beranda</a></li>
                <li><a href="produk.php" class="hover:text-blue-600 transition-colors">Produk</a></li>
                <li><a href="kategori.php" class="hover:text-blue-600 transition-colors">Kategori</a></li>
                <li><a href="register.php" class="hover:text-blue-600 transition-colors">Sign Up</a></li>
                <li><a href="login.php" class="hover:text-blue-600 transition-colors">Login</a></li>
            </ul>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-4 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Product Image -->
            <div>
                <img src="<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" class="w-full h-auto rounded-lg shadow-md">
            </div>

            <!-- Product Details -->
            <div>
                <h2 class="text-3xl font-bold mb-4"><?= htmlspecialchars($product['nama_produk']) ?></h2>
                <p class="text-gray-600 dark:text-gray-400 mb-4"><?= htmlspecialchars($product['deskripsi']) ?></p>
                <p class="text-lg font-semibold text-blue-600 mb-4">Rp <?= number_format($product['harga'], 0, ',', '.') ?></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kategori: <?= htmlspecialchars($product['nama_kategori']) ?></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Stok: <?= htmlspecialchars($product['stok']) ?></p>

                <!-- Add to Cart Button -->
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Tambahkan ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </section>

    <footer class="bg-white shadow-inner py-6 mt-10 dark:bg-gray-800">
        <div class="text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; 2025 StepIn. All rights reserved.
        </div>
    </footer>

</body>
</html>