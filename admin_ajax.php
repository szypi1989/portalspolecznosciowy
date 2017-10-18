<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();

if($_SERVER['REQUEST_METHOD']=='GET'){
switch ($_GET["field"]){
    case "delete_user":
   $user_result = explode(",",$_GET['string_ids']); 
   $sql="DELETE FROM users WHERE user_id=".trim($user_result[$_GET['user_id']]);
   $gosql = @mysql_query($sql);    
    break; 
    case "change_allow_write_msg":
    $user_result = explode(",",$_GET['string_ids']);     
    $sql="UPDATE users SET allow_write_msg=".trim($_GET['value'])." WHERE user_id=".trim($user_result[$_GET['user_id']]);
    $gosql = @mysql_query($sql);   
    break;    
    } 
}

?>
