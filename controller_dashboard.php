<?php

    if(isset($_SESSION['user'])){
        
        include('database.php');
        $user = $_SESSION['user'];
        $query="SELECT * FROM acc_registereds WHERE user_id='$user'"; 
        $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

        while($row = mysqli_fetch_array($sql)) {      
            $user_name = $row['user_id'];
            $user_email= $row['user_email'];
            $user_pass = $row['user_pass'];
            $user_dept = $row['user_department'];
            $user_pos  = $row['user_position'];
        }
        //GETTING INSTRUCTORS UNIQUE CODE
        if($user_pos!=1){
            $query="SELECT user_code FROM cod_instructor WHERE user_id='$user'"; 
            $sql = mysqli_query($conn, $query) or die ("Error 2: ".mysqli_error($conn));
            $unique_code = "";
            while($row = mysqli_fetch_array($sql)){
                $unique_code = $row['user_code'];
            }

            //Getting Date now minus 1 year
            $time = strtotime("-1 year", time());
            $datenow = date("Y-m-d", $time);

            /*
            Getting Groups within instructor's scope
            $groups=[];
            $query="SELECT man_groupings.group_num 
                    FROM man_groupings 
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code'"; 
            $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
            */
            

            //Getting total manuscripts within instructor's scope
            $query="SELECT COUNT(DISTINCT(tbl_manuscripts.man_title)) AS counting 
                    FROM tbl_manuscripts 
                    JOIN man_checkings ON tbl_manuscripts.man_id = man_checkings.man_id 
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id 
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num 
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id 
                    WHERE cod_students.unique_id = '$unique_code' AND man_checkings.date_start >= '$datenow'";
           
           /*
           $query="SELECT COUNT(DISTINCT man_checkings.man_id) AS counting 
                    FROM man_checkings 
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code' AND date_start >= '$datenow'"; 
            */
            $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
            while($row = mysqli_fetch_array($sql)) { $allcount=$row['counting']; }

            //Getting numbers per step
            $query="SELECT COUNT(DISTINCT(tbl_manuscripts.man_title)) AS counterone
                    FROM tbl_manuscripts 
                    JOIN man_checkings ON tbl_manuscripts.man_id = man_checkings.man_id
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code' AND (man_checkings.com_response=1 OR man_checkings.com_response=3) AND man_checkings.com_position=1
                    AND man_checkings.date_start >= '$datenow'"; 
            $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
            $count1=0;
            while($row = mysqli_fetch_array($sql)) {      
                $count1 = $row['counterone'];
            }
            $query="SELECT COUNT(DISTINCT(tbl_manuscripts.man_title)) AS countertwo
                    FROM tbl_manuscripts 
                    JOIN man_checkings ON tbl_manuscripts.man_id = man_checkings.man_id
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code' AND (man_checkings.com_response=1 OR man_checkings.com_response=3) AND man_checkings.com_position=2
                    AND man_checkings.date_start >= '$datenow'"; 

            $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
            $count2=0;
            while($row = mysqli_fetch_array($sql)) {      
                $count2 = $row['countertwo'];
            }
            $query="SELECT COUNT(DISTINCT(tbl_manuscripts.man_title)) AS counterthree
                    FROM tbl_manuscripts 
                    JOIN man_checkings ON tbl_manuscripts.man_id = man_checkings.man_id
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code' AND (man_checkings.com_response=1 OR man_checkings.com_response=3) AND man_checkings.com_position=3
                    AND man_checkings.date_start >= '$datenow'"; 
                    
            $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
            $count3=0;
            while($row = mysqli_fetch_array($sql)) {      
                $count3 = $row['counterthree'];
            }
            $query="SELECT COUNT(DISTINCT(tbl_manuscripts.man_title)) AS counterfour
                    FROM tbl_manuscripts 
                    JOIN man_checkings ON tbl_manuscripts.man_id = man_checkings.man_id
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code' AND (man_checkings.com_response=1 OR man_checkings.com_response=3) AND man_checkings.com_position=4
                    AND man_checkings.date_start >= '$datenow'"; 
 
            $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
            $count4=0;
            while($row = mysqli_fetch_array($sql)) {      
                $count4 = $row['counterfour'];
            }
            $query="SELECT COUNT(DISTINCT(tbl_manuscripts.man_title)) AS counterfive
                    FROM tbl_manuscripts 
                    JOIN man_checkings ON tbl_manuscripts.man_id = man_checkings.man_id
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code' AND (man_checkings.com_response=1 OR man_checkings.com_response=3) AND man_checkings.com_position=5
                    AND man_checkings.date_start >= '$datenow'"; 
                    
            /*
            $query="SELECT man_checkings.com_position, COUNT(CASE WHEN man_checkings.com_response=1 or man_checkings.com_response=3 THEN 1 END) AS counter 
                    FROM man_checkings 
                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id = '$unique_code' AND man_checkings.date_start>='$datenow' AND man_checkings.com_position=5"; 
            */
                    $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
            $count5=0;
            while($row = mysqli_fetch_array($sql)) {      
                $count5 = $row['counterfive'];
            }
            
            $done=0;
            $allprog = $count1 + $count2 + $count3 + $count4 + $count5;
            if($allcount!=0 && $allprog!=0){
                $done=$allcount - $allprog;
            }
            if($done<=0){
                $done=0;
            }
            
            /*
            $researcherProgress = array(
                array("y"=> $count[0], "name"=> "Adviser", "color"=> "#858796"),
                array("y"=> $count[1], "name"=> "Panel 1", "color"=> "#E74A3B"),
                array("y"=> $count[2], "name"=> "Panel 2", "color"=> "#F6C23E"),
                array("y"=> $count[3], "name"=> "Chairman", "color"=> "#4E73DF"),
                array("y"=> $count[4], "name"=> "Dean", "color"=> "#36B9CC"),
                array("y"=> $done, "name"=> "Done", "color"=> "#1CC88A")
            );
            */


            //LINE CHART

            $checkingProgress = array(
                array("x"=> 19,	"y"=> 1.0266),
                array("x"=> 20,	"y"=> 1.0016),
            );   
        }         

    }
?>