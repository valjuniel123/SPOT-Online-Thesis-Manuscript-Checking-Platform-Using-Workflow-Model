<?php
    session_start();
    include('database.php');
    
    $user_code="spot-checking";
    $ucode=$_POST['uniCode'];

    $query="SELECT * FROM `cod_dean` WHERE dean_code='$ucode'";
    $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

    while($row = mysqli_fetch_array($sql)) {      
        $user_code=$row['dean_code'];
    }
    if($ucode==$user_code){
        echo "Success";
    }
    else{
        echo "Missing";
    }
    

?>