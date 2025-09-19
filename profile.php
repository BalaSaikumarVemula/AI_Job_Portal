<?php
session_start();
if(!isset($_SESSION['email'])){
    header("Location: login.html");
    exit();
}
include "db.php";

$email = $_SESSION['email'];
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($userQuery);

// Handle resume upload
if(isset($_POST['upload'])){
    if(isset($_FILES['resume']) && $_FILES['resume']['name'] != ''){
        $targetDir = "uploads/";
        if(!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }
        $resumePath = $targetDir . basename($_FILES['resume']['name']);
        move_uploaded_file($_FILES['resume']['tmp_name'], $resumePath);

        $updateQuery = mysqli_query($conn, "UPDATE users SET resume='$resumePath' WHERE email='$email'");
        if($updateQuery){
            header("Location: profile.php");
            exit();
        } else {
            $error = "Resume upload failed.";
        }
    } else {
        $error = "Please select a file to upload.";
    }
}

// Fetch user's applications
$applicationsQuery = mysqli_query($conn, "
    SELECT j.title, j.location, j.description, a.applied_at
    FROM applications a
    JOIN jobs j ON a.job_id = j.id
    WHERE a.email='$email'
    ORDER BY a.applied_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - AI Job Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Your Profile</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="jobs.php">Jobs</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <div class="job-card">
            <h2>Personal Info</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

            <?php if(isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

            <form method="POST" enctype="multipart/form-data">
                <label>Upload Resume (optional):</label>
                <input type="file" name="resume" accept=".pdf,.doc,.docx">
                
                <?php if(!empty($user['resume'])){ ?>
                    <p>Current Resume: <a href="<?php echo $user['resume']; ?>" target="_blank">View</a></p>
                <?php } ?>

                <button type="submit" name="upload">Upload Resume</button>
            </form>
        </div>

        <h2>My Applications</h2>
        <?php if(mysqli_num_rows($applicationsQuery) > 0){ ?>
            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($app = mysqli_fetch_assoc($applicationsQuery)){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($app['title']); ?></td>
                            <td><?php echo htmlspecialchars($app['location']); ?></td>
                            <td><?php echo htmlspecialchars($app['description']); ?></td>
                            <td><?php echo date("d-m-Y H:i", strtotime($app['applied_at'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>You have not applied for any jobs yet.</p>
        <?php } ?>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> AI Job Portal. All rights reserved.</p>
    </footer>
</body>
</html>
