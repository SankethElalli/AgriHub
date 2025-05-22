<?php
include ('csession.php');
include ('../sql.php');

ini_set('memory_limit', '-1');

if(!isset($_SESSION['customer_login_user'])){
header("location: ../index.php");} // Redirecting To Home Page
$query4 = "SELECT * from custlogin where email='$user_check'";
              $ses_sq4 = mysqli_query($conn, $query4);
              $row4 = mysqli_fetch_assoc($ses_sq4);
              $para1 = $row4['cust_id'];
              $para2 = $row4['cust_name'];
		  
?>

<!DOCTYPE html>
<html>
<?php include ('cheader.php');  ?>

<head>
	<link rel="stylesheet" href="../assets/css/creativetim.min.css" type="text/css">
	<link rel="stylesheet" href="../assets/css/custom.css" type="text/css">
	<link rel="stylesheet" href="../assets/css/footer.css" type="text/css">

</head>

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
<!-- ======================================================================================================================================== -->


<div class="container ">
          <div class="row row-content">
            <div class="col-md-12 mb-3">

				<div class="card text-white bg-gradient-danger mb-3">
				    <div class="card-header d-flex align-items-center justify-content-center py-4">
				        <h2 class="text-white mb-0 buy-crops-title">Buy Crops</h2>
				    </div>
				  
				  <div class="card-body ">
			

				                                                                                                                         

                <table class="table table-striped table-bordered table-responsive-md btn-table  ">

                    <thead class=" text-white text-center">
                    <tr>
					
                        <th>Crop Name</th>
                        <th>Quantity (in KG)</th>
                        <th>Price (in Rs)</th>
						<th>Add Item</th>
	
                    </tr>
                    </thead>

                    <tbody>
					
                    <tr>
					
			
						 
<form method="POST" action="cbuy_redirect.php">

						<td>
                        <div class="form-group">
<?php  
// Join production_approx with farmer_crops_trade to get available crops with prices
$sql = "SELECT DISTINCT p.crop, p.quantity, f.msp 
        FROM production_approx p 
        INNER JOIN farmer_crops_trade f ON p.crop = f.Trade_crop 
        WHERE p.quantity > 0 
        ORDER BY p.crop";
$result = $conn->query($sql);

if($result->num_rows > 0) {
    echo "<select id='crops' name='crops' class='form-control text-dark'>";
    echo "<option value=''>Select Crop</option>";
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row["crop"] . "' data-price='" . $row["msp"] . "' data-quantity='" . $row["quantity"] . "'>" 
             . ucfirst($row["crop"]) . " (Available: " . $row["quantity"] . "kg)</option>";
    }
    echo "</select>";
} else {
    echo "<select class='form-control text-dark'><option>No crops available</option></select>";
}
?>
</div>					
						</td>
			
			
