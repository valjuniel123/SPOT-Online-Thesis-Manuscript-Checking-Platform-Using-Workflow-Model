<?php
session_start ();
if(isset($_SESSION['user'])){
    
    include('database.php');
    include('email_api.php');
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
    
    $unique_code="";
    #Getting Group ID
    $query = "SELECT unique_id FROM cod_students WHERE user_id = '$user_name'";
    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
    
    while($row = mysqli_fetch_array($sql)) {   
        $unique_code=$row['unique_id'];
    }
    
    #Getting Group ID
    $query = "SELECT group_num FROM man_groupings WHERE group_members = '$user_name'";
    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
    
    while($row = mysqli_fetch_array($sql)) {   
        $group_name=$row['group_num'];
    }
    
    $upload = 'err';

    #if(isset($_POST['submit_manuscript'])){
      #Getting Values
      $adv_name = $_POST['adviser'];
      $pan1_name = $_POST['panel1'];
      $pan2_name = $_POST['panel2'];
      $chair_name = $_POST['chair'];
      $dean = $_POST['dean'];
      $res_title = $_POST['restitle'];
      #$fileTitle = str_replace(' ', '', $res_title);
      $fileTitle = toManuscriptDatabase($res_title);
    
      #Getting Manuscript Files
      $file = $_FILES['file'];
      $fileName = $_FILES['file']['name'];
      $fileTmpName = $_FILES['file']['tmp_name'];
      $fileSize = $_FILES['file']['size'];
      $fileError = $_FILES['file']['error'];
      $fileType = $_FILES['file']['type'];
    
      $fileExt = explode('.', $fileName);
      $fileActualExt = strtolower(end($fileExt));
    
      $allowed = array('pdf');
       
      #Getting Cert
      $cert = $_FILES['cert'];
      $certName = $_FILES['cert']['name'];
      $certTmpName = $_FILES['cert']['tmp_name'];
      $certSize = $_FILES['cert']['size'];
      $certError = $_FILES['cert']['error'];
      $certType = $_FILES['cert']['type'];
    
      $certExt = explode('.', $certName);
      $certActualExt = strtolower(end($certExt));
    
      $allowed = array('pdf');
    
      #Uploading Manuscript Files and Certificate
      if (in_array($fileActualExt, $allowed) && in_array($certActualExt, $allowed)){
        if($fileError === 0 && $certError === 0){
          if ($fileSize < 15000000 && $certSize < 15000000){

            #Manuscript
            $fileNameNew = $fileTitle.".".$fileActualExt;
            $fileDestination = 'uploads/' .$fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);
            chmod($fileDestination, 0777);

            #Certificate
            $certNameNew = "g_".$fileTitle.".".$certActualExt;
            $certDestination = 'uploads/' .$certNameNew;
            move_uploaded_file($certTmpName, $certDestination);

            #Adding to Database
            toDatabase($fileTitle, $group_name, $adv_name, $pan1_name, $pan2_name, $chair_name, $dean, $res_title, $unique_code);
          }
          else{
            echo "File is too big";
          }
        }else{
        echo "File error uploading";
        }
      }
      else{
        echo "File type not supported";
      }
      

      /*
      if (in_array($fileActualExt, $allowed)){
        if($fileError === 0){
          if ($fileSize < 15000000){
            $fileNameNew = $fileTitle.".".$fileActualExt;
            $fileDestination = 'uploads/' .$fileNameNew;
            move_uploaded_file($fileTmpName, $fileDestination);
          }
          else{
            echo "File is too big";
          }
        }else{
        echo "File error uploading";
        }
      }
      else{
        echo "File type not supported";
      }

      if (in_array($certActualExt, $allowed)){
        if($certError === 0){
          if ($certSize < 15000000){
            $certNameNew = "g_".$fileTitle.".".$certActualExt;
            $certDestination = 'uploads/' .$certNameNew;
            move_uploaded_file($certTmpName, $certDestination);
          }
          else{
            echo "File is too big";
          }
        }else{
        echo "File error uploading";
        }
      }
      else{
        echo "File type not supported";
      }
      */ 
      $upload = 'ok';
      return $upload;
    #}
    #else{
    #  header("location:upload_manuscript.php#error");
    #}
}else{
    header("location:login.php");
}

    function toManuscriptDatabase($res_title){
      require('database.php');
      $query = "INSERT INTO tbl_manuscripts VALUES (0, '$res_title')"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 1.1: ".mysqli_error($conn));
    
      $query = "SELECT man_id FROM tbl_manuscripts WHERE man_title = '$res_title'"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 1.2: ".mysqli_error($conn));
      $option = 0;
    
      $row = mysqli_fetch_array($sql);
      $man_id = $row[0];
      return $man_id;
    }
    
    function toDatabase($fileTitle, $group_name, $adv_name, $pan1_name, $pan2_name, $chair_name, $dean, $res_title, $unique_code){
      require('database.php');
      $query = "INSERT INTO man_assignings VALUES ($fileTitle, '$group_name','$adv_name', '$pan1_name', '$pan2_name', '$chair_name', '$dean', '$unique_code')"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.0: ".mysqli_error($conn));
      
      $query = "INSERT INTO man_checkings VALUES (now(), '$fileTitle', '$adv_name', 1, 1, NULL, NULL, 0)"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.1: ".mysqli_error($conn));
      $query = "INSERT INTO man_checkings VALUES (NULL, '$fileTitle', '$pan1_name', 2, 0, NULL, NULL, 0)"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.2: ".mysqli_error($conn));
      $query = "INSERT INTO man_checkings VALUES (NULL, '$fileTitle', '$pan2_name', 3, 0, NULL, NULL, 0)"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.3: ".mysqli_error($conn));
      $query = "INSERT INTO man_checkings VALUES (NULL, '$fileTitle', '$chair_name', 4, 0, NULL, NULL, 0)"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));
      $query = "INSERT INTO man_checkings VALUES (NULL, '$fileTitle', '$dean', 5, 0, NULL, NULL, 0)"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));

      // Notifying Instructor
      $query = "SELECT `user_email` 
      FROM acc_registereds
      WHERE user_department = '$user_dept' 
      AND `user_id` = '$adv_name'
      "; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
      
      while($row = mysqli_fetch_array($sql)) { 
        $uemail = $row['user_email'];
      }
      
      //FOR NOTIFICATIONS
      $notif_body = "The ". $res_title ." is ready to be checked.";
      $notif_head = 'Manuscript Manager';
      $query = "INSERT INTO tbl_notifications VALUES (0, '$adv_name','$notif_body', now(), 1,'$notif_head')"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.5: ".mysqli_error($conn));


      //mailing Service
      emailservice($uemail, $notif_body, 'Manuscript Manager');
      header("location:upload_manuscript.php#success");
    }
    

?>