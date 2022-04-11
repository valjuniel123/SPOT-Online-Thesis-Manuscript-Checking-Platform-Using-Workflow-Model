<?php
//FOR EDIT PA DIN

session_start ();
include('database.php');


$man_id=$_POST['man_id'];
$response_id = $_POST['response_id'];

insert_data($conn, $man_id, $response_id);


// Approve data query
function insert_data($connection, $man_id, $response_id){
    $option=0;
    $checker_level = "";
   
   //Select Active Man_chekings
    $query="SELECT com_position FROM man_checkings 
    WHERE man_id='$man_id' AND com_response = '1'";
    $sql = mysqli_query($connection, $query) or die ("Selection Error: ".mysqli_error($connection));

    while($row = mysqli_fetch_array($sql)) {      
        ++$option;
        $checker_level=$row['com_position'];
    }
    $checker_level++;
    
    //Update Man_checkings
    $query="UPDATE man_checkings 
    SET com_response = '1',
    WHERE man_id='$man_id' AND com_response = '$response_id'";

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
        SET com_response = '1' date_start = now()
        WHERE man_id='$man_id' AND com_position = '$checker_level'";
    
        $exec= mysqli_query($connection,$query);
        if($exec){
          
        }else{
            $msg= "Error: " . $query . "<br>" . mysqli_error($connection);
          echo $msg;
    }
    }
    
    
    
}

?>