<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
header("location: ../index.php");} // Redirecting To Home Page
$query4 = "SELECT * from farmerlogin where email='$user_check'";
              $ses_sq4 = mysqli_query($conn, $query4);
              $row4 = mysqli_fetch_assoc($ses_sq4);
              $para1 = $row4['farmer_id'];
              $para2 = $row4['farmer_name'];
			  


$sql = "SELECT farmer_crop, farmer_quantity, farmer_price, `date` FROM farmer_history WHERE farmer_id='".$para1."' ";
$result = mysqli_query($conn, $sql);


?>


<!DOCTYPE html>
<html>
<?php include ('fheader.php');  ?>

  <body class="bg-white" id="top">
  <?php include ('fnav.php');  ?>
  <section class="section section-shaped section-lg">
    <div class="shape shape-style-1 shape-primary">
      <span></span>
      <span></span>
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
          <div class="card text-white bg-gradient-success mb-3">
            <div class="card-header d-flex align-items-center justify-content-center py-4">
              <h2 class="text-white mb-0 buy-crops-title">Selling History</h2>
            </div>
            <div class="card-body text-dark">
              <table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">
                <thead>
                  <th><center>Crop</center></th>
                  <th><center>Quantity (in KG)</center></th>
                  <th><center>Total Amount received (in Rs)</center></th>
                  <th><center>Date of Transaction</center></th>
                </thead>
                <tbody>
                  <?php  
                  while($row = $result->fetch_assoc()) {
                      $cropname=ucfirst($row["farmer_crop"]);
                      $cropquantity=$row["farmer_quantity"];
                      $cropprice=$row["farmer_price"];
                      $currentdate=$row['date'];

                      echo "<tr >";
                          echo "<td><center>$cropname</center></td>";
                          echo "<td><center>$cropquantity</center></td>";
                          echo "<td><center>$cropprice</center></td>";
                          echo "<td><center>$currentdate</center></td>";
                      echo "</tr>";
                  }
                  ?>
                </tbody>
              </table> 
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
    });
  </script>
</body>
</html>
