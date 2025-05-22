<?php
include ('fsession.php');
ini_set('memory_limit', '-1');
require_once('classes/FertilizerAPI.php');

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
				    <h2 class="text-white mb-0 buy-crops-title">Fertilizer Recommendation</h2>
				    <button type="submit" value="Recommend" name="Fert_Recommend" class="btn btn-warning btn-submit">Submit</button>
				  </div>

				  <div class="card-body text-dark">
					 
				<table class="table table-fertilizer table-striped table-hover table-bordered bg-gradient-white text-center display responsive nowrap" id="myTable">
    <thead>
        <tr class="bg-gradient-success text-white">
            <th class="px-4"><center>Nitrogen</center></th>
            <th class="px-4"><center>Phosphorous</center></th>
            <th class="px-4"><center>Potassium</center></th>
            <th class="px-4"><center>Temperature</center></th>
            <th class="px-4"><center>Humidity</center></th>
            <th class="px-4"><center>Soil Moisture</center></th>
            <th class="px-4"><center>Soil Type</center></th>
            <th class="px-4"><center>Crop Type</center></th>
        </tr>
    </thead>
    <tbody>
        <tr class="text-center align-middle">
            <td>
                <div class="form-group px-3 mb-0">
                    <input type="number" name="n" placeholder="in gms" required class="form-control form-control-sm text-center" min="0" step="0.01">
                </div>
            </td>
            <td>
                <div class="form-group px-3 mb-0">
                    <input type="number" name="p" placeholder="in gms" required class="form-control form-control-sm text-center" min="0" step="0.01">
                </div>
            </td>
            <td>
                <div class="form-group px-3 mb-0">
                    <input type="number" name="k" placeholder="in gms" required class="form-control form-control-sm text-center" min="0" step="0.01">
                </div>
            </td>
            <td>
                <div class="form-group px-3 mb-0">
                    <input type="number" name="t" placeholder="in °C" required class="form-control form-control-sm text-center" min="0" step="0.1">
                </div>
            </td>
            <td>
                <div class="form-group px-3 mb-0">
                    <input type="number" name="h" placeholder="in %" required class="form-control form-control-sm text-center" min="0" max="100" step="0.1">
                </div>  
            </td>
            <td>
                <div class="form-group px-3 mb-0">
                    <input type="number" name="soilMoisture" placeholder="in g/m³" required class="form-control form-control-sm text-center" min="0" step="0.1">
                </div>
            </td>
            <td>
                <div class="form-group px-3 mb-0">
                    <select name="soil" class="form-control form-control-sm text-center custom-select">
                        <option value="">Select Type</option>
                        <option value="Sandy">Sandy</option>
                        <option value="Loamy">Loamy</option>
                        <option value="Black">Black</option>
                        <option value="Red">Red</option>
                        <option value="Clayey">Clayey</option>
                    </select>
                </div>
            </td>
            <td>
                <div class="form-group px-3 mb-0">
                    <input type="text" name="crop" placeholder="Enter crop name" required class="form-control form-control-md text-center">
                </div>
            </td>
        </tr>
    </tbody>
</table>
	</div>
	</form>

</div>



