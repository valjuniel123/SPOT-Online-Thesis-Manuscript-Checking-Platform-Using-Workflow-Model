<?php
session_start ();
include('database.php');

$user_id=$_POST['user_id'];
insert_data($conn, $user_id);


// Approve data query
function insert_data($connection, $id){
     
  //Insert into Acc Registered
  $query="INSERT INTO acc_registereds (user_id, user_email, user_pass, user_department, user_position)
  SELECT acc_pendings.user_id, acc_pendings.user_email, acc_pendings.user_pass, acc_pendings.user_department, acc_pendings.user_position
  FROM acc_pendings WHERE `user_id`='$id'";

  $exec= mysqli_query($connection,$query);

  if($exec){
    echo "Data was inserted successfully";
  }else{
      $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
    echo $msg;
  }

  //Insert into TBL USERLIST
  $query="INSERT INTO tbl_userlists (user_id, user_fname, user_mname, user_lname, user_email, user_department, user_position)
  SELECT acc_pendings.user_id, acc_pendings.user_fname, acc_pendings.user_mname, acc_pendings.user_lname, acc_pendings.user_email, acc_pendings.user_department, acc_pendings.user_position
  FROM acc_pendings WHERE `user_id`='$id'";

  $exec= mysqli_query($connection,$query);

  if($exec){
    echo "Data was inserted successfully";
  }else{
      $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
    echo $msg;
  }

  //Delete to Account Pendings
  $query="DELETE from acc_pendings WHERE `user_id`='$id'";
  $exec= mysqli_query($connection,$query);

  if($exec){
    echo "Data was deleted successfully";
  }else{
      $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
    echo $msg;
  }

  $query = "SELECT acc_pendings.user_id, 
        CONCAT(acc_pendings.user_lname,', ', acc_pendings.user_fname,' ',acc_pendings.user_mname) as name, tbl_positions.pos_name, tbl_departments.depnick 
        FROM `acc_pendings` 
        JOIN tbl_positions ON acc_pendings.user_position = tbl_positions.pos_id
        JOIN tbl_departments ON acc_pendings.user_department = tbl_departments.dep_id";
        //WHERE tbl_departments.depnick=''";
  $exec= mysqli_query($connection,$query);

    

}
?>