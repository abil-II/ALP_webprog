<?php
<?php
include 'connection.php';
$error = '';
$entry_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entry_id = intval($_POST['entry_id']);
    if ($entry_id) {
        $conn = my_connectDB();
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $entry_id);
        mysqli_stmt_execute($stmt);
        my_closeDB($conn);
        header("Location: read.php");
        exit();
    } else {
        $error = "Invalid entry!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Entry - StepIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">Delete Entry</h2>
        <?php if ($error): ?>
            <div class="mb-4 text-red-600 text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <p class="mb-4 text-center">Are you sure you want to delete this entry?</p>
        <form method="POST" action="">
            <input type="hidden" name="entry_id" value="<?= htmlspecialchars($entry_id) ?>">
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Delete</button>
                <a href="read.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>