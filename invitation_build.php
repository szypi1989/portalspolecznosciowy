<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class invitation_build{
    var $array_info=array(); 
    function invitation_build($id_login,$id_profile){
        $grant_all=($id_login==$id_profile)?true:false;
        if($grant_all){
        $pathInfo = pathinfo(__FILE__);
        $sql="SELECT id_friends FROM invitation WHERE user_id=".$id_login;
        $friends_sql = @mysql_query($sql);
        $friends_result = @mysql_fetch_assoc($friends_sql);
        $friends_result = explode(",", $friends_result['id_friends']); 
        $num_rows_friends=count($friends_result);
        if($friends_result[0]==""){
        $num_rows_friends=0;
        }
        $building_friends=NULL;
        $building_string_friends_id=NULL;
        $building_href=NULL;
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
        $img_path=(file_exists($file_name))?'<img src="gallery/'.$friends_result[$i].'.jpg" height="60" width="50">':'<img src="gallery/null.jpg" height="60" width="50">';
        $building_friends.='<div class="cont_friends"><a href="'.$building_href.'"><div class="cont_avatar_friends">'.$img_path.'</div></a><div class="cont_info_friends"><a href="'.$building_href.'"><div class="friends_surname"><span>'.strtoupper($friends_result_info["name"][0]).substr($friends_result_info["name"],1,(strlen($friends_result_info["name"]))).'&nbsp;'.strtoupper($friends_result_info["surname"][0]).substr($friends_result_info["surname"],1,(strlen($friends_result_info["surname"]))).'</span></div></a><div id="cont_but_accept"><input type="button" class="accept" value="akceptuj"></div></div></div>';
        }
        $building_friends=(empty($building_friends))?'<div id="view_empty_friends"><span>osoba nie posiada zaproszeń</span></div>':$building_friends;
        echo '<div id="centertop"><div id="cont_friends"><div id="header"><div><span class="header" id="text_header"><b>Zaproszenia</b></span><span>(</span><span id="num_rows_friends" class="header">'.$num_rows_friends.'</span><span>)</span></div></div>
        <div id="friends">'.$building_friends.'</div><div id="hidden_info"><input type="hidden" id="building_string_friends" value="'.$building_string_friends_id.'"><input type="hidden" id="id_login" value="'.$id_login.'"></div>
        </div></div>';
        }else{
        echo "nie ma takiej strony";
        }
    }   
     
}
?>
