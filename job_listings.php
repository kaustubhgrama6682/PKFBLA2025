<?php
// job_listings.php
require_once 'db_connection.php';

// Fetch approved job postings
try {
    $stmt = $pdo->query("SELECT * FROM job_postings WHERE status = 'approved' ORDER BY created_at DESC");
    $job_postings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $job_postings = [];
    $error = "Unable to retrieve job postings: " . $e->getMessage();
}

// Filter and search functionality
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$job_type_filter = isset($_GET['job_type']) ? $_GET['job_type'] : '';

if (!empty($search_query) || !empty($job_type_filter)) {
    $filtered_postings = array_filter($job_postings, function($posting) use ($search_query, $job_type_filter) {
        $match_search = empty($search_query) || 
            stripos($posting['job_title'], $search_query) !== false || 
            stripos($posting['company_name'], $search_query) !== false ||
            stripos($posting['location'], $search_query) !== false;
        
        $match_type = empty($job_type_filter) || $posting['job_type'] === $job_type_filter;
        
        return $match_search && $match_type;
    });
} else {
    $filtered_postings = $job_postings;
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
    
    .heading_container h2 span {
        color: #4D47C3;
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
              Job Listings
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
              <li class="nav-item active">
                <a class="nav-link" href="job_listings.php">View Postings <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="review_faq.php">Reviews & FAQ</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="student_login.php"> <i class="fa fa-user" aria-hidden="true"></i> Student Login</a>
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

  <!-- Replace the "about section" with this new section -->
<section class="layout_padding">
    <div class="container">
        <div class="heading_container heading_center mb-5">
            <h2>Available <span>Positions</span></h2>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-container mb-4">
            <form method="GET" class="search-form">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search jobs..." 
                            value="<?php echo htmlspecialchars($search_query); ?>"
                        >
                    </div>
                    <div class="col-md-3">
                        <select name="job_type" class="form-control">
                            <option value="">All Job Types</option>
                            <option value="internship" <?php echo $job_type_filter === 'internship' ? 'selected' : ''; ?>>Internship</option>
                            <option value="part-time" <?php echo $job_type_filter === 'part-time' ? 'selected' : ''; ?>>Part-Time</option>
                            <option value="full-time" <?php echo $job_type_filter === 'full-time' ? 'selected' : ''; ?>>Full-Time</option>
                            <option value="entry-level" <?php echo $job_type_filter === 'entry-level' ? 'selected' : ''; ?>>Entry-Level</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Job Listings Section -->
        <div class="row">
            <?php if (empty($filtered_postings)): ?>
                <div class="col-12 text-center mt-5">
                    <div class="alert alert-info">
                        No job postings found. Try adjusting your search criteria.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($filtered_postings as $posting): ?>
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
                                        <a href="mailto:<?php echo htmlspecialchars($posting['contact_email']); ?>" 
                                           class="btn btn-outline-secondary btn-sm mr-2">
                                            <i class="fa fa-envelope"></i> Contact
                                        </a>
                                        <a href="apply.php?id=<?php echo $posting['id']; ?>" 
                                           class="btn btn-primary btn-sm">
                                            Apply Now
                                        </a>
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
              Address
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Location
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call +01 1234567890
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  demo@gmail.com
                </span>
              </a>
            </div>
          </div>
          <div class="info_social">
            <a href="">
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-twitter" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-linkedin" aria-hidden="true"></i>
            </a>
            <a href="">
              <i class="fa fa-instagram" aria-hidden="true"></i>
            </a>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 info_col">
          <div class="info_detail">
            <h4>
              Info
            </h4>
            <p>
              necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-2 mx-auto info_col">
          <div class="info_link_box">
            <h4>
              Links
            </h4>
            <div class="info_links">
              <a class="active" href="index.html">
                Home
              </a>
              <a class="" href="about.html">
                About
              </a>
              <a class="" href="service.html">
                Services
              </a>
              <a class="" href="why.html">
                Why Us
              </a>
              <a class="" href="team.html">
                Team
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 info_col ">
          <h4>
            Subscribe
          </h4>
          <form action="#">
            <input type="text" placeholder="Enter email" />
            <button type="submit">
              Subscribe
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- end info section -->

  <!-- footer section -->
  <section class="footer_section">
    <div class="container">
      <p>
        &copy; <span id="displayYear"></span> All Rights Reserved By
        <a href="https://html.design/">Free Html Templates</a>
      </p>
    </div>
  </section>
  <!-- footer section -->

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