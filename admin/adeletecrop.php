<?php
session_start();
require('../sql.php'); // Includes SQL connection script

if (isset($_GET['crop'])) {
    $crop = $_GET['crop'];
    $sql = "DELETE FROM production_approx WHERE crop = '$crop'";
    if (mysqli_query($conn, $sql)) {
        echo "<script type='text/javascript'>alert('Crop Deleted Successfully');
        window.location='aproducedcrop.php';</script>";
    } else {
        echo "<script type='text/javascript'>alert('Error deleting crop');
        window.location='aproducedcrop.php';</script>";
    }
} else {
    header("Location: aproducedcrop.php");
}
?>
