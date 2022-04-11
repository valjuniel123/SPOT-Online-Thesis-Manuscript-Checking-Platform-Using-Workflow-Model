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
        $query="SELECT user_fname FROM tbl_userlists WHERE user_id='$user'"; 
        $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

        while($row = mysqli_fetch_array($sql)) {      
            $user_fname = $row['user_fname'];
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
    <link rel="shortcut icon" type="image/x-icon" href="img/red_spartans.ico" />

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon">
                    <img src="img/red_spartans.png" class="img-fluid" alt="Spartan" width="50" height="600">
                </div>
                <div class="sidebar-brand-text mx-3">SPOT</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            
            <li class="nav-item active">
                <a class="nav-link bg-dark" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    <span>Logout</span>
                </a>

                <hr class="sidebar-divider my-0">

                <a class="nav-link" href="dashboard.php" target="contentarea">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading 
            <div class="sidebar-heading">
                Interface
            </div>
            
            -->

            <!-- Nav Item - Pages Collapse Menu -->
            <!-- INSTRUCTOR -->
            
            <li class="nav-item">
                <?php
                    if($user_pos=='1'){
                ?>
                <a class="nav-link" href="upload_manuscript.php" target="contentarea">
                    <i class="fas fa-fw fa-upload"></i>
                    <span>Upload Manuscript</span>
                </a>
                <a class="nav-link" href="manuscript_status.php" target="contentarea">
                    <i class="fas fa-fw fa-info"></i>
                    <span>Manuscript Status</span>
                </a>
                <?php
                    }
                    if($user_pos=='2'){

                ?>
                <a class="nav-link" href="manage_acc.php" target="contentarea">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span>Manage Accounts</span>
                </a>
                <a class="nav-link" href="manage_group.php" target="contentarea">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span>Manage Groups</span>
                </a>
                <?php
                    }
                    if($user_pos=='4'){
                        $query="SELECT user_code FROM cod_instructor WHERE user_id='$user_name'";
                        $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));
                        $user_code="";
                        while($row = mysqli_fetch_array($sql)) {      
                            $user_code=$row['user_code'];
                        }
                        if($user_code!=""){
                ?>
                
                <a class="nav-link" href="manage_acc.php" target="contentarea">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span>Manage Accounts</span>
                </a>
                <a class="nav-link" href="manage_group.php" target="contentarea">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span>Manage Groups</span>
                </a>

                <?php

                        }
                ?>

                <a class="nav-link" href="dean_signing.php" target="contentarea">
                    <i class="fas fa-fw fa-file-signature"></i>
                    <span>Sign Manuscript</span>
                </a>
                
                <?php
                    }
                    if($user_pos>'1'){
                ?>
                <a class="nav-link" href="pan_checking.php" target="contentarea">
                    <i class="fas fa-fw fa-check"></i>
                    <span>Check Manuscript</span>
                </a>
                <a class="nav-link" href="com_history.php" target="contentarea">
                    <i class="fas fa-fw fa-history"></i>
                    <span>History</span>
                </a>
                <?php
                    }
                ?>
                <a class="nav-link" href="approved_man.php" target="contentarea">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Approved Manuscript</span>
                </a>
                <!--
                <a class="nav-link" href="https://www.pdfzorro.com/api.php?path_to_pdf=http://spot-checking.000webhostapp.com/uploads/8.pdf&save_url=http://spot-checking.000webhostapp.com/pdf_fetch.php&titel_save=title&data=123456">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Fetch Manuscript</span>
                </a>
                -->

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="/acc_man" target="contentarea">Manage Accounts</a>
                        <a class="collapse-item" href="/status_man" target="contentarea">Manuscript Status</a>
                    </div>
                </div>
            </li>

            
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

            <!-- Date and Time-->
            <div class="mt-6">
                <li class="nav-item active">
                    <a class="nav-link bg-dark text-center disabled">
                        <span id="date-part"></span>
                    </a>
                    <a class="nav-link bg-dark text-center disabled">
                        <span id="time-part"></span>
                    </a>
                </li>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <?php
                        if ($user_pos==2 || $user_pos==4){
                    ?>
                    <!-- Topbar Search -->
                    <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <h1 class="h6 mt-2 mr-4 font-weight-bold">Instructor Code</h1>
                            <?php 
                                $unique_code="";
                                $query="SELECT user_code FROM cod_instructor WHERE `user_id` = '$user_name'";
                                $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
                                while($row = mysqli_fetch_array($sql)){
                                    $unique_code = $row['user_code'];
                                }
                            ?>
                            <input type="text" class="form-control border-left-danger bg-light border-0 small text-center font-weight-bold" placeholder="No Code Available"
                                aria-label="Instructors Code" aria-describedby="basic-addon2" id="uCodeText" value="<?php echo $unique_code; ?>">
                            <div class="input-group-append">
                                <?php 
                                    if($unique_code!=""){
                                ?>
                                    <button class="btn btn-danger" onclick="copyFunction()" data-toggle="tooltip" title="Click to copy the Unique Code for the Student">
                                        <span class="small">Copy</span>
                                    </button>
                                <?php 
                                    }else{
                                ?>
                                <form action="controller_generate_code.php" method="post">
                                    <input class="btn btn-danger small" type="submit" name="generate_code" value="Generate">
                                </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <?php } ?>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        
                        <!-- Nav Item - Notifications -->
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1" >
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw" data-toggle="tooltip" title="Notifications"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter count"></span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <ul class="dropdown-menu">
                                    
                                </ul>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"> Hello! &nbsp<?php echo $user_fname ?> </span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div id="tabs">
                        <div id="tbcontent">
                            <iframe src="dashboard.php" name ="contentarea" id="contentarea" scrolling="auto" style="width:100%;height:720px;border:0px;">
                            </iframe>	
                        </div>	
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        function copyFunction() {
            /* Get the text field */
            var copyText = document.getElementById("uCodeText");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText.value);

            /* Alert the copied text */
            alert("Copied the text: " + copyText.value);
        }

        $(document).ready(function(){
            var interval = setInterval(function() {
                var momentNow = moment();
                $('#date-part').html(momentNow.format('MMMM DD YYYY') + ' '
                                    .substring(0,3).toUpperCase());
                $('#time-part').html(momentNow.format('hh:mm:ss A'));
            }, 100);
            
            function load_unseen_notification(view = ''){
                $.ajax({
                    url:"controller_notifications.php",
                    method:"POST",
                    data:{view:view},
                    dataType:"json",
                    success:function(data)
                    {
                        $('.dropdown-menu').html(data.notification);
                        if(data.unseen_notification > 0)
                        {
                        $('.count').html(data.unseen_notification);
                        }
                    }
                });
            }
            
            load_unseen_notification();
 
            $(document).on('click', '.dropdown-toggle', function(){
            $('.count').html('');
            load_unseen_notification('yes');
            });
            
            setInterval(function(){ 
            load_unseen_notification();; 
            }, 5000);
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

    }else{
        header("location:login.php");
    }
?>