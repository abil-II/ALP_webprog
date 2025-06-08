<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit();
}
?>

<?php
include 'connection.php';

// Fetch products from the database
$conn = my_connectDB();
$query = "SELECT * FROM produk"; // Fetch all products from the 'produk' table
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
<html lang="en" class="dark"> <!-- Add "dark" class here -->

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jefferson Jap, Muhammad Dzaky Nabil Amin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function searchProducts() {
      const input = document.getElementById('searchInput').value.toLowerCase();
      const cards = document.querySelectorAll('#productGrid > div');
      cards.forEach(card => {
        const title = card.querySelector('h4').textContent.toLowerCase();
        if (title.includes(input)) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    }

    function filterCategory() {
      const select = document.getElementById('categoryFilter');
      const value = select.value;
      const cards = document.querySelectorAll('#productGrid > div');
      cards.forEach(card => {
        if (value === '' || card.getAttribute('data-category') === value) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    }
  </script>
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-200"> <!-- Add dark mode classes -->

  <!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/ALP_WEBPROG/index.php -->
<nav class="bg-white bg-opacity-90 backdrop-blur-md border-b border-gray-200 dark:bg-gray-800 dark:bg-opacity-90 dark:backdrop-blur-md dark:border-gray-700 fixed top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
        <!-- Logo -->
        <h1 class="text-xl font-bold tracking-tight text-white dark:text-white">
            StepIn
        </h1>

        <!-- Search Bar -->
        <form class="flex justify-center mx-8" onsubmit="event.preventDefault(); searchProducts();">
            <input id="searchInput" type="text" placeholder="Cari sepatu..."
                class="w-64 px-4 py-2 rounded-l-md border border-gray-300 focus:ring-2 focus:ring-blue-400 text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition">Cari</button>
        </form>

        <!-- Navigation Links -->
        <ul class="flex space-x-8 text-sm font-medium"> <!-- Increased spacing with space-x-8 -->
            <li><a href="index.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Beranda</a></li>
            <li><a href="produk.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Produk</a></li>
            <li><a href="kategori.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Kategori</a></li>
        </ul>
        <div style="position: relative; min-width: 40px;">
        <button id="toggleLogout" type="button">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
          </svg>
        </button>
        <div id="logoutMenu" style="display:none; position:absolute; top:0%; left:-110%; background:white; border:1px solid #e5e7eb; border-radius:0.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:1000; min-width:100px;">
          <a href="logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</a>
          <a href="akun.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Akun</a>
        </div>
      </div>
    </div>
</nav>

<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/ALP_WEBPROG/index.php -->
<section class="relative w-full h-72 sm:h-96 flex items-center justify-center overflow-hidden"
  style="background-image: url('shoe.avif'); background-size: cover; background-position: center;">
  <!-- Add a semi-transparent overlay and blur effect -->
  <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm"></div>
  <div class="relative z-10 text-center text-white">
    <h2 class="text-4xl font-bold mb-4 drop-shadow">Selamat Datang di StepIn!</h2>
    <p class="text-lg drop-shadow mb-6">Temukan sepatu terbaik untuk setiap langkah Anda</p>
  </div>
</section>

  <section id="products" class="max-w-7xl mx-auto px-4 py-10">
    <h3 class="text-2xl font-bold mb-6 dark:text-gray-100">Produk Terbaru</h3>
    <!-- Category Filter Dropdown -->
    <div class="mb-6 flex items-center">
      <label for="categoryFilter" class="mr-2 font-medium">Filter Kategori:</label>
      <select id="categoryFilter"
        class="px-3 py-2 border rounded focus:ring-2 focus:ring-blue-400" onchange="filterCategory()">
        <option value="">Semua</option>
        <option value="sneakers">Sneakers</option>
        <option value="boots">Boots</option>
        <option value="running">Running</option>
        <option value="casual">Casual</option>
        <option value="slipon">Slip On</option>
        <option value="anak">Anak</option>
      </select>
    </div>
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      <!-- Example Product Card -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="sneakers">
        <img src="img/example-shoe.jpg" alt="Sepatu Sneakers Putih" class="w-full h-56 object-cover" />
        <div class="p-4 flex flex-col items-center justify-center">
          <h4 class="text-lg font-semibold dark:text-gray-100 text-center">Sepatu Sneakers Putih</h4>
          <p class="text-gray-600 mt-1 mb-4 dark:text-gray-400 text-center">Rp 250.000</p>
<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/ALP_WEBPROG/index.php -->
<a href="#"
  class="inline-block w-28 h-10 flex items-center justify-center bg-gradient-to-r from-orange-700 to-orange-400 text-white font-bold rounded-lg hover:from-blue-600 hover:via-green-500 hover:to-orange-600 transition shadow-lg">
  Lihat
</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="boots">
        <img src="img/example-shoe2.jpg" alt="Sepatu Boots Kulit" class="w-full h-56 object-cover" />
        <div class="p-4 flex flex-col items-center justify-center">
          <h4 class="text-lg font-semibold dark:text-gray-100 text-center">Sepatu Boots Kulit</h4>
          <p class="text-gray-600 mt-1 mb-4 dark:text-gray-400 text-center">Rp 500.000</p>
          <a href="#"
  class="inline-block w-28 h-10 flex items-center justify-center bg-gradient-to-r from-orange-700 to-orange-400 text-white font-bold rounded-lg hover:from-blue-600 hover:via-green-500 hover:to-orange-600 transition shadow-lg">
  Lihat
</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="running">
        <img src="img/example-shoe3.jpg" alt="Sepatu Lari Ringan" class="w-full h-56 object-cover" />
        <div class="p-4 flex flex-col items-center justify-center">
          <h4 class="text-lg font-semibold dark:text-gray-100 text-center">Sepatu Lari Ringan</h4>
          <p class="text-gray-600 mt-1 mb-4 dark:text-gray-400 text-center">Rp 300.000</p>
<!-- filepath: /Applications/XAMPP/xamppfiles/htdocs/ALP_WEBPROG/index.php -->
<a href="#"
  class="inline-block w-28 h-10 flex items-center justify-center bg-gradient-to-r from-orange-700 to-orange-400 text-white font-bold rounded-lg hover:from-blue-600 hover:via-green-500 hover:to-orange-600 transition shadow-lg">
  Lihat
</a>
        </div>
      </div>

      <?php
      foreach ($products as $product): ?>
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="<?= htmlspecialchars($product['kategori_id']) ?>">
          <img src="<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama_produk']) ?>" class="w-full h-56 object-cover" />
          <div class="p-4 flex flex-col items-center justify-center">
          <h4 class="text-2xl font-bold mb-4"><?= htmlspecialchars($product['nama_produk']) ?></h4>
          <p class="text-lg font-semibold text-white mb-4">Rp <?= number_format($product['harga'], 0, ',', '.') ?></p>
          <p class="text-gray-600 dark:text-gray-400 mb-4 text-center"><?= htmlspecialchars($product['deskripsi']) ?></p>
            <a href="product_detail.php?id=<?= $product['id'] ?>"
            class="inline-block w-28 h-10 flex items-center justify-center bg-gradient-to-r from-indigo-500 to-blue-500 text-white font-bold rounded-lg hover:from-blue-600 hover:via-green-500 hover:to-orange-600 transition shadow-lg">
              Lihat
            </a>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  </section>

  </div>
</section>

  <!-- Add the description section here -->
  <section id="about" class="bg-gray-100 py-10 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 text-center">
      <h3 class="text-2xl font-bold mb-4 text-blue-600 dark:text-blue-400">Tentang StepIn</h3>
      <p class="text-gray-700 text-lg leading-relaxed dark:text-gray-300">
        StepIn adalah platform terbaik untuk menemukan sepatu yang sesuai dengan gaya dan kebutuhan Anda.
        Kami menyediakan berbagai jenis sepatu, mulai dari sneakers, boots, hingga sepatu anak-anak,
        dengan kualitas terbaik dan harga yang terjangkau. Temukan sepatu impian Anda dan jadikan setiap langkah lebih berarti bersama StepIn!
      </p>
    </div>
  </section>

  <footer class="bg-white shadow-inner py-6 mt-10 dark:bg-gray-800">
    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
      &copy; 2025 StepIn. All rights reserved.
    </div>
  </footer>

  <script>
    document.getElementById('toggleLogout').addEventListener('click', function(event) {
      event.stopPropagation();
      var logoutMenu = document.getElementById('logoutMenu');
      if (logoutMenu.style.display === 'none' || logoutMenu.style.display === '') {
        logoutMenu.style.display = 'block';
      } else {
        logoutMenu.style.display = 'none';
      }
    });
    // Hide logout menu when clicking outside
    document.addEventListener('click', function(event) {
      var logoutMenu = document.getElementById('logoutMenu');
      var toggleBtn = document.getElementById('toggleLogout');
      if (!logoutMenu.contains(event.target) && !toggleBtn.contains(event.target)) {
        logoutMenu.style.display = 'none';
      }
    });
  </script>

</body>

</html>
