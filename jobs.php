<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.html");
    exit();
}
include "db.php";

$email = $_SESSION['email'];

// Fetch user's applied job IDs
$appliedQuery = mysqli_query($conn, "SELECT job_id FROM applications WHERE email='$email'");
$appliedJobs = [];
while($row = mysqli_fetch_assoc($appliedQuery)){
    $appliedJobs[] = $row['job_id'];
}

// Fetch all jobs
$jobsQuery = mysqli_query($conn, "SELECT * FROM jobs ORDER BY created_at DESC");
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
        <a href="jobs.php">Jobs</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <?php if(mysqli_num_rows($jobsQuery) > 0){ ?>
        <?php while($job = mysqli_fetch_assoc($jobsQuery)){ 
            $alreadyApplied = in_array($job['id'], $appliedJobs);
        ?>
            <div class="job-card <?php echo $alreadyApplied ? 'applied' : ''; ?>">
                <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                <p><?php echo htmlspecialchars($job['description']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                <?php if($alreadyApplied){ ?>
                    <button class="applied-btn" disabled>Already Applied</button>
                <?php } else { ?>
                    <a href="apply.php?job_id=<?php echo $job['id']; ?>">Apply</a>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No jobs available at the moment. Please check back later.</p>
    <?php } ?>
</main>
</body>
</html>
