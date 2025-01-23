<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'db_connection.php';

// Debugging: Check database connection
try {
    // Simple test query
    $test_query = $pdo->query("SELECT 1");
    
    // If you want to test job postings table specifically
    $table_check = $pdo->query("SHOW TABLES LIKE 'job_postings'");
    $table_exists = $table_check->rowCount() > 0;
    
    // If table doesn't exist, create it
    if (!$table_exists) {
        $create_table_sql = "
        CREATE TABLE job_postings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            company_name VARCHAR(255) NOT NULL,
            job_title VARCHAR(255) NOT NULL,
            job_type ENUM('internship', 'part-time', 'full-time', 'entry-level') NOT NULL,
            location VARCHAR(255),
            qualifications TEXT,
            job_description TEXT NOT NULL,
            contact_email VARCHAR(255) NOT NULL,
            contact_phone VARCHAR(20),
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($create_table_sql);
        echo "Table created successfully. ";
    }

    // Simplified statistics query
    $stats_query = $pdo->query("
        SELECT 
            COUNT(*) as total_postings,
            0 as pending_postings,
            0 as approved_postings
    ");
    $stats = $stats_query->fetch(PDO::FETCH_ASSOC);

    // Debug output
    echo "Database connection successful! ";
    print_r($stats);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_styles.css">
</head>
<body>
    <div class="admin-container">
        <header>
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </header>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <h3>Total Job Postings</h3>
                <p><?php echo $stats['total_postings'] ?? 0; ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Postings</h3>
                <p><?php echo $stats['pending_postings'] ?? 0; ?></p>
            </div>
            <div class="stat-card">
                <h3>Approved Postings</h3>
                <p><?php echo $stats['approved_postings'] ?? 0; ?></p>
            </div>
        </div>

        <nav>
            <a href="manage_postings.php" class="nav-btn">Manage Job Postings</a>
            <a href="add_admin.php" class="nav-btn">Add Admin User</a>
        </nav>
    </div>
</body>
</html>