<?php

function my_connectDB() {
    $host = 'localhost';
    $user = 'root';
    $pwd = '';
    $db = 'mywebsite';

    $conn = mysqli_connect($host, $user, $pwd, $db) or die("Connection failed: " . mysqli_connect_error());
    return $conn;
}

function my_closeDB($conn) {
    mysqli_close($conn);
}

function readGuestBook() {
    $conn = my_connectDB();
    $sql_query = "SELECT * FROM guestbook";
    $result = mysqli_query($conn, $sql_query) or die("Query failed: " . mysqli_error($conn));

    if ($result->num_rows > 0) {
        $entries = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $entries[] = $row;
        }
        my_closeDB($conn);
        return $entries;
    } else {
        my_closeDB($conn);
        return [];
    }
}