<?php

    session_start ();
    if(isset($_SESSION['user'])){

        include('controller_dashboard.php');
        include('database.php');
        $user = $_SESSION['user'];
        $query="SELECT * FROM acc_registereds WHERE user_id='$user'"; 
        $sql = mysqli_query($conn, $query) or die ("Dashboard Error 1: ".mysqli_error($conn));

        while($row = mysqli_fetch_array($sql)) {      
            $user_name = $row['user_id'];
            $user_email= $row['user_email'];
            $user_pass = $row['user_pass'];
            $user_dept = $row['user_department'];
            $user_pos  = $row['user_position'];
        }
        /*
        if($user_pos==2 || $user_pos==4){
            $query="SELECT user_code FROM cod_instructor WHERE user_id='$user'"; 
            $sql = mysqli_query($conn, $query) or die ("Dashboard Error 2: ".mysqli_error($conn));
            $unique_code = "";
            while($row = mysqli_fetch_array($sql)){
                $unique_code = $row['user_code'];
            }
        }
        */
        
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
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>

    <!--Charts-->
    <script>
        window.onload = function(){

            //Line Chart
            var ctx1 = document.getElementById('lineChart').getContext('2d');
            var lineChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: [
                        <?php
                            $query = "SELECT CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) AS name, 
                                    COUNT(CASE WHEN man_checkings.com_response=1 THEN 1 END) AS counterPen,
                                    COUNT(CASE WHEN man_checkings.com_response=2 THEN 1 END) AS counterApp,
                                    COUNT(CASE WHEN man_checkings.com_response=3 THEN 1 END) AS counterRej
                                    FROM tbl_userlists 
                                    JOIN man_checkings ON tbl_userlists.user_id = man_checkings.man_checker
                                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                                    WHERE cod_students.unique_id = '$unique_code' AND man_checkings.date_start>='$datenow' AND man_checkings.com_response>=1
                                    GROUP BY name";
                            $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
                            $instruct_name = "";
                            $instruct_val = 0;
                            while($row = mysqli_fetch_array($sql)) {      
                                $instruct_name = $row['name'];
                                echo "'".$instruct_name."'".",";
                            }
                        ?>
                    ],
                    datasets: [{
                        label: "Approved",
                        data: [
                            <?php
                                $query = "SELECT CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) AS name, 
                                    COUNT(CASE WHEN man_checkings.com_response=1 THEN 1 END) AS counterPen,
                                    COUNT(CASE WHEN man_checkings.com_response=2 THEN 1 END) AS counterApp,
                                    COUNT(CASE WHEN man_checkings.com_response=3 THEN 1 END) AS counterRej
                                    FROM tbl_userlists 
                                    JOIN man_checkings ON tbl_userlists.user_id = man_checkings.man_checker
                                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                                    WHERE cod_students.unique_id = '$unique_code' AND man_checkings.date_start>='$datenow' AND man_checkings.com_response>=1
                                    GROUP BY name";
                                $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
                                while($row = mysqli_fetch_array($sql)) {      
                                    $instruct_val = $row['counterApp'];
                                    echo "'".$instruct_val."'".",";
                                }
                            ?>
                            ],
                            
                        legendMarkerType: "circle",
                        backgroundColor: [
                            'rgb(28, 200, 138)','rgb(28, 200, 138)','rgb(28, 200, 138)',
                            'rgb(28, 200, 138)','rgb(28, 200, 138)','rgb(28, 200, 138)',
                            'rgb(28, 200, 138)','rgb(28, 200, 138)','rgb(28, 200, 138)',
                            'rgb(28, 200, 138)','rgb(28, 200, 138)','rgb(28, 200, 138)',
                            'rgb(28, 200, 138)','rgb(28, 200, 138)','rgb(28, 200, 138)',
                            'rgb(28, 200, 138)','rgb(28, 200, 138)','rgb(28, 200, 138)',
                            'rgb(28, 200, 138)','rgb(28, 200, 138)','rgb(28, 200, 138)',
                        ],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    },
                    {
                        label: "Pending",
                        data: [
                            <?php
                                $query = "SELECT CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) AS name, 
                                    COUNT(CASE WHEN man_checkings.com_response=1 THEN 1 END) AS counterPen,
                                    COUNT(CASE WHEN man_checkings.com_response=2 THEN 1 END) AS counterApp,
                                    COUNT(CASE WHEN man_checkings.com_response=3 THEN 1 END) AS counterRej
                                    FROM tbl_userlists 
                                    JOIN man_checkings ON tbl_userlists.user_id = man_checkings.man_checker
                                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                                    WHERE cod_students.unique_id = '$unique_code' AND man_checkings.date_start>='$datenow' AND man_checkings.com_response>=1
                                    GROUP BY name";
                                $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
                                while($row = mysqli_fetch_array($sql)) {      
                                    $instruct_val = $row['counterPen'];
                                    echo "'".$instruct_val."'".",";
                                }
                            ?>
                            ],
                            
                        legendMarkerType: "circle",
                        backgroundColor: [
                            'rgb(246, 194, 62)','rgb(246, 194, 62)','rgb(246, 194, 62)',
                            'rgb(246, 194, 62)','rgb(246, 194, 62)','rgb(246, 194, 62)',
                            'rgb(246, 194, 62)','rgb(246, 194, 62)','rgb(246, 194, 62)',
                            'rgb(246, 194, 62)','rgb(246, 194, 62)','rgb(246, 194, 62)',
                            'rgb(246, 194, 62)','rgb(246, 194, 62)','rgb(246, 194, 62)',
                            'rgb(246, 194, 62)','rgb(246, 194, 62)','rgb(246, 194, 62)',
                            'rgb(246, 194, 62)','rgb(246, 194, 62)','rgb(246, 194, 62)',
                        ],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    },
                    {
                        label: "Reject",
                        data: [
                            <?php
                                $query = "SELECT CONCAT(tbl_userlists.user_lname,', ', tbl_userlists.user_fname,' ',tbl_userlists.user_mname) AS name, 
                                    COUNT(CASE WHEN man_checkings.com_response=1 THEN 1 END) AS counterPen,
                                    COUNT(CASE WHEN man_checkings.com_response=2 THEN 1 END) AS counterApp,
                                    COUNT(CASE WHEN man_checkings.com_response=3 THEN 1 END) AS counterRej
                                    FROM tbl_userlists 
                                    JOIN man_checkings ON tbl_userlists.user_id = man_checkings.man_checker
                                    JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id
                                    JOIN man_groupings ON man_assignings.ass_res = man_groupings.group_num
                                    JOIN cod_students ON man_groupings.group_members = cod_students.user_id
                                    WHERE cod_students.unique_id = '$unique_code' AND man_checkings.date_start>='$datenow' AND man_checkings.com_response>=1
                                    GROUP BY name";
                                $sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
                                while($row = mysqli_fetch_array($sql)) {      
                                    $instruct_val = $row['counterRej'];
                                    echo "'".$instruct_val."'".",";
                                }
                            ?>
                            ],
                            
                        legendMarkerType: "circle",
                        backgroundColor: [
                            'rgb(231, 74, 59)','rgb(231, 74, 59)','rgb(231, 74, 59)',
                            'rgb(231, 74, 59)','rgb(231, 74, 59)','rgb(231, 74, 59)',
                            'rgb(231, 74, 59)','rgb(231, 74, 59)','rgb(231, 74, 59)',
                            'rgb(231, 74, 59)','rgb(231, 74, 59)','rgb(231, 74, 59)',
                            'rgb(231, 74, 59)','rgb(231, 74, 59)','rgb(231, 74, 59)',
                            'rgb(231, 74, 59)','rgb(231, 74, 59)','rgb(231, 74, 59)',
                            'rgb(231, 74, 59)','rgb(231, 74, 59)','rgb(231, 74, 59)',
                        ],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]                   
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    },
                    legend: {
                        position: 'bottom',
                        display: true,
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
                
            }); 

            //DOUGHNUT CHART
            var ctx2 = document.getElementById('pieChart').getContext('2d');
            var pieChart2 = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: [
                        'Adviser',
                        'Panel 1',
                        'Panel 2',
                        'Chairman',
                        'Dean',
                        'Done'
                    ],
                    datasets: [{
                        label: "Researcher's Progress",
                        data: [
                            <?php echo $count1;?>,
                            <?php echo $count2;?>, 
                            <?php echo $count3;?>, 
                            <?php echo $count4;?>, 
                            <?php echo $count5;?>, 
                            <?php echo $done;?>
                        ],
                        legendMarkerType: "circle",
                        backgroundColor: [
                        'rgb(133, 135, 150)',
                        'rgb(231, 74, 59)',
                        'rgb(246, 194, 62)',
                        'rgb(78, 115, 223)',
                        'rgb(54, 185, 204)',
                        'rgb(28, 200, 138)'
                        ],
                    }]                   
                },
                options: {
                    legend: {
                        display: false
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                }
                
            });
        }
    </script>

