<?php
include ('fsession.php');
ini_set('memory_limit', '-1');

if(!isset($_SESSION['farmer_login_user'])){
    header("location: ../index.php");
} // Redirecting To Home Page

$query4 = "SELECT * from farmerlogin where email='$user_check'";
$ses_sq4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($ses_sq4);
$para1 = $row4['farmer_id'];
$para2 = $row4['farmer_name'];

$apiKey = ""; //Your API KEY 
$url = "https://api.currentsapi.services/v1/latest-news?apiKey=$apiKey&country=in";

$newsdata = null;
$api_error = null;
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($response !== false && $httpcode == 200) {
        $newsdata = json_decode($response);
        if (isset($newsdata->status) && $newsdata->status != "ok") {
            $api_error = isset($newsdata->message) ? $newsdata->message : "Unknown API error";
        }
    } else {
        $api_error = "HTTP Error: $httpcode";
    }
    curl_close($ch);
} catch (Exception $e) {
    $api_error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<?php require ('fheader.php');  ?>

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

    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card text-white bg-gradient-success mb-3">
                    <div class="card-header d-flex align-items-center justify-content-center py-4">
                        <h2 class="text-white mb-0 buy-crops-title">AgriHub News Feed</h2>
                    </div>
                    <div class="card-body text-dark">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered bg-gradient-white text-center display" id="myTable">
                                <thead>
                                    <tr class="font-weight-bold text-default">
                                        <th><center>Title</center></th>
                                        <th><center>Published</center></th>
                                        <th><center>Visit</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($api_error) {
                                        echo '<tr><td colspan="3" class="text-center text-danger">API Error: ' . htmlspecialchars($api_error) . '</td></tr>';
                                    } elseif ($newsdata && isset($newsdata->news) && is_array($newsdata->news) && count($newsdata->news) > 0) {
                                        $count = 0;
                                        foreach ($newsdata->news as $news) {
                                            if ($count >= 20) break;
                                            echo '<tr>';
                                            echo '<td class="text-wrap text-justify">' . htmlspecialchars($news->title) . '</td>';
                                            echo '<td class="text-justify">' . (isset($news->published) ? htmlspecialchars($news->published) : '') . '</td>';
                                            echo '<td><a href="' . htmlspecialchars($news->url) . '" class="btn btn-farmer" target="_blank">Read More</a></td>';
                                            echo '</tr>';
                                            $count++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="3" class="text-center">Unable to fetch news at this time. Please try again later.</td></tr>';
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
    $(document).ready(function () {
        $('#myTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[1, 'desc']]
        });
    });
</script>
</body>
</html>