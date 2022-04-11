<?php
session_start ();
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

    $option = 0;

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
    <!-- JS -->
    <script>
        $(document).ready(function(){
            $("#uploadForm").on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt){
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(percentComplete + '%');
                                $(".progress-bar").html(percentComplete+'%');
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    url: 'controller_reupload.php',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function(){
                        $(".progress-bar").width('0%');
                        $('#uploadStatus').html('<img src="img/loading.gif"/>');
                    },
                    error:function(){
                        $('#uploadStatus').html('<p style="color:#EA4335;"> File upload failed, please try again.</p>');
                    },
                    success:function(resp){
                        if(resp == 'ok'){
                            $('#uploadForm')[0].reset();
                            $('#uploadStatus').html('<p style="color:#28A74B;"> File uploaded sucessfully!.</p>');
                            location.reload();
                        }else if (resp == 'err'){
                            $('#uploadStatus').html('<p style="color:#EA4335;"> Please Select a valid pdf file to upload.</p>');
                        }else{
                            $('#uploadStatus').html('<p style="color:#EA4335;"> ' + resp + '.</p>');
                        }
                    }
                });
            });

            // File type Validation
            $("#fileInput").change(function(){
                var allowedTypes = ['application/pdf'];
                var file = this.files[0];
                var filetype = file.type;
                if(!allowedTypes.includes(filetype)){
                    alert('Please select a valid PDF file.');
                    $("#fileInput").val('');
                    return false;
                }
            })
        });
    </script>

    <style>
        #progress-bar-file {background-color: #12CC1A;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
        #progress-bar-cert {background-color: #12CC1A;height:20px;color: #FFFFFF;width:0%;-webkit-transition: width .3s;-moz-transition: width .3s;transition: width .3s;}
    </style>


</head>

