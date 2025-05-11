<?php
session_start();// Starting Session
require('../sql.php'); // Includes Login Script

// Storing Session
$user = $_SESSION['admin_login_user'];

if(!isset($_SESSION['admin_login_user'])){
header("location: ../index.php");} // Redirecting To Home Page
$query4 = "SELECT * from admin where admin_name ='$user'";
              $ses_sq4 = mysqli_query($conn, $query4);
              $row4 = mysqli_fetch_assoc($ses_sq4);
              $para1 = $row4['admin_id'];
              $para2 = $row4['admin_name'];
			  $para3 = $row4['admin_password'];
			  
?>

<!DOCTYPE html>
<html>
<?php require ('aheader.php');  ?>

<head>
    <!-- Other CSS files -->
    <link rel="stylesheet" href="../assets/css/custom.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>

  <body class="bg-white" id="top">
  
<?php require ('anav.php');  ?>
 	
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
<!-- ======================================================================================================================================== -->



<div class="container">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card text-white bg-gradient-success mb-3">
                <div class="card-header d-flex align-items-center justify-content-center py-4">
                    <h2 class="text-white mb-0 buy-crops-title">Produced Crops</h2>
                </div>
                <div class="card-body text-dark">
                    <table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">
                        <thead>
                            <tr class="font-weight-bold text-default">
                                <th><center>Crop Name</center></th>
                                <th><center>Quantity (in KG)</center></th>
                                <th><center>Action</center></th>						
                            </tr>
                        </thead>
                        <tbody>	  
                            <?php 
                                $sql = "SELECT crop, quantity FROM production_approx where quantity > 0";
                                $query = mysqli_query($conn,$sql);
                                while($res = mysqli_fetch_array($query)){	
                            ?>		  
                            <tr class="text-center">
                                <td> <?php echo $res['crop'];  ?> </td>
                                <td> <?php echo $res['quantity'];  ?> </td>
                                <td>
                                    <button class="btn btn-sm btn-danger">
                                        <a href="adeletecrop.php?crop=<?php echo $res['crop']; ?>" class="nav-link text-white">Delete</a>
                                    </button>
                                </td>
                            </tr>
                            <?php 
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
} );
</script>
</body>

</html>

