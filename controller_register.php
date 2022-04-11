<?php
session_start();
include('database.php');
include('email_api.php');

//Getting Values
if (isset($_POST['registerStudent'])) {
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $uid = $_POST['uid'];
    $email = $_POST['email'];
    $upass = $_POST['password'];
    $dep = $_POST['dep'];
    $pos = $_POST['pos'];
    $uni_code = $_POST['uni_code'];
    $count = 0;

    $pass = crypt(md5($upass),'VM');

    // Checking Into DB 
    $query = "SELECT COUNT(user_id) as counting FROM acc_pendings WHERE user_id = '$uid'"; //any select query code
    $sql = mysqli_query($conn, $query) or die ("Account Register Error: ".mysqli_error($conn)); 

    while($row = mysqli_fetch_array($sql)) { 
        $count = $row['counting'];
    }
    if ($count==0){
        // Checking Into DB 
        $query = "SELECT COUNT(user_id) as counting FROM acc_registereds WHERE user_id = '$uid'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Account Register Error: ".mysqli_error($conn)); 

        while($row = mysqli_fetch_array($sql)) { 
            $count = $row['counting'];
        }
        if ($count==0){
            // Inserting to DB  
            $query = "INSERT INTO acc_pendings VALUES ('$uid', '$fname', '$mname', '$lname','$email', '$pass', '$dep', '$pos', '$uni_code')"; //any select query code
            $sql = mysqli_query($conn, $query) or die ("Account Register Error: ".mysqli_error($conn));

            // Notifying Instructor
            $query = "SELECT acc_registereds.user_id, acc_registereds.user_email
                        FROM acc_registereds
                        JOIN cod_instructor ON acc_registereds.user_id COLLATE utf8mb4_unicode_ci =  cod_instructor.user_id
                        WHERE cod_instructor.user_code='$uni_code'
                    "; //any select query code
            $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
            //Getting Position Equivalent
            $pos_equivalent="";

            if($pos==1){$pos_equivalent="Researcher";} else if($pos==2){$pos_equivalent="Instructor";} else if($pos==3){$pos_equivalent="Panel";} else if($pos==4){$pos_equivalent="Dean";}

            $notif_body = "Good Day! Mr/Ms. $lname is asking for the approval of his/her account as $pos_equivalent";

            while($row = mysqli_fetch_array($sql)) { 
                $uemail = $row['user_email'];
                $uid = $row['user_id'];
                /*
                //mailing Service
                emailservice($uemail, $notif_body, 'Account Approval');
                */
                $mini_query = "INSERT INTO tbl_notifications 
                                VALUES(0, '$uid', '$notif_body', now(), '1', 'Account Manager')";
                $minisql = mysqli_query($conn, $mini_query) or die ("Account Notif Error: ".mysqli_error($conn));
                
            }
            echo '<script>alert("Successfully Registered! Wait for the approval of the instructor")</script>';
            echo '<script>window.location.href = "login.php"</script>';
        }else{
            echo '<script>alert("The Faculty ID/ Student ID already have an account")</script>';
            echo '<script>window.location.href = "login.php"</script>';
        }
    }else{
        echo '<script>alert("The Faculty ID/ Student ID already created an account")</script>';
        echo '<script>window.location.href = "login.php"</script>';
    }
}

if (isset($_POST['registerAdmin'])) {
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $uid = $_POST['uid'];
    $email = $_POST['email'];
    $upass = $_POST['password'];
    $dep = $_POST['dep'];
    $pos = $_POST['pos'];
    $uni_code = $_POST['uni_code'];

    $pass = crypt(md5($upass),'VM');

    // Checking Into DB 
    $query = "SELECT COUNT(user_id) as counting FROM acc_pendings WHERE user_id = '$uid'"; //any select query code
    $sql = mysqli_query($conn, $query) or die ("Account Register Error: ".mysqli_error($conn)); 

    while($row = mysqli_fetch_array($sql)) { 
        $count = $row['counting'];
    }
    if ($count==0){
        // Checking Into DB 
        $query = "SELECT COUNT(user_id) as counting FROM acc_registereds WHERE user_id = '$uid'"; //any select query code
        $sql = mysqli_query($conn, $query) or die ("Account Register Error: ".mysqli_error($conn)); 

        while($row = mysqli_fetch_array($sql)) { 
            $count = $row['counting'];
        }
        if ($count==0){
            // Inserting to DB  
            $query = "INSERT INTO acc_registereds VALUES ('$uid', '$email', '$pass', '$dep', '$pos')"; //any select query code
            $sql = mysqli_query($conn, $query) or die ("Account Register Error 1: ".mysqli_error($conn));

            $query = "INSERT INTO tbl_userlists VALUES ('$uid', '$fname', '$mname', '$lname','$email', '$dep', '$pos')"; //any select query code
            $sql = mysqli_query($conn, $query) or die ("Account Register Error 2: ".mysqli_error($conn));

            $query="DELETE from cod_dean WHERE dean_code='$uni_code'";
            $sql = mysqli_query($conn, $query) or die ("Account Register Error 3: ".mysqli_error($conn));

            // Notifying Instructor
            $query = "SELECT acc_registereds.user_id, acc_registereds.user_email
                        FROM acc_registereds
                        WHERE user_department = '$dep' 
                        AND user_position = '2'
                    "; //any select query code
            $sql = mysqli_query($conn, $query) or die ("Account Search Error: ".mysqli_error($conn));
            //Getting Position Equivalent
            $pos_equivalent="";

            if($pos==1){$pos_equivalent="Researcher";} else if($pos==2){$pos_equivalent="Instructor";} else if($pos==3){$pos_equivalent="Panel";} else if($pos==4){$pos_equivalent="Dean";}

            $notif_body = "Good Day! Mr/Ms. $lname is asking for the approval of his/her account as $pos_equivalent";

            while($row = mysqli_fetch_array($sql)) { 
                $uemail = $row['user_email'];
                $uid = $row['user_id'];
                /*
                //mailing Service
                emailservice($uemail, $notif_body, 'Account Approval');
                $mini_query = "INSERT INTO tbl_notifications 
                                VALUES(0, '$uid', '$notif_body', now(), '1', 'Account Manager')";
                $minisql = mysqli_query($conn, $mini_query) or die ("Account Notif Error: ".mysqli_error($conn));
                */
            }
            echo '<script>alert("Successfully Registered!")</script>';
            echo '<script>window.location.href = "login.php"</script>';
        }else{
            echo '<script>alert("The Faculty ID/ Student ID already have an account")</script>';
            echo '<script>window.location.href = "login.php"</script>';
        }
    }else{
        echo '<script>alert("The Faculty ID/ Student ID already created an account")</script>';
        echo '<script>window.location.href = "login.php"</script>';
    }

}

?>