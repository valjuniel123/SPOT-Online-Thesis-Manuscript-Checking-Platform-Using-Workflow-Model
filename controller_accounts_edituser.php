<?php
session_start ();
include('database.php');

$user_ID=$_POST['userID'];
$user_Pos=$_POST['userPosition'];
$user_LName=$_POST['userLName'];
$user_FName=$_POST['userFName'];
$user_MName=$_POST['userMName'];
$user_Email=$_POST['userEmail'];
update_data($conn, $user_ID, $user_Pos, $user_LName, $user_FName, $user_MName, $user_Email);


// Approve data query
function update_data($connection, $user_ID, $user_Pos, $user_LName, $user_FName, $user_MName, $user_Email){
  include('email_api.php');
   
  //Update into Acc Registered
    $query="UPDATE acc_registereds SET user_email='$user_Email'
    WHERE `user_id`='$user_ID'";

    $exec= mysqli_query($connection,$query);

    if($exec){
      echo "Data was inserted successfully";
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }

    //Update into TBL USERLIST
    $query="UPDATE tbl_userlists SET user_fname='$user_FName', user_mname='$user_MName', user_lname='$user_LName', user_email='$user_Email'
    WHERE `user_id`='$user_ID'";

    $exec= mysqli_query($connection,$query);

    if($exec){
      echo "Data was inserted successfully";
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }
   
    //FOR NOTIFICATIONS
    $notif_body = "Your account has been modified by the instructor";
    $notif_head = 'Accounts Manager';
    $query = "INSERT INTO tbl_notifications VALUES (0, '$user_ID','$notif_body', now(), 1,'$notif_head')"; //any select query code
    $sql = mysqli_query($connection, $query) or die ("Adding Error 2.5: ".mysqli_error($connection));


    //mailing Service
    emailservice($user_Email, $notif_body, $notif_head);
    header("location:manage_acc.php#success");

}
?>