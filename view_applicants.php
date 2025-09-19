<?php
session_start();
if(!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin'){
    header("Location: admin_login.php");
    exit();
}

include "db.php";

if (!isset($_GET['job_id'])) {
    echo "Invalid Job ID.";
    exit();
}

$job_id = intval($_GET['job_id']);

// Fetch job info
$jobQuery = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $job_id");
$job = mysqli_fetch_assoc($jobQuery);
if (!$job) {
    echo "Job not found.";
    exit();
}

// Fetch applicants
$applicantsQuery = mysqli_query($conn, "
    SELECT u.name, u.email, u.resume, a.applied_at 
    FROM applications a
    JOIN users u ON a.email = u.email
    WHERE a.job_id = $job_id
    ORDER BY a.applied_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Applicants - <?php echo htmlspecialchars($job['title']); ?></title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Applicants for "<?php echo htmlspecialchars($job['title']); ?>"</h1>
    <nav>
        <a href="admin_dashboard.php">Back to Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <?php if (mysqli_num_rows($applicantsQuery) > 0) { ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Resume</th>
                    <th>Applied On</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($applicant = mysqli_fetch_assoc($applicantsQuery)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                        <td>
                            <?php if (!empty($applicant['resume'])) { ?>
                                <a href="<?php echo $applicant['resume']; ?>" target="_blank">View</a>
                            <?php } else { ?>
                                No Resume
                            <?php } ?>
                        </td>
                        <td><?php echo date("d-m-Y H:i", strtotime($applicant['applied_at'])); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No applicants for this job yet.</p>
    <?php } ?>
</main>
</body>
</html>
