<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.html");
    exit();
}
include "db.php";

$jobsQuery = mysqli_query($conn, "SELECT * FROM jobs ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - AI Job Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Job Listings</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <?php if(mysqli_num_rows($jobsQuery) > 0){ ?>
            <?php while($job = mysqli_fetch_assoc($jobsQuery)) { ?>
                <div class="job-card">
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                    <a href="apply.php?job_id=<?php echo $job['id']; ?>">Apply</a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p>No jobs available at the moment. Please check back later.</p>
        <?php } ?>
    </main>
</body>
</html>