<body id="page-top">

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Reupload Manuscript</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            
                <!-- Approach -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="mx-1 font-weight-bold text-danger">Advisory</span>
                        <p class="mt-1">Please be ensure that the manuscript you were about to upload is in PDF format</p>
                    </div>
                    <div class="card-body">
                    <?php 
                        $query="SELECT group_num
                        FROM man_groupings 
                        WHERE group_members = '$user_name'";
                        $sql = mysqli_query($conn, $query) or die ("Group Error: ".mysqli_error($conn));
                        $num_row1 = mysqli_num_rows($sql);

                        if ($num_row1>0){
                            while($row = mysqli_fetch_array($sql)) {   
                                $group_name=$row['group_num'];
                            }

                            $query = "SELECT man_checkings.man_id, man_checkings.man_checker, man_checkings.com_position, man_checkings.com_response,
                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                            FROM man_checkings
                            JOIN man_assignings 
                            ON man_checkings.man_id = man_assignings.man_id
                            JOIN tbl_userlists 
                            ON man_checkings.man_checker = tbl_userlists.user_id
                            WHERE man_assignings.ass_res = '$group_name' AND tbl_userlists.user_position!=1";
                            $sql = mysqli_query($conn, $query) or die ("Group Error 2: ".mysqli_error($conn));

                            $array_man_id=[];
                            $array_man_checker=[];
                            $array_com_position=[];
                            $array_com_response=[];
                            $array_com_name=[];
                            $count=0;
                            while($row = mysqli_fetch_array($sql)) {
                                $array_man_id[] = $row['man_id'];
                                $array_man_checker[] = $row['man_checker'];
                                $array_com_position[] = $row['com_position'];
                                $array_com_response[] = $row['com_response'];
                                $array_com_name[] = $row['name'];
                                $count++;
                            }
                    ?>                  
                        <!-- UPLOADING -->
                        <div>
                            <form id="uploadForm" enctype="multipart/form-data">
                                <!-- ID -->
                                <div class="form-group row mb-1" hidden>
                                    <label for="form-manid" class="col-sm-2 col-form-label">Manuscript ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-user" id="form-manid" name="man_id"
                                        value="<?php echo $array_man_id[0];?>" required>
                                    </div>
                                </div>
                                <!-- ADVISER -->
                                <?php 

                                    if($array_com_response[0]==0 || $array_com_response[0]==3){

                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-adviser" class="col-sm-2 col-form-label">Adviser</label>
                                    <div class="col-sm-10">
                                        <select id="form-adviser" class="form-control" name="adviser" required>
                                            <option value="<?php echo $array_man_checker[0];?>" disabled selected>--Please select your adviser--</option>
                                            <?php
                                                $query = "SELECT tbl_userlists.user_id, 
                                                CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                                FROM `tbl_userlists` WHERE user_position != 1";
                                                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                                
                                                while($row = mysqli_fetch_array($sql)) { ++$option;  
                                            ?>
                                            <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php 
                                    }else{
                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-ad" class="col-sm-2 col-form-label">Adviser</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-user" id="form-ad"
                                        value="<?php echo $array_com_name[0];?>" readonly>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                                <!-- PANEL 1 and 2-->
                                <?php 

                                    if($array_com_response[1]==0 || $array_com_response[1]==3){

                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-panel1" class="col-sm-2 col-form-label">Panel 1</label>
                                    <div class="col-sm-10">
                                        <select id="form-panel1"class="form-control" name="panel1" required>
                                            <option value="<?php echo $array_man_checker[1];?>" disabled selected>--Please select your panel 1--</option>
                                            <?php
                                                $query = "SELECT tbl_userlists.user_id, 
                                                CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                                FROM `tbl_userlists` WHERE user_position != 1";
                                                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                                
                                                while($row = mysqli_fetch_array($sql)) { ++$option;  
                                            ?>
                                            <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php 
                                    }else{
                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-p1" class="col-sm-2 col-form-label">Panel 1</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-user" id="form-p1"
                                        value="<?php echo $array_com_name[1];?>" readonly>
                                    </div>
                                </div>
                                <?php
                                    }

                                    if($array_com_response[2]==0 || $array_com_response[2]==3){

                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-panel2" class="col-sm-2 col-form-label">Panel 2</label>
                                    <div class="col-sm-10">
                                        <select id="form-panel2"class="form-control" name="panel2" required>
                                            <option value="<?php echo $array_man_checker[2];?>" disabled selected>--Please select your panel 2--</option>
                                            <?php
                                                $query = "SELECT tbl_userlists.user_id, 
                                                CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                                FROM `tbl_userlists` WHERE user_position != 1";
                                                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                                
                                                while($row = mysqli_fetch_array($sql)) { ++$option;  
                                            ?>
                                            <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php 
                                    }else{
                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-p2" class="col-sm-2 col-form-label">Panel 2</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-user" id="form-p2"
                                        value="<?php echo $array_com_name[2];?>" readonly>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                                <!-- CHAIRMAN-->
                                <?php 

                                    if($array_com_response[3]==0 || $array_com_response[3]==3){

                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-chair" class="col-sm-2 col-form-label">Chairman</label>
                                    <div class="col-sm-10">
                                        <select id="form-chair"class="form-control" name="chair" required>
                                            <option value="<?php echo $array_man_checker[3];?>" disabled selected>--Please select your Chairman--</option>
                                            <?php
                                                $query = "SELECT tbl_userlists.user_id, 
                                                CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                                FROM `tbl_userlists` WHERE user_position != 1";
                                                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                                
                                                while($row = mysqli_fetch_array($sql)) { ++$option;  
                                            ?>
                                            <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php 
                                    }else{
                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-ch" class="col-sm-2 col-form-label">Chairman</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-user" id="form-ch"
                                        value="<?php echo $array_com_name[3];?>" readonly>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>
                                <!-- DEAN -->
                                <?php 

                                    if($array_com_response[4]==0 || $array_com_response[4]==3){

                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-chair" class="col-sm-2 col-form-label">Dean</label>
                                    <div class="col-sm-10">
                                        <select id="form-chair"class="form-control" name="dean" required>
                                            <option value="<?php echo $array_man_checker[0];?>" disabled selected>--Please select your College Dean--</option>
                                            <?php
                                                $query = "SELECT tbl_userlists.user_id, 
                                                CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name
                                                FROM `tbl_userlists` WHERE user_position = 4";
                                                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                                
                                                while($row = mysqli_fetch_array($sql)) { ++$option;  
                                            ?>
                                            <option value="<?php echo $row['user_id']; ?>"> <?php echo $row['name']; ?></option>
                                            <?php
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php 
                                    }else{
                                ?>
                                <div class="form-group row mb-1">
                                    <label for="form-p2" class="col-sm-2 col-form-label">Dean</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-user" id="form-p2"
                                        value="<?php echo $array_com_name[4];?>" readonly>
                                    </div>
                                </div>
                                <?php
                                    }
                                ?>

                                <!-- Research Title -->
                                <div class="form-group row mb-1">
                                    <label for="form-mantit" class="col-sm-2 col-form-label">Research Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-user" id="form-mantit" name="restitle"
                                        placeholder="--Please input your Research Title--" required>
                                    </div>
                                </div>

                                <!-- Research File -->
                                <div class="form-group row mb-1">
                                    <label for="form-mantit" class="col-sm-3 col-form-label">Research File</label>
                                    <div class="custom-file col-sm-9" id="file">
                                        <input type="file" class="custom-file-input" id="file" name="file" accept=".pdf" required>
                                        <label class="custom-file-label" for="file">--Choose Research File--</label>
                                    </div>
                                </div>

                                <!-- Research Certificate -->
                                <div class="form-group row mb-1">
                                    <label for="cert" class="col-sm-3 col-form-label">Grammarian Certificate</label>
                                    <div class="custom-file col-sm-9">
                                        <input type="file" accept=".pdf" class="custom-file-input" id="cert" name="cert" required>
                                        <label class="custom-file-label" for="file">--Choose Grammarian Certification file--</label>
                                        <input class="btn btn-danger rounded-pill mt-2 mb-2" id="btnSubmit" type="submit" name="submit_manuscript" value="Submit">
                                    </div>
                                </div>                            
                            </form>
                            <!-- Progress Bar -->
                            <div class="progress mt-5">
                                <div class="progress-bar">

                                </div>
                            </div>
                            <div id="uploadStatus mt-1">

                            </div>
                              
                              <script>
                              // The name of the file appear on select
                              $(".custom-file-input").on("change", function() {
                                var fileName = $(this).val().split("\\").pop();
                                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                              });
                              </script>
                        </div>
                        
                    <?php 
                        }else
                        {
                    ?>
                        <div>
                            <p class="mt-1">Please Assign Groupings First</p>
                        </div>
                    <?php 
                        }
                    ?>
                    </div>
                </div>

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

    }else{
        header("location:login.php");
    }
?>