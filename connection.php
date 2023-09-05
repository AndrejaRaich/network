<?php
    $server = "localhost";
    $database = "network";
    $username = "andreja";
    $password = "andreja123";  // promeniti username i password za domaci

    $conn = new mysqli($server, $username, $password, $database);
    if ($conn->connect_error) {
        header("Location: error.php?m=" . $conn->connect_error);
        // die("Neuspela konekcija: " . $conn->connect_error);
    }
    $conn->set_charset("utf8");

?>