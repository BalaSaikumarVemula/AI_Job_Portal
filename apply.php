<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.html");
    exit();
}
include "db.php";

$userEmail = $_SESSION['email'];

if(isset($_GET['job_id'])){
    $job_id = intval($_GET['job_id']);

    // Check if already applied
    $check = mysqli_query($conn, "SELECT * FROM applications WHERE email='$userEmail' AND job_id=$job_id");
    if(mysqli_num_rows($check) > 0){
        $message = "You have already applied for this job.";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO applications (email, job_id) VALUES ('$userEmail', $job_id)");
        if($insert){
            $message = "Application submitted successfully!";
        } else {
            $message = "Failed to submit application.";
        }
    }
} else {
    $message = "Invalid job ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply - AI Job Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Job Application</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="jobs.php">Jobs</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <p><?php echo $message; ?></p>
        <a href="jobs.php">Back to Jobs</a>
    </main>
</body>
</html>
