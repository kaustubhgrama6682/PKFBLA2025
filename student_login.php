<?php
session_start();
require_once 'db_connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE email = ?");
    $stmt->execute([$email]);
    $student = $stmt->fetch();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_name'] = $student['name'];
        header('Location: student_dashboard.php');
        exit();
    } else {
        $error = 'Invalid email or password';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" href="images/favicon.png" type="">

    <title>Student Login</title>

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
        .login-container {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background: white;
        }
        
        .login-header {
            background: #4D47C3;
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }
        
        .btn-primary {
            background-color: #4D47C3;
            border-color: #4D47C3;
            padding: 12px;
            border-radius: 8px;
        }
        
        .btn-primary:hover {
            background-color: #3d37b3;
            border-color: #3d37b3;
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

        <!-- header section strats -->
        <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              Cougar Connect
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  ">
              <li class="nav-item">
                <a class="nav-link" href="index.html">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="submitpostings.html"> Submit Posting</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="review_faq.php">Reviews & FAQ</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="student_login.php"> <i class="fa fa-user" aria-hidden="true"></i> Student Login <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="admin_login.php"> <i class="fa fa-user" aria-hidden="true"></i> Admin Login</a>
              </li>
              <form class="form-inline">
                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </form>
            </ul>
          </div>
        </nav>
      </div>
    </header>
        <!-- end header section -->
    </div>

    <!-- login section -->
    <section class="layout_padding">
        <div class="container">
            <div class="heading_container heading_center mb-5">
                <h2 style = "color: white">Student Login</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="login-container">
                        <div class="login-card">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            
                            <div class="login-form">
                                <form method="POST">
                                    <div class="form-group">
                                        <label><i class="fa fa-envelope"></i> Email</label>
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fa fa-lock"></i> Password</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                                </form>
                                
                                <div class="text-center mt-4">
                                    <p>Don't have an account? <a href="student_register.php">Register here</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

     <!-- info section -->
     <section class="info_section layout_padding2">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-3 info_col">
          <div class="info_contact">
            <h4>
              Contact Us
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  9130 NE 180th Street
Bothell, WA 98011-3398
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call 425-408-7000
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  careers@nsd.org
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 info_col">
          <div class="info_detail">
            <h4>
              Resources
            </h4>
            <p>
              Access career resources, resume templates, and interview tips to help you succeed in your job search.
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-2 mx-auto info_col">
          <div class="info_link_box">
            <h4>
              Quick Links
            </h4>
            <div class="info_links">
              <a href="index.html">Home</a>
              <a href="job_listings.php">Job Listings</a>
              <a href="student_login.php">Student Login</a>
              <a href="submitpostings.html">Post a Job</a>
              <a href="admin_login.php">Admin Portal</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


    <!-- footer section -->
    <section class="footer_section">
        <div class="container">
            <p>
                &copy; <span id="displayYear"></span> All Rights Reserved By
                <a href="https://html.design/">Free Html Templates</a>
            </p>
        </div>
    </section>

    <!-- JavaScript -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>