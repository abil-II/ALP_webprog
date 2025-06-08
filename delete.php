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
        <p class="mb-4 text-center">Are you sure you want to delete this entry?</p>
        <form method="POST" action="delete_action.php">
            <input type="hidden" name="entry_id" value="<?= htmlspecialchars($entry_id) ?>">
            <div class="flex justify-between">
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">Delete</button>
                <a href="read.php" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>