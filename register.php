<?php
    // Ne dozvoljavamo pristup ovoj stranici logovanim korisnicima
    session_start();
    if (isset($_SESSION["id"]))     // Znaci da je logovan
    {
        header("Location: index.php");  
    }

    require_once "connection.php";
    require_once "validation.php";

    $usernameError = "";
    $passwordError = "";
    $retypeError = "";
    $username = "";
    $password = "";
    $retype = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        // forma je poslata, treba pokupiti vrednosti iz polja

        $username = $conn->real_escape_string($_POST["username"]);
        $password = $conn->real_escape_string($_POST["password"]);
        $retype = $conn->real_escape_string($_POST["retype"]);

        // 1) Izvrsiti validaciju za $username
        $usernameError = usernameValidation($username, $conn);
        // 2) Izvrsiti validaciju za $password
        $passwordError = passwordValidation($password);
        // 3) Izvrsiti validaciju za $retype
        $retypeError = passwordValidation($retype);
        if ($password !== $retype) {
            $retypeError = "You must enter two same passwords";
        }

        // 4.1) Ako su sva polja validna, onda treba dodati novog korisnika
        if ($usernameError == "" && $passwordError == "" && $retypeError == "") 
        {
            // lozinka treba prvo da se sifruje
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $q = "INSERT INTO `users`(`username`, `password`)
            VALUE
            ('$username','$hash')";

            if ($conn->query($q)) {
                // Kreirali smo novog korisnika, vodi ga na stranicu za logovanje
                header("Location: index.php?p=ok");
            } else
            {
                header("Location: error.php?" . http_build_query(['m' => "Error creating user"]));
            }
        }

        // 4.2) Ako postoji neko polje koje nije validno, ne raditi upit
        // nego vratiti korisnika na stranicu i prikazati poruke
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register new user</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "header.php" ?>
    <h1 class="mb-4 text-center heading">
    Register to our site
    </h1>
    <form action="register.php" method="POST" class="custom-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo $username ?>">
            <span class="error">* <?php echo $usernameError ?></span>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control" value="">
            <span class="error">* <?php echo $passwordError ?></span>
        </div>
        <div class="form-group">
            <label for="retype">Retype password:</label>
            <input type="password" name="retype" id="retype" class="form-control" value="">
            <span class="error">* <?php echo $retypeError ?></span>
        </div>
        <div class="form-group">
            <input type="submit" value="Register me!" class="btn btn-primary">
            <a href="index.php" class="btn btn-dark">Return to Home Page</a>
        </div>
    </form>
    <?php include "footer.php";?>
</body>
</html>