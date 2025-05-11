<?php
include ('fsession.php');
include ('classes/YieldPredictionAPI.php');
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
    </div>
<div class="container-fluid">
  <div class="row row-content">
    <div class="col-md-12 mb-3">
      <div class="card text-white bg-gradient-success mb-3">
        <div class="card-header d-flex align-items-center justify-content-center py-4">
          <h2 class="text-white mb-0 buy-crops-title">Yield Prediction</h2>
        </div>
        
        <div class="card-body text-dark">
          <form role="form" action="#" method="post">
            <table class="table table-striped table-hover table-bordered bg-gradient-white text-center display" id="myTable">
              <thead>
                <tr class="font-weight-bold text-default">
                  <th><center>State</center></th>
                  <th><center>District</center></th>
                  <th><center>Season</center></th>
                  <th><center>Crop</center></th>
                  <th><center>Area</center></th>
                  <th><center>Prediction</center></th>
                </tr>
              </thead>
              <tbody>
                <tr class="text-center">
                  <td>
                    <div class="form-group">
                      <select name="state" class="form-control" required>
                        <option value="Karnataka">Karnataka</option>
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <select id="district" name="district" class="form-control" required>
                        <option value="">Select a district</option>
                        <option value="BAGALKOT">Bagalkot</option>
                        <option value="BANGALORE_RURAL">Bangalore Rural</option>
                        <option value="BELGAUM">Belgaum</option>
                        <option value="BELLARY">Bellary</option>
                        <option value="BENGALURU_URBAN">Bengaluru Urban</option>
                        <option value="BIDAR">Bidar</option>
                        <option value="BIJAPUR">Bijapur</option>
                        <option value="CHAMARAJANAGAR">Chamarajanagar</option>
                        <option value="CHIKBALLAPUR">Chikballapur</option>
                        <option value="CHIKMAGALUR">Chikmagalur</option>
                        <option value="CHITRADURGA">Chitradurga</option>
                        <option value="DAKSHIN_KANNAD">Dakshin Kannada</option>
                        <option value="DAVANGERE">Davangere</option>
                        <option value="DHARWAD">Dharwad</option>
                        <option value="GADAG">Gadag</option>
                        <option value="GULBARGA">Gulbarga</option>
                        <option value="HAVERI">Haveri</option>
                        <option value="HASSAN">Hassan</option>
                        <option value="KODAGU">Kodagu</option>
                        <option value="KOLAR">Kolar</option>
                        <option value="KOPPAL">Koppal</option>
                        <option value="MANDYA">Mandya</option>
                        <option value="MYSORE">Mysore</option>
                        <option value="RAMANAGARA">Ramanagara</option>
                        <option value="RAICHUR">Raichur</option>
                        <option value="SHIMOGA">Shimoga</option>
                        <option value="TUMKUR">Tumkur</option>
                        <option value="UDUPI">Udupi</option>
                        <option value="UTTAR_KANNAD">Uttar Kannada</option>
                        <option value="YADGIR">Yadgir</option>
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <select name="Season" class="form-control" id="season" required>
                        <option value="">Select Season ...</option>
                        <option value="Kharif">Kharif</option>
                        <option value="Rabi">Rabi</option>
                        <option value="Summer">Summer</option>
                        <option value="WholeYear">Whole Year</option>
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <input type="text" name="crops" class="form-control" placeholder="Enter crop name" required>
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <input type="number" step="0.01" name="area" placeholder="Area in Hectares" required class="form-control">
                    </div>
                  </td>
                  <td>
                    <center>
                      <div class="form-group">
                        <button type="submit" value="Yield" name="Yield_Predict" class="btn btn-success btn-submit">Predict</button>
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
      if(isset($_POST['Yield_Predict'])) {
        echo '<div class="card text-black bg-gradient-success mb-3">
          <div class="card-header">
            <span class="text-success display-4">Result</span>
          </div>
          <div class="card-body">';

        $state = trim($_POST['state']);
        $district = trim($_POST['district']);
        $season = trim($_POST['Season']);
        $crops = trim($_POST['crops']);
        $area = trim($_POST['area']);

        // Use the API instead of Python script
        $api = new YieldPredictionAPI();
        $result = $api->getYieldPrediction($state, $district, $season, $crops, $area);

        if (isset($result['error'])) {
            $formatted_output = "Error: " . $result['error'];
            $has_error = true;
        } else {
            $formatted_output = number_format($result['yield'], 2);
            $explanation = $result['explanation'];
            $has_error = false;
        }
        ?>
        <div class="prediction-container">
          <h4 class="prediction-title">Yield Prediction Results</h4>
          <div class="prediction-details">
            <div class="prediction-card">
              <div class="prediction-label">For <?php echo $district; ?> (<?php echo $season; ?> Season)</div>
              <div class="prediction-label">Crop: <?php echo ucfirst($crops); ?></div>
              <div class="prediction-label">Area: <?php echo $area; ?> Hectares</div>
              <div class="prediction-value">
                <?php 
                if (!$has_error) {
                    echo $formatted_output . " Quintals";
                    echo '<div class="prediction-explanation mt-3">' . htmlspecialchars($explanation) . '</div>';
                } else {
                    echo '<span style="color: #dc3545;">' . $formatted_output . '</span>';
                }
                ?>
              </div>
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

<?php require("footer.php");?>
</body>
</html>

