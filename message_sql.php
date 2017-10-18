<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
$building_errors=NULL;
$err_vald_cont=array(
            1 => "Treść wiadomości nie może przekraczać 240 znaków",
            2 => "drugi błąd"
     );
if($_SERVER['REQUEST_METHOD']=='GET'){
    switch ($_GET["field"]){
    case "refresh_inbox":
    //budowanie zapytania dla wszystkich wiadomości w skrzynce odbiorczej
        //poniższy skrypt pokazuje tylko wiadomości skrzynki odbiorczej,
        //pokazuje tylko wiadomość zgrupowane przez przyjaciela i według poniższych wartości
        //BUDOWANIE ZAPYTANIA DLA MESSAGE ALL INFO
        //HIERARCHIA WARTO�CI STATUS,NAJWA�NIEJSZE ,POTEM TIME
        //ZAPYTANIE SORTUJE PIERW PO NAJWAŻNIEJSZYM STATUSU ROSNĄCĄ A GDY POSORTUJE ROSNĄCO PO STATUSU TO POTEM W DUGIEJ KOLJNOŚCi po czasie
        //POTEM GRUPUJE ZAPYTANIE WEDŁUG ID , A NA KOŃCU SORTUJE ZGRUPOWANE WARTOŚCI WEDŁUG STATUSU
        $sql="SELECT * 
        FROM (
        SELECT name as name,surname as surname,sender_id as sender,recipient_id as recipient,contents as contents,time as time,status as status FROM message ms JOIN users_info us ON us.user_id=ms.sender_id
        JOIN message_type mst ON mst.id=ms.id  
        WHERE ms.recipient_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." AND mst.hidden=0 AND mst.user_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." 
        ORDER BY status ASC,time DESC
        ) AS message
        GROUP BY sender ORDER BY status ASC";
        $message_info_all_sql = @mysql_query($sql);
        if(!$message_info_all_sql){
        $building_errors.="error:błąd w bazie danych";
        }
        $message_status=NULL;
        $num_rows_message_all_inbox = mysql_num_rows($message_info_all_sql);
        $building_messages_all_inbox=NULL;
        $count_friend_message_missed=0;
        $building_checkbox='<input type="checkbox" name="mark_delete_inbox"/>';
        $building_options_marks_delete_inbox='<div id="but_options_marks_delete_inbox" class="cont_but_options_marks_delete"><div id="but_mark_all_inbox" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all_inbox" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_inbox" class="but_delete_marks"><span>usuń zaznaczoną konwersacje</span></div></div>';
        for($i=0;$i<$num_rows_message_all_inbox;$i++){ 
        $result = @mysql_fetch_assoc($message_info_all_sql);
        //budowanie adresu do profilu
        $count_friend_message_missed=((int)$result['status']==0)?$count_friend_message_missed=$count_friend_message_missed+1:$count_friend_message_missed;
        $message_status=((int)$result['status']!=1)?" no_get_back":NULL;
        $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($result["sender"]);
        $sql_user_info = @mysql_query($sql);
        if(!$sql_user_info){
        $building_errors="error:błąd w bazie danych";
        }
        $sql_user_info = @mysql_fetch_assoc($sql_user_info);
        $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_user_info['name']).'" AND surname="'.mysql_real_escape_string($sql_user_info['surname']).'"';
        $gosql = @mysql_query($sql);
        if(!$gosql){
        $building_errors="error:błąd w bazie danych";
        }
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
        $building_messages_all_inbox.='<div class="message_cont_text_all_inbox'.$message_status.'"><div class="message_cont_left"><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.validate_img_avatar($result["sender"]).'.jpg?'.time().'" height="40" width="40"></a></div></div>';
        $building_messages_all_inbox.='<div class="message_cont_info"><a class="first_link" href="message_profile.php?id_talk='.$result["sender"].'"><div class="message_cont_info_head"><div class="date_name_head">'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"])))."&nbsp;".strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</div><div class="date_time_head">'
        .$result["time"].'</div></div></a><div class="date_cont_message"><a href="message_profile.php?id_talk='.$result["sender"].'"><div class="contents"><span>'.$result["contents"].'</span></div></a><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
        }
        echo $building_messages_all_inbox;
        //exit scirpt skrzynka odbiorcza
    break; 
    case "refresh_outbox":
        $sql="SELECT * 
        FROM (
        SELECT name as name,surname as surname,sender_id as sender,recipient_id as recipient,contents as contents,time as time,status as status FROM message ms JOIN users_info us ON us.user_id=ms.recipient_id JOIN message_type mst ON mst.id=ms.id WHERE ms.sender_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." AND mst.hidden=0 AND mst.user_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." 
        ORDER BY status ASC,time DESC
        ) AS message
        GROUP BY recipient ORDER BY status ASC";
        $message_info_all_sql = @mysql_query($sql);
        $num_rows_message_all_outbox = mysql_num_rows($message_info_all_sql);
        $building_messages_all_outbox=NULL;
        $building_checkbox='<input type="checkbox" name="mark_delete_outbox"/>';
        $building_options_marks_delete_outbox='<div id="but_options_marks_delete_outbox" class="cont_but_options_marks_delete"><div id="but_mark_all_outbox" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all_outbox" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_outbox" class="but_delete_marks"><span>usuń zaznaczoną konwersacje</span></div></div>';
        for($i=0;$i<$num_rows_message_all_outbox;$i++){ 
        $result = @mysql_fetch_assoc($message_info_all_sql);
        $message_status=((int)$result['status']!=1)?" no_get_back":NULL;
        //budowanie adresu do profilu
        $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($result["recipient"]);
        $sql_user_info = @mysql_query($sql);
        $sql_user_info = @mysql_fetch_assoc($sql_user_info);
        $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_user_info['name']).'" AND surname="'.mysql_real_escape_string($sql_user_info['surname']).'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($num_profile=0;$seek==NULL;$num_profile++)
        {
            if((mysql_result($gosql,$num_profile,0))==$result["recipient"]){ 
            break;
            }                 
        $c=$c+1;
        }
        $building_href='main_profile.php?surname='.$sql_user_info['name'].'.'.$sql_user_info['surname'].'.'.$num_profile;
        //exit
        $building_messages_all_outbox.='<div class="message_cont_text_all_outbox'.$message_status.'"><div class="message_cont_left"><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.validate_img_avatar($result["recipient"]).'.jpg?'.time().'" height="40" width="40"></a></div></div>';
        $building_messages_all_outbox.='<a class="first_link" href="message_profile.php?id_talk='.$result["recipient"].'"><div class="message_cont_info"><div class="message_cont_info_head"><div class="date_name_head">'.'wiadomość do:'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"])))."&nbsp;".strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</div><div class="date_time_head">'
        .$result["time"].'</div></div></a><div class="date_cont_message"><a href="message_profile.php?id_talk='.$result["recipient"].'"><div class="contents"><span>'.$result["contents"].'</span></div></a><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
        }
        echo $building_messages_all_outbox;
    break;
    case "refresh_bin":
    $sql="SELECT usb.name as sender_name,usb.surname as sender_surname,us.name as recipient_name,us.surname as recipient_surname,sender,ids,recipient,contents,time,status
        FROM (
        SELECT id as ids,sender_id as sender,recipient_id as recipient,contents as contents,time as time,status as status FROM message ms 
        WHERE (ms.sender_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." OR ms.recipient_id=".mysql_real_escape_string(urldecode($_GET["id_login"])).") 
        ORDER BY status ASC,time DESC
        ) AS message 
        JOIN users_info us ON us.user_id=message.recipient
        JOIN users_info usb ON usb.user_id=message.sender
        JOIN message_type mst ON mst.id=message.ids
        WHERE (mst.user_id=".mysql_real_escape_string(urldecode($_GET["id_login"])).") AND (usb.user_id!=".mysql_real_escape_string(urldecode($_GET["id_login"]))." or us.user_id!=".mysql_real_escape_string(urldecode($_GET["id_login"])).") AND mst.hidden=1
        GROUP BY sender,recipient ORDER BY status ASC";
        $message_info_all_bin_sql = @mysql_query($sql);
        $num_rows_message_all_bin = mysql_num_rows($message_info_all_bin_sql);
        $building_messages_all_bin=NULL;
        $class_type_user=NULL;
        $building_checkbox='<input type="checkbox" name="mark_delete_bin"/>';
        $building_options_marks_delete_bin='<div id="but_options_marks_delete_bin" class="cont_but_options_marks_delete"><div id="but_mark_all_bin" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all_bin" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_all_bin" class="but_delete_marks"><span>przywróć zaznaczoną konwersacje</span></div></div>';
        for($i=0;$i<$num_rows_message_all_bin;$i++){ 
        $result = @mysql_fetch_assoc($message_info_all_bin_sql);
        $message_status=((int)$result['status']!=1)?" no_get_back":NULL;
        //budowanie adresu do profilu
        //tablica $sql_info_switch_friends_status ustala czy użytkownik jest nadawcą czy odbiorcą i buduje dane dla message_bin
        $sql_info_switch_id=(((int)$result["sender"])==mysql_real_escape_string(urldecode($_GET["id_login"])))?$result["recipient"]:$result["sender"];
        if(((int)$result["sender"]==mysql_real_escape_string(urldecode($_GET["id_login"])))){
        $sql_info_switch_friends_status["id"]=$result["recipient"];
        $sql_info_switch_friends_status["txt_status"]="wysłane do:";
        $sql_info_switch_friends_status["class"]="status_sender";
        $class_type_user=" sender";
        }else{
        $sql_info_switch_friends_status["id"]=$result["sender"];
        $sql_info_switch_friends_status["txt_status"]="odebrane od:";  
        $sql_info_switch_friends_status["class"]="status_recipient";
        $class_type_user=" recipient";
        }
        //budowanie linku dla użytkownika w zależności od statusu(building a link to the user according to the status)
        $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($sql_info_switch_id);
        $sql_user_info = @mysql_query($sql);
        $sql_user_info = @mysql_fetch_assoc($sql_user_info);
        $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_user_info['name']).'" AND surname="'.mysql_real_escape_string($sql_user_info['surname']).'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($num_profile=0;$seek==NULL;$num_profile++)
        {
            if((mysql_result($gosql,$num_profile,0))==$sql_info_switch_friends_status["id"]){ 
            break;
            }                 
        $c=$c+1;
        }
        $building_href='main_profile.php?surname='.$sql_user_info['name'].'.'.$sql_user_info['surname'].'.'.$num_profile;
        //exit
        $building_messages_all_bin.='<div class="message_cont_text_all_bin'.$class_type_user.$message_status.'"><div class="message_cont_left"><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.validate_img_avatar($sql_info_switch_friends_status["id"]).'.jpg?'.time().'" height="40" width="40"></a></div></div>';
        $building_messages_all_bin.='<a class="first_link" href="message_profile.php?id_talk='.$sql_info_switch_friends_status["id"].'&status=1"><div class="message_cont_info"><div class="message_cont_info_head"><div class="date_text_head"><span class="'.$sql_info_switch_friends_status["class"].'">'.$sql_info_switch_friends_status["txt_status"].'</span><span class="date_name_head">'.strtoupper($sql_user_info['name'][0]).substr($sql_user_info['name'],1,(strlen($sql_user_info['name'])))."&nbsp;".strtoupper($sql_user_info['surname'][0]).substr($sql_user_info['surname'],1,(strlen($sql_user_info['surname']))).'</span></div><div class="date_time_head">'
        .$result["time"].'</div></div></a><div class="date_cont_message"><a href="message_profile.php?id_talk='.$sql_info_switch_friends_status["id"].'&status=1"><div class="contents"><span>'.$result["contents"].'</span></div></a><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
        } 
        echo $building_messages_all_bin;
    break;   
    case "get_info_profile":
        $sql='SELECT user_id,name,surname FROM users_info WHERE user_id='.mysql_real_escape_string(urldecode($_GET["id_profile"]));
        $gosql = @mysql_query($sql);
        $result = @mysql_fetch_assoc($gosql);
        $seek=NULL;
        for($num_profile=0;$seek==NULL;$num_profile++)
        {
            if((mysql_result($gosql,$num_profile,0))==mysql_real_escape_string(urldecode($_GET["id_profile"]))){ 
            break;
            }  
        }
        $result['target']='main_profile.php?surname='.$result['name'].'.'.$result['surname'].'.'.$num_profile;       
        echo json_encode($result);       
    break;  
      case "missed_message":
        $id_friend_sql=(isset($_GET["id_friend"]))?" AND ms.sender_id!=".mysql_real_escape_string(urldecode($_GET["id_friend"])):NULL;  
        $sql="SELECT * 
        FROM (
        SELECT name as name,surname as surname,sender_id as sender,recipient_id as recipient,contents as contents,time as time,status as status FROM message ms JOIN users_info us ON us.user_id=ms.sender_id
        JOIN message_type mst ON mst.id=ms.id  
        WHERE ms.recipient_id=".mysql_real_escape_string(urldecode($_GET["id_login"]))." AND mst.hidden=0 AND mst.user_id=".mysql_real_escape_string(urldecode($_GET["id_login"])).$id_friend_sql." 
        ORDER BY status ASC,time DESC
        ) AS message WHERE status=0 
        GROUP BY sender ORDER BY status ASC";
        $gosql = @mysql_query($sql);
        $num_rows_message = @mysql_num_rows($gosql);
        echo $num_rows_message;
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
