<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
$building_errors=NULL;
$err_vald_cont=array(1=>"Wiadomość nie może zawierać więcej niż 200 znaków");
if($_SERVER['REQUEST_METHOD']=='GET'){
    switch ($_GET["field"]){
  case "send":
        //sprawdzanie możliwości wysyłania wiadomości
        $allow_write_msg=0;
        $sql="SELECT user_id FROM users WHERE allow_write_msg=1";
        $goadmin = @mysql_query($sql);
        $num_rows_admin = @mysql_num_rows($goadmin);
        for($i=0;$i<$num_rows_admin;$i++){ 
        $result = @mysql_fetch_assoc($goadmin);
            if($result['user_id']==mysql_real_escape_string(urldecode($_GET["id_login"]))){
                $allow_write_msg=1;
            }
        }
    if($allow_write_msg!=1){
        echo "Brak dostępu do możliwości wysłania wiadomości";
        exit;
    }    
    $array_result_validate=is_validate_contents(mysql_real_escape_string(urldecode($_GET["contents"])));   
    if(empty($array_result_validate['errors'][0])){
    $sql='INSERT INTO message (sender_id, contents, recipient_id) VALUES('.mysql_real_escape_string(urldecode($_GET["id_login"])).',"'.$array_result_validate['contents'].'",'.mysql_real_escape_string(urldecode($_GET["id_friend"])).')'; 
    $gosql = mysql_query($sql);
        if(!$gosql){
        err_val();
        $building_errors.="błąd w bazie danych;";
        }else{
        $sql='SELECT id,sender_id,recipient_id,time,contents FROM `message` WHERE sender_id='.mysql_real_escape_string(urldecode($_GET["id_login"])).' AND recipient_id='.mysql_real_escape_string(urldecode($_GET["id_friend"])).' ORDER BY time DESC LIMIT 1';
        $gosql = mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        $sql='INSERT INTO message_type (id, user_id, hidden,spam) VALUES('.$row['id'].','.$row['sender_id'].',0,0)'; 
        $gosql = mysql_query($sql);
        $sql='INSERT INTO message_type (id, user_id, hidden,spam) VALUES('.$row['id'].','.$row['recipient_id'].',0,0)'; 
        $gosql = mysql_query($sql);
        $sql='SELECT name,surname FROM users_info WHERE user_id='.$row['sender_id'];
        $gosql=mysql_query($sql);
        $user_info = @mysql_fetch_assoc($gosql);
        $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($user_info['name']).'" AND surname="'.mysql_real_escape_string($user_info['surname']).'"';
        $find_user= @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($num_friends=0;$seek==NULL;$num_friends++)
        {
            if((mysql_result($find_user,$num_friends,0))==$row['sender_id']){ 
            break;
            }                 
        $c=$c+1;
        }
        $building_data_result='#data:'.$row['id'];
        $building_data_result.="#data:";
        $building_data_result.=mysql_real_escape_string(urldecode($_GET["id_login"]));
        $building_data_result.="#data:";
        $building_data_result.=strtoupper($user_info["name"][0]).substr($user_info["name"],1,(strlen($user_info["name"]))).'&nbsp;'.strtoupper($user_info["surname"][0]).substr($user_info["surname"],1,(strlen($user_info["surname"])));
        $building_data_result.="#data:";
        $building_data_result.=$row["time"];
        $building_data_result.="#data:";
        $building_data_result.= "main_profile.php?surname=".$user_info['name'].".".$user_info['surname'].".".$num_friends;
        $building_data_result.="#data:";
        $building_data_result.=validate_img_avatar($row['sender_id']); 
        $building_data_result.="#data:";
        $building_data_result.= $row["contents"];
        echo $building_data_result;
        }
    }else{
        err_val();
        $building_errors.="Błedy wadliwacji:#";
        for($i=0;$i<count($array_result_validate['errors']);$i++){     
        $building_errors.="-";
        $building_errors.=$err_vald_cont[$array_result_validate['errors'][$i]];
        if(count($array_result_validate['errors'])>$i+1)
        $building_errors.="#";    
        }
        $building_errors.=";";
    }
    if(!empty($building_errors)){
        echo substr($building_errors,0,strlen($building_errors)-1);
    }
    break;  
    } 
}

function is_validate_contents($data){ 
$return_arrays=array();
$error_contents=array();
$filter_contents=NULL;
    if(strlen($data)>200){
    $error_contents[]=1; 
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
?>
