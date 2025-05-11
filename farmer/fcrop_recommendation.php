<?php
include ('fsession.php');
ini_set('memory_limit', '-1');
require_once('classes/CropAPI.php');

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

<div class="container-fluid">
          <div class="row row-content">
            <div class="col-md-12 mb-3">

				<div class="card text-white bg-gradient-success mb-3">
				<form role="form" action="#" method="post" >  
				  <div class="card-header d-flex align-items-center justify-content-between py-4">
				    <h2 class="text-white mb-0 buy-crops-title">Crop Recommendation</h2>
				    <button type="submit" value="Recommend" name="Crop_Recommend" class="btn btn-warning btn-submit">Submit</button>
				  </div>

				  <div class="card-body text-dark">
				     <form role="form" action="#" method="post" >     
					 
				<table class="table table-recommendation table-striped table-hover table-bordered bg-gradient-white text-center display responsive nowrap" id="myTable">
        <thead>
            <tr class="bg-gradient-success text-white">
                <th><center>Nitrogen</center></th>
                <th><center>Phosphorous</center></th>
                <th><center>Potassium</center></th>
                <th><center>Temperature</center></th>
                <th><center>Humidity</center></th>
                <th><center>pH</center></th>
                <th><center>Rainfall</center></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="form-group">
                        <input type="number" name="n" placeholder="in gms" required class="form-control" min="0" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="p" placeholder="in gms" required class="form-control" min="0" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="k" placeholder="in gms" required class="form-control" min="0" step="0.01">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="t" placeholder="in °C" required class="form-control" min="0" step="0.1">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="h" placeholder="in %" required class="form-control" min="0" max="100" step="0.1">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="ph" placeholder="0-14" required class="form-control" min="0" max="14" step="0.1">
                    </div>
                </td>
                <td>
                    <div class="form-group">
                        <input type="number" name="r" placeholder="in mm" required class="form-control" min="0" step="0.1">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
	</form>
</div>
</div>



<?php 
if(isset($_POST['Crop_Recommend'])) {
    echo '<div class="card text-black bg-gradient-success mb-3">
        <div class="card-header d-flex align-items-center justify-content-center py-4">
            <h2 class="text-white mb-0 buy-crops-title">Result</h2>
        </div>
        <div class="card-body">';

    $n = trim($_POST['n']);
    $p = trim($_POST['p']);
    $k = trim($_POST['k']);
    $t = trim($_POST['t']);
    $h = trim($_POST['h']);
    $ph = trim($_POST['ph']); 
    $r = trim($_POST['r']);

    // Your DeepseekAPI API key
    $deepseek = new DeepseekAPI('');
    
    // Prepare parameters
    $params = [
        'n' => $n,
        'p' => $p,
        'k' => $k,
        't' => $t,
        'h' => $h,
        'ph' => $ph,
        'r' => $r
    ];

    try {
        // Pass parameters directly to API
        $predictions = $deepseek->getCropPrediction($params);

        if (isset($predictions['error'])) {
            if (strpos($predictions['error'], 'Insufficient Balance') !== false) {
                echo "<div class='alert alert-danger'>Service temporarily unavailable. Please try again later or contact administrator.</div>";
            } else {
                echo "<div class='alert alert-danger'>{$predictions['error']}</div>";
            }
            error_log("DeepSeek API Error: " . print_r($predictions['error'], true));
        } else if (empty($predictions)) {
            echo "<div class='alert alert-warning'>No crop recommendations found for these conditions. Please verify your input values.</div>";
        } else {
            echo "<div class='recommendations-grid'>";
            foreach ($predictions as $prediction) {
                echo "<div class='recommendation-result'>";
                echo "<div class='recommendation-header'>{$prediction['crop']}</div>";
                echo "<div class='recommendation-confidence'>Confidence: {$prediction['confidence']}%</div>";
                echo "<div class='recommendation-details'>{$prediction['explanation']}</div>";
                echo "<div class='recommendation-meta'>";
                echo "Optimal conditions:<br>";
                echo "N: {$n} mg/kg, P: {$p} mg/kg, K: {$k} mg/kg<br>";
                echo "pH: {$ph}, Temperature: {$t}°C";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
        error_log("Crop Recommendation Error: " . $e->getMessage());
    }
    
    echo '</div></div>';
}
?>
 
	
	
            </div>
          </div>  
       </div>
		 
</section>

    <?php require("footer.php");?>

</body>
</html>
