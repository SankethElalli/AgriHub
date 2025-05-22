<?php
session_start();
require('../sql.php');

// Verify payment was completed
if(!isset($_SESSION['payment_completed']) || !$_SESSION['payment_completed']) {
    header("location: cbuy_crops.php");
    exit();
}

date_default_timezone_set("Asia/Calcutta"); 
$userlogin=$_SESSION['customer_login_user'];
$servername="localhost";
$username="root";
$password="";
$dbname="agriculture_portal";

//Create Connection 
$conn =mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$query1 = "SELECT * from cart";
$result1 = mysqli_query($conn, $query1);

if ($result1 && mysqli_num_rows($result1) > 0) {
    while($row1 = $result1->fetch_assoc()) {
        if (!empty($row1['quantity']) && !empty($row1['cropname'])) {
            $x = $row1['quantity'];

            $query2 = "UPDATE production_approx SET quantity = quantity - ? WHERE crop = ?";
            $stmt2 = $conn->prepare($query2);
            $stmt2->bind_param("is", $x, $row1['cropname']);
            $stmt2->execute();

            do {
                $query3 = "SELECT * FROM farmer_crops_trade WHERE Trade_crop = ? LIMIT 1";
                $stmt3 = $conn->prepare($query3);
                $stmt3->bind_param("s", $row1['cropname']);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                $row3 = $result3->fetch_assoc();

                if (!$row3) {
                    break; // No more crops to process
                }

                if ($row3['Crop_quantity'] == $x) {
                    // Insert into history and delete record
                    $query11 = "INSERT INTO farmer_history (farmer_id, farmer_crop, farmer_quantity, farmer_price, date) VALUES (?, ?, ?, ?, ?)";
                    $stmt11 = $conn->prepare($query11);
                    $stmt11->bind_param("isids", $row3['farmer_fkid'], $row3['Trade_crop'], $row3['Crop_quantity'], $row1['price'], $date);
                    $stmt11->execute();

                    mysqli_query($conn, "DELETE FROM farmer_crops_trade WHERE trade_id = " . $row3['trade_id']);
                    break;
                }

                if ($row3['Crop_quantity'] > $x) {
                    // Update quantities
                    $price = $x * $row3['msp'];
                    $query12 = "INSERT INTO farmer_history (farmer_id, farmer_crop, farmer_quantity, farmer_price, date) VALUES (?, ?, ?, ?, ?)";
                    $stmt12 = $conn->prepare($query12);
                    $stmt12->bind_param("isids", $row3['farmer_fkid'], $row3['Trade_crop'], $x, $price, $date);
                    $stmt12->execute();

                    mysqli_query($conn, "UPDATE farmer_crops_trade SET Crop_quantity = Crop_quantity - $x WHERE trade_id = " . $row3['trade_id']);
                    break;
                }

                if ($row3['Crop_quantity'] < $x) {
                    $x = $x - $row3['Crop_quantity'];
                    $price = $row3['Crop_quantity'] * $row3['msp'];
                    
                    $query13 = "INSERT INTO farmer_history (farmer_id, farmer_crop, farmer_quantity, farmer_price, date) VALUES (?, ?, ?, ?, ?)";
                    $stmt13 = $conn->prepare($query13);
                    $stmt13->bind_param("isids", $row3['farmer_fkid'], $row3['Trade_crop'], $row3['Crop_quantity'], $price, $date);
                    $stmt13->execute();

                    mysqli_query($conn, "DELETE FROM farmer_crops_trade WHERE trade_id = " . $row3['trade_id']);
                }
            } while ($x > 0);

            // Update MSP
            $total = 0;
            $count = 0;
            $query = "SELECT costperkg FROM farmer_crops_trade WHERE Trade_crop = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $row1['cropname']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while($row = $result->fetch_assoc()) {
                if(isset($row["costperkg"])) {
                    $total += $row["costperkg"];
                    $count++;
                }
            }

            if ($count > 0) {
                $newMsp = ceil($total/$count);
                $newMsp = ceil($newMsp * 1.5);
                
                $query7 = "UPDATE farmer_crops_trade SET msp = ? WHERE Trade_crop = ?";
                $stmt7 = $conn->prepare($query7);
                $stmt7->bind_param("ds", $newMsp, $row1['cropname']);
                $stmt7->execute();
            }
        }
    }
}

// Store cart items for invoice
$cart_items = [];
$result_cart = mysqli_query($conn, "SELECT * FROM cart");
while($row = mysqli_fetch_assoc($result_cart)) {
    $cart_items[] = $row;
}
$_SESSION['last_invoice_items'] = $cart_items;

// Clear session data
unset($_SESSION['payment_completed']);
unset($_SESSION['payment_id']); 
unset($_SESSION['amount']);
unset($_SESSION["shopping_cart"]);

// Clear cart
mysqli_query($conn, "TRUNCATE TABLE cart");

header("location: cmoney_transfered.php");
exit();
?>