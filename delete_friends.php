<?php 
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
if($_SERVER['REQUEST_METHOD']=='GET'){
$friends_result = explode(",",trim(urldecode($_GET['string_ids']))); 
$num_rows_friends=count($friends_result);
$delete_id=trim(urldecode($_GET['delete_id']));
$building_string=NULL;
for($i=0;$i<$num_rows_friends;$i++){  
    if(!($i==(int)$delete_id)){
    $building_string.=$friends_result[$i].',';   
    }
    
}
$sql="UPDATE friends SET id_friends='".substr($building_string,0,(strlen($building_string)-1))."' WHERE user_id=".trim(strtolower(urldecode($_GET["id_user"])));
$gosql = @mysql_query($sql);    
if(!$gosql){
echo 'error';    
}
echo substr($building_string,0,(strlen($building_string)-1));
}
?>
