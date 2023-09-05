<?php
    require_once "connection.php";

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $q = "SELECT * 
        FROM `profiles` 
        LEFT JOIN `users` ON `profiles`.`id_user` = `users`.`id`
        WHERE `profiles`.`id_user` = $id";
        $res = $conn->query($q);
        if ($res->num_rows > 0) {
            $profileExists = true;
            $profile = $res->fetch_assoc();

            $genderClass = '';
            if ($profile['gender'] == 'm') {
                $genderClass = 'text-primary';
            } elseif ($profile['gender'] == 'f') {
                $genderClass = 'text-pink';
            } else {
                $genderClass = 'text-secondary';
            }
        } else {
            $profileExists = false;
        }
    }?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Your Profile</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include "header.php"; ?>
    <?php if ($profileExists): ?>
        <div class = "container profile">
            <h1 class="msg">Welcome, <?php echo $profile['first_name'] . ' ' . $profile['last_name'];?>!</h1>
            <table class = "table profile-table">
                <?php echo '<tr class = '. $genderClass. '>'?>
                    <td>First Name: </td>
                    <td><?php echo $profile['first_name'] ?></td>
                </tr>
                <?php echo '<tr class = '. $genderClass. '>'?>
                    <td>Last Name: </td>
                    <td><?php echo $profile['last_name'] ?></td>
                </tr>
                <?php echo '<tr class = '. $genderClass. '>'?>
                    <td>Username: </td>
                    <td><?php echo $profile['username'] ?></td>
                </tr>
                <?php echo '<tr class = '. $genderClass. '>'?>
                    <td>Date of birth: </td>
                    <td><?php echo $profile['dob'] ?></td>
                </tr>
                <?php echo '<tr class = '. $genderClass. '>'?>
                    <td>Gender: </td>
                    <td><?php echo $profile['gender'] ?></td>
                </tr>
                <?php echo '<tr class = '. $genderClass. '>'?>
                    <td>About me: </td>
                    <td><?php echo $profile['bio'] ?></td>
                </tr>
            </table>
            <p><a href="followers.php" class="btn btn-dark btn-lg">Go to Followers Page</a></p>
        </div>
        <?php else : ?>
        <p class="error_msg">The profile does not exist.</p>
        <div class="text-center">
            <a href="followers.php" class="btn btn-dark btn-lg ">Go to Followers Page</a>
        </div>
        <?php endif; ?>
    <?php include "footer.php"; ?>
</body>
</html>

