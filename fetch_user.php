<?php

//fetch_user.php

include('database_connection.php');

session_start();

$query = "
SELECT * FROM login 
WHERE user_id != '".$_SESSION['user_id']."' 
";

$statement = $con->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '
<table class="table table-bordered table-striped" border: 3px solid #ccc>
 <tr>
  <th width="70%" style="color:#310E68;">Users</td>
  <th width="10%" style="color:#310E68;">Status</td>
  <th width="20%" style="color:#310E68;">Action</td>
 </tr>
';

foreach($result as $row)
{
 $status = '';
 $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
 $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
 $user_last_activity = fetch_user_last_activity($row['user_id'], $con);
 if($user_last_activity > $current_timestamp)
 {
  $status = '<span class="badge badge-success">Online</span>';
 }
 else
 {
  $status = '<span class="badge badge-danger">Offline</span>';
 }
 $output .= '
 <tr>
  <td>'.$row['username'].'<span class="badge badge-info">'.count_unseen_message($row['user_id'], $_SESSION['user_id'], $con).'</span>'.fetch_is_type_status($row['user_id'], $con).'</td>
  <td>'.$status.'</td>
  <td><button type="button" class="btn btn-outline-warning btn-xs start_chat" style="background: #9932CC;" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'"><span style="color:white;">Start Chat</span></button></td>
 </tr>
 ';
}

$output .= '</table>';

echo $output;

?>