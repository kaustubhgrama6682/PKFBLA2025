<?php
// success.php
?>


<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> Finexo </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
  <style>
    .job-card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .btn-primary {
        background-color: #4D47C3;
        border-color: #4D47C3;
        padding: 12px 24px;
    }
    
    .btn-primary:hover {
        background-color: #3d37b3;
        border-color: #3d37b3;
        transform: translateY(-2px);
    }
    
    .btn-outline-primary {
        color: #4D47C3;
        border-color: #4D47C3;
        padding: 12px 24px;
    }
    
    .btn-outline-primary:hover {
        background-color: #4D47C3;
        border-color: #4D47C3;
        transform: translateY(-2px);
    }
    
    .heading_container h2 span {
        color: #4D47C3;
    }

    .text-success {
        color: #28a745;
    }
    body {
  background: url('images/background.jpg') no-repeat center center fixed;
  background-size: cover;
}
</style>
</head>

<body class="sub_page">

  <div class="hero_area">

    <div class="hero_bg_box">
      <div class="bg_img_box">
        <img src="images/hero-bg.png" alt="">
      </div>
    </div>

    <header class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <a class="navbar-brand" href="index.html">
                        <span>Cougar Connect</span>
                    </a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                        <span class=""></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.html">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="job_listings.php">View Postings</a>
                            </li>
                            <li class="nav-item">
                <a class="nav-link" href="review_faq.php">Reviews & FAQ</a>
              </li>
                            <li class="nav-item">
                                <a class="nav-link" href="student_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="edit_profile.php">Edit Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="student_logout.php">Logout</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
    <!-- end header section -->
  </div>

  <section class="layout_padding">
    <div class="container">
        <div class="heading_container heading_center mb-5">
            <h2 style = "color:white" >Submission Successful</h2>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card job-card">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fa fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        
                        <h3 class="mb-4">Thank You!</h3>
                        <p class="mb-4">Your application has been received and is pending review. 
                            We will process your submission as soon as possible.</p>
                        
                        <div class="mt-4">
                            <a href="student_dashboard.php" class="btn btn-primary mr-3">
                                <i class="fa fa-plus"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

  <!-- end about section -->

  <!-- jQery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->

</body>

</html>