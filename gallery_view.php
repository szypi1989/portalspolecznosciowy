<?php
//blokada pliku dla innych serwisach
//klasa zarządza galerią zdjęć
if (!defined('IN_SZYPI'))
{
	exit;
}

class gallery_view{     
    function gallery_view($id_login,$id_profile,$data=null){
    $sql="SELECT name,surname FROM users_info WHERE user_id=".$id_profile;
    $gosql = @mysql_query($sql);
    $user_info = @mysql_fetch_assoc($gosql);
    @mysql_free_result($gosql);
    echo '<div id="gallery_bg"></div>';
    echo '<div id="gallery_view_cont">';
     echo '<div id="loading_sign"><img src="ajax-loader.gif" width="32" height="32"></div>';
        echo '<div id="gallery_data">';
        echo '<div id="off_gallery"><span>X</span></div>';
            //informacje na temat zdjęć(komentarze itp)
            echo '<div id="gl_inf_but_visibility_cnt"><input type="button" id="bt_inf" value="r&#10;&#10;o&#10;&#10;z&#10;&#10;w&#10;&#10;i&#10;&#10;ń&#10;&#10;&#10;&#10;&#10;&#10;k&#10;&#10;o&#10;&#10;m&#10;&#10;e&#10;&#10;n&#10;&#10;t&#10;&#10;a&#10;&#10;r&#10;&#10;z&#10;&#10;e" /></div>';
            echo '<div id="gallery_info_cont">';
            echo '<div id="contents_full_cm"></div>';
            echo '<div id="cont_cloud_comment_contents"><div id="cloud_comment_contents"></div></div>';
            echo '<div id="cont_gv"><div id="head_info"><div class="gv_head_info"><div id="gv_cnt_img"><img src="gallery/'.$id_profile.'.jpg" height="40" width="40"></div><div id="gv_surname"><a href="'.$data['link_profile'].'"><span>'.strtoupper($user_info["name"][0]).substr($user_info["name"],1,(strlen($user_info["name"]))).' '.strtoupper($user_info["surname"][0]).substr($user_info["surname"],1,(strlen($user_info["surname"]))).'</span></a></div></div><div class="gv_head_info"><div id="gv_time" class="gv_time">Data przesłania:<span id="gv_time_contents"></span></div></div></div>
            <div id="send_comment"><div id="send_comment_inf"><div id="text_left_cm_cont">pozostało:<span id="text_left_cm"></span></div><div id="bt_write_cm_cont"><input type="button" id="bt_write_cm" value="Napisz komentarz"></div><div id="bt_send_cm_cont"><input type="button" id="bt_send_cm" value="Wyślij komentarz"></div>
            </div>
            <div id="field_comment"><textarea id="contents_comment" placeholder="tutaj wpisz treść komentarza" rows="2" cols="45"></textarea></div>
            </div>        
            <div id="cont_gv_comment"><div class="comment"><center><span class="empty_cm">brak komentarzy</span></center></div></div></div>';
           // echo '';
            echo '</div>';
            //kontener zdjęć
            echo '<div id="gallery_img_cont">';
            echo '<div id="description"><div id="description_bg"></div><div id="description_info"><span></span></div></div><div id="cont_edit_description"><div id="cont_ed_context"><textarea id="ed_context" placeholder="tutaj wpisz treść opisu" rows="2" cols="33"></textarea><div id="cont_ed_bt"><input type="button" id="bt_set_description" value="Edytuj opis"></div></div></div>';
            echo '<div id="gallery_arrow_cont">';
                echo '<div id="arrow_left" class="arrow"></div>';
                echo '<div id="arrow_right" class="arrow"></div>';
                echo '</div>';
                echo '<img src="" id="big_img" />';
            echo '</div>';
        echo '</div>';
    echo '</div>';
    }
}
?>
