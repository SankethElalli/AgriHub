<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="assets/img/logo.png" />
  <title>AgriHub</title>

  <!-- Fonts and icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
  integrity="sha384-omKxF1yAglAmlnO4UI/2hG8TjZpPaMcb0OnWGVPOLOHGQy+pV5cSNXjNmVItKkHA" crossorigin="anonymous">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" 
  integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css"/>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
  
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/creativetim.min.css" type="text/css">
  <link rel="stylesheet" href="assets/css/custom.css" type="text/css">
  <link rel="stylesheet" href="assets/css/footer.css" type="text/css">

</head>

<body class="bg-white" id="top" onload="myFunction()">
  <!-- Navbar -->
  <nav id="navbar-main" class="navbar navbar-main navbar-expand-lg bg-default navbar-light position-sticky top-0 shadow py-0">
    <div class="container">
      <ul class="navbar-nav navbar-nav-hover align-items-lg-center">
        <li class="nav-item dropdown">
          <a href="../index.php" class="navbar-brand text-white" style="white-space: nowrap; font-size: 2rem; font-weight: bold; font-family: 'Arial', sans-serif;">
            AGRIHUB
          </a>
        </li>
      </ul>

      <button class="navbar-toggler bg-white" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon text-white"></span>
      </button>
      <div class="navbar-collapse collapse bg-default" id="navbar_global">
        <div class="navbar-collapse-header">
          <div class="row">
            <div class="col-10 collapse-brand">
              <a href="../index.php" class="navbar-brand text-white" style="white-space: nowrap; font-size: 2rem; font-weight: bold; font-family: 'Arial', sans-serif;">
                AGRIHUB
              </a>
            </div>
            <div class="col-2 collapse-close bg-danger">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>

        <ul class="navbar-nav align-items-lg-center ml-auto">
          <li class="nav-item">
            <a href="contact.php" class="nav-link">
              <span class="text-white nav-link-inner--text"><i class="text-white fas fa-address-card"></i> Contact</span>
            </a>
          </li>
          <li class="nav-item">
            <div class="dropdown show">
              <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-white nav-link-inner--text"><i class="text-white fas fa-user-plus"></i> Sign Up</span>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="farmer/fregister.php">Farmer</a>
                <a class="dropdown-item" href="customer/cregister.php">Customer</a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <div class="dropdown show">
              <a class="nav-link dropdown-toggle text-white" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-white nav-link-inner--text"><i class="text-white fas fa-sign-in-alt"></i> Login</span>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="farmer/flogin.php">Farmer</a>
                <a class="dropdown-item" href="customer/clogin.php">Customer</a>
                <a class="dropdown-item" href="admin/alogin.php">Admin</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <div class="video-container">
    <video autoplay loop muted playsinline>
      <source src="assets/img/crop.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
    <div class="overlay-content">
      <div class="text-overlay" onclick="scrollToFeatures()">
        <h1 class="text-white fw-bold">Cultivating the Future with Intelligence.</h1>
        <p class="text-white">
          <!-- Add any additional text here -->
        </p>
      </div>
    </div>
  </div>

  <!-- Page Content -->
  <div class="wrapper">
    <!-- ======================================================================================================================================== -->
    <section class="section features-2" id="features">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-5 col-md-8 mr-auto text-left">
            <div class="features-content pr-md-5">
              <h2 class="display-3 font-weight-bold">Features</h2>
              <p class="lead text-muted">
                The time is now for the next step in farming. We bring you the future of farming along with great tools for assisting the farmers.
              </p>
              <ul class="features-list list-unstyled mt-5">
                <li class="py-3">
                  <div class="d-flex align-items-center">
                    <div class="icon-circle">
                      <i class="ni ni-settings-gear-65"></i>
                    </div>
                    <div class="ml-3">
                      <h6 class="mb-0">Highly Reliable and Accurate</h6>
                    </div>
                  </div>
                </li>
                <li class="py-3">
                  <div class="d-flex align-items-center">
                    <div class="icon-circle">
                      <i class="ni ni-html5"></i>
                    </div>
                    <div class="ml-3">
                      <h6 class="mb-0">Faster & Responsive Website</h6>
                    </div>
                  </div>
                </li>
                <li class="py-3">
                  <div class="d-flex align-items-center">
                    <div class="icon-circle">
                      <i class="ni ni-settings-gear-65"></i>
                    </div>
                    <div class="ml-3">
                      <h6 class="mb-0">Real Time Weather Forecast</h6>
                    </div>
                  </div>
                </li>
                <li class="py-3">
                  <div class="d-flex align-items-center">
                    <div class="icon-circle">
                      <i class="ni ni-satisfied"></i>
                    </div>
                    <div class="ml-3">
                      <h6 class="mb-0">Integrated News Feature</h6>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======================================================================================================================================== -->
    <?php require("footer.php");?>

    <script>
      function scrollToFeatures() {
        document.getElementById('features').scrollIntoView({ behavior: 'smooth' });
      }
    </script>
    
  </div>
</body>
</html>