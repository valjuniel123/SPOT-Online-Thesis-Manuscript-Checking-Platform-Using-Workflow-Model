<?php
session_start ();
include('database.php');

$user_id=$_POST['user_id'];
delete_data($conn, $user_id);


// Delete data query
function delete_data($connection, $user_ID){
  include('email_api.php');
   
  //Update into Acc Registered
    $query="DELETE FROM acc_registereds
    WHERE `user_id`='$user_ID'";

    $exec= mysqli_query($connection,$query);

    if($exec){
      echo "Data was inserted successfully";
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }

    //Update into TBL USERLIST
    $query="DELETE FROM tbl_userlists
    WHERE `user_id`='$user_ID'";

    $exec= mysqli_query($connection,$query);

    if($exec){
      echo "Data was inserted successfully";
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }
   
    //FOR NOTIFICATIONS
    $notif_body = "Your account has been deleted by the instructor";
    $notif_head = 'Accounts Manager';
    $query = "INSERT INTO tbl_notifications VALUES (0, '$user_ID','$notif_body', now(), 1,'$notif_head')"; //any select query code
    $sql = mysqli_query($connection, $query) or die ("Adding Error 2.5: ".mysqli_error($connection));


    //mailing Service
    emailservice($user_Email, $notif_body, $notif_head);
    header("location:manage_acc.php#success");

}
?>