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
    
    #Getting Group ID
    $query = "SELECT group_num FROM man_groupings WHERE group_members = '$user_name'";
    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
    
    while($row = mysqli_fetch_array($sql)) {   
        $group_name=$row['group_num'];
    }
    
    #if(isset($_POST['submit_manuscript'])){
      #Getting Values
      $adv_name = $_POST['adviser'];
      $pan1_name = $_POST['panel1'];
      $pan2_name = $_POST['panel2'];
      $chair_name = $_POST['chair'];
      $dean = $_POST['dean'];
      $res_title = $_POST['restitle'];
      $manuscript_id = $_POST['man_id'];
      #$fileTitle = str_replace(' ', '', $res_title);
      $fileTitle = toManuscriptDatabase($res_title, $manuscript_id);

      #Adding to Database
      toDatabase($fileTitle, $adv_name, $pan1_name, $pan2_name, $chair_name, $dean, $res_title);
    
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

    function toManuscriptDatabase($res_title, $man_id){
      require('database.php');
      $query = "UPDATE tbl_manuscripts SET man_title='$res_title' WHERE man_id='$man_id'"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Update Error 1.1: ".mysqli_error($conn));

      return $man_id;
    }
    
    function toDatabase($fileTitle, $adv_name, $pan1_name, $pan2_name, $chair_name, $dean, $res_title){
      require('database.php');
      $query = "UPDATE man_assignings SET ass_adv='$adv_name', ass_pan1='$pan1_name', ass_pan2='$pan2_name', ass_chair='$chair_name', ass_dean='$dean' WHERE man_id='$fileTitle'"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Update Error 2.0: ".mysqli_error($conn));

      $query = "SELECT com_position, check_id FROM man_checkings WHERE man_id='$fileTitle' AND com_response='3'"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Selecting Error 2.0: ".mysqli_error($conn));
      
      $check_pos;
      $array_check_id=[];
      while($row = mysqli_fetch_array($sql)) {      
        $check_pos = $row['com_position'];
      }
      
      if($check_pos==1){
        $query = "UPDATE man_checkings SET date_start=now(), man_checker='$adv_name', com_response=1, date_checked=NULL WHERE com_position='1' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.1: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$pan1_name' WHERE com_position='2'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.2: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$pan2_name' WHERE com_position='3' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.3: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$chair_name' WHERE com_position='4' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$dean' WHERE com_position='5' AND man_id='$fileTitle'"; //any select query code
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
        $notif_receiver=$adv_name;
      }

      else if($check_pos==2){
        $query = "UPDATE man_checkings SET date_start=now(), man_checker='$pan1_name', com_response=1, date_checked=NULL WHERE com_position='2' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.1: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$pan2_name' WHERE com_position='3' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.3: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$chair_name' WHERE com_position='4' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$dean' WHERE com_position='5' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));
        // Notifying Instructor
        $query = "SELECT `user_email` 
        FROM acc_registereds
        WHERE user_department = '$user_dept' 
        AND `user_id` = '$pan1_name'
        "; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
        
        while($row = mysqli_fetch_array($sql)) { 
          $uemail = $row['user_email'];
          $notif_receiver=$pan1_name;
        }
      }

      else if($check_pos==3){
        $query = "UPDATE man_checkings SET date_start=now(), man_checker='$pan2_name', com_response=1, date_checked=NULL WHERE com_position='3' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.1: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$chair_name' WHERE com_position='4' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$dean' WHERE com_position='5' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));
        // Notifying Instructor
        $query = "SELECT `user_email` 
        FROM acc_registereds
        WHERE user_department = '$user_dept' 
        AND `user_id` = '$pan2_name'
        "; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
        
        while($row = mysqli_fetch_array($sql)) { 
          $uemail = $row['user_email'];
        }
        $notif_receiver=$pan2_name;
      }
      
      else if($check_pos==4){
        $query = "UPDATE man_checkings SET date_start=now(), man_checker='$chair_name', com_response=1, date_checked=NULL WHERE com_position='4' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.1: ".mysqli_error($conn));
        $query = "UPDATE man_checkings SET man_checker='$dean' WHERE com_position='$array_check_id[4]' AND man_id='$fileTitle'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Adding Error 2.4: ".mysqli_error($conn));
        // Notifying Instructor
        $query = "SELECT `user_email` 
        FROM acc_registereds
        WHERE user_department = '$user_dept' 
        AND `user_id` = '$chair_name'
        "; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
        
        while($row = mysqli_fetch_array($sql)) { 
          $uemail = $row['user_email'];
        }
        $notif_receiver=$chair_name;
      }
      
      //FOR NOTIFICATIONS
      $notif_body = "The ". $res_title ." is ready to be checked.";
      $notif_head = 'Manuscript Manager';
      $query = "INSERT INTO tbl_notifications VALUES (0, '$notif_receiver','$notif_body', now(), 1,'$notif_head')"; //any select query code
      $sql = mysqli_query($conn, $query) or die ("Adding Error 2.5: ".mysqli_error($conn));


      //mailing Service
      emailservice($uemail, $notif_body, 'Manuscript Manager');
      header("location:upload_manuscript.php#success");
    }
    

?>