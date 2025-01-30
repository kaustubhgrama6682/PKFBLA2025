<?php
session_start();
require_once 'db_connection.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

// Fetch student information
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch student's applications
$stmt = $pdo->prepare("
    SELECT a.*, j.job_title, j.company_name, j.job_type 
    FROM applications a 
    JOIN job_postings j ON a.job_id = j.id 
    WHERE a.student_id = ? 
    ORDER BY a.created_at DESC
");
$stmt->execute([$_SESSION['student_id']]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recommended jobs based on student's skills
$stmt = $pdo->prepare("
    SELECT * FROM job_postings 
    WHERE status = 'approved' 
    AND (
        LOWER(qualifications) LIKE ? 
        OR LOWER(job_description) LIKE ?
    )
    ORDER BY created_at DESC 
    LIMIT 5
");
$skills = explode(',', $student['skills']);
$skillsQuery = '%' . strtolower(implode('%', $skills)) . '%';
$stmt->execute([$skillsQuery, $skillsQuery]);
$recommended_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <title>Student Dashboard</title>

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
        .dashboard-card {
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .profile-section {
            padding: 30px;
            text-align: center;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
            border: 3px solid #4D47C3;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .badge {
            padding: 8px 12px;
            border-radius: 20px;
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
        
        .card-header {
            background-color: #4D47C3;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .recommended-job-card {
            transition: transform 0.2s;
        }
        
        .recommended-job-card:hover {
            transform: translateY(-5px);
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
                            <li class="nav-item active">
                                <a class="nav-link" href="student_dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
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

     <!-- Marquee section -->
     <div class="marquee-container">
  <div class="marquee-track">
    <span class="marquee-text">Student Mode</span>
    <span class="marquee-text">Student Mode</span>
    <span class="marquee-text">Student Mode</span>
    <span class="marquee-text">Student Mode</span>
  </div>
</div>
    <!-- Dashboard section -->
    <section class="layout_padding">
        <div class="container">
            <div class="heading_container heading_center mb-5">
                <h2 style = "color: white">Welcome, <?php echo htmlspecialchars($student['name']); ?></h2>
            </div>

            <div class="row">
               <!-- Profile Section -->
<div class="col-md-4">
    <div class="dashboard-card">
        <div class="profile-section">
            <img src="<?php 
                // Use default profile picture if no profile picture exists
                $defaultProfilePic = 'images/default-profile.png';
                $profilePic = (!empty($student['profile_picture']) && file_exists($student['profile_picture'])) 
                    ? $student['profile_picture'] 
                    : $defaultProfilePic;
                echo htmlspecialchars($profilePic);
            ?>" alt="Profile Picture" class="profile-image">
            
            <h4><?php echo htmlspecialchars($student['name']); ?></h4>
            <p class="text-muted"><?php echo htmlspecialchars($student['major']); ?></p>
            <p>Class of <?php echo htmlspecialchars($student['graduation_year']); ?></p>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        </div>
    </div>
</div>

                <!-- Application History -->
                <div class="col-md-8">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0">Application History</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($applications)): ?>
                                <p class="text-center">No applications submitted yet.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Company</th>
                                                <th>Type</th>
                                                <th>Applied Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($applications as $application): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                                                    <td><?php echo htmlspecialchars($application['company_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($application['job_type']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($application['created_at'])); ?></td>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            <?php echo htmlspecialchars($application['status'] ?? 'Pending'); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommended Jobs -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <h5 class="mb-0">Recommended Jobs</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($recommended_jobs)): ?>
                                <p class="text-center">No recommended jobs at this time.</p>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($recommended_jobs as $job): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="card recommended-job-card h-100">
                                                <div class="card-body">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($job['job_title']); ?></h5>
                                                    <h6 class="card-subtitle mb-2 text-muted">
                                                        <?php echo htmlspecialchars($job['company_name']); ?>
                                                    </h6>
                                                    <p class="card-text">
                                                        <?php echo substr(htmlspecialchars($job['job_description']), 0, 100) . '...'; ?>
                                                    </p>
                                                    <a href="apply.php?id=<?php echo $job['id']; ?>" class="btn btn-primary">
                                                        Apply Now
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Copy the info section and footer from job_listings.php -->
    
    <!-- JavaScript -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
</body>
</html>
