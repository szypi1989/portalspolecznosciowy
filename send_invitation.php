<?php 
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
if($_SERVER['REQUEST_METHOD']=='GET'){
$sql="SELECT id_friends FROM invitation WHERE user_id=".trim(urldecode($_GET['id_friends']));
$friends_sql = @mysql_query($sql);
$friends_result = @mysql_fetch_assoc($friends_sql);
if(empty($friends_result['id_friends'])){
$sql="INSERT INTO invitation(id_friends,user_id) VALUES('".trim(strtolower(urldecode($_GET['id_user'])))."',".trim(strtolower(urldecode($_GET['id_friends']))).")";
$gosql = @mysql_query($sql);
if(!$gosql){
echo "error";    
}
}else{
$sql="UPDATE invitation SET id_friends='".$friends_result['id_friends'].",".$_GET['id_user']."' WHERE user_id=".trim(strtolower(urldecode($_GET["id_friends"])));
$gosql = @mysql_query($sql);    
if(!$gosql){
echo "error";    
}
}

}
?>
