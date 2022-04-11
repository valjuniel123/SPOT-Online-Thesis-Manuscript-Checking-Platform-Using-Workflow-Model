<?php
session_start();
include('database.php');
include('email_api.php');

//Getting Values
if (isset($_POST['submit'])) {
    $email = $_POST['umail'];
    $uid = $_POST['uid'];

    $forgot_key = md5($user_email).rand(10,9999);

    // Searching on DB
    $query = "SELECT * from acc_registereds 
                WHERE user_email = '$email' AND `user_id`='$uid'"; //any select query code
    $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
    
    //if not empty
    if(mysqli_num_rows($sql)==0){
        echo '<script>alert("No Accounts Found")</script>';
        echo '<script>window.location.href = "login.php"</script>';
    }else{
        $query = "INSERT INTO acc_forgot
                VALUES ('0', '$email', '$forgot_key', '$uid')"; //any select query code
        $sql = mysqli_query($conn, $query);
        if($sql){
            $output='<p>Dear user,</p>';
            $output.='<p>Please click on the following link to reset your password.</p>';
            $output.='<p>-------------------------------------------------------------</p>';
            $output.='<p><a href="https://spot-checking.com/reset_password.php?
            key='.$forgot_key.'&email='.$email.'&action=reset" target="_blank">
            RESET PASSWORD</a></p>';		
            $output.='<p>-------------------------------------------------------------</p>';
            $output.='<p>If you did not request this forgotten password email, no action 
            is needed, your password will not be reset.';
            $output.='<p>SPOT CHECKING TEAM</p>';
            $notif_body = $output;
            //$notif_body = "Change password request. $lname is asking for the approval of his/her account as $pos_equivalent";
    
            //mailing Service
            emailservice($email, $notif_body, 'Account Reset');
            /*
            $mini_query = "INSERT INTO tbl_notifications 
                            VALUES(0, '$uid', 'Account Manager', '$notif_body', now(), '1')";
            $minisql = mysqli_query($conn, $mini_query) or die ("Account Notif Error: ".mysqli_error($conn));
            */
            echo '<script>alert("Email Sent!")</script>';
            echo '<script>window.location.href = "login.php"</script>';
        }
        else{
            echo '<script>alert("Email Already Sent! Kindly check your spam if there is nothing on your inbox")</script>';
            echo '<script>window.location.href = "login.php"</script>';
        }
        
        

    }
        
}
?>