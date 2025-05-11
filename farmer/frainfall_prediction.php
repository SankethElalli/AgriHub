<?php
include ('fsession.php');
include ('classes/RainfallPredictionAPI.php');
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
    </div>
<!-- ======================================================================================================================================== -->

<div class="container ">		
          <div class="row row-content">
            <div class="col-md-12 mb-3">

				<div class="card text-white bg-gradient-success mb-3">
				<form role="form" action="#" method="post" >  
				  <div class="card-header d-flex align-items-center justify-content-center py-4">
				    <h2 class="text-white mb-0 buy-crops-title">Rainfall Prediction</h2>
				  </div>

				  <div class="card-body text-dark">
					 
				<table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">

    <thead>
					<tr class="font-weight-bold text-default">
					<th><center>Region</center></th>
					<th><center>Month</center></th>
					<th><center>Prediction</center></th>
					
					
        </tr>
    </thead>
 <tbody>
                                 <tr class="text-center">

                                   <td>
                                    	<div class="form-group ">
											    <select id="region-select" name="region" class="form-control" required>
													<option value="">Select Region</option>
												</select>
												<script language="javascript"> print_region("region-select"); </script>
										</div>
                                    </td>

									<td>
										<div class="form-group ">
											    <select id="month-select" name="month" class="form-control" required>
													<option value="">Select Month</option>
												</select>
												<script language="javascript"> print_months("month-select"); </script>
										</div>
                                    </td>
									
									<td>
                                    <center>
										<div class="form-group ">
											<button type="submit" value="Yield" name="Rainfall_Predict" class="btn btn-success btn-submit">Predict</button>
										</div>
                                    
                                    </center>
                                    </td>
                                </tr>
                            </tbody>
							
					
	</table>
	</div>
	</form>
</div>

<?php 
if(isset($_POST['Rainfall_Predict'])) {
    echo '<div class="card text-black bg-gradient-success mb-3">
        <div class="card-header d-flex align-items-center justify-content-center py-4">
            <h2 class="text-white mb-0 buy-crops-title">Result</h2>
        </div>
        <div class="card-body">';

    $region = trim($_POST['region']);
    $month = trim($_POST['month']);

    // Use the API instead of Python script
    $api = new RainfallPredictionAPI();
    $result = $api->getRainfallPrediction($region, $month);

    if (isset($result['error'])) {
        echo '<div class="alert alert-danger">' . $result['error'] . '</div>';
    } else {
        ?>
        <div class="prediction-container">
            <h4 class="prediction-title">Rainfall Prediction Results</h4>
            <div class="prediction-details">
                <div class="prediction-card">
                    <div class="prediction-label">For <?php echo $region; ?> in <?php echo $month; ?></div>
                    <div class="prediction-value"><?php echo number_format($result['rainfall'], 2); ?> mm</div>
                    <div class="prediction-explanation mt-3"><?php echo htmlspecialchars($result['explanation']); ?></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    echo '</div></div>';
}
?>

<style>
.prediction-container {
    margin: 20px;
    padding: 20px;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.prediction-title {
    color: #2dce89;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
}

.prediction-details {
    display: flex;
    justify-content: center;
}

.prediction-card {
    padding: 20px;
    border-radius: 8px;
    background-color: #f8f9fa;
    text-align: center;
}

.prediction-label {
    color: #525f7f;
    margin-bottom: 10px;
    font-size: 1.1em;
}

.prediction-value {
    color: #2dce89;
    font-size: 2em;
    font-weight: bold;
    margin-top: 10px;
}

.prediction-explanation {
    color: #525f7f;
    font-size: 15px;
    margin-top: 15px;
    line-height: 1.5;
}
</style>
 
	
	
            </div>
          </div>  
       </div>
		 
</section>

    <?php require("footer.php");?>

</body>
</html>

