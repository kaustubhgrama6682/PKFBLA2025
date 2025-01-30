<?php
// apply.php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $resume = $_POST['resume'];
    $cover_letter = $_POST['cover_letter'];
    $education = $_POST['education'];
    $work_experience = $_POST['work_experience'];
    $skills = $_POST['skills'];
    $job_id = $_POST['job_id'];

    $stmt = $pdo->prepare("INSERT INTO applications (name, email, phone, resume, cover_letter, education, work_experience, skills, job_id) VALUES (:name, :email, :phone, :resume, :cover_letter, :education, :work_experience, :skills, :job_id)");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'resume' => $resume,
        'cover_letter' => $cover_letter,
        'education' => $education,
        'work_experience' => $work_experience,
        'skills' => $skills,
        'job_id' => $job_id
    ]);

    header("Location: success.php");
    exit;
}

$job_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM job_postings WHERE id = :id");
$stmt->execute(['id' => $job_id]);
$job_posting = $stmt->fetch(PDO::FETCH_ASSOC);
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

    <title>Apply for Position</title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />

    <style>
        body {
  background: url('images/background.jpg') no-repeat center center fixed;
  background-size: cover;
}
        .job-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .form-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
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
            font-size: 1rem;
        }
        
        .btn-primary:hover {
            background-color: #3d37b3;
            border-color: #3d37b3;
            transform: translateY(-2px);
        }
        
        .required-field::after {
            content: "*";
            color: #dc3545;
            margin-left: 4px;
        }
        
        .heading_container h2 span {
            color: #4D47C3;
        }

        .form-section h5 {
            color: #4D47C3;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .form-section h5 i {
            margin-right: 8px;
        }

        .job-details {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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

        <!-- header section -->
        <header class="header_section">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg custom_nav-container">
                    <a class="navbar-brand" href="index.html">
                        <span>Job Listings</span>
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
                            <li class="nav-item">
                                <a class="nav-link" href="job_listings.php">View Postings</a>
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
                <h2 style = "color:white" >Apply for Position</h2>
                <p style = "color:white">You are applying for: <?php echo htmlspecialchars($job_posting['job_title']); ?> at <?php echo htmlspecialchars($job_posting['company_name']); ?></p>
            </div>

            <!-- Job Details Summary -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="job-details">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-2"><?php echo htmlspecialchars($job_posting['job_title']); ?></h5>
                                <p class="mb-1"><?php echo htmlspecialchars($job_posting['company_name']); ?></p>
                                <p class="mb-0"><i class="fa fa-map-marker"></i> <?php echo htmlspecialchars($job_posting['location']); ?></p>
                            </div>
                            <span class="badge badge-primary"><?php echo htmlspecialchars($job_posting['job_type']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card job-card">
                        <div class="card-body p-4">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="job_id" value="<?php echo $job_posting['id']; ?>">

                                <!-- Personal Information -->
                                <div class="form-section mb-4">
                                    <h5><i class="fa fa-user"></i> Personal Information</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="required-field">Full Name</label>
                                            <input type="text" class="form-control" name="name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="required-field">Email</label>
                                            <input type="email" class="form-control" name="email" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="required-field">Phone Number</label>
                                            <input type="tel" class="form-control" name="phone" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Education and Experience -->
                                <div class="form-section mb-4">
                                    <h5><i class="fa fa-graduation-cap"></i> Education & Experience</h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="required-field">Education</label>
                                            <textarea class="form-control" name="education" rows="3" required></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="required-field">Work Experience</label>
                                            <textarea class="form-control" name="work_experience" rows="4" required></textarea>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="required-field">Skills</label>
                                            <textarea class="form-control" name="skills" rows="3" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Application Materials -->
                                <div class="form-section mb-4">
                                    <h5><i class="fa fa-file-text"></i> Application Materials</h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="required-field">Resume</label>
                                            <input type="file" class="form-control" name="resume" required>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label class="required-field">Cover Letter</label>
                                            <textarea class="form-control" name="cover_letter" rows="6" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-paper-plane"></i> Submit Application
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include the same footer as submitpostings.html -->
    <!-- info section -->
    <section class="info_section layout_padding2">
        <!-- Copy the entire info section from submitpostings.html -->
    </section>

    <!-- footer section -->
    <section class="footer_section">
        <div class="container">
            <p>&copy; <span id="displayYear"></span> All Rights Reserved</p>
        </div>
    </section>

    <!-- JavaScript -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
</body>
</html>