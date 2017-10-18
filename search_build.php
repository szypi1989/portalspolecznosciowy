<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class search_build{
    var $array_info=array(); 
    function search_build($id_login,$id_profile,$field_get){
        $grant_all=($id_login==$id_profile)?true:false;
        $pathInfo = pathinfo(__FILE__);
        $field_get = explode(" ", trim($field_get)); 
        $building_friends=NULL;        
        if(empty($field_get[0])){
        $a="%%";    
        }else{
        $a=$field_get[0];    
        }
        if(empty($field_get[1])){
        $b="%%";    
        }else{
        $b=$field_get[1];    
        }    
        $sql="SELECT user_id FROM users_info WHERE (name LIKE '".mysql_real_escape_string($a)."' AND surname LIKE '".mysql_real_escape_string($b)."') OR (name LIKE '".$b."' AND surname LIKE '".mysql_real_escape_string($a)."') OR (name LIKE '%".$b."%' AND surname LIKE '%".mysql_real_escape_string($a)."%')";
        $search_sql = @mysql_query($sql);
        $num_rows_friends = mysql_num_rows($search_sql);
        for($i=0;$i<$num_rows_friends;$i++){  
        $sql="SELECT name,surname FROM users_info WHERE user_id=".mysql_real_escape_string(mysql_result($search_sql,$i,0));
        $friends_sql_info = @mysql_query($sql);
        $friends_result_info = @mysql_fetch_assoc($friends_sql_info);
        //
        $sql ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($friends_result_info['name']).'" AND surname="'.mysql_real_escape_string($friends_result_info['surname']).'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($num_profile=0;$seek==NULL;$num_profile++)
        {
            if((mysql_result($gosql,$num_profile,0))==mysql_result($search_sql,$i,0)){ 
            break;
            }                 
        $c=$c+1;
        }
        $building_href='main_profile.php?surname='.$friends_result_info['name'].'.'.$friends_result_info['surname'].'.'.$num_profile;
        $file_name='gallery/'.mysql_result($search_sql,$i,0).'.jpg';
        $img_path=(file_exists($file_name))?'<img src="gallery/'.mysql_result($search_sql,$i,0).'.jpg" height="130" width="110">':'<img src="gallery/null.jpg" height="130" width="110">';
        $building_friends.='<div class="cont_friends"><a href="'.$building_href.'"><div class="cont_avatar_friends">'.$img_path.'</div></a><div class="cont_info_friends"><a href="'.$building_href.'"><div class="friends_surname"><span>'.strtoupper($friends_result_info["name"][0]).substr($friends_result_info["name"],1,(strlen($friends_result_info["name"]))).'&nbsp;'.strtoupper($friends_result_info["surname"][0]).substr($friends_result_info["surname"],1,(strlen($friends_result_info["surname"]))).'</span></div></a></div></div>';
        }
        $building_friends=(empty($building_friends))?'<div id="view_empty_friends"><span>brak wyników wyszukiwania</span></div>':$building_friends;
        echo '<div id="centertop"><div id="cont_friends"><div id="header"><div><span class="header" id="text_header"><b>Ilość wyników wyszukiwania</b></span><span>(</span><span id="num_rows_friends" class="header">'.$num_rows_friends.'</span><span>)</span></div></div>
        <div id="friends">'.$building_friends.'</div><div id="hidden_info"><input type="hidden" id="building_string_friends" value=""><input type="hidden" id="id_profile" value="'.$id_profile.'"></div>
        </div></div>';

    }   
     
}
?>
