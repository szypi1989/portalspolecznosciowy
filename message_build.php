<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
//budowa klasy odpowiedzialnej za moduł wiadomości
class message_build{
    var $array_info=array(); 
    var $id_login=NULL;
    var $id_talk=NULL;
    var $status=NULL;
    var $num_no_get_back_msg=NULL;
    var $building_data=NULL;
    function message_build($id_login=NULL,$id_talk=NULL,$status=NULL){ 
        //domyślne wartości funkcji muszą być ustawione w celu usunięcia błędów
        $this->id_login=$id_login;
        $this->id_talk=$id_talk;
        $this->status=$status;
        $this->allow_write_msg=0;
        if(($id_login==NULL) || ($id_talk==$id_login)){
        $this->building_data.="strona nie istnieje";
        exit;
        }
        //odkrywanie panelu wysyłania wiadomości w przypadku możliwości dostępu dla tej opcji(kolumna allow_write_msg=1 dla tabeli user);
        $sql="SELECT user_id FROM users WHERE allow_write_msg=1";
        $goadmin = @mysql_query($sql);
        $num_rows_admin = @mysql_num_rows($goadmin);
        for($i=0;$i<$num_rows_admin;$i++){ 
        $result = @mysql_fetch_assoc($goadmin);
            if($result['user_id']==$id_login){
                $this->allow_write_msg=1;
            }
        }

        if(empty($id_talk)){
            //#KOD01 KOD PROGRAMU WYSYŁAJĄCY ZAPYTANIE, KTÓRE POBIERA ID  UŻYTKOWNIKA OD KTÓREGO OSTATNIO ZALOGOWANY UŻYTKOWNIK ODEBRAŁ WIADOMOŚĆ ORAZ TYP KOMUNIKACJI UŻYTKOWNIKA (NADAWCA/ODBIORCA) 
            $sql="SELECT ms.sender_id as sender_id,ms.recipient_id as recipient_id FROM message ms LEFT JOIN message_type mst ON mst.id=ms.id WHERE (ms.sender_id=".mysql_real_escape_string($id_login)." OR ms.recipient_id=".mysql_real_escape_string($id_login).") AND (mst.hidden=0) AND (mst.user_id=".mysql_real_escape_string($id_login).") ORDER BY time DESC";
            $gosql = @mysql_query($sql);
            $row = @mysql_fetch_assoc($gosql);   
            if(((int)$row["sender_id"]==$id_login)){
            $id_user=$row["sender_id"];
            $friends_id=$row["recipient_id"];
            }else{
            $id_user=$row["recipient_id"];
            $friends_id=$row["sender_id"];    
            }
        }else{
        $friends_id=$id_talk; 
        $id_user=$id_login;
        //TWORZENIE ZAPYTAŃ W CELU UTWORZENIA INDENTYFIKACJI UŻYTKOWNIKA Z KTÓRYM TOCZY SIĘ ROZMOWA
        $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($id_talk);
        $sql_user_info = @mysql_query($sql);
        $sql_user_info = @mysql_fetch_assoc($sql_user_info);
        $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_user_info['name']).'" AND surname="'.mysql_real_escape_string($sql_user_info['surname']).'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($num_profile=0;$seek==NULL;$num_profile++)
        {
            if((@mysql_result($gosql,$num_profile,0))==$id_talk){ 
            break;
            }                 
        $c=$c+1;
        }
        $building_href='main_profile.php?surname='.$sql_user_info['name'].'.'.$sql_user_info['surname'].'.'.$num_profile;
        $friend_conversation['name']=$sql_user_info['name']; 
        $friend_conversation['surname']=$sql_user_info['surname'];
        $friend_conversation['building_href']=$building_href; 
        $id_friend=$id_talk;
        }
        $sql= "UPDATE message SET status = 1,time=time WHERE sender_id=".$friends_id." AND recipient_id=".$this->id_login;
        $mysql = mysql_query($sql);
        $sql_hidden_status=($status==1)?NULL:"AND (mst.hidden=0) ";
        $html_building_bin_options=($status==1)?'<div id="message_options_bin"><div class="but_enabled_options_message_bin" id="but_all_options_bin"><span>wszystkie</span></div><div class="but_disabled_options_message_bin" id="but_only_delete_options_bin"><span>w koszu</span></div></div>':NULL;
        $building_options_marks_delete=($status==1)?'<div id="but_options_marks_delete_bin" class="cont_but_options_marks_delete"><div id="but_mark_bin" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_bin" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_bin" class="but_delete_marks"><span>przywróć zaznaczone</span></div></div>':'<div id="but_options_marks_delete" class="cont_but_options_marks_delete"><div id="but_mark_all" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks" class="but_delete_marks"><span>usuń zaznaczone</span></div></div>';
        //#KOD02 Zapytanie dla grupy wiadomości aktywnych z danym użytkownikiem 
        $sql="SELECT us.name as name,us.surname as surname,ms.id,ms.sender_id as sender,ms.recipient_id as recipient,ms.contents as contents,ms.time as time,ms.status as status,mst.hidden as hidden FROM message ms 
        LEFT JOIN users_info us ON us.user_id=ms.sender_id
        JOIN message_type mst ON mst.id=ms.id
        WHERE (mst.user_id=".mysql_real_escape_string($id_login).") AND ((ms.sender_id=".mysql_real_escape_string($id_user)." AND ms.recipient_id=".mysql_real_escape_string($friends_id).") OR (ms.sender_id=".mysql_real_escape_string($friends_id)." AND ms.recipient_id=".mysql_real_escape_string($id_user).")) ".$sql_hidden_status."
        ORDER BY time DESC";
        $message_info_sql = @mysql_query($sql);
        $num_rows_message = @mysql_num_rows($message_info_sql);
        $building_messages=NULL;
        $count_message_missed=0;
        $num_rows_delete_message=0;
        $class_not_delete=NULL;
        $active_bin=false;
        $building_checkbox='<input type="checkbox" name="mark_delete"/>';
        for($i=0;$i<$num_rows_message;$i++){ 
        $result = @mysql_fetch_assoc($message_info_sql);
        $message_status=((int)$result['status']!=1)?" no_get_back":NULL;
        //budowanie adresu do profilu   
        $count_message_missed=((int)$result["sender"]==$id_login)?$count_message_missed:$count_message_missed=$count_message_missed+1;
        $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string($result["sender"]);
        $sql_user_info = @mysql_query($sql);
        $sql_user_info = @mysql_fetch_assoc($sql_user_info);
        $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($sql_user_info['name']).'" AND surname="'.mysql_real_escape_string($sql_user_info['surname']).'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        if($status==1){
            $class_not_delete=((int)$result["hidden"]==1)?NULL:" active";
            $building_checkbox=NULL;
            if((int)$result["hidden"]==1){
            $num_rows_delete_message=$num_rows_delete_message+1;
            $building_checkbox='<input type="checkbox" name="mark_delete"/>';
            $active_bin=true;
            }
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
        if(((int)$result["sender"]!=$id_login)&&(empty($friend_conversation))){
        $friend_conversation['name']=$sql_user_info['name']; 
        $friend_conversation['surname']=$sql_user_info['surname'];
        $friend_conversation['building_href']=$building_href; 
        $id_friend=(int)$result["sender"];
        }
        if(!((int)$result["sender"]!=$id_login)&&(empty($friend_conversation))){
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
        $building_messages.='<div class="message_cont_text'.$class_not_delete.$message_status.'"><div class="message_cont_left"><div class="id_message">'.$result["id"].'</div><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.$this->validate_img_avatar($result["sender"]).'.jpg" height="40" width="40"></a></div></div>';
        $building_messages.='<div class="message_cont_info"><div class="message_cont_info_head"><div class="date_name_head">'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"])))."&nbsp;".strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</div><div class="date_time_head">'
        .$result["time"].'</div></div><div class="date_cont_message"><div class="contents"><span>'.$result["contents"].'</span></div><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
        }
        if($status==1){
        $num_rows_message=$num_rows_delete_message;  
            if(!$active_bin){
            $building_messages=NULL; 
            }
        }
        $id_friend=((empty($id_friend))?((empty($id_talk))?NULL:$id_talk):$id_friend);
        //#KOD03 Zapytanie Mysql dla grupy wiadomości odebranych 
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
        WHERE ms.recipient_id=".mysql_real_escape_string($id_login)." AND mst.hidden=0 AND mst.user_id=".mysql_real_escape_string($id_login)." 
        ORDER BY status ASC,time DESC
        ) AS message
        GROUP BY sender ORDER BY status ASC,time DESC";
        $message_info_all_sql = @mysql_query($sql);
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
        $building_messages_all_inbox.='<div class="message_cont_text_all_inbox'.$message_status.'"><div class="message_cont_left"><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.$this->validate_img_avatar($result["sender"]).'.jpg" height="40" width="40"></a></div></div>';
        $building_messages_all_inbox.='<div class="message_cont_info"><a class="first_link" href="message_profile.php?id_talk='.$result["sender"].'"><div class="message_cont_info_head"><div class="date_name_head">'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"])))."&nbsp;".strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</div><div class="date_time_head">'
        .$result["time"].'</div></div></a><div class="date_cont_message"><a href="message_profile.php?id_talk='.$result["sender"].'"><div class="contents"><span>'.$result["contents"].'</span></div></a><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
        }
        //exit scirpt skrzynka odbiorcza
        //#KOD04 Zapytanie Mysql dla grupy wiadomości nadawczych 
        //budowanie zapytania dla wszystkich wiadomości w skrzynce 
        //poniższy skrypt pokazuje tylko wiadomości skrzynki nadawczej,
        //pokazuje tylko wiadomość zgrupowane przez zarejestrowane użytkownika i według poniższych wartości
        //BUDOWANIE ZAPYTANIA DLA MESSAGE ALL INFO
        //HIERARCHIA WARTO�CI STATUS-NAJWA�NIEJSZE ,POTEM TIME
        //ZAPYTANIE SORTUJE PIERW PO NAJWAŻNIEJSZYM STATUSU ROSNĄCĄ A GDY POSORTUJE ROSNĄCO PO STATUSU TO POTEM W DUGIEJ KOLJNOŚCi po czasie
        //POTEM GRUPUJE ZAPYTANIE WEDŁUG ID , A NA KOŃCU SORTUJE ZGRUPOWANE WARTOŚCI WEDŁUG STATUSU
        $sql="SELECT * 
        FROM (
        SELECT name as name,surname as surname,sender_id as sender,recipient_id as recipient,contents as contents,time as time,status as status FROM message ms JOIN users_info us ON us.user_id=ms.recipient_id JOIN message_type mst ON mst.id=ms.id WHERE ms.sender_id=".mysql_real_escape_string($id_login)." AND mst.hidden=0 AND mst.user_id=".mysql_real_escape_string($id_login)." 
        ORDER BY status ASC,time DESC
        ) AS message
        GROUP BY recipient ORDER BY status ASC,time DESC";
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
        $building_messages_all_outbox.='<div class="message_cont_text_all_outbox'.$message_status.'"><div class="message_cont_left"><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.$this->validate_img_avatar($result["recipient"]).'.jpg" height="40" width="40"></a></div></div>';
        $building_messages_all_outbox.='<a class="first_link" href="message_profile.php?id_talk='.$result["recipient"].'"><div class="message_cont_info"><div class="message_cont_info_head"><div class="date_name_head">'.'wiadomość do:'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"])))."&nbsp;".strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</div><div class="date_time_head">'
        .$result["time"].'</div></div></a><div class="date_cont_message"><a href="message_profile.php?id_talk='.$result["recipient"].'"><div class="contents"><span>'.$result["contents"].'</span></div></a><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
        }
        //exit scirpt skrzynka nadawcza
        //#KOD05 Zapytanie Mysql dla grupy wiadomości w koszu  
        //budowanie zapytania dla wszystkich wiadomości w skrzynce wiadomości usuniętych
        //poniższy skrypt pokazuje tylko wiadomości skrzynce wiadomości usuniętych,
        //pokazuje tylko wiadomość zgrupowane przez zarejestrowane użytkownika i według poniższych wartości
        //BUDOWANIE ZAPYTANIA DLA MESSAGE ALL BIN INFO
        //HIERARCHIA WARTO�CI STATUS-NAJWA�NIEJSZE ,POTEM TIME
        //kosz bedzie tak że będą grupowane wiadomości w zależności od tego kto jest sender i pokazane!!!
        //ZAPYTANIE ŁĄCZY TABELE MESSAGE,USERS_INFO(DWIE KOPIE USERS_INFO ,SENDER I RECIPIENT)
        //PRZYKŁADOWA BUDOWANA JEDNEGO REKORDU PO WYSŁANIU ZAPYTANIA DO BAZY
        //sender_name 	|sender_surname|recipient_name|recipient_surname|sender|recipient|contents    |time               |status
        //piotr 	|mad           |mariusz       |szypula          |37    |12 	 |heeej co .. |2014-05-29 19:49:52|0
        $sql="SELECT * 
        FROM (
        SELECT ms.id as ids,sender_id as sender,recipient_id as recipient,contents as contents,time,status,us.name as recipient_name,us.surname  as recipient_surname,usb.name as sender_name,usb.surname as sender_surname FROM message ms
        JOIN users_info us ON us.user_id=ms.recipient_id
        JOIN users_info usb ON usb.user_id=ms.sender_id
        JOIN message_type mst ON mst.id=ms.id    
        WHERE (ms.sender_id=".mysql_real_escape_string($id_login)." OR ms.recipient_id=".mysql_real_escape_string($id_login).") AND mst.user_id=".mysql_real_escape_string($id_login)." AND mst.hidden=1  
        ORDER BY status ASC,time desc
        ) AS message 
        GROUP by sender,recipient ORDER BY status ASC,time desc";       
        $message_info_all_bin_sql = @mysql_query($sql);
        $num_rows_message_all_bin = mysql_num_rows($message_info_all_bin_sql);
        $building_messages_all_bin=NULL;
        $class_type_user=NULL;
        $building_checkbox='<input type="checkbox" name="mark_delete_bin"/>';
        $building_options_marks_delete_bin='<div id="but_options_marks_delete_all_bin" class="cont_but_options_marks_delete"><div id="but_mark_all_bin" class="but_mark_all"><span>zaznacz wszystkie</span></div><div id="but_not_mark_all_bin" class="but_not_mark_all"><span>odznacz wszystkie</span></div><div id="but_delete_marks_all_bin" class="but_delete_marks"><span>przywróć zaznaczoną konwersacje</span></div></div>';
        for($i=0;$i<$num_rows_message_all_bin;$i++){ 
        $result = @mysql_fetch_assoc($message_info_all_bin_sql);
        $message_status=((int)$result['status']!=1)?" no_get_back":NULL;
        //budowanie adresu do profilu
        //tablica $sql_info_switch_friends_status ustala czy użytkownik jest nadawcą czy odbiorcą i buduje dane dla message_bin
        $sql_info_switch_id=(((int)$result["sender"])==$id_login)?$result["recipient"]:$result["sender"];
        if(((int)$result["sender"]==$id_login)){
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
        $building_messages_all_bin.='<div class="message_cont_text_all_bin'.$class_type_user.$message_status.'"><div class="message_cont_left"><div class="message_cont_image"><a href="'.$building_href.'"><img src="gallery/'.$this->validate_img_avatar($sql_info_switch_friends_status["id"]).'.jpg" height="40" width="40"></a></div></div>';
        $building_messages_all_bin.='<a class="first_link" href="message_profile.php?id_talk='.$sql_info_switch_friends_status["id"].'&status=1"><div class="message_cont_info"><div class="message_cont_info_head"><div class="date_text_head"><span class="'.$sql_info_switch_friends_status["class"].'">'.$sql_info_switch_friends_status["txt_status"].'</span><span class="date_name_head">'.strtoupper($sql_user_info['name'][0]).substr($sql_user_info['name'],1,(strlen($sql_user_info['name'])))."&nbsp;".strtoupper($sql_user_info['surname'][0]).substr($sql_user_info['surname'],1,(strlen($sql_user_info['surname']))).'</span></div><div class="date_time_head">'
        .$result["time"].'</div></div></a><div class="date_cont_message"><a href="message_profile.php?id_talk='.$sql_info_switch_friends_status["id"].'&status=1"><div class="contents"><span>'.$result["contents"].'</span></div></a><div class="options_message_delete">'.$building_checkbox.'</div></div></div></div>';
        }      
        $this->num_no_get_back_msg=$count_friend_message_missed;
        $this->building_data.='<div id="centertop"><div id="message_cont_text_temp"></div><input type="hidden" name="id_login" value="'.$id_login.'"/><div id="cont_message"><div id="header"><div><span id="but_message_all" class="text_header"><b>Menu wiadomości</b></span><span class="text_header">(</span><span id="num_rows_friends" class="text_header">'.$count_friend_message_missed.'</span><span class="text_header">)</span><a href="'.((isset($friend_conversation['building_href']))?$friend_conversation['building_href']:NULL).'"><span class="username_friend_head"><span>&nbsp;</span>'.((isset($friend_conversation['name']))?$friend_conversation['name']:NULL).'&nbsp;'.((isset($friend_conversation['surname']))?$friend_conversation['surname']:NULL).'</span></a>'.((empty($building_messages))?'<span class="username_friend_head">(</span><span class="username_friend_head" id="count_message">0</span><span class="username_friend_head">)</span>':'<span class="username_friend_head">(</span><span class="username_friend_head" id="count_message">'.$num_rows_message.'</span><span class="username_friend_head">)</span>').'</div></div>';
        $this->building_data.=((empty($building_messages))?'<div id="message_cont_text"><div id="id_friend">'.((empty($id_friend))?"":$id_friend).'</div>'.(($this->status!=1 && $this->allow_write_msg==1 && (!empty($id_friend)))?'<div id="cont_send_message"><div id="cont_text_message"><textarea id="contents_message" placeholder="tutaj wpisz treść wiadomości" rows="3" cols="60"></textarea></div><div id="options_send_message"><div id="button_cont_send"><input type="button" id="send_message" value="wyślij wiadomość"></div><div id="status_actual_write"><div class="status_actual_write">'.$friend_conversation['name'].'</div><div class="status_actual_write">pisze...</div></div></div><div id="block_for_all_message">'.$html_building_bin_options.'</div></div>':NULL).'<div class="empty_message">brak wiadomości</div><div id="block_for_all_message"></div></div>':'<div id="message_cont_text"><div id="id_friend">'.$id_friend.'</div>'.(($this->status!=1 && $this->allow_write_msg==1)?'<div id="cont_send_message"><div id="cont_text_message"><textarea id="contents_message" placeholder="tutaj wpisz treść wiadomości" rows="3" cols="60"></textarea></div><div id="options_send_message"><div id="button_cont_send"><input type="button" id="send_message" value="wyślij wiadomość"></div><div id="status_actual_write"><div class="status_actual_write">'.$friend_conversation['name'].'</div><div class="status_actual_write">pisze...</div></div></div><div id="block_for_all_message">'.$html_building_bin_options.'</div></div>':'<div id="block_for_all_message">'.$html_building_bin_options.'</div>').'<div id="message_cont_text_contents">'.$building_messages.'</div>'.$building_options_marks_delete.'</div>'); 
        $this->building_data.='<div id="message_all_cont_text"><div id="message_all_cont_text_options"><div class="but_enabled_options_message but_options_message" id="but_inbox"><span>Skrzynka odbiorcza</span></div><div class="but_disabled_options_message but_options_message" id="but_outbox"><span>Skrzynka nadawcza</span></div><div class="but_disabled_options_message but_options_message" id="but_bin_message"><span>Kosz</span></div></div><div id="message_all_cont_text_contents"><div id="message_all_cont_text_inbox" class="message_cont"><div class="build_message">'.$building_messages_all_inbox.'</div>'.(empty($building_messages_all_inbox)?'<div class="empty_message">brak wiadomości</div>':$building_options_marks_delete_inbox).'</div><div id="message_all_cont_text_outbox" class="message_cont"><div class="build_message">'.$building_messages_all_outbox.'</div>'.(empty($building_messages_all_outbox)?'<div class="empty_message">brak wiadomości</div>':$building_options_marks_delete_outbox).'</div><div id="message_all_cont_text_bin" class="message_cont"><div class="build_message">'.$building_messages_all_bin.'</div>'.(empty($building_messages_all_bin)?'<div class="empty_message">brak wiadomości</div>':$building_options_marks_delete_bin).'</div></div></div>';
        $this->building_data.='</div>';
        $this->create_data_session_action_logout();
       }
    function create_data_html(){ 
        echo $this->building_data;
    }
    function create_data_session_action_logout(){ 
        global $session;
        $sql="INSERT INTO session_action_data(session_id,name_function,worth) VALUES('".$session->session_id."','reset_message_control','".$this->id_login."')";
        $gosql = @mysql_query($sql);
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
}
?>
