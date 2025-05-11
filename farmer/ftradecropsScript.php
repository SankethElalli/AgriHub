<?php 
session_start();
ini_set('memory_limit', '-1');
$userlogin=$_SESSION['farmer_login_user'];

require('../sql.php'); // Includes Login Script

if(isset($_POST['Crop_submit'])){
    $x=0.0;
    $y=0;
    $trade_crop=$_POST['crops'];
    $quantity=$_POST['trade_farmer_cropquantity'];
    $costperkg=$_POST['trade_farmer_cost'];

    
    $query1="SELECT farmer_id from farmerlogin where email='".$userlogin."';";
    $run = mysqli_query($conn,$query1);
    $row=mysqli_fetch_array($run);
    $farmer_pid= $row[0];
    
    $query2="INSERT INTO `farmer_crops_trade`(`farmer_fkid`, `Trade_crop`, `Crop_quantity`,`costperkg`) 
    VALUES ($farmer_pid,'$trade_crop', $quantity, $costperkg);";
    $result = mysqli_query($conn, $query2);


    // Update MSP calculation
    $query = "SELECT costperkg from farmer_crops_trade where Trade_crop='$trade_crop'";
    $result = mysqli_query($conn, $query);
    while($row = $result->fetch_assoc()) {
        $x = $x + $row["costperkg"];
        $y++;
    }

    if ($y > 0) {
        $x = CEIL($x/$y);
        $x = $x + CEIL($x*0.5);
        
        $query3 = "UPDATE farmer_crops_trade SET msp='$x' where Trade_crop='$trade_crop'";
        $result = mysqli_query($conn, $query3);
    }

    // Check if crop exists in production_approx
    $check_query = "SELECT * FROM production_approx WHERE crop='$trade_crop'";
    $check_result = mysqli_query($conn, $check_query);

    if(mysqli_num_rows($check_result) > 0) {
        // Update existing record
        $query4 = "UPDATE production_approx SET quantity=quantity+'$quantity' WHERE crop='$trade_crop'";
    } else {
        // Insert new record
        $query4 = "INSERT INTO production_approx (crop, quantity) VALUES ('$trade_crop', '$quantity')";
    }

    $result = mysqli_query($conn, $query4);

    if(!$result) {
        die("Database update failed: " . mysqli_error($conn));
    }

    echo 
"<script type='text/javascript'>alert('Crop Details Added Successfully');
      window.location='ftradecrops.php';</script>";

}

?>