<?php

$hostname = "localhost";
$username = "root";
$password = "Raihan110123041";
$database = "db_post";

$connection = mysqli_connect($hostname, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset UTF-8
mysqli_set_charset($connection, "utf8mb4");