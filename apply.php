<?php
// apply.php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $name = $_POST['name'];
    $email = $_POST['email'];
    $resume = $_POST['resume'];
    $job_id = $_POST['job_id'];

    // Validate and save the application to the database
    $stmt = $pdo->prepare("INSERT INTO applications (name, email, resume, job_id) VALUES (:name, :email, :resume, :job_id)");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'resume' => $resume,
        'job_id' => $job_id
    ]);

    // Redirect the user to a success page or display a success message
    header("Location: apply_success.php");
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
            <label for="resume">Resume:</label>
            <input type="file" id="resume" name="resume" required>
        </div>
        <button type="submit">Apply</button>
    </form>
</body>
</html>