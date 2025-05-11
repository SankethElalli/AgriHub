<?php
session_start();
require('../sql.php'); // Includes SQL connection script

if (isset($_POST['crops'])) {

  $crop = $_POST['crops'];

  // Get total available quantity
  $query = "SELECT p.quantity, f.trade_id, f.msp 
            FROM production_approx p 
            INNER JOIN farmer_crops_trade f ON p.crop = f.Trade_crop 
            WHERE p.crop = '$crop'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  $available_quantity = $row['quantity'];
  $trade_id = $row['trade_id'];
  $msp = $row['msp'];

  // Check cart quantity if exists
  $cart_query = "SELECT quantity FROM cart WHERE cropname = '$crop'";
  $cart_result = mysqli_query($conn, $cart_query);
  if($cart_result && mysqli_num_rows($cart_result) > 0) {
      $cart_row = mysqli_fetch_assoc($cart_result);
      $available_quantity -= $cart_row['quantity'];
  }

  $response = array(
      "quantityR" => $available_quantity,
      "TradeIdR" => $trade_id,
      "mspR" => $msp
  );

  echo json_encode($response);

}