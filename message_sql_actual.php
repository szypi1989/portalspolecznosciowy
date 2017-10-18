<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
$building_errors=NULL;

if($_SERVER['REQUEST_METHOD']=='GET'){
    switch ($_GET["field"]){
  case "refresh_inbox_actual":
    $sql="SELECT us.name as name,us.surname as surname,ms.id,ms.sender_id as sender,ms.recipient_id as recipient,ms.contents as contents,ms.time as time,ms.status as status,mst.hidden as hidden FROM message ms 
    LEFT JOIN users_info us ON us.user_id=ms.sender_id
    JOIN message_type mst ON mst.id=ms.id
    WHERE (mst.user_id=".mysql_real_escape_string(urldecode($_GET["id_login"])).") AND ((ms.sender_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." AND ms.recipient_id=".mysql_real_escape_string(urldecode($_GET["id_friend"])).") OR (ms.sender_id=".mysql_real_escape_string(urldecode($_GET["id_friend"]))." AND ms.recipient_id=".mysql_real_escape_string(urldecode($_GET["id_login"])).")) AND (mst.hidden=0) 
    ORDER BY time DESC";
    $message_info_sql = @mysql_query($sql);
    $num_rows_message = @mysql_num_rows($message_info_sql);
    $checkbox_state=explode(",", $_GET['ids_actual_checkbox']);
    $building_messages=NULL;
    $count_message_missed=0;
    $friend_conversation=NULL;
    $num_rows_delete_message=0;
    $class_not_delete=NULL;
    $active_bin=false;
    for($i=0;$i<$num_rows_message;$i++){
    $building_checkbox='<input type="checkbox" name="mark_delete" />'; 
    $result = @mysql_fetch_assoc($message_info_sql);
        for($j=0;$j<count($checkbox_state);$j++){
            if($result['id']==$checkbox_state[$j]){
            $building_checkbox='<input type="checkbox" name="mark_delete" checked = "checked" />';    
            }
        }
    $message_status=((int)$result['status']!=1)?" no_get_back":NULL;
    //budowanie adresu do profilu   
    $count_message_missed=((int)$result["sender"]==mysql_real_escape_string(urldecode($_GET["id_login"])))?$count_message_missed:$count_message_missed=$count_message_missed+1;
    $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($result["sender"]);
    $sql_user_info = @mysql_query($sql);
    $sql_user_info = @mysql_fetch_assoc($sql_user_info);
    $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_user_info['name']).'" AND surname="'.mysql_real_escape_string($sql_user_info['surname']).'"';
    $gosql = @mysql_query($sql);
    $seek=NULL;
    $c=0;
    for($num_profile=0;$seek==NULL;$num_profile++)
    {
        if((mysql_result($gosql,$num_profile,0))==$result["sender"]){ 
        break;
        }                 
    $c=$c+1;
    }
    $building_href='main_profile.php?surname='.$sql_user_info['name'].'.'.$sql_user_info['surname'].'.'.$num_profile;
    //exit
    if(((int)$result["sender"]!=mysql_real_escape_string(urldecode($_GET["id_login"])))&&(empty($friend_conversation))){
    $friend_conversation['name']=$sql_user_info['name']; 
    $friend_conversation['surname']=$sql_user_info['surname'];
    $friend_conversation['building_href']=$building_href; 
    $id_friend=(int)$result["sender"];
    }
    if(!((int)$result["sender"]!=mysql_real_escape_string(urldecode($_GET["id_login"])))&&(empty($friend_conversation))){
    $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($result["recipient"]);
    $sql_temp = @mysql_query($sql);
    $sql_temp = @mysql_fetch_assoc($sql_temp);
    $friend_conversation['name']=$sql_temp['name']; 
    $friend_conversation['surname']=$sql_temp['surname'];
    $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_temp['name']).'" AND surname="'.mysql_real_escape_string($sql_temp['surname']).'"';
    $gosql = @mysql_query($sql);
    $seek=NULL;
    $id_friend=(int)$result["recipient"];
    $c=0;
    for($num_friends=0;$seek==NULL;$num_friends++)
    {
        if((mysql_result($gosql,$num_friends,0))==$result["recipient"]){ 
        break;
        }                 
    $c=$c+1;
    }
    $building_href='main_profile.php?surname='.$sql_temp['name'].'.'.$sql_temp['surname'].'.'.$num_friends;
    $friend_conversation['building_href']=$building_href;
    }
    $building_messages.='<div class="message_cont_text'.$message_status.'"><div class="message_cont_left"><div class="id_message">'.$result["id"].'</div><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.validate_img_avatar($result["sender"]).'.jpg?'.time().'" height="40" width="40"></a></div></div>';
    $building_messages.='<div class="message_cont_info"><div class="message_cont_info_head"><div class="date_name_head">'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"])))."&nbsp;".strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</div><div class="date_time_head">'
    .$result["time"].'</div></div><div class="date_cont_message"><div class="contents"><span>'.$result["contents"].'</span></div><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
    }
    echo $building_messages;    
    break;
    case "refresh_bin_actual":
    $sql="SELECT us.name as name,us.surname as surname,ms.id,ms.sender_id as sender,ms.recipient_id as recipient,ms.contents as contents,ms.time as time,ms.status as status,mst.hidden as hidden FROM message ms 
    LEFT JOIN users_info us ON us.user_id=ms.sender_id
    JOIN message_type mst ON mst.id=ms.id
    WHERE (mst.user_id=".mysql_real_escape_string(urldecode($_GET["id_login"])).") AND ((ms.sender_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." AND ms.recipient_id=".mysql_real_escape_string(urldecode($_GET["id_friend"])).") OR (ms.sender_id=".mysql_real_escape_string(urldecode($_GET["id_friend"]))." AND ms.recipient_id=".mysql_real_escape_string(urldecode($_GET["id_login"])).")) 
    ORDER BY time DESC";
    $message_info_sql = @mysql_query($sql);
    $num_rows_message = @mysql_num_rows($message_info_sql);
    $building_messages=NULL;
    $count_message_missed=0;
    $friend_conversation=NULL;
    $num_rows_delete_message=0;
    $class_not_delete=NULL;
    $active_bin=false;
    $checkbox_state=explode(",", $_GET['ids_actual_checkbox']);
    for($i=0;$i<$num_rows_message;$i++){ 
    $result = @mysql_fetch_assoc($message_info_sql);
    $message_status=((int)$result['status']!=1)?" no_get_back":NULL;
    //budowanie adresu do profilu   
    $count_message_missed=((int)$result["sender"]==mysql_real_escape_string(urldecode($_GET["id_login"])))?$count_message_missed:$count_message_missed=$count_message_missed+1;
    $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($result["sender"]);
    $sql_user_info = @mysql_query($sql);
    $sql_user_info = @mysql_fetch_assoc($sql_user_info);
    $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_user_info['name']).'" AND surname="'.mysql_real_escape_string($sql_user_info['surname']).'"';
    $gosql = @mysql_query($sql);
    $seek=NULL;
    $c=0;
    $class_not_delete=((int)$result["hidden"]==1)?NULL:" active";
    $building_checkbox=NULL;
    if((int)$result["hidden"]==1){
    $num_rows_delete_message=$num_rows_delete_message+1;
    $building_checkbox='<input type="checkbox" name="mark_delete" />'; 
            for($j=0;$j<count($checkbox_state);$j++){
                if($result['id']==$checkbox_state[$j]){
                $building_checkbox='<input type="checkbox" name="mark_delete" checked = "checked" />';    
                }
            }
    $active_bin=true;
    }
    for($num_profile=0;$seek==NULL;$num_profile++)
    {
        if((mysql_result($gosql,$num_profile,0))==$result["sender"]){ 
        break;
        }                 
    $c=$c+1;
    }
    $building_href='main_profile.php?surname='.$sql_user_info['name'].'.'.$sql_user_info['surname'].'.'.$num_profile;
    //exit
    if(((int)$result["sender"]!=mysql_real_escape_string(urldecode($_GET["id_login"])))&&(empty($friend_conversation))){
    $friend_conversation['name']=$sql_user_info['name']; 
    $friend_conversation['surname']=$sql_user_info['surname'];
    $friend_conversation['building_href']=$building_href; 
    $id_friend=(int)$result["sender"];
    }
    if(!((int)$result["sender"]!=mysql_real_escape_string(urldecode($_GET["id_login"])))&&(empty($friend_conversation))){
    $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($result["recipient"]);
    $sql_temp = @mysql_query($sql);
    $sql_temp = @mysql_fetch_assoc($sql_temp);
    $friend_conversation['name']=$sql_temp['name']; 
    $friend_conversation['surname']=$sql_temp['surname'];
    $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_temp['name']).'" AND surname="'.mysql_real_escape_string($sql_temp['surname']).'"';
    $gosql = @mysql_query($sql);
    $seek=NULL;
    $id_friend=(int)$result["recipient"];
    $c=0;
    for($num_friends=0;$seek==NULL;$num_friends++)
    {
        if((mysql_result($gosql,$num_friends,0))==$result["recipient"]){ 
        break;
        }                 
    $c=$c+1;
    }
    $building_href='main_profile.php?surname='.$sql_temp['name'].'.'.$sql_temp['surname'].'.'.$num_friends;
    $friend_conversation['building_href']=$building_href;
    }
    $building_messages.='<div class="message_cont_text'.$class_not_delete.$message_status.'"><div class="message_cont_left"><div class="id_message">'.$result["id"].'</div><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.validate_img_avatar($result["sender"]).'.jpg?'.time().'" height="40" width="40"></a></div></div>';
    $building_messages.='<div class="message_cont_info"><div class="message_cont_info_head"><div class="date_name_head">'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"])))."&nbsp;".strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</div><div class="date_time_head">'
    .$result["time"].'</div></div><div class="date_cont_message"><div class="contents"><span>'.$result["contents"].'</span></div><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
    }
    echo $building_messages;        
    break; 
    
    } 
}
function validate_img_avatar($id){
        if(!(empty($id))){
        $test = file_exists('gallery/'.$id.'.jpg'); //sprawdzenie czy plik istnieje avataru       
        $var=$id; 
            if(!$test){
                return $var='null';
            }
        }else{
            return $var='null';
        }
        return $var;
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
