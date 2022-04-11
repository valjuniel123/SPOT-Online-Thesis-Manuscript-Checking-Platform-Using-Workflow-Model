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

        $query="SELECT user_code FROM cod_instructor WHERE user_id='$user'"; 
        $sql = mysqli_query($conn, $query) or die ("Registration Error: ".mysqli_error($conn));

        $unique_code="";
        while($row = mysqli_fetch_array($sql)) {      
            $unique_code = $row['user_code'];
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
    
    <!-- Custom styles for this page -->
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

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
            <h1 class="h2 mb-2 text-gray-800">Group Management</h1>

        </div>
        
        <!-- DataTables for Group Management -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Groups</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <?php
                    $count=0;
                    $array_groups = [];
                    $main_query = "SELECT DISTINCT group_num 
                    FROM `man_groupings`
                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                    WHERE cod_students.unique_id='$unique_code'";
                    $main_sql = mysqli_query($conn, $main_query) or die ("System Error 1: ".mysqli_error($conn));
                    
                    while($main_row = mysqli_fetch_array($main_sql)) {
                        $array_groups[] = $main_row['group_num'];
                ?>
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th with class="col-sm-2">SR Code</th>
                                <th with>Group Members</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="dataTablemain">
                            <?php
                                $query = "SELECT CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name, tbl_userlists.user_id
                                FROM `tbl_userlists` 
                                JOIN man_groupings ON tbl_userlists.user_id COLLATE utf8mb4_unicode_ci = man_groupings.group_members
                                WHERE man_groupings.group_num ='$array_groups[$count]'";
                                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));  
                                while($row = mysqli_fetch_array($sql)) {
                            ?>

                            <tr id="mtr_<?php echo $row['user_id']; ?>">
                                <td class='user_id'><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-danger" 
                                    onclick="deleteGroup('<?php echo $row['user_id']; ?>')">Delete</button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php $count++;} ?>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    <script type="text/javascript">
    //FOR MODAL
        function deleteReg(user_id){
            if(confirm('Are you sure you want to delete this registration? This can not be undone.')){
                jQuery.ajax({
                    url:'controller_accounts_del.php',
                    type:'POST',
                    data: { user_id : user_id },
                    dataType: "html",
                    success: function(result){
                        jQuery('#tr_'+user_id).hide();
                        alert('Registration Deleted');
                    }
                })
            }
        }
        function approveReg(user_id){
            if(confirm('Are you sure you want to approve this registration?')){
                jQuery.ajax({
                    url:'controller_accounts_app.php',
                    type:'POST',
                    data: { user_id : user_id },
                    dataType: "html",
                    success: function(result){
                        jQuery('#tr_'+user_id).hide();
                        alert('Registration Approved');
                    }
                })
            }
        }
        function deleteGroup(user_id){
            if(confirm('Are you sure you want to delete this group member? This can not be undone.')){
                jQuery.ajax({
                    url:'controller_group_del.php',
                    type:'POST',
                    data: { user_id : user_id },
                    dataType: "html",
                    success: function(result){
                        jQuery('#mtr_'+user_id).hide();
                        alert('Group Member Deleted');
                    }
                })
            }
        }
        
    </script>
<!--
    <script type="text/javascript">
        $(document).ready(function() {
            $("#dataTablemain").click(function() {                
                $.ajax({    //create an ajax request to display.php
                    type: "GET",
                    url: "controller_account_reload.php",             
                    dataType: "html",   //expect html to be returned                
                    success: function(response){                    
                        $("#dataTablemain").html(response); 
                    }
                });
            });
        });

    </script>
-->

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

</body>

</html>

<?php

    }else{
        header("location:login.php");
    }
?>