<?php
session_start();
require_once 'db_connection.php';

// Fetch reviews
$stmt = $pdo->query("
    SELECT r.*, s.name as student_name 
    FROM reviews r 
    JOIN students s ON r.student_id = s.id 
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch FAQs
$stmt = $pdo->query("
    SELECT * FROM questions 
    WHERE is_faq = TRUE AND status = 'answered' 
    ORDER BY created_at DESC
");
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['student_id'])) {
        header('Location: student_login.php');
        exit();
    }

    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];
    
    $stmt = $pdo->prepare("INSERT INTO reviews (student_id, rating, review_text) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['student_id'], $rating, $review_text]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle question submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_question'])) {
    if (!isset($_SESSION['student_id'])) {
        header('Location: student_login.php');
        exit();
    }

    $question = $_POST['question'];
    
    $stmt = $pdo->prepare("INSERT INTO questions (student_id, question) VALUES (?, ?)");
    $stmt->execute([$_SESSION['student_id'], $question]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
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

    <title>Reviews & FAQ</title>

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
        body {
  background: url('images/background.jpg') no-repeat center center fixed;
  background-size: cover;
}
        .job-card, .review-card, .faq-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .job-card:hover, .review-card:hover, .faq-card:hover {
            transform: translateY(-5px);
        }
        
        .rating {
            color: #4D47C3;
            font-size: 24px;
        }
        
        .star-rating {
            color: #4D47C3;
            font-size: 24px;
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

        .accordion .card {
            border: none;
            margin-bottom: 10px;
        }

        .accordion .card-header {
            background: white;
            padding: 0;
        }

        .accordion .btn-link {
            color: #4D47C3;
            text-decoration: none;
            width: 100%;
            text-align: left;
            padding: 15px;
        }

        .accordion .card-body {
            background: #f8f9fa;
            padding: 20px;
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

        .search-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                            <li class="nav-item">
                                <a class="nav-link" href="job_listings.php">View Postings</a>
                            </li>
                            <li class="nav-item active">
                <a class="nav-link" href="review_faq.php">Reviews & FAQ  <span class="sr-only">(current)</span></a>
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
                                <a class="nav-link" href="review_faq.php">Reviews & FAQ <span class="sr-only">(current)</span></a>
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


    <!-- Main Content Section -->
    <section class="layout_padding">
        <div class="container">
            <div class="heading_container heading_center mb-5">
                <h2 style = "color: white" >Reviews & FAQ</h2>
            </div>

            <!-- Reviews Section -->
            <div class="row mb-5">
                <div class="col-md-8">
                    <div class="card job-card">
                        <div class="card-body">
                            <h3 class="mb-4">Student Reviews</h3>
                            <?php foreach ($reviews as $review): ?>
                                <div class="review-card p-4 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5><?php echo htmlspecialchars($review['student_name']); ?></h5>
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa fa-star<?php echo $i <= $review['rating'] ? '' : '-o'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                                    <small class="text-muted">
                                        Posted on <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card job-card">
                        <div class="card-body">
                            <?php if (isset($_SESSION['student_id'])): ?>
                                <h4>Leave a Review</h4>
                                <form method="POST" class="mt-3">
                                    <div class="form-group">
                                        <label>Rating</label>
                                        <div class="star-rating mb-3">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <input type="radio" name="rating" value="<?php echo $i; ?>" required>
                                                <i class="fa fa-star"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Your Review</label>
                                        <textarea class="form-control" name="review_text" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" name="submit_review" class="btn btn-primary btn-block">
                                        Submit Review
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    Please <a href="student_login.php">login</a> to leave a review.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="row mt-5">
                <div class="col-md-8">
                    <div class="card job-card">
                        <div class="card-body">
                            <h3 class="mb-4">Frequently Asked Questions</h3>
                            <div class="accordion" id="faqAccordion">
                                <?php foreach ($faqs as $index => $faq): ?>
                                    <div class="card faq-card mb-3">
                                        <div class="card-header" id="heading<?php echo $index; ?>">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" 
                                                        data-target="#collapse<?php echo $index; ?>">
                                                    <i class="fa fa-question-circle"></i>
                                                    <?php echo htmlspecialchars($faq['question']); ?>
                                                </button>
                                            </h5>
                                        </div>
                                        <div id="collapse<?php echo $index; ?>" class="collapse" 
                                             data-parent="#faqAccordion">
                                            <div class="card-body">
                                                <i class="fa fa-comment-o"></i>
                                                <?php echo htmlspecialchars($faq['answer']); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card job-card">
                        <div class="card-body">
                            <?php if (isset($_SESSION['student_id'])): ?>
                                <h4>Ask a Question</h4>
                                <form method="POST" class="mt-3">
                                    <div class="form-group">
                                        <label>Your Question</label>
                                        <textarea class="form-control" name="question" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" name="submit_question" class="btn btn-primary btn-block">
                                        Submit Question
                                    </button>
                                </form>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    Please <a href="student_login.php">login</a> to ask a question.
                                </div>
                            <?php endif; ?>
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
    <!-- end footer section -->

    <!-- JavaScript files -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>

</body>
</html>