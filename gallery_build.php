<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class gallery_build{
    var $array_info=array(); 
    function gallery_build($id_login,$id_profile){
        $grant_all=($id_login==$id_profile)?false:true;
        //wyciąganie galeri zdjęć-
        $sql="SELECT id,name,user_id FROM gallery WHERE user_id=".$id_profile;
        $gallery_sql = @mysql_query($sql);
        $num_rows_gallery = mysql_num_rows($gallery_sql);
        $building_gallery=NULL;
        $upload_file=(!$grant_all)?'<div id="form_cont"><form id="image_upload" action="upload_file_gallery.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="form_id_profile" name="form_id_profile" value="'.$id_profile.'"><input type="hidden" id="form_id_login" name="form_id_login" value="'.$id_login.'"><div id="but_upload" class="but_upload"><span>Dodaj zdjęcie</span></div><div id="file_cont" class="but_upload"><input name="plik" type="file" id="plik"/></div></form></div>':'<input type="hidden" id="form_id_profile" name="form_id_profile" value="'.$id_profile.'"><input type="hidden" id="form_id_login" name="form_id_login" value="'.$id_login.'">';
        $result_name_file=NULL;
        $photo_options_cont=(!$grant_all)?'<div class="photo_options_cont"><div class="photo_options"><span>Usuń</span></div></div>':NULL;
        $photo_options_but=(!$grant_all)?'<div class="photo_options_but"><span>Opcje</span></div>':NULL;
        for($i=0;$i<$num_rows_gallery;$i++){       
        $building_gallery.='<div class="cont_photo">'.$photo_options_cont.'<img src="gallery/'.$id_profile.'/'.mysql_result($gallery_sql,$i,1).'" height="130" width="110">'.$photo_options_but.'</div>';
        }
        $building_gallery=(empty($building_gallery))?'<div id="view_empty_gallery"><span>osoba nie posiada zdjęć</span></div>':$building_gallery;
        echo '<div id="centertop"><div id="cloud_check_upload_file"><span>sprawdzanie</span></div><div id="cont_gallery"><div id="header"><div><span class="header" id="text_header"><b>Galeria zdjęć (kliknij na zdjęcia aby dodać komentarze)</b></span><span>(</span><span id="num_rows_gallery" class="header">'.$num_rows_gallery.'</span><span>)</span></div>'.$upload_file.'</div>
        <div id="gallery">'.$building_gallery.'</div>
        </div></div>';

    }   
     
}
?>
