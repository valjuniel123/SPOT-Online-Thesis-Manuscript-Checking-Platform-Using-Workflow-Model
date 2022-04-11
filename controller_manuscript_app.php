<?php
session_start ();
include('database.php');


$man_id=$_POST['man_id'];
$comment=$_POST['comment'];

insert_data($conn, $man_id, $comment);


// Approve data query
function insert_data($connection, $man_id, $comment)
{
    include('email_api.php');
    $option=0;
    $notif_action="";
   
   //Select Active Man_chekings
    $query="SELECT com_position FROM man_checkings 
    WHERE man_id='$man_id' AND com_response = '1'";
    $sql = mysqli_query($connection, $query) or die ("Selection Error: ".mysqli_error($connection));

    while($row = mysqli_fetch_array($sql)) 
    {      
        ++$option;
        $checker_level=$row['com_position'];
    }
    $checker_level++;
    
    //Update Man_checkings
    $query="UPDATE man_checkings 
    SET com_response = '2', com_comment='$comment', date_checked= now()
    WHERE man_id='$man_id' AND com_response = '1'";

    $exec= mysqli_query($connection,$query);
    if($exec){
      
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }
    if($checker_level==6){
        echo "for Dean Signing";
    }
    else{
        //Activating new Man_checker
        $query="UPDATE man_checkings 
        SET com_response = '1', date_start = now()
        WHERE man_id='$man_id' AND com_position = '$checker_level'";
    
        $exec= mysqli_query($connection,$query);
        if($exec){
          
        }else{
            $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
          echo $msg;
        }
        if($checker_level==5){
            $notif_action = "signed";
        }else{
        $notif_action = "checked";
        }
    }

    //NOTIFICATIONS
    
    
    //Selecting Manuscript to Notification
    $query="SELECT tbl_manuscripts.man_title, man_checkings.man_checker FROM tbl_manuscripts
    JOIN man_checkings ON tbl_manuscripts.man_id = man_checkings.man_id
    WHERE man_checkings.man_id='$man_id' AND com_position = '$checker_level'";
    $sql = mysqli_query($connection, $query) or die ("Selection Error: ".mysqli_error($connection));
    
    //Getting the Manuscript Name
    while($row = mysqli_fetch_array($sql)) 
    {      
        $res_title=$row['man_title'];
        $notif_sendee=$row['man_checker'];
    }

    $notif_body = "The ". $res_title ." is ready to be ".$notif_action.".";
    $notif_head = "Manuscript Manager";
    $query = "INSERT INTO tbl_notifications VALUES (0, '$notif_sendee','$notif_body', now(), 1,'$notif_head')"; //any select query code
    $sql = mysqli_query($connection, $query) or die ("Notif Error 1: ".mysqli_error($connection));
    
    //Selecting Email of User where notif will be sent
    $query="SELECT user_email FROM acc_registereds
    WHERE `user_id` = '$notif_sendee'";
    $sql = mysqli_query($connection, $query) or die ("Selection Error: ".mysqli_error($connection));
    
    //Getting the Manuscript Name
    while($row = mysqli_fetch_array($sql)) 
    {      
        $uemail=$row['user_email'];
    }


    //mailing Service
    emailservice($uemail, $notif_body, $notif_head);


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
            $mini_notif_body = "Your Manuscript is now being ". $notif_action .". ". $checker_level-- ." out of 5.";
            $mini_notif_head = "Manuscript Manager";
            $mini_mini_query = "INSERT INTO tbl_notifications VALUES (0, '$group_members','$mini_notif_body', now(), 1,'$mini_notif_head')"; //any select query code
            $mini_mini_sql = mysqli_query($connection, $mini_mini_query) or die ("Notif Error 1: ".mysqli_error($connection));

        }

        
        //mailing Service
        emailservice($res_email, $mini_notif_body, $mini_notif_head);
        header("location:pan_checking.php#success");
        
    }
    
}

?>