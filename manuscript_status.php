<?php

    session_start ();
    if(isset($_SESSION['user'])){
        
        include('database.php');
        $user = $_SESSION['user'];
        $query="SELECT * FROM acc_registereds WHERE user_id='$user'"; 
        $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));
        $option = "";

        while($row = mysqli_fetch_array($sql)) {      
            $user_name = $row['user_id'];
            $user_email= $row['user_email'];
            $user_pass = $row['user_pass'];
            $user_dept = $row['user_department'];
            $user_pos  = $row['user_position'];
        }
        if($user_pos == 1){
            $progress="";
            $percent="";
            $reject="";
            $group="";
            $group_name="";
            $array_comment = [];
            $unique_code="";
            $manuscript = "";
            $man_id = "";
            $fname = "";

            $query="SELECT user_lname FROM tbl_userlists WHERE user_id='$user'"; 
            $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

            while($row = mysqli_fetch_array($sql)) {      
                $fname = $row['user_lname'];
            }

            $query = "SELECT group_num FROM man_groupings WHERE group_members = '$user_name'";
            $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
            
            while($row = mysqli_fetch_array($sql)) {++$option;  
                $group_name=$row['group_num'];
            }
            // UNIQUE CODE
            $query = "SELECT unique_id FROM cod_students WHERE user_id = '$user_name'";
            $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
            
            while($row = mysqli_fetch_array($sql)) {++$option;  
                $unique_code=$row['unique_id'];
            }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    
    <!-- UPLOADING FORMATING -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body id="page-top">

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Manuscript Status</h1>
        </div>
        
        <?php 
            $query="SELECT man_assignings.ass_res, man_assignings.man_id, tbl_manuscripts.man_title 
                    FROM man_assignings 
                    JOIN tbl_manuscripts ON man_assignings.man_id = tbl_manuscripts.man_id 
                    WHERE man_assignings.ass_res =  '$group_name'";
            $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));
    
            while($row = mysqli_fetch_array($sql)) {      
                $man_id = $row['man_id'];
                $manuscript = $row['man_title'];
                if($man_id!=""){
            
        ?>
        <!-- Content Row -->
        <div class="row">

            <!-- Progress Card -->
            <div class="col-lg-12 mb-4">

                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Manuscript</h6>
                    </div>
                    <div class="card-body">
                        <p><?php echo $manuscript; ?></p>
                        <a class="btn btn-outline-danger mx-1" 
                        href="https://spot-checking.com/uploads/<?php echo $man_id; ?>.pdf"
                        target="_blank">View Manuscript</a>

                        <a class="btn btn btn-outline-danger mx-1" 
                        href="https://spot-checking.com/uploads/g_<?php echo $man_id; ?>.pdf"
                        target="_blank">View Grammarian Certificate</a>
                        
                        <?php
                            $query="SELECT COUNT(man_checkings.man_id) AS reject
                                    FROM man_checkings
                                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                                    WHERE man_checkings.com_response = '3' AND man_assignings.ass_res = '$group_name'";
                            $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));
                    
                            while($row = mysqli_fetch_array($sql)) {      
                                $reject = $row['reject'];
                            }
                            if ($reject!='1'){
                            ?>
                        <a class="ml-5 btn btn-danger passingIDRej" data-toggle="modal" data-target="#RejectModal">Unsubmit</a>
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <?php } }?>

        <!-- Content Row -->
        <div class="row">

            <!-- Progress Card -->
            <div class="col-lg-12 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-sm font-weight-bold text-danger text-uppercase mb-1">Checking Status
                                </div>
                                <?php
                                $query="SELECT COUNT(man_checkings.man_id) AS numbers, (COUNT(man_checkings.man_id)*20) AS percent
                                        FROM man_checkings
                                        JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                                        WHERE (man_checkings.com_response = '2' OR man_checkings.com_response = '5') AND man_assignings.ass_res = '$group_name'";
                                $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));
                        
                                while($row = mysqli_fetch_array($sql)) {      
                                    $progress = $row['numbers'];
                                    $percent = $row['percent'];
                                }
                                ?>
                                <div class="text-xs font-weight-bold text-dark text-uppercase mb-1"><?php echo $progress ?> out of 5 approval 
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $percent ?>%</div>
                                    </div>
                                    <div class="col">
                                        <?php if($percent == 0) { ?>
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-secondary" role="progressbar"
                                                style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <?php }elseif($percent == 20) { ?>
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-danger" role="progressbar"
                                                style="width: 20%" aria-valuenow="20" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <?php }elseif($percent == 40) { ?>
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-warning" role="progressbar"
                                                style="width: 40%" aria-valuenow="40" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <?php }elseif($percent == 60) { ?>
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: 60%" aria-valuenow="60" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <?php }elseif($percent == 80) { ?>
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: 80%" aria-valuenow="80" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <?php }elseif($percent == 100) { ?>
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-sucess" role="progressbar"
                                                style="width: 100%" aria-valuenow="100" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <?php if($percent == 100){ ?>
                <!-- Approved -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 text-center">
                        <h6 class="m-0 font-weight-bold text-danger">Approved!</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/undraw_approve.png" alt="">
                        </div>
                        <p class="text-center">Your thesis manuscript has been approved by all the neccessary personnels <br> 
                            You may now proceed to book binding</p>
                    </div>
                </div>
                <?php } 
                $query="SELECT COUNT(man_checkings.man_id) AS reject
                        FROM man_checkings
                        JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                        WHERE man_checkings.com_response = '3' AND man_assignings.ass_res = '$group_name'";
                $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));
        
                while($row = mysqli_fetch_array($sql)) {      
                    $reject = $row['reject'];
                }
                if ($reject=='1'){
                ?>
                <!-- Revisions -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 text-center">
                        <h6 class="m-0 font-weight-bold text-danger">For Revision</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/undraw_access_denied.svg" alt="">
                        </div>
                        <p class="text-center">Your thesis manuscript has been considered for revision by the neccessary personnels<br> 
                            You may view the comment on your manuscript and comment section below
                        </p>
                        <a href="reupload_manuscript.php" class="btn btn-danger rounded-pill mt-2">Reupload Manuscript</a>
                    </div>
                </div>
                <?php } ?>
                <?php 
                $query="SELECT man_checkings.com_comment
                        FROM man_checkings
                        JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                        WHERE man_assignings.ass_res = '$group_name'";
                $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));
        
                while($row = mysqli_fetch_array($sql)) {  
                    ++$option;
                    $array_comment[] = $row['com_comment'];
                }
                if (!$array_comment) {
                    
                } else {

                ?>

                <!-- Comments -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Comments</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tr>
                                    <th>Adviser</th>
                                    <td><?php echo $array_comment[0]; ?></td>
                                  </tr>
                                  <tr>
                                    <th>Panel 1</th>
                                    <td><?php echo $array_comment[1]; ?></td>
                                  </tr>
                                  <tr>
                                    <th>Panel 2</th>
                                    <td><?php echo $array_comment[2]; ?></td>
                                  </tr>
                                  <tr>
                                    <th>Chairman</th>
                                    <td><?php echo $array_comment[3]; ?></td>
                                  </tr>
                                  <tr>
                                    <th>Dean</th>
                                    <td><?php echo $array_comment[4]; }?></td>
                                  </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php 
                    $query="SELECT COUNT(man_groupings.group_num) AS groupings
                    FROM man_groupings 
                    WHERE group_members = '$user_name'";
                    $sql = mysqli_query($conn, $query) or die ("Group Error: ".mysqli_error($conn));

                    while($row = mysqli_fetch_array($sql)) {      
                        $group = $row['groupings'];
                    }
                    if ($group>=1 && $group<=4){
                    ?>
                        <div class="card shadow mb-4" id="memberCard">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-danger">Members</h6>
                            </div>
                            <div class="card-body">
                                
                                <?php
                                    $query = "SELECT group_num FROM man_groupings WHERE group_members = '$user_name'";
                                    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                    
                                    while($row = mysqli_fetch_array($sql)) { ++$option;  
                                        $group_name=$row['group_num'];
                                ?>
                                <?php
                                    }
                                ?>
                                <?php
                                    $query = "SELECT tbl_userlists.user_id, 
                                    CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                    FROM `tbl_userlists` 
                                    JOIN man_groupings ON tbl_userlists.user_id COLLATE utf8mb4_unicode_ci = man_groupings.group_members
                                    WHERE man_groupings.group_num ='$group_name'";
                                    $sql = mysqli_query($conn, $query) or die ("System Error 2: ".mysqli_error($conn));
                                    $option=0;
                                    while($row = mysqli_fetch_array($sql)) { ++$option;  
                                ?>
                                    <!-- Member 1 -->
                                    <div class="form-group row mb-1">
                                        <label for="input-member1" class="col-sm-2 col-form-label">Member <?php echo $option;?></label>
                                        <div class="col-sm-10">                                     
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control form-control-user" id="<?php echo $row['user_id'];?>" name="<?php echo $row['user_id'];?>"
                                                placeholder="<?php echo $row['name'];?>">
                                            </div>
                                        </div>
                                    </div> 
                                <?php
                                    }
                                ?>                           
                                
                            </div>
                        </div>
                <?php
                    }else{
                ?>
                <!-- Selecting Group Members -->
                <div class="card shadow mb-4" id="memberCard">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Members</h6>
                    </div>
                    <div class="card-body">
                        <form action="controller_group.php" method="post" enctype="multipart/form-data">
                            <!-- Member 1 -->
                            <div class="form-group row mb-1">
                                <label for="form-member1" class="col-sm-2 col-form-label">Member 1</label>
                                <div class="col-sm-10">
                                    <select id="form-member1" class="form-control" name="member1">
                                        <?php
                                            $query = "SELECT tbl_userlists.user_id, 
                                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                            FROM `tbl_userlists` 
                                            WHERE user_position = 1 AND tbl_userlists.user_id ='$user_name' AND tbl_userlists.user_department = '$user_dept'";
                                            $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                            
                                            while($row = mysqli_fetch_array($sql)) { ++$option;  
                                        ?>
                                        <option value="<?php echo $row['user_id']; ?>" selected> <?php echo $row['name']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Member 2 -->
                            <div class="form-group row mb-1">
                                <label for="form-member2" class="col-sm-2 col-form-label">Member 2</label>
                                <div class="col-sm-10">
                                    <select id="form-member2"class="form-control" name="member2">
                                        <option value="" selected>-- NONE --</option>
                                        <?php
                                            $query = "SELECT tbl_userlists.user_id, 
                                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                            FROM `tbl_userlists`
                                            JOIN cod_students ON tbl_userlists.user_id COLLATE utf8mb4_general_ci = cod_students.user_id
                                            WHERE tbl_userlists.user_position = 1 AND tbl_userlists.user_id !='$user_name' AND cod_students.unique_id = '$unique_code'
                                            AND NOT EXISTS (SELECT  man_groupings.group_members FROM man_groupings WHERE man_groupings.group_members COLLATE utf8mb4_general_ci = tbl_userlists.user_id)";

                                            /*
                                            $query = "SELECT tbl_userlists.user_id, 
                                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                            FROM `tbl_userlists` 
                                            WHERE user_position = 1 AND tbl_userlists.user_id !='$user_name' AND tbl_userlists.user_department = '1'
                                            AND NOT EXISTS (SELECT  man_groupings.group_members FROM man_groupings WHERE man_groupings.group_members COLLATE utf8mb4_general_ci = tbl_userlists.user_id)";
                                            */
                                            $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                            
                                            while($row = mysqli_fetch_array($sql)) { 
                                        ?>
                                        <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Member 3 -->
                            <div class="form-group row mb-1">
                                <label for="form-member3" class="col-sm-2 col-form-label">Member 3</label>
                                <div class="col-sm-10">
                                    <select id="form-member3"class="form-control" name="member3">
                                        <option value="" selected>-- NONE --</option>
                                        <?php
                                            $query = "SELECT tbl_userlists.user_id, 
                                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                            FROM `tbl_userlists`
                                            JOIN cod_students ON tbl_userlists.user_id COLLATE utf8mb4_general_ci = cod_students.user_id
                                            WHERE tbl_userlists.user_position = 1 AND tbl_userlists.user_id !='$user_name' AND cod_students.unique_id = '$unique_code'
                                            AND NOT EXISTS (SELECT  man_groupings.group_members FROM man_groupings WHERE man_groupings.group_members COLLATE utf8mb4_general_ci = tbl_userlists.user_id)";

                                            /*
                                            $query = "SELECT tbl_userlists.user_id, 
                                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                            FROM `tbl_userlists` 
                                            WHERE user_position = 1 AND tbl_userlists.user_id !='$user_name' AND tbl_userlists.user_department = '1'
                                            AND NOT EXISTS (SELECT  man_groupings.group_members FROM man_groupings WHERE man_groupings.group_members COLLATE utf8mb4_general_ci = tbl_userlists.user_id)";
                                            */
                                            $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                            
                                            while($row = mysqli_fetch_array($sql)) { 
                                        ?>
                                        <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- Member 4 -->
                            <div class="form-group row mb-1">
                                <label for="form-member4" class="col-sm-2 col-form-label">Member 4</label>
                                <div class="col-sm-10">
                                    <select id="form-member4"class="form-control" name="member4">
                                        <option value="" selected>-- NONE --</option>
                                        <?php
                                            $query = "SELECT tbl_userlists.user_id, 
                                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                            FROM `tbl_userlists`
                                            JOIN cod_students ON tbl_userlists.user_id COLLATE utf8mb4_general_ci = cod_students.user_id
                                            WHERE tbl_userlists.user_position = 1 AND tbl_userlists.user_id !='$user_name' AND cod_students.unique_id = '$unique_code'
                                            AND NOT EXISTS (SELECT  man_groupings.group_members FROM man_groupings WHERE man_groupings.group_members COLLATE utf8mb4_general_ci = tbl_userlists.user_id)";

                                            /*
                                            $query = "SELECT tbl_userlists.user_id, 
                                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                            FROM `tbl_userlists` 
                                            WHERE user_position = 1 AND tbl_userlists.user_id !='$user_name' AND tbl_userlists.user_department = '1'
                                            AND NOT EXISTS (SELECT  man_groupings.group_members FROM man_groupings WHERE man_groupings.group_members COLLATE utf8mb4_general_ci = tbl_userlists.user_id)";
                                            */
                                            $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                            
                                            while($row = mysqli_fetch_array($sql)) {  
                                        ?>
                                        <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-1">
                                <input class="btn btn-danger rounded-pill mt-2" type="submit" name="submit_group" value="Submit">
                            </div>                            
                        </form>
                    </div>
                </div>
                <?php 
                    }
                ?>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    
    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>SPOT: Online Thesis Manuscript Checking Platform &copy; 2021</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->
    
    <!-- Manucript Rejecting Modal-->
    <div class="modal fade" id="RejectModal" tabindex="-1" role="dialog" aria-labelledby="RejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RejectModalLabel">Are you sure you want to unsubmit this manuscript?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body" hidden>
                    <input type="text" class="form-control form-control-user" id="manRej" value="<?php echo $man_id;?>" hidden>
                    <input type="text" class="form-control form-control-user" id="commentRej"
                        placeholder="Enter Comment" name="uid" value="Unsubmitted by <?php echo $fname; ?>">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button id="modRej" class="btn btn-danger buttRej" data-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        //FOR Rejecting
        $(".buttRej").click(function () {
            var man_id = $("#manRej").val();
            var comment = $("#commentRej").val();
            $.ajax({
                url:'controller_manuscript_rej.php',
                type:'POST',
                data: { man_id : man_id, comment: comment},
                success: function(result){
                    alert('Manuscript Rejected and considered for Revision');
                    $(location).prop('href', 'manuscript_status.php');
                }
            })
        });
    </script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>

<?php

    }}else{
        header("location:login.php");
    }
?>
