<?php

$db_host = 'localhost'; // Nama server
$db_user = 'root'; // User server
$db_pass = ''; // Password server
$db_name = 'globalin'; // Nama database

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
if (!$conn) {
    die('Gagal terhubung MySQL: ' . mysqli_connect_error());
};

?>