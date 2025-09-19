<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit();
}
include "db.php";

$id = intval($_GET['id']);
$jobQuery = mysqli_query($conn, "SELECT * FROM jobs WHERE id=$id");
$job = mysqli_fetch_assoc($jobQuery);

if(!$job){
    die("Job not found!");
}

// Handle update
if(isset($_POST['update'])){
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    mysqli_query($conn, "UPDATE jobs SET title='$title', description='$desc', location='$location' WHERE id=$id");
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Job</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h2>Edit Job</h2>
        <form method="POST">
            <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>" required>
            <textarea name="description" required><?php echo htmlspecialchars($job['description']); ?></textarea>
            <input type="text" name="location" value="<?php echo htmlspecialchars($job['location']); ?>" required>
            <button type="submit" name="update">Update Job</button>
        </form>
    </main>
</body>
</html>
