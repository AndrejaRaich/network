<?php
    // Onaj ko salje zahtev za otpracivanjem: Logovan korisnik
    // Onaj kome je upucen zahtev za otpracivanjem: Dohvatamo iz URL-a
    session_start();
    if(empty($_SESSION["id"]))
    {
        header("Location: index.php");
    }
    $id = $_SESSION["id"];
    require_once "connection.php";

    if(empty($_GET["friend_id"]))
    {
        header("Location: index.php");
    }
    $friendId = $conn->real_escape_string($_GET["friend_id"]);

    $q = "DELETE FROM `followers`
            WHERE `id_sender` = $id
            AND `id_reciever` = $friendId";

    $conn->query($q);
    
    header("Location: followers.php");
?>