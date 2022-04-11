<?php
session_start ();
if(isset($_SESSION['user'])){

    if(isset($_POST['generate_code'])){
    
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

        $department="";
        $query="SELECT depnick FROM tbl_departments WHERE dep_id = '$user_dept'";
        $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
        while($row = mysqli_fetch_array($sql)){
            $department = $row['depnick'];
        }
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $input_length = strlen($permitted_chars);
        $user_code = $department . "_";
        for($i = 0; $i < 6; $i++) {
            $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
            $user_code .= $random_character;
        }

        $query="INSERT INTO cod_instructor VALUES ('$user_name','$user_code')";
        $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
        
    
        header("location:index.php#success");
    }
}
?>