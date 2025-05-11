<?php
include ('fsession.php');
ini_set('memory_limit', '-1');
require_once 'classes/CropPredictionAPI.php';  // Add this line

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
    </div>
<!-- ======================================================================================================================================== -->

<div class="container ">		
          <div class="row row-content">
            <div class="col-md-12 mb-3">

				<div class="card text-white bg-gradient-success mb-3">
				  <div class="card-header d-flex align-items-center justify-content-center py-4">
				    <h2 class="text-white mb-0 buy-crops-title">Crop Prediction</h2>
				  </div>

				  <div class="card-body text-dark">
				     <form role="form" action="#" method="post" >     
					 
				<table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">

    <thead>
					<tr class="font-weight-bold text-default">
					<th><center> State</center></th>
					<th><center>District</center></th>
					<th><center>Season</center></th>
					<th><center>Prediction</center></th>
					
        </tr>
    </thead>
 <tbody>
                                 <tr class="text-center">

                                    <td>
                                    	<div class="form-group">
											<select onchange="print_city('state', this.selectedIndex);" id="sts" name ="stt" class="form-control" required></select>
											<script language="javascript">print_state("sts");</script>
											
										</div>
                                    </td>

									<td>
										<div class="form-group ">
											<select id ="state" name="district" class="form-control" required>
											<option value="">Select District</option>
											</select>
											<script language="javascript">print_state("sts");</script>
										</div>
                                    </td>
									
									<td>
										<div class="form-group ">
									
													<select name="Season" class="form-control">
													<option value="">Select Season ...</option>
													<option name="Kharif" value="Kharif">Kharif</option>
													<option name="Whole Year" value="Whole Year">Whole Year</option>
													<option name="Autumn" value="Autumn">Autumn</option>
													<option name="Rabi" value="Rabi">Rabi</option>
													<option name="Summer" value="Summer">Summer</option>
													<option name="Winter" value="Winter">Winter</option>
												
													</select>
										</div>

									</td>

									<td>
                                    <center>
										<div class="form-group ">
											<button type="submit" name="Crop_Predict" class="btn btn-success btn-submit">Predict</button>
										</div>
                                    
                                    </center>
                                    </td>

                                </tr>
                            </tbody>
	</table>
	</form>
</div>
</div>

<?php 
if(isset($_POST['Crop_Predict'])) {
    echo '<div class="card text-black bg-gradient-success mb-3">
        <div class="card-header d-flex align-items-center justify-content-center py-4">
            <h2 class="text-white mb-0 buy-crops-title">Result</h2>
        </div>
        <div class="card-body">';
    
    $state = trim($_POST['stt']);
    $district = trim($_POST['district']);
    $season = trim($_POST['Season']);

    $JsonState = json_encode($state);
    $JsonDistrict = json_encode($district);
    $JsonSeason = json_encode($season);
    
    // Get ML model predictions
    $command = escapeshellcmd("python ML/crop_prediction/ZDecision_Tree_Model_Call.py $JsonState $JsonDistrict $JsonSeason");
    ob_start();
    passthru($command);
    $mlOutput = ob_get_clean();
    $mlOutput = str_replace(['[', ']', "'"], '', $mlOutput);
    $mlCrops = array_map('trim', explode(',', $mlOutput));

    // Get DeepseekAPI predictions
    $api = new CropPredictionAPI();
    $aiCrops = $api->getCropPrediction($state, $district, $season);
    ?>

        <div class="prediction-section ai-model">
            <h4 class="prediction-title">
                AI Model Recommendations for <?php echo $district; ?> (<?php echo $season; ?> Season)
            </h4>
            <div class="crop-grid">
                <?php
                if (isset($aiCrops['error'])) {
                    echo '<div class="alert alert-danger">' . $aiCrops['error'] . '</div>';
                } else {
                    foreach($aiCrops as $crop) {
                        if(!empty($crop)) {
                            echo '<div class="crop-card">';
                            echo '<div class="crop-name">' . $crop . '</div>';
                            echo '</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}
?>

            </div>
          </div>  
       </div>
		 
</section>

    <?php require("footer.php");?>

</body>
</html>