<?php 
if(isset($_POST['Fert_Recommend'])) {
    echo '<div class="card text-black bg-gradient-success mb-3">
        <div class="card-header d-flex align-items-center justify-content-center py-4">
            <h2 class="text-white mb-0 buy-crops-title">Fertilizer Recommendations</h2>
        </div>
        <div class="card-body bg-white rounded shadow-sm">';

    // Validate inputs
    if(empty($_POST['n']) || empty($_POST['p']) || empty($_POST['k']) || 
       empty($_POST['t']) || empty($_POST['h']) || empty($_POST['soilMoisture']) || 
       empty($_POST['soil']) || empty($_POST['crop'])) {
        echo "<div class='alert alert-warning'>Please fill all the required fields.</div>";
    } else {
        $n = trim($_POST['n']);
        $p = trim($_POST['p']);
        $k = trim($_POST['k']);
        $t = trim($_POST['t']);
        $h = trim($_POST['h']);
        $sm = trim($_POST['soilMoisture']);
        $soil = trim($_POST['soil']);
        $crop = trim($_POST['crop']);

        // Add input value validation
        if($n < 0 || $p < 0 || $k < 0 || $t < 0 || $h < 0 || $sm < 0) {
            echo "<div class='alert alert-danger'>Please enter valid positive values.</div>";
        } else {
            $fertilizer = new FertilizerAPI(''); // Set your API key here
            
            $params = [
                'n' => $n,
                'p' => $p,
                'k' => $k,
                't' => $t,
                'h' => $h,
                'sm' => $sm,
                'soil' => $soil,
                'crop' => $crop
            ];

            try {
                $predictions = $fertilizer->getFertilizerPrediction($params);

                if (isset($predictions['error'])) {
                    echo "<div class='alert alert-danger'>{$predictions['error']}</div>";
                } else {
                    // Call Python script for initial recommendation
                    $command = escapeshellcmd("python ML/fertilizer_recommendation/fertilizer_recommendation.py " . 
                                           "$n $p $k $t $h $sm " . escapeshellarg($soil));
                    $pythonOutput = shell_exec($command);
                    
                    echo "<div class='results-container'>";
                    if($pythonOutput) {
                        echo "<div class='result-card primary-recommendation mb-4'>";
                        echo "<h4 class='text-success font-weight-bold mb-3'>Primary Recommendation</h4>";
                        echo "<p class='lead text-dark'>" . htmlspecialchars($pythonOutput) . "</p>";
                        echo "</div>";
                    }
                    
                    echo "<div class='result-card crop-compatibility mb-4'>";
                    echo "<h4 class='text-primary font-weight-bold mb-3'>Crop-Soil Compatibility Analysis</h4>";
                    
                    // Calculate overall compatibility score based on soil conditions
                    $compatibility = calculateCompatibility($n, $p, $k, $sm, $soil, $crop);
                    
                    if ($compatibility['score'] >= 70) {
                        echo "<div class='alert alert-success'>";
                        echo "<h5>Suitable Match</h5>";
                        echo "<p>{$crop} is compatible with your soil conditions. " . $compatibility['reason'] . "</p>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-warning'>";
                        echo "<h5>⚠️ Reconsideration Advised</h5>";
                        echo "<p>Your soil conditions may not be optimal for {$crop}. " . $compatibility['reason'] . "</p>";
                        echo "<p><strong>Suggestions:</strong></p>";
                        echo "<ul>";
                        foreach ($compatibility['suggestions'] as $suggestion) {
                            echo "<li>{$suggestion}</li>";
                        }
                        echo "</ul>";
                        echo "</div>";
                    }
                    echo "</div>";
                    
                    // Show fertilizer recommendations
                    if (!empty($predictions)) {
                        echo "<h4 class='text-success font-weight-bold mb-3'>Additional Recommendations</h4>";
                        echo "<div class='recommendation-grid'>";
                        foreach ($predictions as $prediction) {
                            echo "<div class='result-card'>";
                            echo "<div class='recommendation-header'>";
                            echo "<h5 class='text-primary'>{$prediction['fertilizer']}</h5>";
                            echo "<span class='badge badge-success'>{$prediction['confidence']}% Suitable</span>";
                            echo "</div>";
                            echo "<p class='text-muted'>{$prediction['details']}</p>";
                            echo "<div class='soil-conditions'>";
                            echo "<small class='text-muted'>";
                            echo "<strong>Soil Analysis:</strong><br>";
                            echo "NPK Ratio: {$n}-{$p}-{$k}<br>";
                            echo "Soil Type: {$soil}<br>";
                            echo "Moisture Level: {$sm}%";
                            echo "</small>";
                            echo "</div>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }
                    echo "</div>";
                }
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
            }
        }
    }
    echo '</div></div>';
}

function calculateCompatibility($n, $p, $k, $moisture, $soil_type, $crop) {
    $score = 0;
    $reasons = [];
    $suggestions = [];

    // Basic nutrient requirements for most crops
    $minN = 30;
    $minP = 20;
    $minK = 20;
    $minMoisture = 30;

    // Check nutrients
    if ($n >= $minN) $score += 25;
    else {
        $reasons[] = "Low nitrogen levels";
        $suggestions[] = "Consider adding nitrogen-rich fertilizers";
    }

    if ($p >= $minP) $score += 25;
    else {
        $reasons[] = "Low phosphorus levels";
        $suggestions[] = "Add phosphate fertilizers";
    }

    if ($k >= $minK) $score += 25;
    else {
        $reasons[] = "Low potassium levels";
        $suggestions[] = "Include potassium supplements";
    }

    // Check moisture
    if ($moisture >= $minMoisture) $score += 25;
    else {
        $reasons[] = "Insufficient soil moisture";
        $suggestions[] = "Improve irrigation or water management";
    }

    $reason = empty($reasons) ? 
        "All soil parameters are within acceptable ranges." : 
        "Issues found: " . implode(", ", $reasons);

    return [
        'score' => $score,
        'reason' => $reason,
        'suggestions' => $suggestions
    ];
}
?>

<style>
.results-container {
    padding: 20px;
}

.recommendation-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.result-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.primary-recommendation {
    border-left: 4px solid #2dce89;
    background: #f8f9fe;
}

.crop-compatibility {
    border-left: 4px solid #5e72e4;
    background: #f8f9fe;
}

.recommendation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.soil-conditions {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.badge {
    padding: 8px 12px;
    border-radius: 30px;
}

.table {
    width: 100% !important;
    margin-bottom: 0 !important;
}

.table thead th {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px !important;
    vertical-align: middle;
    border: 1px solid rgba(0,0,0,0.1);
}

.table tbody td {
    padding: 12px 8px !important;
    vertical-align: middle;
    border: 1px solid rgba(0,0,0,0.1);
}

.form-control-sm {
    height: calc(1.5em + 0.5rem + 2px);
    font-size: 0.875rem;
}

.form-control-md {
    height: calc(1.5em + 0.75rem + 2px); 
    font-size: 0.95rem;
}

.form-control::placeholder {
    font-size: 0.8rem;
    opacity: 0.7;
}

.custom-select {
    font-size: 0.875rem;
}

.table .form-group {
    margin: 0;
}

.table input:focus,
.table select:focus {
    border-color: #2dce89;
    box-shadow: 0 0 0 0.2rem rgba(45, 206, 137, 0.25);
}

@media (max-width: 768px) {
    .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
}
</style>
 
	
	
            </div>
          </div>  
       </div>
		 
</section>

    <?php require("footer.php");?>

</body>
</html>

