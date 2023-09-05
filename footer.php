<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/babede9b63.js" crossorigin="anonymous"></script>
</head>
<body>
        <footer class="py-3">
            <div class = "left">
                <?php date_default_timezone_set('Europe/Belgrade'); 
                $date = date('Y-m-d H:i:s'); ?>
                <?php echo $date; ?>
            </div>
            <div class = "right">
                <i class="icon fa-brands fa-twitter"></i>
                <i class="icon fa-brands fa-facebook"></i>
                <i class="icon fa-brands fa-instagram"></i>
                <i class="icon fa-solid fa-envelope"></i>
            </div>
        </footer>
</body>
</html>