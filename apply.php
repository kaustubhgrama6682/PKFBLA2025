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
    <!-- Include your HTML head content -->
</head>
<body>
    <h1>Apply for: <?php echo htmlspecialchars($job_posting['job_title']); ?></h1>

    <form method="POST">
        <input type="hidden" name="job_id" value="<?php echo $job_posting['id']; ?>">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="phone">Phone:</label>
            <input type="tel" id="phone" name="phone" required>
        </div>
        <div>
            <label for="resume">Resume:</label>
            <input type="file" id="resume" name="resume" required>
        </div>
        <div>
            <label for="cover_letter">Cover Letter:</label>
            <textarea id="cover_letter" name="cover_letter" required></textarea>
        </div>
        <div>
            <label for="education">Education:</label>
            <input type="text" id="education" name="education" required>
        </div>
        <div>
            <label for="work_experience">Work Experience:</label>
            <textarea id="work_experience" name="work_experience" required></textarea>
        </div>
        <div>
            <label for="skills">Skills:</label>
            <textarea id="skills" name="skills" required></textarea>
        </div>
        <button type="submit">Apply</button>
    </form>
</body>
</html>