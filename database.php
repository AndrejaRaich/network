<?php

require_once "connection.php";

$sql = "CREATE TABLE IF NOT EXISTS `users`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL UNIQUE, 
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = INNODB;
";

$sql .= "CREATE TABLE IF NOT EXISTS `profiles`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(255) NOT NULL, 
    `last_name` VARCHAR(255) NOT NULL,
    `gender` CHAR(1),
    `dob` DATE,
    `id_user` INT UNSIGNED NOT NULL UNIQUE,
    `image` INT UNSIGNED UNIQUE,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`id_user`) REFERENCES `users`(`id`)
    ON UPDATE CASCADE ON DELETE NO ACTION
) ENGINE = INNODB;
";

$sql .= " CREATE TABLE IF NOT EXISTS `followers`(
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `id_sender` INT UNSIGNED NOT NULL, 
        `id_reciever` INT UNSIGNED NOT NULL,
        PRIMARY KEY(`id`),
        FOREIGN KEY(`id_sender`) REFERENCES `users`(`id`)
            ON UPDATE CASCADE ON DELETE NO ACTION,
        FOREIGN KEY(`id_reciever`) REFERENCES `users`(`id`)
            ON UPDATE CASCADE ON DELETE NO ACTION
        ) ENGINE = INNODB;
        ";

if ($conn->multi_query($sql)) {
    echo "<p>Tables created successfully</p>";
} else {
    header("location:error.php?m=ErrorInQuery");
}
?>