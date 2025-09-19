<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.html");
    exit();
}
include "db.php";

$email = $_SESSION['email'];

// Use prepared statement for safety
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch jobs (latest 5)
$jobsQuery = $conn->query("SELECT * FROM jobs ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AI Job Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <nav>
            <a href="profile.php">Profile</a>
            <a href="jobs.php">Jobs</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h2>Recommended Jobs for You</h2>
        <p>Check out AI-generated job recommendations below.</p>

        <?php if($jobsQuery->num_rows > 0): ?>
            <?php while($job = $jobsQuery->fetch_assoc()): ?>
                <div class="job-card">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
                    <small><em>Posted on: <?php echo $job['created_at']; ?></em></small><br>
                    <a href="apply.php?job_id=<?php echo $job['id']; ?>">Apply Now</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No job recommendations available right now.</p>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> AI Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
