<?php
session_start ();
if(isset($_SESSION['user'])){
    
    include('database.php');
    $user = $_SESSION['user'];
    $query="SELECT * FROM acc_registereds WHERE user_id='$user'"; 
    $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

    while($row = mysqli_fetch_array($sql)) {      
        $user_name = $row['user_id'];
        $user_email= $row['user_email'];
        $user_pass = $row['user_pass'];
        $user_dept = $row['user_department'];
        $user_pos  = $row['user_position'];
    }


    $query = "SELECT cod_dean.user_id, cod_dean.dean_code FROM cod_dean
    WHERE cod_dean.user_id='$user_name'";
    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));

    $msg="Codes";
    while($row = mysqli_fetch_array($sql)) { 
        $msg .= "\r\n" . $row['dean_code'];
    }

    echo $msg;
}
?>