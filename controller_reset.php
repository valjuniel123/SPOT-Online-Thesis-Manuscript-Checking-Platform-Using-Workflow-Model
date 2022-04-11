<?php
session_start();
include('database.php');
include('email_api.php');

//Getting Values
if (isset($_POST['reset'])) {
    $email = $_POST['email'];
    $upass = $_POST['password'];

    $pass = crypt(md5($upass),'VM');


    // Inserting to DB
    $query="UPDATE acc_registereds SET user_pass='$pass'
            WHERE `user_email`='$email'"; //any select query code
    $sql = mysqli_query($conn, $query) or die ("Account Reset Error: ".mysqli_error($conn));

    $query="DELETE FROM acc_forgot
    WHERE `forgot_email`='$email'";
    $sql = mysqli_query($conn, $query) or die ("Forgot Account Remove Error: ".mysqli_error($conn));



    echo '<script>alert("Password Successfully Changed!")</script>';
    echo '<script>window.location.href = "login.php"</script>';
}
?>