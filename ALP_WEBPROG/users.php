<?php
include 'connection.php';
$result = mysqli_query($conn, "SELECT * FROM users");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar User</title>
</head>
<body>
    <?php include 'menu.php'; ?>
    <h2>Daftar User</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['role'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>