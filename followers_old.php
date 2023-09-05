<?php
    session_start();
    if(empty($_SESSION["id"]))
    {
        header("Location: index.php");
    }
    $id = $_SESSION["id"];
    require_once "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members of Social Network</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "header.php" ?>
    <h1 class="mb-4 text-center heading">See other members from our site</h1>
    <?php
        $q = "SELECT `u`.`id`, `u`.`username`,
                CONCAT(`p`.`first_name`, ' ', `p`.`last_name`) AS `full_name`,
                `gender`, `image`
                FROM `users` AS `u`
                LEFT JOIN `profiles` AS `p`
                ON `u`.`id` = `p`.`id_user`
                WHERE `u`.`id` != $id
                ORDER BY `full_name`;
            ";
        $result = $conn->query($q);
        if($result->num_rows == 0)
        {
    ?>
        <div class="error">No other users in database :-( </div>
    <?php
        }
        else
        {
            echo "<table class='table'>";
            echo "<thead class='table-dark'><tr><th>Name</th><th>Action</th><th>Profile Picture</th></tr></thead>";
            echo "<tbody>";
            while($row = $result->fetch_assoc())
            {
                echo "<tr><td>";
                if($row["full_name"] !== NULL)
                {
                    echo $row["full_name"];
                }
                else
                {
                    echo $row["username"];
                }
                echo "</td><td>";
                // Ovde cemo linkove za pracenje korisnika
                $friendId = $row["id"];
                echo "<a href='follow.php?friend_id=$friendId' class='btn btn-secondary follow'>Follow</a>";
                echo "<a href='unfollow.php?friend_id=$friendId' class='btn btn-light follow'>Unfollow</a>";
                echo "</td><td>";
                if ($row["image"] !== null) {
                    echo "<img src=" .$row["image"]. " alt='' class = 'gender img'>";
                } else {
                        if ($row["gender"] == 'm') 
                    {
                        echo "<img src='uploads\\male.png' alt='' class = 'gender img'>";
                    } elseif ($row["gender"] == 'f') {
                        echo "<img src='uploads\\female.png' alt='' class = 'gender img'>";
                    } else {
                        echo "<img src='uploads\\other.png' alt='' class = 'gender img'>";
                    }
                }
                echo "</td></tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
    ?>
        <div class="text-center mt-4 return">
            <a href="index.php" class="btn btn-dark btn-lg">Return to Home Page</a>
        </div>
</body>
</html>