<?php
session_start ();
if(isset($_SESSION['user'])){
    
  include('email_api.php');
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
  
  $group_id="";

  if($user_pos==1){
    $query = "SELECT group_num FROM man_groupings WHERE group_members = '$user_name'";
    $sql = mysqli_query($conn, $query) or die ("System Error 1: ".mysqli_error($conn));
    
    while($row = mysqli_fetch_array($sql)) {  
      $group_id=$row['group_num'];
    }
  }

  //FETCH NOTIFICATION
  if(isset($_POST["view"])){
    
    if($_POST["view"] == 'yes')
    {
      $query = "UPDATE tbl_notifications 
                SET notif_stat = 0 
                WHERE notif_stat = 1
                AND (group_id ='$user_name' 
                OR group_id = '$group_id')";
      $sql = mysqli_query($conn, $query) or die ("Notification Change error: ".mysqli_error($conn));
    }

    $query = "SELECT * FROM tbl_notifications 
              WHERE (group_id ='$user_name' OR group_id = '$group_id')
              ORDER BY notif_id DESC LIMIT 5";
    $sql = mysqli_query($conn, $query) or die ("Notification load error: ".mysqli_error($conn));
    $output = '
      <h6 class="dropdown-header">
          Notifications
      </h6>';
    
    $sql_rows = mysqli_num_rows($sql);
    if($sql_rows > 0)
    {
      while($row = mysqli_fetch_array($sql))
      {
        $notif_body = $row['notif_body'];
        $notif_head = $row['notif_head'];
        $output .= '
        <li>
          <a class="dropdown-item d-flex align-items-center" href="#">
            <div class="mr-3">
                <div class="icon-circle bg-primary">
                    <i class="fas fa-file-alt text-white"></i>
                </div>
            </div>
            <div>
                <div class="small text-gray-500">'. $row['notif_date'].'</div>
                <span class="font-weight-bold">'. $notif_head .'</span><br>
                <span class="font-weight-light">'. $notif_body .'</span>
            </div>
          </a>
        </li>
            ';
      }
    }
    else
    {
      $output .= '
      <a class="dropdown-item d-flex align-items-center" href="#">
          <div class="mr-3">
              <div class="icon-circle bg-primary">
                  <i class="fas fa-frown text-white"></i>
              </div>
          </div>
          <div>
              <span class="font-weight-bold">No new Notification</span>
          </div>
      </a>
      ';
    }

    //Counting Notifs
    $query = "SELECT * FROM tbl_notifications 
              WHERE notif_stat = 1
              AND (group_id ='$user_name' 
              OR group_id = '$group_id') 
              ORDER BY notif_date DESC";
    $sql = mysqli_query($conn, $query) or die ("Notification load error: ".mysqli_error($conn));
    $count = mysqli_num_rows($sql);
    $data = array(
      'notification'   => $output,
      'unseen_notification' => $count
    );
    echo json_encode($data);
    }
  }
?>