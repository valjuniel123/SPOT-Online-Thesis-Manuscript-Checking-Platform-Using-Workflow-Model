<?php
session_start ();
include('database.php');

$user_id=$_POST['user_id'];
delete_data($conn, $user_id);


// delete data query
function delete_data($connection, $id){
   
    $query="DELETE from man_groupings WHERE group_members='$id'";
    $exec= mysqli_query($connection,$query);

    if($exec){
      echo $id . " was deleted successfully";
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      echo $msg;
    }
}
?>