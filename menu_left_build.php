<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class menu_left_build{
    var $all_grant=NULL;
    function menu_left_build($id_login,$array_data=NULL){
        $sql="SELECT * 
        FROM (
        SELECT name as name,surname as surname,sender_id as sender,recipient_id as recipient,contents as contents,time as time,status as status FROM message ms JOIN users_info us ON us.user_id=ms.sender_id
        JOIN message_type mst ON mst.id=ms.id  
        WHERE ms.recipient_id=".mysql_real_escape_string($id_login)." AND mst.hidden=0 AND mst.user_id=".mysql_real_escape_string($id_login)." 
        ORDER BY status ASC,time DESC
        ) AS message WHERE status=0 
        GROUP BY sender ORDER BY status ASC";
        $gosql = @mysql_query($sql);
        $num_rows_message = @mysql_num_rows($gosql);
        $sql="SELECT user_id FROM users WHERE all_grant=1";
        $goadmin = @mysql_query($sql);
        $num_rows_admin = @mysql_num_rows($goadmin);
        for($i=0;$i<$num_rows_admin;$i++){ 
        $result = @mysql_fetch_assoc($goadmin);
            if($result['user_id']==$id_login){
                $this->all_grant=TRUE;
            }
        }
        $sql="SELECT * FROM users";
        $us_sql = @mysql_query($sql);
        $num_rows_us = @mysql_num_rows($us_sql);
        
       $all_options=(($this->all_grant==TRUE)?'<center><BR><BR>Panel<br>administracyjny:<BR></center><div id="list_cont"><a href="list_profile.php"><div id="list"><div id="txt_list"><span><center>ustawienia użytkowników</center></span></div><div id="info_list"></div></div></a></div>':NULL);
       
       $sql="SELECT IF(LENGTH(name)>17,CONCAT(LEFT(name,14),'...'),name) as name,IF(LENGTH(surname)>17,CONCAT(LEFT(surname,14),'...'),surname) as surname,
       IF(LENGTH(CONCAT(name,' ',surname))>17,'length_more','length_normal') as text_class FROM users_info WHERE user_id=".$id_login;
       $gosql = @mysql_query($sql);
       $row = @mysql_fetch_assoc($gosql);
       @mysql_free_result($gosql); 
       $sql="SELECT id_friends FROM invitation WHERE user_id=".$id_login;
       $invitation_sql = @mysql_query($sql);
        $friends_result = @mysql_fetch_assoc($invitation_sql);
        $friends_result = explode(",", $friends_result['id_friends']); 
        $num_rows_invitation=count($friends_result);
        if($friends_result[0]==""){
        $num_rows_invitation=0;
        }
       $next_line=($row['text_class']=='length_more')?'<br>':NULL;
       echo '<div class="menu_left_build">';
       echo '<div id="content_profile">';
       echo '<div id="image" class="content_profile">
       <img class="content_profile" src="gallery/'.$this->validate_img_avatar($id_login).'.jpg?'.time().'" height="40" width="40"></div>';
               echo '<div id="text_surname" class="content_profile">
       <span class="'.$row['text_class'].'"><a href="main_profile.php">'.strtoupper($row["name"][0]).substr($row["name"],1,(strlen($row["name"]))).' '.$next_line.strtoupper($row["surname"][0]).substr($row["surname"],1,(strlen($row["surname"]))).'</a></span></div></div>';
       echo '<div id="main_options"><div id="invitation_cont"><a href="invitation_profile.php"><div id="invitation"><div id="txt_ivitation"><span>zaproszenia</span></div><div id="info_invitation"><span>'.$num_rows_invitation.'</span></div></div></a></div>'
               . '<div id="message_cont"><a href="message_profile.php"><div id="message"><div id="txt_message"><span>wiadomości</span></div><div id="info_message"><span id="ml_num_no_get_back">'.$num_rows_message.'</span></div></div></a></div></div>'.$all_options;
       echo '</div>';
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
