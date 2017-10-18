<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
$building_errors=NULL;
if($_SERVER['REQUEST_METHOD']=='GET'){
    switch ($_GET["field"]){  
    case "check_actual_write":
    $sql='SELECT recipients_id FROM message_control WHERE ((recipients_id LIKE "'.mysql_real_escape_string(urldecode($_GET["id_login"])).',%") OR (recipients_id LIKE "%,'.mysql_real_escape_string(urldecode($_GET["id_login"])).'") OR (recipients_id="'.mysql_real_escape_string(urldecode($_GET["id_login"])).'")) AND sender_id='.mysql_real_escape_string(urldecode($_GET["id_friend"]));
    $message_recipients_sql = @mysql_query($sql);
    $result = @mysql_fetch_assoc($message_recipients_sql);   
        if(empty($result["recipients_id"])){
        echo "0";
        }else{
        echo "1";    
        }
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
