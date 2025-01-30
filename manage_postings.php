<?php
// manage_postings.php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'db_connection.php';

// Handle posting actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve'])) {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $stmt = $pdo->prepare("UPDATE job_postings SET status = 'approved' WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif (isset($_POST['reject'])) {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $stmt = $pdo->prepare("UPDATE job_postings SET status = 'rejected' WHERE id = :id");
        $stmt->execute(['id' => $id]);
    } elseif (isset($_POST['delete'])) {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $stmt = $pdo->prepare("DELETE FROM job_postings WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

// Fetch job postings
$status = isset($_GET['status']) ? $_GET['status'] : 'pending';
$stmt = $pdo->prepare("SELECT * FROM job_postings WHERE status = :status ORDER BY created_at DESC");
$stmt->execute(['status' => $status]);
$postings = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    .search-container {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .job-card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .job-card:hover {
        transform: translateY(-5px);
    }
    
    .badge {
        padding: 8px 12px;
        font-size: 0.9em;
    }
    
    .badge-primary {
        background-color: #4D47C3;
    }
    
    .btn-primary {
        background-color: #4D47C3;
        border-color: #4D47C3;
    }
    
    .btn-primary:hover {
        background-color: #3d37b3;
        border-color: #3d37b3;
    }
    
    .btn-outline-primary {
        color: #4D47C3;
        border-color: #4D47C3;
    }
    
    .btn-outline-primary:hover {
        background-color: #4D47C3;
        border-color: #4D47C3;
    }
    
    .heading_container h2 span {
        color: #4D47C3;
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
                <a class="nav-link" href="manage_review_faq.php">Manage Reviews & FAQ</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="manage_postings.php">Manage Postings  <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="add_admin.php">Add Admin</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php"> <i class="fa fa-user" aria-hidden="true"></i> Logout</a>
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
  <div class="marquee-container">
            <div class="marquee-track">
                <span class="marquee-text">Admin Mode</span>
                <span class="marquee-text">Admin Mode</span>
                <span class="marquee-text">Admin Mode</span>
                <span class="marquee-text">Admin Mode</span>
            </div>
        </div>
  <!-- Replace the "about section" with this updated section -->
<section class="layout_padding">
    <div class="container">
        <div class="heading_container heading_center mb-5">
            <h2 style="color:white">Manage Postings</h2>
        </div>

        <!-- Filter Navigation -->
        <div class="search-container mb-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center mb-4">
                        <a href="?status=pending" class="btn btn-<?php echo $status == 'pending' ? 'primary' : 'outline-primary'; ?> mx-2">
                            Pending
                        </a>
                        <a href="?status=approved" class="btn btn-<?php echo $status == 'approved' ? 'primary' : 'outline-primary'; ?> mx-2">
                            Approved
                        </a>
                        <a href="?status=rejected" class="btn btn-<?php echo $status == 'rejected' ? 'primary' : 'outline-primary'; ?> mx-2">
                            Rejected
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Postings List -->
        <div class="row">
            <?php if (empty($postings)): ?>
                <div class="col-12 text-center mt-5">
                    <div class="alert alert-info">
                        No job postings found in this category.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($postings as $posting): ?>
                    <div class="col-12 mb-4">
                        <div class="card job-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h3 class="card-title"><?php echo htmlspecialchars($posting['job_title']); ?></h3>
                                        <h5 class="text-muted mb-3"><?php echo htmlspecialchars($posting['company_name']); ?></h5>
                                    </div>
                                    <div class="col-md-4 text-md-right">
                                        <span class="badge badge-primary"><?php echo htmlspecialchars($posting['job_type']); ?></span>
                                        <?php if (!empty($posting['location'])): ?>
                                            <div class="mt-2">
                                                <i class="fa fa-map-marker"></i> 
                                                <?php echo htmlspecialchars($posting['location']); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <hr>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <?php if (!empty($posting['qualifications'])): ?>
                                            <div class="mb-3">
                                                <h6><i class="fa fa-graduation-cap"></i> Qualifications:</h6>
                                                <p><?php echo htmlspecialchars($posting['qualifications']); ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mb-3">
                                            <h6><i class="fa fa-file-text"></i> Job Description:</h6>
                                            <p><?php echo htmlspecialchars($posting['job_description']); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <small class="text-muted">
                                            <i class="fa fa-calendar"></i> 
                                            Posted: <?php echo date('M d, Y', strtotime($posting['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <form method="POST" class="d-inline-block">
                                            <input type="hidden" name="id" value="<?php echo $posting['id']; ?>">
                                            
                                            <?php if ($status == 'pending'): ?>
                                                <button type="submit" name="approve" class="btn btn-success btn-sm">
                                                    <i class="fa fa-check"></i> Approve
                                                </button>
                                                <button type="submit" name="reject" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-times"></i> Reject
                                                </button>
                                            <?php endif; ?>
                                            
                                            <button type="submit" name="delete" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this posting?');">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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

  <!-- end info section -->

  

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