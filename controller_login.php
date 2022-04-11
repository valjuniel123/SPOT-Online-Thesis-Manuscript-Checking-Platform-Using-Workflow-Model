<?php
    session_start();
    include('database.php');
    
    $option=0;
    $user_id=$_POST['uname'];
    $user_pw=$_POST['upass'];
    $user_name="";
    $user_email="";
    $user_pass="";
    $user_dept="";
    $user_pos="";

    $query="SELECT * FROM acc_registereds WHERE user_email='$user_id'";
    $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

    while($row = mysqli_fetch_array($sql)) {      
        ++$option;
        $user_name=$row['user_id'];
        $user_email=$row['user_email'];
        $user_pass=$row['user_pass'];
        $user_dept=$row['user_department'];
        $user_pos=$row['user_position'];
    }

    if($user_pass == crypt(md5($user_pw),'VM')){
        $_SESSION['user'] = $user_name;
        header("location:index.php");
    }
    else{
        echo '<script>alert("Username/Password not match")</script>';
        #header("location:login.php?err=1");
        echo "<script>window.location.href='login.php'</script>";
    }

?>