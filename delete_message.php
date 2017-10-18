<?php 
//szypi creates
//Rozwiązanie zmienia zawartość kolumny hidden na wartość 1(inaczej ujmując wrzuca dane do kosza)
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
if($_SERVER['REQUEST_METHOD']=='GET'){
    switch ($_GET["field"]){
    case "delete_message_main":
    $sql='UPDATE message_type SET hidden=1 WHERE id IN ('.mysql_real_escape_string($_GET["ids_message"]).') AND user_id='.mysql_real_escape_string($_GET["id_login"]);
    $gosql = @mysql_query($sql);
    if(!$gosql){
        echo "error0:błąd w bazie danych";
    }
    break;
    case "return_message_bin":
    $sql='UPDATE message_type SET hidden=0 WHERE id IN ('.mysql_real_escape_string($_GET["ids_message"]).') AND user_id='.mysql_real_escape_string($_GET["id_login"]);
    $gosql = @mysql_query($sql);
    if(!$gosql){
        echo "error1:błąd w bazie danych";
    }
    break;
    case "delete_message_inbox":
    $building_ids_message=NULL;
    $sql='SELECT id FROM message WHERE sender_id IN ('.mysql_real_escape_string($_GET["id_talk"]).') AND recipient_id='.mysql_real_escape_string($_GET["id_login"]); 
    $gosql = @mysql_query($sql);
    $num_rows_message= mysql_num_rows($gosql);
    for($i=0;$i<$num_rows_message;$i++){  
    $result = @mysql_fetch_assoc($gosql);
    $building_ids_message.=$result['id'];        
    $building_ids_message.=",";
    }
    $building_ids_message=substr($building_ids_message,0,(strlen($building_ids_message)-1));
    $sql='UPDATE message_type SET hidden=1 WHERE id IN ('.$building_ids_message.') AND user_id='.mysql_real_escape_string($_GET["id_login"]);
    $gosql = @mysql_query($sql);
    if(!$gosql){
        echo "error2:błąd w bazie danych";
    }
    break;
    case "delete_message_outbox":
    $building_ids_message=NULL;
    $sql='SELECT id FROM message WHERE recipient_id IN ('.mysql_real_escape_string($_GET["id_talk"]).') AND sender_id='.mysql_real_escape_string($_GET["id_login"]); 
    $gosql = @mysql_query($sql);
    $num_rows_message= mysql_num_rows($gosql);
    for($i=0;$i<$num_rows_message;$i++){  
    $result = @mysql_fetch_assoc($gosql);
    $building_ids_message.=$result['id'];        
    $building_ids_message.=",";
    }
    $building_ids_message=substr($building_ids_message,0,(strlen($building_ids_message)-1));
    $sql='UPDATE message_type SET hidden=1 WHERE id IN ('.$building_ids_message.') AND user_id='.mysql_real_escape_string($_GET["id_login"]);
    $gosql = @mysql_query($sql);
    if(!$gosql){
        echo "error3:błąd w bazie danych";
    }
    break;
    case "return_message_all_bin":
    //konwersja zmiennych w celu wyciągnięcia statusu skrzynki odbiorczej i nadawczej
    $building_ids_message=NULL;
    $sql='SELECT id FROM message WHERE recipient_id IN ('.mysql_real_escape_string($_GET["id_talk"]).') AND sender_id='.mysql_real_escape_string($_GET["id_login"]); 
    $gosql = @mysql_query($sql);
    $num_rows_message= mysql_num_rows($gosql);
    for($i=0;$i<$num_rows_message;$i++){  
    $result = @mysql_fetch_assoc($gosql);
    $building_ids_message.=$result['id'];        
    $building_ids_message.=",";
    }
    $building_ids_message=substr($building_ids_message,0,(strlen($building_ids_message)-1));
    $sql='UPDATE message_type SET hidden=0 WHERE id IN ('.$building_ids_message.') AND user_id='.mysql_real_escape_string($_GET["id_login"]);
    $gosql = @mysql_query($sql);
    $building_ids_message=NULL;
    $sql='SELECT id FROM message WHERE sender_id IN ('.mysql_real_escape_string($_GET["id_talk"]).') AND recipient_id='.mysql_real_escape_string($_GET["id_login"]); 
    $gosql = @mysql_query($sql);
    $num_rows_message= mysql_num_rows($gosql);
    for($i=0;$i<$num_rows_message;$i++){  
    $result = @mysql_fetch_assoc($gosql);
    $building_ids_message.=$result['id'];        
    $building_ids_message.=",";
    }
    $building_ids_message=substr($building_ids_message,0,(strlen($building_ids_message)-1));
    $sql='UPDATE message_type SET hidden=0 WHERE id IN ('.$building_ids_message.') AND user_id='.mysql_real_escape_string($_GET["id_login"]);
    $gosql = @mysql_query($sql);
    echo $sql;
    if(!$gosql){
        echo "error4:błąd w bazie danych";
    }
    break;
    }
}
?>
