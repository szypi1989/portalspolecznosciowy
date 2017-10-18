<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
$building_errors=NULL;
if($_SERVER['REQUEST_METHOD']=='GET'){
switch ($_GET["field"]){
case "send_actual_write_msg":
    $sql="SELECT recipients_id FROM message_control WHERE sender_id=".mysql_real_escape_string(urldecode($_GET["id_login"]));  
    $message_recipients_sql = @mysql_query($sql);
    $result = mysql_fetch_assoc($message_recipients_sql); 
    if(empty($result['recipients_id'])){
    $sql='INSERT INTO message_control(sender_id,recipients_id) 
    VALUES('.mysql_real_escape_string(urldecode($_GET["id_login"])).',"'.trim(mysql_real_escape_string(urldecode($_GET["id_friend"]))).',") 
    ON DUPLICATE KEY UPDATE sender_id='.mysql_real_escape_string(urldecode($_GET["id_login"])).',recipients_id="'.trim(mysql_real_escape_string(urldecode($_GET["id_friend"]))).'"';
    $gosql =@mysql_query($sql);  
    exit;
    }
    $result = @mysql_fetch_assoc($message_recipients_sql);      
    $recipients_id=explode(",", $result["recipients_id"]);
    for($i=0;$i<count($recipients_id);$i++){
        if(trim($recipients_id[$i])==trim(mysql_real_escape_string(urldecode($_GET["id_friend"])))){ 
        break;
        }else{
            if($i+1==count($recipients_id)){
            $sql='UPDATE message_control SET recipients_id="'.$result['recipients_id'].(empty($result['recipients_id'])?NULL:',').trim(mysql_real_escape_string(urldecode($_GET["id_friend"]))).'" WHERE sender_id='.mysql_real_escape_string(urldecode($_GET["id_login"]));
            $gosql =mysql_query($sql); 
            echo $sql;
            break;
            }     
        }       
    }
    break;     
    } 
}

function err_val(){
global $building_errors;
$building_errors="error:";
}
?>
