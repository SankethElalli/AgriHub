<?php
    // connect to database
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

if (!$servername || !$username || !$dbname) {
    die('Database environment variables are not set.');
}

$conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        echo 'Connection error' . mysqli_connect_error();
    } 
?>