<?php
session_start();
include "db.php";

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check admin credentials in admin table
    $query = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email' AND password='$password'");
    if(mysqli_num_rows($query) === 1){
        $admin = mysqli_fetch_assoc($query);
        $_SESSION['email'] = $admin['email'];
        $_SESSION['role'] = 'admin'; // role flag

        header("Location: admin_dashboard.php"); // redirect to admin dashboard
        exit();
    } else {
        $error = "Invalid admin credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - AI Job Portal</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<main>
    <h1>Admin Login</h1>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <p><a href="login.html">User Login</a></p>
</main>
</body>
</html>
