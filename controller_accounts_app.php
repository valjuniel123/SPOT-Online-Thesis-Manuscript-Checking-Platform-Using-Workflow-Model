<?php
session_start ();
include('database.php');
include('email_api.php');

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

  //Insert into COD STUDENTS
  $query="INSERT INTO cod_students (std_id, user_id, unique_id)
  SELECT '0' as std_id, acc_pendings.user_id, acc_pendings.unique_id
  FROM acc_pendings WHERE `user_id`='$id'";

  $exec= mysqli_query($connection,$query);

  if($exec){
    echo "Data was inserted successfully";
  }else{
      $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
    echo $msg;
  }

  $query = "SELECT tbl_userlists.user_lname, tbl_userlists.user_email
              FROM tbl_userlists
              WHERE `user_id`='$id'
          "; //any select query code
  $sql = mysqli_query($connection, $query) or die ("Account Search Error: ".mysqli_error($connection));
  $uemail="";
  $uid="";
  $notif_body="";
  while($row = mysqli_fetch_array($sql)) { 
    $uemail = $row['user_email'];
    $uid = $row['user_lname'];
    $notif_body = "Dear Mr/Ms ".$uid.", Your Spot account has been approved!";
    //mailing Service
    emailservice($uemail, $notif_body, 'Account Approved');
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