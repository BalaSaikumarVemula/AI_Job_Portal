<?php
session_start();
if(!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin'){
    header("Location: admin_login.php");
    exit();
}

include "db.php";

// Add job
if (isset($_POST['add_job'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    mysqli_query($conn, "INSERT INTO jobs (title, description, location) VALUES ('$title','$description','$location')");
    header("Location: admin_dashboard.php");
    exit();
}

// Delete job safely
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Delete all applications for this job first
    mysqli_query($conn, "DELETE FROM applications WHERE job_id=$id");

    // Then delete the job
    mysqli_query($conn, "DELETE FROM jobs WHERE id=$id");

    header("Location: admin_dashboard.php");
    exit();
}

// Fetch jobs
$jobsQuery = mysqli_query($conn, "SELECT * FROM jobs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - AI Job Portal</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Admin Dashboard</h1>
    <nav>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <h2>Post a New Job</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Job Title" required>
        <textarea name="description" placeholder="Job Description" required></textarea>
        <input type="text" name="location" placeholder="Location" required>
        <button type="submit" name="add_job">Post Job</button>
    </form>

    <h2>Manage Jobs</h2>
    <?php if (mysqli_num_rows($jobsQuery) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Posted On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($job = mysqli_fetch_assoc($jobsQuery)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($job['title']); ?></td>
                        <td><?php echo htmlspecialchars($job['description']); ?></td>
                        <td><?php echo htmlspecialchars($job['location']); ?></td>
                        <td><?php echo date("d-m-Y H:i", strtotime($job['created_at'])); ?></td>
                        <td>
                            <a href="edit_job.php?id=<?php echo $job['id']; ?>">Edit</a> | 
                            <a href="admin_dashboard.php?delete=<?php echo $job['id']; ?>" onclick="return confirm('Are you sure you want to delete this job?');">Delete</a> | 
                            <a href="view_applicants.php?job_id=<?php echo $job['id']; ?>">View Applicants</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No jobs posted yet.</p>
    <?php } ?>
</main>
</body>
</html>
