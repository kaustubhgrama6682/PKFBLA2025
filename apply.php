<?php
// apply.php
session_start();
require_once 'db_connection.php';

// Check if student is logged in
if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if not logged in
    header("Location: student_login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate input
        $requiredFields = ['name', 'email', 'phone', 'education', 'work_experience', 'skills', 'job_id', 'cover_letter'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("All fields are required");
            }
        }

        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Handle file upload for resume
        if (!isset($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
            // Get specific upload error
            $uploadErrors = [
                UPLOAD_ERR_OK => 'No errors',
                UPLOAD_ERR_INI_SIZE => 'File too large (php.ini)',
                UPLOAD_ERR_FORM_SIZE => 'File too large (HTML form)',
                UPLOAD_ERR_PARTIAL => 'Partial upload',
                UPLOAD_ERR_NO_FILE => 'No file uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                UPLOAD_ERR_EXTENSION => 'PHP extension stopped file upload'
            ];
            
            $errorMsg = isset($uploadErrors[$_FILES['resume']['error']]) 
                ? $uploadErrors[$_FILES['resume']['error']] 
                : 'Unknown upload error';
            
            throw new Exception("Resume upload failed: " . $errorMsg);
        }

        // Additional file validation
        $maxFileSize = 5 * 1024 * 1024; // 5MB max
        if ($_FILES['resume']['size'] > $maxFileSize) {
            throw new Exception("Resume file is too large. Maximum file size is 5MB.");
        }

        // Validate file type
        $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($_FILES['resume']['type'], $allowedTypes)) {
            throw new Exception("Invalid file type. Only PDF and Word documents are allowed.");
        }

        // Create uploads directory
        $uploadDir = 'uploads/resumes/';
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                throw new Exception("Failed to create upload directory");
            }
        }

        // Generate unique filename
        $fileExt = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileExt;
        $uploadPath = $uploadDir . $fileName;

        // Attempt to move file
        if (!move_uploaded_file($_FILES['resume']['tmp_name'], $uploadPath)) {
            throw new Exception("Failed to move uploaded resume");
        }

        // Prepare and execute database insertion
        $stmt = $pdo->prepare("INSERT INTO applications (
            name, email, phone, resume, cover_letter, 
            education, work_experience, skills, 
            job_id, student_id
        ) VALUES (
            :name, :email, :phone, :resume, :cover_letter, 
            :education, :work_experience, :skills, 
            :job_id, :student_id
        )");
        
        $stmt->execute([
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'resume' => $uploadPath,
            'cover_letter' => $_POST['cover_letter'],
            'education' => $_POST['education'],
            'work_experience' => $_POST['work_experience'],
            'skills' => $_POST['skills'],
            'job_id' => $_POST['job_id'],
            'student_id' => $_SESSION['student_id']  // Use session student_id
        ]);

        // Redirect to success page
        header("Location: success_apply.php");
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch job details
if (!isset($_GET['id'])) {
    die("No job ID provided");
}

$job_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM job_postings WHERE id = :id");
$stmt->execute(['id' => $job_id]);
$job_posting = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job_posting) {
    die("Job posting not found");
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

    <title>Apply for <?php echo htmlspecialchars($job_posting['job_title']); ?></title>

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
                                <a class="nav-link" href="student_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
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
                <h2 style="color:white">Apply for <?php echo htmlspecialchars($job_posting['job_title']); ?></h2>
            </div>

            <!-- Error Handling -->
            <?php if (!empty($error)): ?>
                <div class="row justify-content-center mb-4">
                    <div class="col-md-10">
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

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

    <!-- Remaining sections (info, footer) stay the same as before -->

    <!-- JavaScript -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>
</body>
</html>