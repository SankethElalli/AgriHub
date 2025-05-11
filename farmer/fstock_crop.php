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
			  
?>

<!DOCTYPE html>
<html>
<?php include ('fheader.php');  ?>

<head>
<link rel="stylesheet" href="../assets/css/custom.css" type="text/css">
<link rel="stylesheet" href="../assets/css/footer.css" type="text/css">
</head>

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
    </div>
<!-- ======================================================================================================================================== -->



<div class="container">    		
    <div class="row row-content">
        <div class="col-md-12 mb-3">
            <div class="card text-white bg-gradient-success mb-3">
                <div class="card-header d-flex align-items-center justify-content-center py-4">
                    <h2 class="text-white mb-0 buy-crops-title">Crop Availability</h2>
                </div>
                <div class="card-body text-dark">
                    <div class="table-responsive">
                        <table class="table-custom dataTable" id="myTable">
                            <thead>
                                <tr class="font-weight-bold text-default">
                                    <th><center>Crop Name</center></th>
                                    <th><center>Quantity</center></th>
                                    <th><center>Actions</center></th>
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
</div>
		 
</section>

<?php require("footer.php");?>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [{
                targets: -1,
                orderable: false
            }]
        });
    });
</script>
</body>

</html>

