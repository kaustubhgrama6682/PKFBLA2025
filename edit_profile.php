<?php
session_start();
require_once 'db_connection.php';

// Add this at the beginning of your PHP code
if (!file_exists('uploads/profiles')) {
    mkdir('uploads/profiles', 0777, true);
}
if (!file_exists('uploads/resumes')) {
    mkdir('uploads/resumes', 0777, true);
}

if (!isset($_SESSION['student_id'])) {
    header('Location: student_login.php');
    exit();
}

// Fetch current student data
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$_SESSION['student_id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $school = $_POST['school'];
    $major = $_POST['major'];
    $graduation_year = $_POST['graduation_year'];
    $skills = $_POST['skills'];

    // Handle profile picture upload
    $newProfilePicture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = 'profile_' . $_SESSION['student_id'] . '.' . $filetype;
            $upload_path = 'uploads/profiles/' . $newname;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                $newProfilePicture = $upload_path;
                chmod($upload_path, 0644); // Make file readable
                error_log("Profile picture uploaded successfully to: " . $upload_path);
            } else {
                $error = "Failed to move uploaded file";
                error_log("Failed to move uploaded file to: " . $upload_path);
            }
        } else {
            $error = "Invalid file type. Allowed types: " . implode(', ', $allowed);
        }
    }

    // Handle resume upload
    $newResume = null;
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $allowed = ['pdf', 'doc', 'docx'];
        $filename = $_FILES['resume']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = 'resume_' . $_SESSION['student_id'] . '.' . $filetype;
            $upload_path = 'uploads/resumes/' . $newname;
            
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $upload_path)) {
                $newResume = $upload_path;
            }
        }
    }

    // Update student profile
    $sql = "UPDATE students SET 
            name = ?, 
            phone = ?, 
            school = ?, 
            major = ?, 
            graduation_year = ?, 
            skills = ?";
    $params = [$name, $phone, $school, $major, $graduation_year, $skills];

    if ($newProfilePicture !== null) {
        $sql .= ", profile_picture = ?";
        $params[] = $newProfilePicture;
    }
    if ($newResume !== null) {
        $sql .= ", resume = ?";
        $params[] = $newResume;
    }

    $sql .= " WHERE id = ?";
    $params[] = $_SESSION['student_id'];

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        // Update the $student array with the new profile picture path
        $student['profile_picture'] = $newProfilePicture ?? $student['profile_picture'];
        $success = 'Profile updated successfully';
    } else {
        $error = 'Failed to update profile';
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

    <title>Edit Profile</title>

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
        }
        
        .btn-primary:hover {
            background-color: #3d37b3;
            border-color: #3d37b3;
            transform: translateY(-2px);
        }

        .section-heading {
            color: #4D47C3;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .section-icon {
            margin-right: 8px;
        }

        .profile-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            border: 3px solid #4D47C3;
        }

        .file-upload {
            position: relative;
            overflow: hidden;
            margin: 10px 0;
        }

        .file-upload input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            cursor: pointer;
            display: block;
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
    <!-- Add the hero area and header section from other pages -->
    <div class="hero_area">
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
                            <li class="nav-item">
                                <a class="nav-link" href="student_dashboard.php">Dashboard</a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="edit_profile.php">Edit Profile <span class="sr-only">(current)</span></a>
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
    <div class="marquee-container">
        <div class="marquee-track">
            <span class="marquee-text">Student Mode</span>
            <span class="marquee-text">Student Mode</span>
            <span class="marquee-text">Student Mode</span>
            <span class="marquee-text">Student Mode</span>
        </div>
    </div>
    <section class="layout_padding">
        <div class="container">
            <div class="heading_container heading_center mb-5">
                <h2 style="color:white">Edit Profile</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card job-card">
                        <div class="card-body p-4">
                            <?php if ($error): ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success">
                                    <i class="fa fa-check-circle"></i> <?php echo $success; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" enctype="multipart/form-data">
                                <!-- Profile Picture Section -->
                                <div class="form-section text-center mb-4">
                                    <img src="<?php 
                                        $defaultProfilePic = 'images/default-profile.png';
                                        $profilePic = (!empty($student['profile_picture']) && file_exists($student['profile_picture'])) 
                                            ? $student['profile_picture'] 
                                            : $defaultProfilePic;
                                        echo htmlspecialchars($profilePic); 
                                    ?>" class="profile-preview" alt="Profile Picture">
                                    
                                    <div class="file-upload mt-3">
                                        <label class="btn btn-outline-primary">
                                            <i class="fa fa-camera"></i> Change Profile Picture
                                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                                        </label>
                                    </div>
                                </div>

                                <!-- Personal Information -->
                                <div class="form-section mb-4">
                                    <h5 class="section-heading">
                                        <i class="fa fa-user section-icon"></i> Personal Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" name="name" 
                                                   value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label>Phone Number</label>
                                            <input type="tel" class="form-control" name="phone" 
                                                   value="<?php echo htmlspecialchars($student['phone']); ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic Information -->
                                <div class="form-section mb-4">
                                    <h5 class="section-heading">
                                        <i class="fa fa-graduation-cap section-icon"></i> Academic Information
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label>School</label>
                                            <input type="text" class="form-control" name="school" 
                                                   value="<?php echo htmlspecialchars($student['school']); ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>Prospective Major</label>
                                            <input type="text" class="form-control" name="major" 
                                                   value="<?php echo htmlspecialchars($student['major']); ?>">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>Graduation Year</label>
                                            <input type="number" class="form-control" name="graduation_year" 
                                                   value="<?php echo htmlspecialchars($student['graduation_year']); ?>">
                                        </div>
                                    </div>
                                </div>


                                <!-- Skills and Resume -->
                                <div class="form-section mb-4">
                                    <h5 class="section-heading">
                                        <i class="fa fa-file-text section-icon"></i> Skills
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label>Skills (comma-separated)</label>
                                            <textarea class="form-control" name="skills" rows="3"><?php echo htmlspecialchars($student['skills']); ?></textarea>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Update Profile
                                    </button>
                                    <a href="student_dashboard.php" class="btn btn-outline-secondary ml-2">
                                        <i class="fa fa-arrow-left"></i> Back to Dashboard
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/custom.js"></script>
    <script>
document.getElementById('profile_picture').onchange = function(e) {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('.profile-preview');
            img.src = e.target.result;
            console.log('File preview loaded:', e.target.result);
        }
        reader.onerror = function(e) {
            console.error('File reading error:', e);
        }
        reader.readAsDataURL(file);
    }
};
</script>
</body>
</html>