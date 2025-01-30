<?php
session_start();
require_once 'db_connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        try {
            // Check if email already exists
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = :email");
            $stmt->execute(['email' => $email]);
            
            if ($stmt->rowCount() > 0) {
                $error = "Email already registered";
            } else {
                // Hash password
                $password_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insert new student
                $insert_stmt = $pdo->prepare("INSERT INTO students (name, email, password) VALUES (:name, :email, :password)");
                $insert_stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password_hash
                ]);

                // Set session and redirect
                $_SESSION['student_id'] = $pdo->lastInsertId();
                $_SESSION['student_name'] = $name;
                header('Location: student_dashboard.php');
                exit();
            }
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
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
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> Student Registration </title>

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
    
    .form-control {
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e9ecef;
    }
    
    .form-control:focus {
        border-color: #4D47C3;
        box-shadow: 0 0 0 0.2rem rgba(77, 71, 195, 0.25);
    }
    
    .btn-primary {
        background-color: #4D47C3;
        border-color: #4D47C3;
        padding: 12px 24px;
    }
    
    .btn-primary:hover {
        background-color: #3d37b3;
        border-color: #3d37b3;
    }
    
    .heading_container h2 span {
        color: #4D47C3;
    }

    .alert {
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    body {
        background: url('images/background.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .marquee-container {
        width: 100%;
        overflow: hidden;
        background: #4D47C3;
        padding: 10px 0;
        margin-bottom: 20px;
        position: relative;
        white-space: nowrap;
    }

    .marquee-track {
        display: inline-block;
        white-space: nowrap;
        animation: marquee 10s linear infinite;
    }

    .marquee-text {
        color: white;
        font-size: 18px;
        font-weight: bold;
        display: inline-block;
        padding: 0 40px;
    }

    @keyframes marquee {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .login-link {
        color: #4D47C3;
        font-weight: bold;
    }

    .login-link:hover {
        color: #3d37b3;
        text-decoration: underline;
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

    <!-- header section starts -->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container">
          <a class="navbar-brand" href="index.html">
            <span>
              Cougar Connect
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="index.html">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="student_login.php">Login</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <div class="marquee-container">
    <div class="marquee-track">
      <span class="marquee-text">Student Registration</span>
      <span class="marquee-text">Student Registration</span>
      <span class="marquee-text">Student Registration</span>
      <span class="marquee-text">Student Registration</span>
    </div>
  </div>

  <!-- Registration section -->
  <section class="layout_padding">
    <div class="container">
      <div class="heading_container heading_center mb-5">
        <h2 style="color:white">Student Registration</h2>
      </div>

      <!-- Registration Form -->
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card job-card">
            <div class="card-body">
              <?php if($error): ?>
                <div class="alert alert-danger">
                  <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
              <?php endif; ?>

              <form method="POST" class="p-3">
                <div class="form-group row mb-4">
                  <label for="name" class="col-sm-3 col-form-label">
                    <i class="fa fa-user"></i> Full Name
                  </label>
                  <div class="col-sm-9">
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           required
                           placeholder="Enter your full name">
                  </div>
                </div>

                <div class="form-group row mb-4">
                  <label for="email" class="col-sm-3 col-form-label">
                    <i class="fa fa-envelope"></i> Email
                  </label>
                  <div class="col-sm-9">
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           required
                           placeholder="Enter your email">
                  </div>
                </div>

                <div class="form-group row mb-4">
                  <label for="password" class="col-sm-3 col-form-label">
                    <i class="fa fa-lock"></i> Password
                  </label>
                  <div class="col-sm-9">
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           required
                           placeholder="Enter password">
                  </div>
                </div>

                <div class="form-group row mb-4">
                  <label for="confirm_password" class="col-sm-3 col-form-label">
                    <i class="fa fa-lock"></i> Confirm
                  </label>
                  <div class="col-sm-9">
                    <input type="password" 
                           class="form-control" 
                           id="confirm_password" 
                           name="confirm_password" 
                           required
                           placeholder="Confirm password">
                  </div>
                </div>

                <div class="row mt-4">
                  <div class="col-sm-9 offset-sm-3">
                    <button type="submit" class="btn btn-primary">
                      <i class="fa fa-user-plus"></i> Register
                    </button>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-sm-9 offset-sm-3">
                    <p>Already have an account? <a href="student_login.php" class="login-link">Login here</a></p>
                  </div>
                </div>
              </form>
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
            <h4>Address</h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>Location</span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>Call +01 1234567890</span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>demo@gmail.com</span>
              </a>
            </div>
          </div>
          <div class="info_social">
            <a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a>
            <a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a>
            <a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a>
            <a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 info_col">
          <div class="info_detail">
            <h4>Info</h4>
            <p>necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-2 mx-auto info_col">
          <div class="info_link_box">
            <h4>Links</h4>
            <div class="info_links">
              <a class="active" href="index.html">Home</a>
              <a class="" href="about.html">About</a>
              <a class="" href="service.html">Services</a>
              <a class="" href="why.html">Why Us</a>
              <a class="" href="team.html">Team</a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 info_col ">
          <h4>Subscribe</h4>
          <form action="#">
            <input type="text" placeholder="Enter email" />
            <button type="submit">Subscribe</button>
          </form>
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