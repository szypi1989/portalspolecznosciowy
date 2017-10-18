<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class list_build{
    var $array_info=array(); 
    function list_build($id_login,$id_profile){
        $grant_all=($id_login==$id_profile)?true:false;
        $pathInfo = pathinfo(__FILE__);
        $friends_result_all=NULL;
        $allow_write_msg=NULL;
        $sql="SELECT * FROM users";
        $friends_sql = @mysql_query($sql);
        $num_rows_friends = @mysql_num_rows($friends_sql);
        for($i=0;$i<$num_rows_friends;$i++){  
        $friends_result = @mysql_fetch_assoc($friends_sql); 
        $friends_result_all.=$friends_result['user_id'].",";
            if($friends_result['allow_write_msg']=="1"){
            $allow_write_msg[$i]=' checked="checked"';    
            }else{
            $allow_write_msg[$i]=NULL;    
            }
        }  
        $friends_result_all=substr($friends_result_all, 0, strlen($friends_result_all)-1); 
        $friends_result = explode(",", $friends_result_all); 
        $num_rows_friends=count($friends_result);
        if($friends_result[0]==""){
        $num_rows_friends=0;
        }

        $building_friends=NULL;
        $building_string_friends_id=NULL;
        $building_href=NULL;
        $delete_option=($grant_all)?'<div class="delete_friend"><span>Usuń użytkownika</span></div>':NULL;
        for($i=0;$i<$num_rows_friends;$i++){  
        $building_string_friends_id.=($i<($num_rows_friends-1))?$friends_result[$i].',':$friends_result[$i];
        $sql="SELECT name,surname FROM users_info WHERE user_id=".$friends_result[$i];
        $friends_sql_info = @mysql_query($sql);
        $friends_result_info = @mysql_fetch_assoc($friends_sql_info);
        //
        $sql ='SELECT user_id FROM users_info WHERE name="'.$friends_result_info['name'].'" AND surname="'.$friends_result_info['surname'].'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($num_profile=0;$seek==NULL;$num_profile++)
        {
            if((mysql_result($gosql,$num_profile,0))==$friends_result[$i]){ 
            break;
            }                 
        $c=$c+1;
        }
        $building_href='main_profile.php?surname='.$friends_result_info['name'].'.'.$friends_result_info['surname'].'.'.$num_profile;
        $file_name='gallery/'.$friends_result[$i].'.jpg';
        $img_path=(file_exists($file_name))?'<img src="gallery/'.$friends_result[$i].'.jpg?'.time().'" height="130" width="110">':'<img src="gallery/null.jpg" height="130" width="110">';
        $building_friends.='<div class="cont_friends"><a href="'.$building_href.'"><div class="cont_avatar_friends">'.$img_path.'</div></a><div class="cont_info_friends"><div class="info"><a href="'.$building_href.'"><div class="friends_surname"><span>'.strtoupper($friends_result_info["name"][0]).substr($friends_result_info["name"],1,(strlen($friends_result_info["name"]))).'&nbsp;'.strtoupper($friends_result_info["surname"][0]).substr($friends_result_info["surname"],1,(strlen($friends_result_info["surname"]))).'</span></div></a></div><div class="info">'.$delete_option.'</div><div class="info" id="all_msg">'.
                '<span>Umożliwienie wysyłania wiadomości</span><input type="checkbox" name="allow_write_msg"'.$allow_write_msg[$i].'/></div></div></div>';
        //        $building_friends.='<div class="cont_friends"><a href="'.$building_href.'"><div class="cont_avatar_friends">'.$img_path.'</div></a><div class="cont_info_friends"><a href="'.$building_href.'"><div class="friends_surname"><span>'.strtoupper($friends_result_info["name"][0]).substr($friends_result_info["name"],1,(strlen($friends_result_info["name"]))).'&nbsp;'.strtoupper($friends_result_info["surname"][0]).substr($friends_result_info["surname"],1,(strlen($friends_result_info["surname"]))).'</span></div></a><br><div class="co">'.$delete_option.'</div></div></div>';
        }
        $building_friends=(empty($building_friends))?'<div id="view_empty_friends"><span>osoba nie posiada znajomych</span></div>':$building_friends;
        echo '<div id="centertop"><div id="cont_friends"><div id="header"><div><span class="header" id="text_header"><b>Lista wszystkich użytkowników</b></span><span>(</span><span id="num_rows_friends" class="header">'.$num_rows_friends.'</span><span>)</span></div></div>
        <div id="friends">'.$building_friends.'</div><div id="hidden_info"><input type="hidden" id="building_string_friends" value="'.$building_string_friends_id.'"><input type="hidden" id="id_profile" value="'.$id_profile.'"></div>
        </div></div>';

    }   
     
}
?>
