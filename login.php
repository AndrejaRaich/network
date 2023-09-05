<?php
    session_start(); // Ova f-ja treba na pocetku (kao prva) da se pozove
    if (isset($_SESSION["id"]))     // Znaci da je logovan
    {
        header("Location: index.php");  
    }
    // Cim treba nekako da koristimo sesiju, mora ova f-ja da se pozove
    require_once "connection.php";

    $usernameError = "*";
    $passwordError = "*";
    $username = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Korisnik je poslao username i password i pokusava logovanje
        $username = $conn->real_escape_string($_POST["username"]);
        $password = $conn->real_escape_string($_POST["password"]);

        // Vrsimo razlicite validacije
        if (empty($username)) {
            $usernameError = "Username cannot be blank!";
        }
        if (empty($password)) {
            $passwordError = "Password cannot be blank!";
        }
        if ($usernameError == "*" && $passwordError == "*") {
            // Ovde mozemo da pokusamo da logujemo korisnika
            // (ako se svi kredencijali za logovanje podudaraju)
            $q = "SELECT * FROM `users` WHERE `username` = '$username'";
            $result = $conn->query($q);
            if ($result->num_rows == 0) {
                $usernameError = "This username doesn't exist!";
            }
            else {
                // Postoji takav korisnik, proveriti lozinke
                $row = $result->fetch_assoc();
                $dbPassword = $row['password']; // Heshirana vrednost iz baze
                if(!password_verify($password, $dbPassword)){
                    // Poklopio se username, ali lozinka nije dobra
                    $passwordError = "Wrong password, try again!";
                }
                else {
                    // Dobri su i username i password, izvrsi logovanje
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];
                    header("Location: index.php");
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "header.php" ?>
    <h1 class="mb-4 text-center heading">Please login</h1>
    <form action="#" method="post" class="custom-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo $username ?>" class="form-control">
            <span class="error"><?php echo $usernameError ?></span>
        </div>
            <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" class="form-control">
            <span class="error"><?php echo $passwordError ?></span>
        </div>
            <div class="form-group text-center">
            <input type="submit" value="Login" class="btn btn-primary">
        </div>
    </form>
    <?php include "footer.php";?>
</body>
</html>