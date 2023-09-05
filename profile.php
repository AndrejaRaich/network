<?php
    // Ne dozvoljavamo pristup ovoj stranici logovanim korisnicima
    session_start();
    if (!isset($_SESSION["id"]))     // Znaci da je logovan
    {
        header("Location: index.php");  
    }

    $id = $_SESSION["id"];
    $first_name = $last_name = $dob = $gender = $image = $bio = "";
    $firstNameError = $lastNameError = $dobError = $genderError = $profilePictureError = "";

    $successMessage = "";
    $errorMessage = "";

    require_once "connection.php";
    require_once "validation.php";

    $profileRow = profileExists($id, $conn);
    //  $profileRow = false, ako profil ne postoji
    //  $profileRow = asocijativni niz, ako profil postoji
    if ($profileRow !== false) {
        // var_dump($profileRow);
        $first_name = $profileRow["first_name"];
        $last_name = $profileRow["last_name"];
        $gender = $profileRow["gender"];
        $dob = $profileRow["dob"];
        $image = $profileRow["image"];
        $bio = $profileRow["bio"];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        $first_name = $conn->real_escape_string($_POST["first_name"]);
        $last_name = $conn->real_escape_string($_POST["last_name"]);
        $gender = $conn->real_escape_string($_POST["gender"]);
        $dob = $conn->real_escape_string($_POST["dob"]);
        $image = $conn->real_escape_string($_POST["image"]);
        $bio = $conn->real_escape_string($_POST["bio"]);

        $orig_file = $_FILES["profile_picture"]["tmp_name"];
        $ext = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
        $targer_dir = 'profile_pictures/';
        $image = "$targer_dir$id.$ext";
        move_uploaded_file($orig_file, $image);

        // Vrsimo validaciju polja
        $firstNameError = nameValidation($first_name);
        $lastNameError = nameValidation($last_name);
        $genderError = genderValidation($gender);
        $dobError = dobValidation($dob);
        $profilePictureError = imageValidation($image);

        // Ako je sve u redu, ubacujemo novi red u tabelu `profiles`
        if ($firstNameError == '' && $lastNameError == '' && $genderError == '' && $dobError == '') {
            $q = "";
            if ($profileRow === false) 
            {
                if (file_exists($image)) {
                    $q = "INSERT INTO `profiles`(`first_name`, `last_name`, `gender`, `dob`, `id_user`, `image`, `bio`)
                    VALUE
                    ('$first_name', '$last_name', '$gender', '$dob', $id, '$image', '$bio')";
                }
                else {
                    $q = "INSERT INTO `profiles`(`first_name`, `last_name`, `gender`, `dob`, `id_user`, `image`, `bio`)
                    VALUE
                    ('$first_name', '$last_name', '$gender', '$dob', $id, NULL, '$bio')";
                }
            }
            else
            {
                $q = "UPDATE `profiles`
                    SET `first_name` = '$first_name',
                    `last_name` = '$last_name',
                    `gender` = '$gender',
                    `dob` = '$dob',
                    `image` = '$image',
                    `bio` = '$bio'
                    WHERE `id_user` = $id
                    ";
            }
                if ($conn->query($q)) 
                {
                    // Uspesno kreitan ili editovan profil
                    if ($profileRow !== false) {
                        $successMessage = "You have edited your profile";
                    } else {
                        $successMessage = "You have created your profile";
                    }
                } else 
                {
                    // Desila se greska u upitu
                    $errorMessage = "Error creating profile: " . $conn->error;
                }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "header.php" ?>
    <h1 class="mb-4 text-center heading">
    Please fill the profile details
    </h1>
    <div class="form-container">
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="first_name" class="form-label">First name:</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo $first_name; ?>" class="form-control">
                <span class="error"><?php echo $firstNameError ?></span>
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last name:</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo $last_name; ?>" class="form-control">
                <span class="error"><?php echo $lastNameError ?></span>
            </div>
            <div class="mb-3">
                <label class="form-label">Gender:</label>
                <div class="form-check">
                    <input type="radio" name="gender" id="m" value="m" <?php if ($gender == "m")  { echo "checked"; } ?> class="form-check-input">
                    <label for="m" class="form-check-label">Male</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="gender" id="f" value="f" <?php if ($gender == "f")  { echo "checked"; } ?> class="form-check-input">
                    <label for="f" class="form-check-label">Female</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="gender" id="o" value="o" <?php if ($gender == "o" || $gender == "")  { echo "checked"; } ?> class="form-check-input">
                    <label for="o" class="form-check-label">Other</label>
                </div>
                <span class="error"><?php echo $genderError?></span>
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of birth:</label>
                <input type="date" name="dob" id="dob" value="<?php echo $dob?>" class="form-control">
                <span class="error"><?php echo $dobError ?></span>
            </div>
            <div class="mb-3">
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="bio">Biography:</label>
                <textarea name="bio" id="bio"><?php echo $bio ?></textarea>
            </div>
            <br>
            <p>
                <?php 
                    $poruka;
                    if ($profileRow === false) {
                        $poruka = "Create profile";
                    } else {
                        $poruka = "Edit profile";
                    }
                ?>
                <input type="submit" value="<?php echo $poruka; ?>" class="btn btn-primary">
            </p>
        </form>
    </div>
    <div class="success">
        <?php echo $successMessage; ?>
    </div>
    <div class="error">
        <?php echo $errorMessage; ?>
    </div>
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-dark btn-lg">Return to Home Page</a>
    </div>
    <?php include "footer.php";?>
</body>
</html>