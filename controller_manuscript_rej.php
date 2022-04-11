<?php
session_start ();
include('database.php');


$man_id=$_POST['man_id'];
$comment=$_POST['comment'];

reject_data($conn, $man_id, $comment);


// Reject data query
function reject_data($connection, $man_id, $comment){
    
    //Update Man_checkings for Reject
    $query="UPDATE man_checkings 
    SET com_response = '3', com_comment='$comment', date_checked= now()
    WHERE man_id='$man_id' AND com_response = '1'";

    $exec= mysqli_query($connection,$query);
    if($exec){
      
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }
    
    //Select Active Man_chekings
    $query="SELECT com_position FROM man_checkings 
    WHERE man_id='$man_id' AND com_response = '3'";
    $sql = mysqli_query($connection, $query) or die ("Selection Error: ".mysqli_error($connection));

    while($row = mysqli_fetch_array($sql)) 
    {      
        $checker_level=$row['com_position'];
    }

    //NOTIFICATIONS

    //Selecting Researcher ID where notif will be sent
    $query="SELECT man_groupings.group_members FROM man_groupings
    JOIN man_assignings ON man_groupings.group_num = man_assignings.ass_res
    WHERE `man_id` = '$man_id'";
    $sql = mysqli_query($connection, $query) or die ("Selection Error: ".mysqli_error($connection));
    
    //Getting the Group Members
    while($row = mysqli_fetch_array($sql)) 
    {      
        $group_members = $row['group_members'];
        //Selecting Email of Researcher where notif will be sent
        $mini_query="SELECT user_email FROM acc_registereds
        WHERE `user_id` = '$group_members'";
        $mini_sql = mysqli_query($connection, $mini_query) or die ("Selection Error: ".mysqli_error($connection));
        
        //Getting the Researchers
        while($row = mysqli_fetch_array($mini_sql)) 
        {      
            $res_email=$row['user_email'];
            $mini_notif_body = "Your Manuscript has been rejected ". $checker_level ." out of 5.";
            $mini_notif_head = "Manuscript Manager";
            $mini_mini_query = "INSERT INTO tbl_notifications VALUES (0, '$group_members','$mini_notif_body', now(), 1,'$mini_notif_head')"; //any select query code
            $mini_mini_sql = mysqli_query($connection, $mini_mini_query) or die ("Notif Error 1: ".mysqli_error($connection));

        }

        //mailing Service
        emailservice($res_email, $mini_notif_body, $mini_notif_head);
        header("location:pan_checking.php#success");
    }
}

echo("Manuscript Rejected and considered for Revision");

?>