<input hidden name="tradeid" id="tradeid"  value="">



						<td>   
						  <div class="form-group">     
							<input id="quantity" type="number" placeholder="Available Quantity" max="" name="quantity" required class="form-control text-dark">   
						  </div> 
						</td>


                        <td>
                        <div class="form-group" >
                        <input id="price" type="text" value="0" name="price"  readonly class="form-control text-dark">
                        </div>
						</td>	
						
						
						 
						<td>
						 <div class="form-group" >
						<button class="btn btn-success form-control" name="add_to_cart" type="submit" disabled >Add To Cart </button>
						</div>
						</td>
							    
	</form>
	
		
						</tr>
						</tbody>
                        </table> 

			<h3 class=" text-black">Order Details</h3>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-responsive-md btn-table display" id="myTable">
					<tr class=" bg-dange">
						<th width="40%">Item Name</th>
						<th width="10%">Quantity (in KG)</th>
						<th width="20%">Price (in Rs.)</th>				
						<th width="5%">Action</th>
					</tr>
					<?php
					if(!empty($_SESSION["shopping_cart"]))
					{
						$total = 0;
						foreach($_SESSION["shopping_cart"] as $keys => $values)
						{
					?>

	
					<tr class=" bg-white">
						<td><?php echo ucfirst($values["item_name"]); ?></td>
						<td><?php echo $values["item_quantity"]; ?></td>
						<td>Rs. <?php echo $values["item_price"]; ?> </td>
				
					<td><a href="cbuy_crops.php?action=delete&id=<?php echo $values["item_id"]; ?>" type="button" class="btn btn-warning btn-block" >Remove</a></td>
					
					</tr>

<?php

		if(isset($_GET["action"]))
		{
			if($_GET["action"] == "delete")
			{
				foreach($_SESSION["shopping_cart"] as $keys => $values)
				{
					if($values["item_id"] == $_GET["id"])
					{
						unset($_SESSION["shopping_cart"][$keys]);
						$b=$_GET["id"];
						
						$query5="SELECT Trade_crop from farmer_crops_trade where trade_id= $b";
						$result5 = mysqli_query($conn, $query5);
						$row5 = $result5->fetch_assoc(); 
						$a=$row5["Trade_crop"];
						
						
						$query6="DELETE FROM `cart` WHERE `cropname` = '".$a."'";
						$result6 = mysqli_query($conn, $query6); 

						echo '<script>alert("Item Removed")</script>';
						echo '<script>window.location="cbuy_crops.php"</script>';
		

					     
						
					}
				}
			}
		}
?>

					<?php
							$total = $total +  $values["item_price"];
							$_SESSION['Total_Cart_Price']=$total;
						}
					?>
					<tr class="text-dark">
						<td colspan="2" align="right" >Total</td>
						<td align="right">Rs. <?php echo number_format($total,2); ?></td>
						<td>
                            <?php
                                $_SESSION['amount'] = $total; // Store total in session for PayPal
                            ?>
                            <a href="payment/checkout.php" class="btn btn-info form-control">Pay with PayPal</a>
						</td>
					</tr>
					<?php
					}
					?>
						
				</table>
			</div>








</div>
				</div>				 		  
            </div>
          </div>
        </div>
		 
</section>
	   <?php require("footer.php");?>

												
												
<script>
				$(document).ready( function () {
    $('#myTable').DataTable();
} );
</script>

						
<script> 
document.getElementById("crops").addEventListener("change", function() {   
  var crops = jQuery('#crops').val();   
  jQuery.ajax({     
    url: 'ccheck_quantity.php',     
    type: 'post',     
    data: 'crops=' + crops,     
    success: function(result) { 
		      try {
				 var result = JSON.parse(result);
				  
				 var cquantity = parseInt(result.quantityR);
				 var TradeId = parseInt(result.TradeIdR);  
				  console.log(result);

				 if (cquantity > 0) {         
						document.getElementById("quantity").placeholder = cquantity;         
					   
						document.getElementById("tradeid").value = TradeId;
					  } else {         
						document.getElementById("quantity").placeholder = "Select Crop";       
					  } 

			} catch (error) {
				  console.log('Error:', error);
			}

	  
    }   
  }); 
}); 
</script>    

<script>
  document.getElementById("quantity").addEventListener("change", function() {
const addToCartBtn = document.querySelector('[name="add_to_cart"]');
    var quantity = jQuery('#quantity').val();
	  var crops = jQuery('#crops').val();
		
    jQuery.ajax({
      url: 'ccheck_price.php',
      type: 'post',
      data: { crops: crops, quantity: quantity },
      success: function(result) {
			var cprice = parseInt(result);
			if(cprice>0){
				document.getElementById("price").value = cprice;
				addToCartBtn.removeAttribute('disabled');
			}
			else{
				document.getElementById("price").value = "0";
			}
		}
	});
});
</script>

	<script>

const quantityInput = document.getElementById("quantity");

quantityInput.addEventListener("change", () => {
  const max = document.getElementById("quantity").placeholder;
  
  if (quantityInput.value > max) {
    quantityInput.value = max;
  }
});

document.querySelector('form').addEventListener('submit', function(event) {
  const quantityInput = document.getElementById("quantity");
  const max = document.getElementById("quantity").placeholder;

  if (quantityInput.value > max) {
    event.preventDefault();
    alert(`Maximum quantity exceeded. Please enter a quantity less than or equal to ${max}.`);
  }
});
</script>
	
</body>
</html>
