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
        <h1 class="h3 mb-2 text-gray-800">Approved Manuscript</h1>
        
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Manuscripts</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date Approved</th>
                                <th>Research Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Date Approved</th>
                                <th>Research Title</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        <?php

                            $query = "SELECT tbl_manuscripts.man_id, tbl_manuscripts.man_title, man_checkings.date_checked
                            FROM tbl_manuscripts
                            JOIN man_checkings on tbl_manuscripts.man_id = man_checkings.man_id
                            WHERE man_checkings.com_response = '5' AND com_position = '5'";
                            /*
                            $query = "SELECT tbl_userlists.user_id, 
                            CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) as name, tbl_positions.pos_name, tbl_departments.depnick 
                            FROM `tbl_userlists` 
                            JOIN tbl_positions ON tbl_userlists.user_position = tbl_positions.pos_id
                            JOIN tbl_departments ON tbl_userlists.user_department = tbl_departments.dep_id";
                            */

                            //WHERE tbl_departments.depnick=''";
                            $sql = mysqli_query($conn, $query) or die ("System Error 2: ".mysqli_error($conn));
                            
                            while($row = mysqli_fetch_array($sql)) { ++$option;
                        ?>
                            <tr id=tr_<?php echo $row['man_id']; ?>>
                                <td><?php echo $row['date_checked']; ?></td>
                                <td><?php echo $row['man_title']; ?></td>
                                <td class="text-center align-middle">
                                    <div class="btn-group-vertical">
                                        <a class="btn btn-dark" 
                                        href="https://spot-checking.com/uploads/<?php echo $row['man_id']; ?>.pdf"
                                        target="_blank">View</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- Manucript Approval Modal-->
    <div class="modal fade" id="ApproveModal" tabindex="-1" role="dialog" aria-labelledby="ApproveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ApproveModalLabel">Are you sure you want to approve this manuscript?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <input type="text" class="form-control form-control-user" id="commentApp"
                        placeholder="Enter Comment" name="uid">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button id="modApp" class="btn btn-success buttApp" >Approve</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Manucript Rejecting Modal-->
    <div class="modal fade" id="RejectModal" tabindex="-1" role="dialog" aria-labelledby="RejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="RejectModalLabel">Are you sure you want to reject this manuscript?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <input type="text" class="form-control form-control-user" id="commentRej"
                        placeholder="Enter Comment" name="uid">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button id="modApp" class="btn btn-success buttRej" >Reject</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".buttApp").click(function () {
            var man_id = $(".passingIDApp").attr('data-id');
            var comment = $("#commentApp").val();
            $.ajax({
                url:'controller_manuscript_app.php',
                type:'POST',
                data: { man_id : man_id, comment: comment},
                success: function(result){
                    jQuery('#tr_'+man_id).hide();
                    alert('Manuscript Approved');
                    alert(result);
                }
            })
        });
        $(".buttRej").click(function () {
            var man_id = $(".passingIDRej").attr('data-id');
            var comment = $("#commentRej").val();
            $.ajax({
                url:'controller_manuscript_rej.php',
                type:'POST',
                data: { man_id : man_id, comment: comment},
                success: function(result){
                    jQuery('#tr_'+man_id).hide();
                    alert('Manuscript Rejected and considered for Revision');
                    alert(result);
                }
            })
        });
    </script>
    <!-- /.container-fluid -->

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
