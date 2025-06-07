<!DOCTYPE html>
<html lang="en">

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
  </script>
</head>

<body class="bg-gray-50 text-gray-800">

  <nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
      <h1 class="text-xl font-bold text-blue-600 tracking-tight">StepIn</h1>
      <ul class="flex space-x-4 text-sm font-medium">
        <li><a href="index.html" class="hover:text-blue-600 transition-colors">Beranda</a></li>
        <li><a href="produk.php" class="hover:text-blue-600 transition-colors">Produk</a></li>
        <li><a href="kategori.php" class="hover:text-blue-600 transition-colors">Kategori</a></li>
        <li><a href="users.php" class="hover:text-blue-600 transition-colors">User</a></li>
      </ul>
    </div>
  </nav>

  <section class="relative w-full h-72 sm:h-96 flex items-center justify-center overflow-hidden"
    style="background-image: url('shoe.avif'); background-size: cover; background-position: center;">
    <div class="absolute inset-0 bg-blue-900 bg-opacity-30"></div>
    <div class="relative z-10 text-center text-white">
      <h2 class="text-4xl font-bold mb-4 drop-shadow">Selamat Datang di StepIn!</h2>
      <p class="text-lg drop-shadow mb-6">Temukan sepatu terbaik untuk setiap langkah Anda</p>
      <form class="flex justify-center" onsubmit="event.preventDefault(); searchProducts();">
        <input id="searchInput" type="text" placeholder="Cari sepatu..."
          class="w-64 px-4 py-2 rounded-l-md border-0 focus:ring-2 focus:ring-blue-400 text-gray-800" />
        <button type="submit"
          class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition">Cari</button>
      </form>
    </div>
  </section>

  <section id="products" class="max-w-7xl mx-auto px-4 py-10">
    <h3 class="text-2xl font-bold mb-6">Produk Terbaru</h3>
    <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="img/example-shoe.jpg" alt="Sepatu 1" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold">Sepatu Sneakers Putih</h4>
          <p class="text-gray-600 mt-1 mb-2">Rp 250.000</p>
          <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="img/example-shoe2.jpg" alt="Sepatu 2" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold">Sepatu Boots Kulit</h4>
          <p class="text-gray-600 mt-1 mb-2">Rp 500.000</p>
          <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="img/example-shoe3.jpg" alt="Sepatu 3" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold">Sepatu Lari Ringan</h4>
          <p class="text-gray-600 mt-1 mb-2">Rp 300.000</p>
          <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="img/example-shoe4.jpg" alt="Sepatu 4" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold">Sepatu Casual Hitam</h4>
          <p class="text-gray-600 mt-1 mb-2">Rp 275.000</p>
          <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="img/example-shoe5.jpg" alt="Sepatu 5" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold">Sepatu Slip On</h4>
          <p class="text-gray-600 mt-1 mb-2">Rp 220.000</p>
          <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
        <img src="img/example-shoe6.jpg" alt="Sepatu 6" class="w-full h-56 object-cover" />
        <div class="p-4">
          <h4 class="text-lg font-semibold">Sepatu Anak Sekolah</h4>
          <p class="text-gray-600 mt-1 mb-2">Rp 180.000</p>
          <a href="#" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Detail</a>
        </div>
      </div>

    </div>
  </section>

  <footer class="bg-white shadow-inner py-6 mt-10">
    <div class="text-center text-sm text-gray-500">
      &copy; 2025 StepIn. All rights reserved.
    </div>
  </footer>

</body>

</html>