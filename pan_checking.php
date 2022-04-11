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
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-2 text-gray-800">Check Manuscript</h1>

            <a href="#" data-toggle="modal" data-target="#manualModal" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm">
                <i class="fas fa-question fa-sm text-white-50 mr-2"></i>Help</a>
        </div>
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
                                <th>Date</th>
                                <th>Research Title</th>
                                <th>Status</th>
                                <th hidden>Manuscript</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Research Title</th>
                                <th>Status</th>
                                <th hidden>Manuscript</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        <?php

                            $query = "SELECT tbl_manuscripts.man_id, tbl_manuscripts.man_title, man_checkings.date_start, man_checkings.com_position
                            FROM tbl_manuscripts
                            JOIN man_checkings on tbl_manuscripts.man_id = man_checkings.man_id
                            WHERE man_checkings.man_checker = '$user_name' 
                            AND man_checkings.com_response = '1' AND man_checkings.com_position < '5'";
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
                                <td><?php echo $row['date_start']; ?></td>
                                <td>
                                    <?php echo $row['man_title']; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    <?php 
                                                        $total=((($row['com_position']-1)/5)*100);
                                                    ?>
                                                    style="width: <?php echo $total; ?>%" aria-valuenow="50" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-xs font-weight-bold text-dark text-uppercase mb-1"><?php echo ($row['com_position']-1); ?> out of 5 approval
                                    </div>
                                </td>
                                <td id="mID" hidden>
                                    <?php echo $row['man_id']; ?>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group-vertical">
                                        <a class="btn btn-success passingIDApp" data-id="<?php echo $row['man_id']; ?>"
                                        href="#" data-toggle="modal" data-target="#ApproveModal">Approve</a>
                                        <a class="btn btn-dark" 
                                        href="https://www.pdfzorro.com/api.php?path_to_pdf=http://spot-checking.com/uploads/<?php echo $row['man_id']; ?>.pdf&save_url=http://spot-checking.com/pdf_fetch.php&titel_save=title&data=123456"
                                        target="_blank">Annotate</a>
                                        <a class="btn btn-danger passingIDRej" data-id="<?php echo $row['man_id']; ?>" 
                                        href="#" data-toggle="modal" data-target="#RejectModal">Reject</a>
                                        <a class="btn btn-outline-secondary" 
                                        href="https://spot-checking.com/uploads/<?php echo $row['man_id']; ?>.pdf"
                                        target="_blank">View Manuscript</a>
                                        <a class="btn btn btn-outline-primary" 
                                        href="https://spot-checking.com/uploads/g_<?php echo $row['man_id']; ?>.pdf"
                                        target="_blank">View Grammarian Certificate</a>
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
    
    <!-- Manucript Manual Modal-->
    <div class="modal fade" id="manualModal" tabindex="-1" role="dialog" aria-labelledby="manualLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manualLabel">FREQUENTLY ASKED QUESTIONS</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div> 
                <div class="modal-body">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="d-none d-lg-inline text-gray-600 small">
                                        <i class="far fa-question-circle" aria-hidden="true"></i> 
                                        <!--How to enroll subjects?-->
                                        <u class="text-dark text-decoration-none">How to Approve or Reject Manuscript?</u>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    <strong class="text-danger">Approving or Rejecting Manuscript :</strong>
                                    <hr>
                                    <ul class="uk-list uk-list-bullet" style="margin-left:10px;font-size:15px !important">
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 1</strong> : Click the <strong class="text-dark">Approve / Reject </strong> button on your desired manuscript to Approve / Reject.</li>
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 2</strong> : Insert your comment to the group owner of the manuscript</li>

                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 3</strong> : Click the <strong>Approve / Reject </strong> approve / reject the manuscript</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading3">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3" class="d-none d-lg-inline text-gray-600 small">
                                        <i class="far fa-question-circle" aria-hidden="true"></i> 
                                        <!--How to enroll subjects?-->
                                        <u class="text-dark text-decoration-none">How to view the Manuscript?</u>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse3" class="collapse" aria-labelledby="heading3" data-parent="#accordionExample">
                                <div class="card-body">
                                    <strong class="text-danger">Viewing Manuscript :</strong>
                                    <hr>
                                    <ul class="uk-list uk-list-bullet" style="margin-left:10px;font-size:15px !important">
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 1</strong> : Click the <strong class="text-dark">View Manuscript </strong> button on your desired manuscript to View.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="heading2">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2" class="d-none d-lg-inline text-gray-600 small">
                                        <i class="far fa-question-circle" aria-hidden="true"></i> 
                                        <!--How to enroll subjects?-->
                                        <u class="text-dark text-decoration-none">How to Annotate Manuscript?</u>
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionExample">
                                <div class="card-body">
                                    <strong class="text-danger">Annotating Manuscript :</strong>
                                    <hr>
                                    <ul class="uk-list uk-list-bullet" style="margin-left:10px;font-size:15px !important">
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 1</strong> : Click the <strong class="text-dark">Annotate </strong> button on your desired manuscript to check.</li>
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 2</strong> : Click the desired page to comment</li>
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 3</strong> : Choose tool to use:
                                            <div class="alert alert-info" role="alert">
                                                <center><img width="60%" src="img/pz_at.png" alt="tools"></center>  
                                                <ul> 
                                                    <li style="font-size:15px !important;">Use <strong>"Modify"</strong> button to move the objects created.</li>
                                                    <li style="font-size:15px !important;">Click <strong>"Color"</strong> button to change the color of the object or text</li>
                                                    <li style="font-size:15px !important;">Click <strong>"Border"</strong> button to change the weight of the border</li>
                                                    <li style="font-size:15px !important;">Use <strong>"Rectangle"</strong> button add rectangle to the manuscript.</li>
                                                    <li style="font-size:15px !important;">Use <strong>"Box"</strong> button add box to the manuscript.</li>
                                                    <li style="font-size:15px !important;">Use <strong>"Line"</strong> button to insert a line to the manuscript</li>
                                                    <li style="font-size:15px !important;">Use <strong>"Pencil"</strong> button to draw lines to the manuscript.</li>
                                                    <li style="font-size:15px !important;">Use <strong>"Write"</strong> button insert text to the manuscript.</li>
                                                    <li style="font-size:15px !important;">Use <strong>"Erase"</strong> button to insert a white space to the manuscript</li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 4</strong> : Click the Save button once done commenting the manuscript</li>
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 4</strong> : Click Finish and download to finalize the manuscript. </li>
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 5</strong> : To save it to the server, click <strong>Save </strong>button.</li>
                                        <li style="font-size:15px !important;"><strong class="text-danger">Step 6</strong> : Close the PDF Zorro.</li>
                                        <div class="alert alert-info" role="alert">
                                            <strong>Note:</strong>
                                            <ul> 
                                                <li style="font-size:15px !important;">Due to caching, manuscript may take <strong> 15-20 minutes</strong> to see it in the system.</li>
                                            </ul>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> 
                    
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
                    <input type="text" class="form-control form-control-user" id="manApp" value="" hidden>
                    <input type="text" class="form-control form-control-user" id="commentApp"
                        placeholder="Enter Comment" name="uid">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button id="modApp" class="btn btn-success buttApp" data-dismiss="modal">Approve</button>
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
                <input type="text" class="form-control form-control-user" id="manRej" value="" hidden>
                    <input type="text" class="form-control form-control-user" id="commentRej"
                        placeholder="Enter Comment" name="uid">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button id="modApp" class="btn btn-success buttRej" data-dismiss="modal">Reject</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        //FOR MAIN
        $(".passingIDApp").click(function() {
            var $row = $(this).closest("tr");    //  Find the row
            var $mID = $row.find("#mID").text(); // Find the text
            
            //STORE THE TEXT
            var $remove_space = $mID.replace(/ /g,'')
            $("#manApp").val($remove_space);
        });
        //FOR MAIN
        $(".passingIDRej").click(function() {
            var $row = $(this).closest("tr");    //  Find the row
            var $mID = $row.find("#mID").text(); // Find the text
            
            //STORE THE TEXT
            var $remove_space = $mID.replace(/ /g,'')
            $("#manRej").val($remove_space);
        });

        $(".buttApp").click(function () {
            var man_id = $("#manApp").val();
            var comment = $("#commentApp").val();
            $.ajax({
                url:'controller_manuscript_app.php',
                type:'POST',
                data: { man_id : man_id, comment: comment},
                success: function(result){
                    jQuery('#tr_'+man_id).hide();
                    alert('Manuscript Approved');
                }
            })

            
        });
        $(".buttRej").click(function () {
            var man_id = $("#manRej").val();
            var comment = $("#commentRej").val();
            $.ajax({
                url:'controller_manuscript_rej.php',
                type:'POST',
                data: { man_id : man_id, comment: comment},
                success: function(result){
                    jQuery('#tr_'+man_id).hide();
                    alert('Manuscript Rejected and considered for Revision');
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
