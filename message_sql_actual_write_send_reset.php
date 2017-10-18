<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
$building_errors=NULL;
if($_SERVER['REQUEST_METHOD']=='GET'){
    switch ($_GET["field"]){
    case "send_actual_write_msg_reset":
    $sql="SELECT recipients_id FROM message_control WHERE sender_id=".mysql_real_escape_string(urldecode($_GET["id_login"]));  
    $message_recipients_sql = @mysql_query($sql);
    $result = @mysql_fetch_assoc($message_recipients_sql);   
    $recipients_id=explode(",", $result["recipients_id"]);
    $building_id_send=NULL;
    for($i=0;$i<count($recipients_id);$i++){
        if(trim($recipients_id[$i])!=trim(mysql_real_escape_string(urldecode($_GET["id_friend"])))){
        $building_id_send.=$recipients_id[$i];  
        $building_id_send.=",";      
        }
    }
    $building_id_send=substr($building_id_send,0,(strlen($building_id_send)-1));
    $sql='INSERT INTO message_control(sender_id,recipients_id) 
    VALUES('.mysql_real_escape_string(urldecode($_GET["id_login"])).',"'.$building_id_send.'") 
    ON DUPLICATE KEY UPDATE sender_id='.mysql_real_escape_string(urldecode($_GET["id_login"])).',recipients_id="'.$building_id_send.'"';
    $gosql =@mysql_query($sql); 
    break;
    
    } 
}

function is_validate_contents($data){ 
$return_arrays=array();
$error_contents=array();
$filter_contents=NULL;
    if(strlen($data)>240){
    $error_contents[]=1; 
    $error_contents[]=2;
    } 
///FILTERING DATE
$filter_contents=$data;
///
$return_arrays['errors']=$error_contents;
$return_arrays['contents']=$filter_contents;
return $return_arrays;  
}
function err_val(){
global $building_errors;
$building_errors="error:";
}
?>
