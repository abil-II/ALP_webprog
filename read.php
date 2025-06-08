<?php
<?php
include 'connection.php';
$conn = my_connectDB();
$result = mysqli_query($conn, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read Entries</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 text-gray-800">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 h-14 flex items-center justify-between">
            <h1 class="text-xl font-bold text-blue-600 tracking-tight">Entry Management</h1>
            <ul class="flex space-x-4 text-sm font-medium">
                <li><a href="create.php" class="hover:text-blue-600 transition-colors">Create</a></li>
            </ul>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-4 py-10">
        <h2 class="text-2xl font-bold mb-6">List of Entries</h2>
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">Role</th>
                    <th class="py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['id']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['email']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($row['role']) ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="update.php?id=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Edit</a> |
                        <a href="delete.php?id=<?= $row['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Delete this entry?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; my_closeDB($conn); ?>
            </tbody>
        </table>
    </section>

    <footer class="bg-white shadow-inner py-6 mt-10">
        <div class="text-center text-sm text-gray-500">
            &copy; 2025 Entry Management. All rights reserved.
        </div>
    </footer>

</body>
</html>