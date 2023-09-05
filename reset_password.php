<?php
    session_start();

    if (!isset($_SESSION['id'])) 
    {
        header("Location: login.php");
    }

    require_once "connection.php";
    require_once "validation.php";

    $newPasswordError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $oldPassword = $conn->real_escape_string($_POST["old_password"]);
        $newPassword = $conn->real_escape_string($_POST["new_password"]);
        $confirmPassword = $conn->real_escape_string($_POST["confirm_password"]);

        $newPasswordError = passwordValidation($newPassword);

        if ($newPasswordError == "") {
            if ($newPassword !== $confirmPassword) {
                $error = "Passwords do not match.";
            } else {
                $username = $_SESSION['username'];
                $q = "SELECT `password` FROM `users` WHERE `username` = '$username'";
                $resultY = $conn->query($q);
                if ($resultY -> num_rows === 1) {
                    $row = $resultY->fetch_assoc();
                    $currentPassword = $row['password'];

                    if (password_verify($oldPassword, $currentPassword)) {
                        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

                        $sql = "UPDATE `users` SET `password` = '$hash' WHERE `username` = '$username'";
                        if ($conn->query($sql) == TRUE) {
                            $success = "Password has been successfully updated.";
                        } else 
                        {
                            $error = "Error updating password: " . $conn->error;
                        }
                    } else {
                        $error = "Old password is incorrect.";
                    }
                } else {
                    $error = "User not found.";
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
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "header.php" ?>
    <h1 class="mb-4 text-center heading">Reset Your Password</h1>
            <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
            <?php } elseif (isset($success)) { ?>
                <p style="color: green;"><?php echo $success; ?></p>
            <?php } ?>
    <form action="#" method="post" class="custom-form">
        <div class="form-group">
            <label for="old_password">Enter you old password:</label>
            <input type="password" name="old_password" id="old_password" class="form-control">
        </div>
        <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" class="form-control">
            <span class="error"><?php echo $newPasswordError ?></span>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control">
            <span class="error"><?php echo $newPasswordError ?></span>
        </div>
        <div class="form-group">
            <input type="submit" value="Reset Password" class="btn btn-secondary">
            <a href="index.php" class="btn btn-dark">Return to Home Page</a>
        </div>
    </form>
    <?php include "footer.php";?>
</body>
</html>