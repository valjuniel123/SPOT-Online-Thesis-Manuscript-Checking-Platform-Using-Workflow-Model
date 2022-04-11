<?php
session_start ();
include('database.php');

$user_id=$_POST['user_id'];
delete_data($conn, $user_id);


// delete data query
function delete_data($connection, $id){
   
    $query="DELETE from cod_dean WHERE dean_code='$id'";
    $exec= mysqli_query($connection,$query);

    if($exec){
      print $id . " was deleted successfully";
    }else{
        $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
      print $msg;
    }
}
?>