<?php
// submit_job.php
require_once 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $companyName = filter_input(INPUT_POST, 'companyName', FILTER_SANITIZE_STRING);
    $jobTitle = filter_input(INPUT_POST, 'jobTitle', FILTER_SANITIZE_STRING);
    $jobType = filter_input(INPUT_POST, 'jobType', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $qualifications = filter_input(INPUT_POST, 'qualifications', FILTER_SANITIZE_STRING);
    $jobDescription = filter_input(INPUT_POST, 'jobDescription', FILTER_SANITIZE_STRING);
    $contactEmail = filter_input(INPUT_POST, 'contactEmail', FILTER_SANITIZE_EMAIL);
    $contactPhone = filter_input(INPUT_POST, 'contactPhone', FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO job_postings 
                (company_name, job_title, job_type, location, qualifications, 
                job_description, contact_email, contact_phone, status) 
                VALUES 
                (:company_name, :job_title, :job_type, :location, :qualifications, 
                :job_description, :contact_email, :contact_phone, 'pending')";
        
        // Prepare and execute the statement
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':company_name', $companyName);
        $stmt->bindParam(':job_title', $jobTitle);
        $stmt->bindParam(':job_type', $jobType);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':qualifications', $qualifications);
        $stmt->bindParam(':job_description', $jobDescription);
        $stmt->bindParam(':contact_email', $contactEmail);
        $stmt->bindParam(':contact_phone', $contactPhone);
        
        // Execute the statement
        $stmt->execute();
        
        // Redirect with success message
        header("Location: success.php");
        exit();
    } catch(PDOException $e) {
        // Log error and show user-friendly message
        error_log("Submission error: " . $e->getMessage());
        die("Sorry, there was an error submitting your job posting. Please try again later.");
    }
} else {
    // Redirect if accessed directly without POST
    header("Location: index.html");
    exit();
}
?>