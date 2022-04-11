<?php
    session_start();
    include('database.php');
    
    $user_code="spot-checking";
    $ucode=$_POST['uniCode'];
    $dcode=$_POST['depCode'];

    $query="SELECT cod_instructor.user_code FROM cod_instructor 
            JOIN acc_registereds ON cod_instructor.user_id COLLATE utf8mb4_general_ci = acc_registereds.user_id 
            WHERE cod_instructor.user_code='$ucode' AND acc_registereds.user_department='$dcode'";
    $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

    while($row = mysqli_fetch_array($sql)) {      
        $user_code=$row['user_code'];
    }
    if($ucode==$user_code){
        echo "Success";
    }
    else{
        echo "Missing";
    }
    

?>