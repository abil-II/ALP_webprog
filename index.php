<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header('Location: login.php');
  exit();
}
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
  <nav class="bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
      <!-- Apply gradient to the StepIn text -->
      <h1 class="text-xl font-bold tracking-tight bg-gradient-to-r from-blue-500 to-pink-500 text-transparent bg-clip-text">
        StepIn
      </h1>
      <form class="flex justify-center mx-8" onsubmit="event.preventDefault(); searchProducts();">
        <input id="searchInput" type="text" placeholder="Cari sepatu..."
          class="w-64 px-4 py-2 rounded-l-md border border-black focus:ring-2 focus:ring-blue-400 text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200" />
        <button type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition">Cari</button>
      </form>
      <ul class="flex space-x-4 text-sm font-medium" id="mainMenu">
        <li><a href="index.html" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Beranda</a></li>
        <li><a href="produk.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Produk</a></li>
        <li><a href="kategori.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Kategori</a></li>
        <!-- <li><a href="register.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Sign Up</a></li>
        <li><a href="login.php" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Login</a></li> -->
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

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="sneakers">
        <img src="img/example-shoe.jpg" alt="Sepatu 1" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold dark:text-gray-100">Sepatu Sneakers Putih</h4>
          <p class="text-gray-600 mt-1 mb-2 dark:text-gray-400">Rp 250.000</p>
          <a href="#"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="boots">
        <img src="img/example-shoe2.jpg" alt="Sepatu 2" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold dark:text-gray-100">Sepatu Boots Kulit</h4>
          <p class="text-gray-600 mt-1 mb-2 dark:text-gray-400">Rp 500.000</p>
          <a href="#"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="running">
        <img src="img/example-shoe3.jpg" alt="Sepatu 3" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold dark:text-gray-100">Sepatu Lari Ringan</h4>
          <p class="text-gray-600 mt-1 mb-2 dark:text-gray-400">Rp 300.000</p>
          <a href="#"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="casual">
        <img src="img/example-shoe4.jpg" alt="Sepatu 4" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold dark:text-gray-100">Sepatu Casual Hitam</h4>
          <p class="text-gray-600 mt-1 mb-2 dark:text-gray-400">Rp 275.000</p>
          <a href="#"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="slipon">
        <img src="img/example-shoe5.jpg" alt="Sepatu 5" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold dark:text-gray-100">Sepatu Slip On</h4>
          <p class="text-gray-600 mt-1 mb-2 dark:text-gray-400">Rp 220.000</p>
          <a href="#"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition dark:bg-gray-800" data-category="anak">
        <img src="img/example-shoe6.jpg" alt="Sepatu 6" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold dark:text-gray-100">Sepatu Anak Sekolah</h4>
          <p class="text-gray-600 mt-1 mb-2 dark:text-gray-400">Rp 180.000</p>
          <a href="#"
            class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

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