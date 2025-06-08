<?php
<?php
include 'connection.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['message']); // Using 'message' field as 'role' for demo

    if ($nama && $email && $role) {
        $conn = my_connectDB();
        $stmt = mysqli_prepare($conn, "INSERT INTO users (nama, email, role) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $role);
        mysqli_stmt_execute($stmt);
        my_closeDB($conn);
        header("Location: read.php");
        exit();
    } else {
        $error = "All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Entry - StepIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-700">Create New Entry</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-red-600 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="name">Name</label>
                <input type="text" name="name" id="name" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-4">
                <label class="block mb-1 font-medium" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="mb-6">
                <label class="block mb-1 font-medium" for="message">Role</label>
                <input type="text" name="message" id="message" required
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <button type="submit"
                class="w-full py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Create Entry</button>
        </form>
    </div>

</body>
</html>