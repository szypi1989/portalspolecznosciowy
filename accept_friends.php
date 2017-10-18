<?php 
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
if($_SERVER['REQUEST_METHOD']=='GET'){
$invitation_result = explode(",",trim(urldecode($_GET['string_ids']))); 
$num_rows_invitation=count($invitation_result);
$delete_id=trim(urldecode($_GET['id_accept']));
$building_string=NULL;
$id_accept=NULL;
for($i=0;$i<$num_rows_invitation;$i++){  
    if(!($i==(int)$delete_id)){
    $building_string.=$invitation_result[$i].',';   
    }else{
    $id_accept=$invitation_result[$i];    
    }
    
}
$sql="UPDATE invitation SET id_friends='".substr($building_string,0,(strlen($building_string)-1))."' WHERE user_id=".trim(strtolower(urldecode($_GET["id_user"])));
$gosql = @mysql_query($sql);    
if(!$gosql){
echo 'error';    
}else{
//dodawanie do bazy danych ciągu nowego użytkownika w obu użytkownikach    
$sql="SELECT id_friends FROM friends WHERE user_id=".trim(strtolower(urldecode($_GET["id_user"])));
$friends_sql = @mysql_query($sql);
$friends_result = @mysql_fetch_assoc($friends_sql);
if(empty($friends_result)){
$sql="INSERT INTO friends(user_id,id_friends) VALUES(".trim(strtolower(urldecode($_GET['id_user']))).",'".$id_accept."')";
$gosql = @mysql_query($sql);    
}else{
$friends_result=(trim($friends_result['id_friends'])==NULL)?$id_accept:$friends_result['id_friends'].",".$id_accept;
$sql="UPDATE friends SET id_friends='".$friends_result."' WHERE user_id=".trim(strtolower(urldecode($_GET["id_user"])));
$gosql = @mysql_query($sql);     
echo $sql;
}   

$sql="SELECT id_friends FROM friends WHERE user_id=".trim($id_accept);
$friends_sql = @mysql_query($sql);
$friends_result = @mysql_fetch_assoc($friends_sql);
if(empty($friends_result)){
$sql="INSERT INTO friends(user_id,id_friends) VALUES(".$id_accept.",'".trim(strtolower(urldecode($_GET["id_user"])))."')";
$gosql = @mysql_query($sql);    
}else{
$friends_result=(trim($friends_result['id_friends'])==NULL)?trim(strtolower(urldecode($_GET["id_user"]))):$friends_result['id_friends'].",".trim(strtolower(urldecode($_GET["id_user"])));
$sql="UPDATE friends SET id_friends='".$friends_result."' WHERE user_id=".$id_accept;
$gosql = @mysql_query($sql);     
echo $sql;
}
}

}
?>
