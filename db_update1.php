<?php
    require_once "connection.php";

    $sql = "ALTER TABLE `profiles` ADD `bio` TEXT;";

    if ($conn->query($sql) == TRUE) {
        echo "New column added";
    } else {
        echo "Error adding column: " . $conn->error;
    }
    
?>