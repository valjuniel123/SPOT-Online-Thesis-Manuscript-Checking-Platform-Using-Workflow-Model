<?php
 

include('database.php');

$query="SELECT COUNT(DISTINCT man_checkings.man_id) AS counting FROM man_checkings"; 
$sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));
while($row = mysqli_fetch_array($sql)) { $allcount=$row['counting']; }

$level=[];
$count=[];

$query="SELECT com_position, COUNT(CASE WHEN man_checkings.com_response=1 or com_response=3 THEN 1 END) AS counter 
        FROM man_checkings
        GROUP BY com_position"; 
$sql = mysqli_query($conn, $query) or die ("Error: ".mysqli_error($conn));

while($row = mysqli_fetch_array($sql)) {      
    $level[] = $row['com_position'];
    $count[] = $row['counter'];
}
$done=$allcount-array_sum($count);
$researcherProgress = array(
    array("y"=> $count[0], "name"=> "Adviser", "color"=> "#858796"),
    array("y"=> $count[1], "name"=> "Panel 1", "color"=> "#E74A3B"),
    array("y"=> $count[2], "name"=> "Panel 2", "color"=> "#F6C23E"),
    array("y"=> $count[3], "name"=> "Chairman", "color"=> "#4E73DF"),
    array("y"=> $count[4], "name"=> "Dean", "color"=> "#36B9CC"),
    array("y"=> $done, "name"=> "Done", "color"=> "#1CC88A")
);
 
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
    window.onload = function () {
    
    var totalVisitors = <?php echo $totalVisitors ?>;
    var resProgress = {
            "Researcher Progress": [{
            cursor: "pointer",
            explodeOnClick: false,
            innerRadius: "75%",
            legendMarkerType: "circle",
            name: "New vs Returning Visitors",
            radius: "100%",
            showInLegend: true,
            startAngle: 90,
            type: "doughnut",
            dataPoints: <?php echo json_encode($researcherProgress, JSON_NUMERIC_CHECK); ?>
        }],
    };
    
    var resProgressOptions = {
        animationEnabled: true,
        theme: "light2",
        legend: {
            fontFamily: "calibri",
            fontSize: 14,
            itemTextFormatter: function (e) {
                return e.dataPoint.name + ": " + Math.round(e.dataPoint.y / totalVisitors * 100) + "%";  
            }
        },
        data: []
    };
    var chart = new CanvasJS.Chart("chartContainer", resProgressOptions);
    chart.options.data = resProgress["Researcher Progress"];
    chart.render();
    }
</script>
</head>
<body>
 
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>   