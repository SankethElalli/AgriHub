<?php
include ('csession.php');
include ('../sql.php');

ini_set('memory_limit', '-1');
date_default_timezone_set("Asia/Calcutta"); 

if(!isset($_SESSION['Total_Cart_Price'])){
header("location: cprofile.php");} // Redirecting To Home Page
$query4 = "SELECT * from custlogin where email='$user_check'";
              $ses_sq4 = mysqli_query($conn, $query4);
              $row4 = mysqli_fetch_assoc($ses_sq4);
              $para1 = $row4['cust_id'];
              $para2 = $row4['cust_name'];
              $para3 = $row4['password'];
			  $para5 = $row4['email'];
			  $para6 = $row4['phone_no'];
			  $para7 = $row4['state'];
			  $para8 = $row4['city'];
			  $para9 = $row4['address'];
			  $para10 = $row4['pincode'];

$totalPrice = $_SESSION['Total_Cart_Price'];
unset($_SESSION['Total_Cart_Price']); 

//Cart Details Query
$Cartquery="SELECT * from cart";
$cartresult=mysqli_query($conn,$Cartquery);
?>

<!DOCTYPE html>
<html>
<?php include ('cheader.php');  ?>
  <body class="bg-white" id="top">
  
<?php include ('cnav.php');  ?>
 	
  <section class="section section-shaped section-lg">
    <div class="shape shape-style-1 shape-primary">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
      <span></span>
    </div>

    <div class="container">
        <div class="row row-content">
            <div class="col-md-12 mb-3">
                <div class="card text-white bg-gradient-danger mb-3">
                    <div class="card-header d-flex align-items-center justify-content-center py-4">
                        <h2 class="text-white mb-0">Invoice Details</h2>
                    </div>
                  
                    <div class="card-body">
                        <!-- Invoice Header -->
                        <div class="row mb-4">
                            <div class="col-6">
                                <h3 class="text-dark">AgriHub</h3>
                                <div class="text-dark">
                                    <p>Invoice Date: <?php echo date('d/m/Y'); ?><br>
                                    Invoice Time: <?php echo date('H:i:s'); ?><br>
                                    Invoice No: #0000123DSS</p>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <h3 class="text-dark">Customer Details</h3>
                                <div class="text-dark">
                                    <p><strong><?php echo $para2;?></strong><br>
                                    <?php echo $para9;?><br>
                                    <?php echo $para8;?>, <?php echo $para10;?><br>
                                    Phone: <?php echo $para6;?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-responsive-md btn-table">
                                <thead class="text-white text-center">
                                    <tr>
                                        <th>Product Name</th>
                                        <th width="20%">Quantity (in KG)</th>
                                        <th width="20%">Price (in Rs)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $items = isset($_SESSION['last_invoice_items']) ? $_SESSION['last_invoice_items'] : [];
                                    $totalPrice = 0;
                                    if (!empty($items)) {
                                        foreach($items as $rows) {
                                            echo '<tr class="bg-white">';
                                            echo '<td>' . ucfirst($rows['cropname']) . '</td>';
                                            echo '<td class="text-center">' . $rows['quantity'] . '</td>';
                                            echo '<td class="text-center">Rs. ' . $rows['price'] . '</td>';
                                            echo '</tr>';
                                            $totalPrice += $rows['price'];
                                        }
                                        echo '<tr class="bg-white font-weight-bold">
                                                <td colspan="2" class="text-right">Total Amount</td>
                                                <td class="text-center">Rs. ' . $totalPrice . '</td>
                                            </tr>';
                                    } else {
                                        echo '<tr><td colspan="3" class="text-center">No items found for this invoice.</td></tr>';
                                    }
                                    // Optionally clear the session variable after displaying
                                    unset($_SESSION['last_invoice_items']);
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Invoice Actions -->
                        <div class="text-center mt-4">
                            <button onclick="window.print()" class="btn btn-farmer mx-2">
                                <i class="fa fa-print"></i> Print Invoice
                            </button>
                            <a href="cprofile.php" class="btn btn-farmer mx-2">
                                <i class="fa fa-home"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require("footer.php");?>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>

</body>
</html>

