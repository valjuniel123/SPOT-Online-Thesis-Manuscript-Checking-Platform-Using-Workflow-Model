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

    $query="SELECT depnick FROM tbl_departments WHERE dep_id='$user_dept'"; 
    $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

    while($row = mysqli_fetch_array($sql)) {      
        $department = $row['depnick'];
    }
    
    if(isset($_POST['submit_group'])){
      #Getting Values
      $mem1_id = $_POST['member1'];
      $mem2_id = $_POST['member2'];
      $mem3_id = $_POST['member3'];
      $mem4_id = $_POST['member4'];

      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

      $input_length = strlen($permitted_chars);
      $group_num = $department . "_";
      for($i = 0; $i < 10; $i++) {
          $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
          $group_num .= $random_character;
      }
     
      $query = "INSERT INTO man_groupings VALUES ('$group_num', '$mem1_id', '$user_dept')"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 1.1: ".mysqli_error($conn));     
      
      if($mem2_id!=""){
        $query = "INSERT INTO man_groupings VALUES ('$group_num', '$mem2_id', '$user_dept')"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 1.2: ".mysqli_error($conn));
      }
      if($mem3_id!="" && $mem3_id!=$mem2_id){
        $query = "INSERT INTO man_groupings VALUES ('$group_num', '$mem3_id', '$user_dept')"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 1.3: ".mysqli_error($conn));
      }
      if($mem4_id!="" && $mem4_id!=$mem2_id && $mem4_id!=$mem3_id){
        $query = "INSERT INTO man_groupings VALUES ('$group_num', '$mem4_id', '$user_dept')"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 1.4: ".mysqli_error($conn));
      }
      echo '<script>alert("Groupings successfully saved")</script>';
      header("location:manuscript_status.php#$mem2_id.$mem3_id.$mem4_id");

    }
}else{
    header("location:login.php");
}

?>