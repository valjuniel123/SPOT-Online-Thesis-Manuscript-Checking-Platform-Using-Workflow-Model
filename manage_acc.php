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
            <h1 class="h2 mb-2 text-gray-800">Account Management</h1>
            <?php if($user_pos==4){ ?>

            <a href="#" data-toggle="modal" data-target="#CodeModal" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-user-friends fa-sm text-white-50 mr-2"></i>Dean Codes</a>

            <?php } ?>
            <a href="#" data-toggle="modal" data-target="#AccountModal" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-user-friends fa-sm text-white-50 mr-2"></i>Account Approval</a>

                <?php
                    if ($user_pos==4){
                ?>
            

                <?php } ?>
        </div>
        
        <!-- DataTables for Account Management -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger">Accounts</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th with>ID</th>
                                <th with>Name</th>
                                <th>Position</th>
                                <th width="20%">Actions</th>
                                <th id="accountLName" style="display:none;">Last Name</th>
                                <th id="accountFName" style="display:none;">First Name</th>
                                <th id="accountMName" style="display:none;">Middle Name</th>
                                <th id="accountEmail" style="display:none;">Email</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th width="20%">Actions</th>
                                <th id="accountLName" style="display:none;">Last Name</th>
                                <th id="accountFName" style="display:none;">First Name</th>
                                <th id="accountMName" style="display:none;">Middle Name</th>
                                <th id="accountEmail" style="display:none;">Email</th>
                            </tr>
                        </tfoot>
                        <tbody id="dataTablemain">
                            <?php
                                    $query = "SELECT tbl_userlists.user_id, tbl_userlists.user_lname, tbl_userlists.user_fname, tbl_userlists.user_mname, tbl_userlists.user_email,
                                    CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name, tbl_positions.pos_name, tbl_departments.depnick 
                                    FROM `tbl_userlists` 
                                    JOIN tbl_positions ON tbl_userlists.user_position = tbl_positions.pos_id
                                    JOIN tbl_departments ON tbl_userlists.user_department = tbl_departments.dep_id
                                    JOIN cod_instructor ON tbl_userlists.user_id COLLATE utf8mb4_unicode_ci =  cod_instructor.user_id
                                    WHERE cod_instructor.user_code='$unique_code' AND tbl_positions.pos_id !='1'";
                                    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                    
                                    while($row = mysqli_fetch_array($sql)) { ++$option;
                                ?>
                                <tr id="mtr_<?php echo $row['user_id']; ?>">
                                    <td id="accountID" class='user_id'><?php echo $row['user_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td id="accountPosition"><?php echo $row['pos_name']; ?></td>
                                    <td class="text-center align-middle">
                                        <button href="#" data-toggle="modal" data-target="#EditModal" 
                                        class="btn btn-warning user-edit">Edit</button>
                                        <button class="btn btn-danger" 
                                        onclick="deleteUser('<?php echo $row['user_id']; ?>')">Delete</button>
                                    </td>
                                    <td id="accountLName" style="display:none;"><?php echo $row['user_lname']; ?></td>
                                    <td id="accountFName" style="display:none;"><?php echo $row['user_fname']; ?></td>
                                    <td id="accountMName" style="display:none;"><?php echo $row['user_mname']; ?></td>
                                    <td id="accountEmail" style="display:none;"><?php echo $row['user_email']; ?></td>
                                </tr>
                            <?php } 
                            
                                $query = "SELECT tbl_userlists.user_id, tbl_userlists.user_lname, tbl_userlists.user_fname, tbl_userlists.user_mname, tbl_userlists.user_email,
                                CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name, tbl_positions.pos_name, tbl_departments.depnick 
                                FROM `tbl_userlists` 
                                JOIN tbl_positions ON tbl_userlists.user_position = tbl_positions.pos_id
                                JOIN tbl_departments ON tbl_userlists.user_department = tbl_departments.dep_id
                                JOIN cod_students ON tbl_userlists.user_id COLLATE utf8mb4_unicode_ci =  cod_students.user_id
                                WHERE cod_students.unique_id='$unique_code' AND tbl_positions.pos_id=1";
                                
                                $sql = mysqli_query($conn, $query) or die ("System Error 2: ".mysqli_error($conn));
                                
                                while($row = mysqli_fetch_array($sql)) { ++$option;
                            ?>
                                <tr id="mtr_<?php echo $row['user_id']; ?>">
                                    <td id="accountID" class='user_id'><?php echo $row['user_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td id="accountPosition"><?php echo $row['pos_name']; ?></td>
                                    <td class="text-center align-middle">
                                        <button href="#" data-toggle="modal" data-target="#EditModal" 
                                        class="btn btn-warning user-edit">Edit</button>
                                        <button class="btn btn-danger" 
                                        onclick="deleteUser('<?php echo $row['user_id']; ?>')">Delete</button>
                                    </td>
                                    <td id="accountLName" style="display:none;"><?php echo $row['user_lname']; ?></td>
                                    <td id="accountFName" style="display:none;"><?php echo $row['user_fname']; ?></td>
                                    <td id="accountMName" style="display:none;"><?php echo $row['user_mname']; ?></td>
                                    <td id="accountEmail" style="display:none;"><?php echo $row['user_email']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

    <!-- User Account Edit Modal-->
    <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content card">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Accounts</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="container">
                        <form method="POST" action="controller_accounts_edituser.php">
                            <div class="form-group row mb-2">
                                <div class="col-4">
                                    <p class="font-weight-bold">User ID</p>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-user" id="userID"
                                        placeholder="User ID" name="userID" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-4">
                                    <p class="font-weight-bold">Position</p>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-user mb-2" id="userPosition"
                                        placeholder="Position" name="userPosition" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-4">
                                    <p class="font-weight-bold">Last Name</p>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-user mb-2" id="userLName"
                                        placeholder="Enter Last Name" name="userLName">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-4">
                                    <p class="font-weight-bold">First Name</p>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-user mb-2" id="userFName"
                                        placeholder="Enter First Name" name="userFName">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-4">
                                    <p class="font-weight-bold">Middle Name</p>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control form-control-user mb-2" id="userMName"
                                        placeholder="Enter Middle Name" name="userMName">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-4">
                                    <p class="font-weight-bold">Email</p>
                                </div>
                                <div class="col">
                                    <input type="email" class="form-control form-control-user mb-2" id="userEmail"
                                        placeholder="Enter Email" name="userEmail">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <button class="btn btn-danger" name="submit">Save</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Account Approval Modal-->
    <div class="modal fade" id="AccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Accounts Approval</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="dataTables">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th with>ID</th>
                                    <th with>Name</th>
                                    <th>Position</th>
                                    <th width="20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                /*
                                    $query = "SELECT acc_pendings.user_id, 
                                    CONCAT(acc_pendings.user_lname,', ', acc_pendings.user_fname,' ',acc_pendings.user_mname) as name, tbl_departments.depnick 
                                    FROM `acc_pendings` 
                                    JOIN tbl_departments ON acc_pendings.user_department = tbl_departments.dep_id";
                                */
                                    $query = "SELECT acc_pendings.user_id, 
                                    CONCAT(acc_pendings.user_lname,', ', acc_pendings.user_fname,' ',acc_pendings.user_mname) as name, tbl_positions.pos_name, tbl_departments.depnick 
                                    FROM `acc_pendings` 
                                    JOIN tbl_positions ON acc_pendings.user_position = tbl_positions.pos_id
                                    JOIN tbl_departments ON acc_pendings.user_department = tbl_departments.dep_id
                                    WHERE unique_id='$unique_code'";
                                    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                    
                                    while($row = mysqli_fetch_array($sql)) { ++$option;
                                ?>
                                <tr id="tr_<?php echo $row['user_id']; ?>">
                                    <td class='user_id'><?php echo $row['user_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['pos_name']; ?></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-success" 
                                        onclick="approveReg('<?php echo $row['user_id']; ?>')">Accept</button>
                                        <button class="btn btn-danger" id="testing" 
                                        onclick="deleteReg('<?php echo $row['user_id']; ?>')">Delete</button>
                                    </td>
                                </tr>
                                <?php } 

                                    //FOR INSTRUCTOR PANEL DEAN
                                    $query = "SELECT acc_pendings.user_id, 
                                    CONCAT(acc_pendings.user_lname,', ', acc_pendings.user_fname,' ',acc_pendings.user_mname) as name, tbl_positions.pos_name, tbl_departments.depnick 
                                    FROM `acc_pendings` 
                                    JOIN tbl_positions ON acc_pendings.user_position = tbl_positions.pos_id
                                    JOIN tbl_departments ON acc_pendings.user_department = tbl_departments.dep_id
                                    WHERE tbl_departments.dep_id='$user_dept' AND tbl_positions.pos_id >1";
                                    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                    
                                    while($row = mysqli_fetch_array($sql)) { ++$option;
                                ?>
                                <tr id="tr_<?php echo $row['user_id']; ?>">
                                    <td class='user_id'><?php echo $row['user_id']; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['pos_name']; ?></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-success" 
                                        onclick="approveRegAdm('<?php echo $row['user_id']; ?>')">Accept</button>
                                        <button class="btn btn-danger" id="testing" 
                                        onclick="deleteReg('<?php echo $row['user_id']; ?>')">Delete</button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <p id="msg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dean Code Modal-->
    <div class="modal fade" id="CodeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Dean Codes</h5>
                    <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm ml-5" type="button" aria-label="Generate Code">
                        <span aria-hidden="true" onclick="codeCopyAll('<?php echo $user_name;?>')">Copy All Codes</span>
                    </button>
                    <button class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm ml-5" type="button" aria-label="Generate Code">
                        <span aria-hidden="true" onclick="codeGenerate('<?php echo $user_name;?>')">Generate Code</span>
                    </button>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="dataTables">
                        <table class="table table-bordered" id="deanTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                    $query = "SELECT cod_dean.user_id, cod_dean.dean_code FROM cod_dean
                                    WHERE cod_dean.user_id='$user_name'";
                                    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                                    
                                    while($row = mysqli_fetch_array($sql)) { ++$option;
                                ?>
                                <tr id="tr_<?php echo $row['dean_code']; ?>">
                                    <td><?php echo $row['dean_code']; ?></td>
                                    <td class="text-center align-middle">
                                        <button class="btn btn-warning" 
                                        onclick="codeCopy('<?php echo $row['dean_code']; ?>')">Copy</button>
                                        <button class="btn btn-danger" id="testing" 
                                        onclick="codeDelete('<?php echo $row['dean_code']; ?>')">Delete</button>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <p id="msg"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function codeCopy(copyText) {
            /* Get the text field */

            /* Copy the text inside the text field */
            navigator.clipboard.writeText(copyText);

            /* Alert the copied text */
            alert("Copied the text: " + copyText);
        }
    </script>

    <script type="text/javascript">
        //CODE COPY ALL
        function codeCopyAll(user_id){
            jQuery.ajax({
                url:'controller_codeCopy_all.php',
                type:'POST',
                data: { user_id : user_id },
                dataType: 'html',
                success: function(result){
                    navigator.clipboard.writeText(result);
                    alert("Copied")
                }
            })
        }
    //Dean Code Modal
        function codeGenerate(user_id){
            jQuery.ajax({
                url:'controller_generate_dean.php',
                type:'POST',
                data: { user_id : user_id },
                dataType: 'html',
                success: function(result){
                    markup = "<tr id='tr_"+result+"'><td>" 
                        + result + "</td><td class='text-center align-middle'>"
                        +"<button class='btn btn-warning'"
                        +"onclick='codeCopy('"+result+"')'>Copy</button>"
                        +"<button class='btn btn-danger' id='testing'"
                        +"onclick=codeDelete('"+result+"')>Delete</button>"
                        +"</td></tr>";
                    tableBody = $("#tableBody");
                    tableBody.append(markup);
                }
            })
        }
        function codeDelete(user_id){
            if(confirm('Are you sure you want to delete this code? This can not be undone')){
                jQuery.ajax({
                    url:'controller_delete_dean.php',
                    type:'POST',
                    data: { user_id : user_id },
                    dataType: "html",
                    success: function(result){
                        jQuery('#tr_'+user_id).hide();
                        alert("Code " +result+ " Successfully Deleted");
                    }
                })
            }
        }

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

        function approveRegAdm(user_id){
            if(confirm('Are you sure you want to approve this registration?')){
                jQuery.ajax({
                    url:'controller_accounts_adm.php',
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

        function deleteUser(user_id){
            if(confirm('Are you sure you want to delete this account? This can not be undone.')){
                jQuery.ajax({
                    url:'controller_accounts_deleteuser.php',
                    type:'POST',
                    data: { user_id : user_id },
                    dataType: "html",
                    success: function(result){
                        jQuery('#mtr_'+user_id).hide();
                        alert('Account Deleted');
                    }
                })
            }
        }
    //FOR MAIN
        $(".user-edit").click(function() {
            var $row = $(this).closest("tr");    //  Find the row
            var $aID = $row.find("#accountID").text(); // Find the text
            var $aPos = $row.find("#accountPosition").text(); // Find the text
            var $aLName = $row.find("#accountLName").text(); // Find the text
            var $aFName = $row.find("#accountFName").text(); // Find the text
            var $aMName = $row.find("#accountMName").text(); // Find the text
            var $aEmail = $row.find("#accountEmail").text(); // Find the text
            
            //STORE THE TEXT
            $("#userID").val($aID);
            $("#userPosition").val($aPos);
            $("#userLName").val($aLName);
            $("#userFName").val($aFName);
            $("#userMName").val($aMName);
            $("#userEmail").val($aEmail);

        });
        
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