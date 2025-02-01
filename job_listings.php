<?php
session_start();
// job_listings.php
require_once 'db_connection.php';

/**
 * Fetches and filters approved job postings from database
 * Implements security measures through prepared statements
 * Includes error handling for database connection issues
 * 
 * @param string $search_query Optional search term for filtering
 * @param string $job_type_filter Optional job type for filtering
 * @return array List of filtered and approved job postings
 */
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

    .bg_img_box img {
  width: 100%;
  height: 100%;
  object-fit: contain; /* Changed from 'cover' to 'contain' */
  object-position: center;
}

.layout_padding {
    /*background-image: url('images/background.jpg');*/
    background-size: contain no-repeat center center fixed;  /* This will show the full image */
    background-repeat: no-repeat;
    background-position: center top;
    min-height: 100vh;        /* This ensures minimum height of viewport */
    margin-bottom: 0;         /* Remove any bottom margin */
    padding-bottom: 50px;     /* Add some padding at bottom */.
}

/* Update card styles to ensure content is readable */
.search-container, .job-card {
    background: rgba(255, 255, 255, 0.95);  /* Slight transparency */
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
     <!-- header section starts -->

     <?php if (isset($_SESSION['student_id'])): ?>
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
                            <li class="nav-item active">
                                <a class="nav-link" href="job_listings.php">View Postings</a>
                            </li>
                            <li class="nav-item">
                <a class="nav-link" href="review_faq.php">Reviews & FAQ</a>
              </li>
                            <li class="nav-item">
                                <a class="nav-link" href="student_dashboard.php">Dashboard </a>
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

        







        <?php else: ?>    
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
                                <a class="nav-link" href="submitpostings.html">Submit Posting</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="job_listings.php">View Postings <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="review_faq.php">Reviews & FAQ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="student_login.php"><i class="fa fa-user" aria-hidden="true"></i> Student Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="admin_login.php"><i class="fa fa-user" aria-hidden="true"></i> Admin Login</a>
                            </li>
                            <form class="form-inline">
                                <button class="btn my-2 my-sm-0 nav_search-btn" type="submit">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </form>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!-- end header section -->
         <?php endif; ?>
</div>
<?php if (isset($_SESSION['student_id'])): ?>
        <div class="marquee-container">
            <div class="marquee-track">
                <span class="marquee-text">Student Mode</span>
                <span class="marquee-text">Student Mode</span>
                <span class="marquee-text">Student Mode</span>
                <span class="marquee-text">Student Mode</span>
            </div>
        </div>

    <?php endif; ?>
  <!-- Replace the "about section" with this new section -->
<section class="layout_padding">
    <div class="container">
        <div class="heading_container heading_center mb-5">
            <h2 style = "color: white">Available Positions</h2>
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
                            <option value="Internship" <?php echo $job_type_filter === 'Internship' ? 'selected' : ''; ?>>Internship</option>
                            <option value="Part-Time" <?php echo $job_type_filter === 'Part-Time' ? 'selected' : ''; ?>>Part-Time</option>
                            <option value="Full-Time" <?php echo $job_type_filter === 'Full-Time' ? 'selected' : ''; ?>>Full-Time</option>
                            <option value="Entry-Level" <?php echo $job_type_filter === 'Entry-Level' ? 'selected' : ''; ?>>Entry-Level</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Cougar Connect Section -->
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
              <a href="job_listings.php">Cougar Connect</a>
              <a href="student_login.php">Student Login</a>
              <a href="submitpostings.html">Post a Job</a>
              <a href="admin_login.php">Admin Portal</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

 
  <!-- end footer section -->
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

  <script>
    document.querySelector('search-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const searchQuery = document.querySelector('input[name="search"]').value;
    try {
        const results = await fetchSearchResults(searchQuery);
        updateJobListings(results);
        updateResultsCount(results.length);
    } catch (error) {
        handleSearchError(error);
    }
});

function updateJobListings(results) {
    const container = document.querySelector('job-listings');
    // Dynamic content update logic
}
</script>
<body>

</html>