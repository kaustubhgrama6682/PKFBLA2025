<?php
session_start();
require_once 'db_connection.php';

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
    $degree = $_POST['degree'];
    $major = $_POST['major'];
    $graduation_year = $_POST['graduation_year'];
    $skills = $_POST['skills'];

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = 'profile_' . $_SESSION['student_id'] . '.' . $filetype;
            $upload_path = 'uploads/profiles/' . $newname;
            
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                $profile_picture = $upload_path;
            }
        }
    }

    // Handle resume upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $allowed = ['pdf', 'doc', 'docx'];
        $filename = $_FILES['resume']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $newname = 'resume_' . $_SESSION['student_id'] . '.' . $filetype;
            $upload_path = 'uploads/resumes/' . $newname;
            
            if (move_uploaded_file($_FILES['resume']['tmp_name'], $upload_path)) {
                $resume = $upload_path;
            }
        }
    }

    // Update student profile
    $sql = "UPDATE students SET 
            name = ?, 
            phone = ?, 
            degree = ?, 
            major = ?, 
            graduation_year = ?, 
            skills = ?";
    $params = [$name, $phone, $degree, $major, $graduation_year, $skills];

    if (isset($profile_picture)) {
        $sql .= ", profile_picture = ?";
        $params[] = $profile_picture;
    }
    if (isset($resume)) {
        $sql .= ", resume = ?";
        $params[] = $resume;
    }

    $sql .= " WHERE id = ?";
    $params[] = $_SESSION['student_id'];

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        $success = 'Profile updated successfully';
    } else {
        $error = 'Failed to update profile';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Profile</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($student['name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($student['phone']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="degree">Degree</label>
                                <input type="text" class="form-control" id="degree" name="degree" 
                                       value="<?php echo htmlspecialchars($student['degree']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="major">Major</label>
                                <input type="text" class="form-control" id="major" name="major" 
                                       value="<?php echo htmlspecialchars($student['major']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="graduation_year">Graduation Year</label>
                                <input type="number" class="form-control" id="graduation_year" name="graduation_year" 
                                       value="<?php echo htmlspecialchars($student['graduation_year']); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="skills">Skills (comma-separated)</label>
                                <textarea class="form-control" id="skills" name="skills" rows="3"><?php echo htmlspecialchars($student['skills']); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="profile_picture">Profile Picture</label>
                                <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
                            </div>
                            
                            <div class="form-group">
                                <label for="resume">Resume</label>
                                <input type="file" class="form-control-file" id="resume" name="resume">
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                                <a href="student_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>