</head>

<body id="page-top">

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        </div>

        <?php
            //Student Dashboard
            if($user_pos==1){
                $option=0;
                $progress="";
                $percent="";
                $reject="";
                $group="";
                $group_name="";
                $array_dates = [];
                $array_datec = [];
                $array_sol = [];

                $query = "SELECT group_num FROM man_groupings WHERE group_members = '$user_name'";
                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                
                while($row = mysqli_fetch_array($sql)) {  
                    $group_name=$row['group_num'];
                }
                $query = "SELECT date_start, date_checked FROM man_checkings 
                JOIN man_assignings ON man_checkings.man_id = man_assignings.man_id 
                WHERE ass_res = '$group_name'";
                $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
                
                while($row = mysqli_fetch_array($sql)) {  
                    $array_dates[$option] = $row['date_start'];
                    $array_datec[$option] = $row['date_checked'];
                    
                    if($array_dates[$option]!=""){
                        //if date_checked is empty
                        if($array_datec[$option]==""){
                            $your_date = strtotime($array_dates[$option]);
                            $now = time(); // or your date as well
                            $array_datec[$option] = "present";
                        }
                        else{
                            $your_date = strtotime($array_dates[$option]);
                            $now = strtotime($array_datec[$option]);
                        }
                        $datediff = $now - $your_date;
                        
                        $array_sol[$option] = round($datediff / (60 * 60 * 24));
                        
                    }
                    else{
                        $array_sol[$option] = "--";
                    }
                    $option++;
                }
                
        ?>
        <!-- STUDENTS RUNNING DAYS -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark">Running Days</h6>
            </div>
            <div class="card-body">
                <div class="row">            
                    <!-- RUNNING DAYS ADVISER -->
                    <div class="col-xl col-md-6 mb-4">
                        <a href="pan_checking.php" class="text-decoration-none">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Adviser</div>
                                            <div class="h5 mb-1 font-weight-bold text-gray-800"><?php echo $array_sol[0];?> days</div>
                                            <div class="text-xs font-weight-normal text-dark text-lowercase mb-0">
                                            <?php if($array_dates[0]!=""){echo $array_dates[0] . " - ". $array_datec[0];}?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- RUNNING DAYS Panel 1 -->
                    <div class="col-xl col-md-6 mb-4">
                        <a href="pan_checking.php" class="text-decoration-none">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Panel 1</div>
                                            <div class="h5 mb-1 font-weight-bold text-gray-800"><?php echo $array_sol[1];?> days</div>
                                            <div class="text-xs font-weight-normal text-dark text-lowercase mb-0">
                                            <?php if($array_dates[0]!=""){echo $array_dates[0] . " - ". $array_datec[1];}?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- RUNNING DAYS Panel 2 -->
                    <div class="col-xl col-md-6 mb-4">
                        <a href="pan_checking.php" class="text-decoration-none">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Panel 2</div>
                                            <div class="h5 mb-1 font-weight-bold text-gray-800"><?php echo $array_sol[2];?> days</div>
                                            <div class="text-xs font-weight-normal text-dark text-lowercase mb-0">
                                            <?php if($array_dates[0]!=""){echo $array_dates[0] . " - ". $array_datec[2];}?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- RUNNING DAYS Chairman -->
                    <div class="col-xl col-md-6 mb-4">
                        <a href="pan_checking.php" class="text-decoration-none">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Chairman</div>
                                            <div class="h5 mb-1 font-weight-bold text-gray-800"><?php echo $array_sol[3];?> days</div>
                                            <div class="text-xs font-weight-normal text-dark text-lowercase mb-0">
                                            <?php if($array_dates[0]!=""){echo $array_dates[0] . " - ". $array_datec[3];}?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- RUNNING DAYS Dean -->
                    <div class="col-xl col-md-6 mb-4">
                        <a href="dean_signing.php" class="text-decoration-none">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Dean</div>
                                            <div class="h5 mb-1 font-weight-bold text-gray-800"><?php echo $array_sol[4];?> days</div>
                                            <div class="text-xs font-weight-normal text-dark text-lowercase mb-0">
                                            <?php if($array_dates[0]!=""){echo $array_dates[0] . " - ". $array_datec[4];}?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Progress Card -->
        <div class="col-lg-13 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-sm font-weight-bold text-dark text-uppercase mb-1">Checking Status
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

        <?php
            }else{
        ?>
        <!-- Content Row -->
        <div class="row">

            <?php if($user_pos==2 || $user_pos==4){ ?>
            <!-- Accounts Pending Approvals -->
            <div class="col mb-4">
                <?php 
                    $query="SELECT COUNT(acc_pendings.user_id) as pending_count FROM acc_pendings 
                            WHERE unique_id = '$unique_code' OR (user_department='$user_dept' AND user_position!='1')"; 
                    $sql = mysqli_query($conn, $query) or die ("Dashboard Error 3: ".mysqli_error($conn));
            
                    while($row = mysqli_fetch_array($sql)) {      
                        $accpending_count = $row['pending_count'];
                    }
                ?>
                <a href="manage_acc.php" class="text-decoration-none">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Account Approvals </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $accpending_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
            <!-- Pending Manuscripts Check -->
            <div class="col mb-4">
                <?php 
                    $query="SELECT COUNT(man_checkings.man_checker) as pending_count FROM man_checkings WHERE man_checker='$user_name' AND com_response='1' AND NOT com_position='5'"; 
                    $sql = mysqli_query($conn, $query) or die ("Dashboard Error 4: ".mysqli_error($conn));
            
                    while($row = mysqli_fetch_array($sql)) {      
                        $manpending_count = $row['pending_count'];
                    }
                ?>
                <a href="pan_checking.php" class="text-decoration-none">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Pending Checks</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $manpending_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php if($user_pos==4){ ?>
            <!-- Pending Manuscripts Sign -->
            <div class="col mb-4">
                <?php 
                    $query="SELECT COUNT(man_checkings.man_checker) as pending_count FROM man_checkings WHERE man_checker='$user_name' AND com_response='1' AND com_position='5'"; 
                    $sql = mysqli_query($conn, $query) or die ("Dashboard Error 5: ".mysqli_error($conn));
            
                    while($row = mysqli_fetch_array($sql)) {      
                        $manpending_count = $row['pending_count'];
                    }
                ?>
                <a href="dean_signing.php" class="text-decoration-none">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Pending Sign</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $manpending_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>

        <!-- Content for Approev and Reject -->
        <div class="row">
            <?php if($user_pos!=1){ ?>
            <!-- Approve Manuscripts Check -->
            <div class="col mb-4">
                <?php 
                    $query="SELECT COUNT(man_checkings.man_checker) as approve_count FROM man_checkings WHERE man_checker='$user_name' AND com_response='2' AND NOT com_position='5'"; 
                    $sql = mysqli_query($conn, $query) or die ("Dashboard Error 4: ".mysqli_error($conn));
                    $manapprove_count = 0;
                    while($row = mysqli_fetch_array($sql)) {      
                        $manapprove_count = $row['approve_count'];
                    }
                ?>
                <a href="#" class="text-decoration-none">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Approve Manuscripts</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $manapprove_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Reject Manuscripts Check -->
            <div class="col mb-4">
                <?php 
                    $query="SELECT COUNT(man_checkings.man_checker) as reject_count FROM man_checkings WHERE man_checker='$user_name' AND com_response='3' AND NOT com_position='5'"; 
                    $sql = mysqli_query($conn, $query) or die ("Dashboard Error 4: ".mysqli_error($conn));
                    $manreject_count = 0;
                    while($row = mysqli_fetch_array($sql)) {      
                        $manapprove_count = $row['approve_count'];
                    }
                ?>
                <a href="#" class="text-decoration-none">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Rejected Manuscripts</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $manreject_count; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php } ?>
        </div>        

        <?php if($user_pos==2 || $user_pos==4){ ?>
        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Checking Status</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Filter</div>
                                <a class="dropdown-item">June</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="lineChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div
                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-danger">Researcher's Progress</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-pie pt-2 pb-2">
                            <canvas id="pieChart" width="50%" height="50%"></canvas>
                        </div>
                        <div class="mt-1 text-center small row">
                            <span class="mr-4 ml-4 col">
                                <i class="fas fa-circle text-secondary"></i> Adviser
                            </span>
                            <span class="mr-4 col">
                                <i class="fas fa-circle text-danger"></i> Panel 1
                            </span>
                            <span class="mr-4 col">
                                <i class="fas fa-circle text-warning"></i> Panel 2
                            </span>
                            
                        </div>
                        <div class="mx-0 text-center small">
                            <span class="mr-4 col">
                                <i class="fas fa-circle text-primary"></i> Chairman
                            </span>
                            <span class="mr-4 col">
                                <i class="fas fa-circle text-info"></i> Dean
                            </span>
                            <span class="mr-4 col">
                                <i class="fas fa-circle text-success"></i> Done
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
            }
        }
        if($user_pos==3){

        ?>
            <!-- Content Row -->
            <div class="row">
                <!-- Accounts Pending Approvals -->
                <div class="col mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Account Approvals</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">5</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example -->
                <div class="col mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Earnings (Annual)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Manuscripts Sign -->
                <div class="col mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
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

                <!-- Pending Manuscripts Check -->
                <div class="col mb-4">
                    <a href="pan_checking.php" class="text-decoration-none">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Checks</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php
        }
        ?>

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
    }
?>