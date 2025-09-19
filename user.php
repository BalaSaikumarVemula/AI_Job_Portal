<?php
ob_start();
session_start();
include "db.php";

if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        echo "Email already registered";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users(name,email,password) VALUES('$name','$email','$password')");
        if($insert){
            $_SESSION['email'] = $email;
            echo "success";
        } else {
            echo "Signup failed";
        }
    }
}

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($query) > 0){
        $user = mysqli_fetch_assoc($query);
        if(password_verify($password, $user['password'])){
            $_SESSION['email'] = $email;
            echo "success";
        } else {
            echo "Incorrect password";
        }
    } else {
        echo "Email not registered";
    }
}
?>
