<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class profile_build{
    var $array_info=array(); 
    function profile_build($id_login,$id_profile){
       $grant_all=($id_login==$id_profile)?true:false;
       $sql="SELECT us.name,us.surname,us.sex,us.birth as birth,IF(us.village IS NULL,false,us.village) as village,wk.id as wk_key,sh.id as sch_key,wk.profession,IF((YEAR(CURRENT_DATE))<=sh.time_end,true,false) as still_study,IF((YEAR(CURRENT_DATE))<=wk.time_end,true,false) as still_work,IF(sh.specialization IS NULL,false,sh.specialization) as specialization,
       IF(sh.id IS NULL,false,(SELECT name FROM schools WHERE sh.school_id=schools.id)) as 
       school_name,IF(sh.id IS NULL,false,(SELECT place FROM schools WHERE sh.school_id=schools.id)) as 
       school_place,IF(sh.id IS NULL,false,(SELECT IF((SELECT degree_school FROM schools WHERE schools.id=sh.school_id)=5,true,false))) as 
       degree_school,IF(wk.id IS NULL,false,(SELECT name FROM works WHERE wk.work_id=works.id)) as 
       work_name,IF(wk.id IS NULL,false,(SELECT place FROM works WHERE wk.work_id=works.id)) as 
       work_place
       FROM users_info us LEFT JOIN school_info sh ON us.user_id=sh.user_id
       LEFT JOIN work_info wk ON us.user_id=wk.user_id
       WHERE us.user_id=".$id_profile." ORDER BY sh.time_start DESC,sh.time_end DESC,wk.time_start DESC,wk.time_end DESC LIMIT 1";
       $gosql = @mysql_query($sql);
       $row = @mysql_fetch_assoc($gosql);
       @mysql_free_result($gosql);
       $sql="SELECT sch_inf.time_start,sch_inf.time_end,sch_inf.specialization,sch.name,sch.place FROM school_info sch_inf LEFT JOIN schools sch ON sch_inf.school_id=sch.id WHERE sch_inf.user_id=".$id_profile." AND sch_inf.id!=".$row['sch_key']." ORDER BY time_end DESC,time_start DESC";
       $shgosql = @mysql_query($sql);
       $num_rows_sh = @mysql_num_rows($shgosql);
       $view_old_school_array_sg=array(
            0 => "-",
            1 => " ",
            2 => " w:",
            3 => "(",
            4 => ")"
       );
       $sql="SELECT wk_inf.time_start,wk_inf.time_end,wk_inf.profession,wk.name,wk.place FROM work_info wk_inf LEFT JOIN works wk ON wk_inf.work_id=wk.id WHERE wk_inf.user_id=".$id_profile." AND wk_inf.id!=".$row['wk_key']." ORDER BY time_end DESC,time_start DESC";
       $worksql = @mysql_query($sql);
       $num_rows_work = @mysql_num_rows($worksql);
       $view_old_employee_array_sg=array(
            0 => "-",
            1 => " ",
            2 => " w:",
            3 => "(",
            4 => ")"
       );
       $view_count_image=array(
            0 => NULL,
            1 => array('width'=>130,'height'=>150,'count'=>1),
            2 => array('width'=>130,'height'=>75,'count'=>2),
            3 => array('width'=>130,'height'=>75,'count'=>2),
            4 => array('width'=>65,'height'=>75,'count'=>4),
            5 => array('width'=>65,'height'=>75,'count'=>4),
            6 => array('width'=>65,'height'=>50,'count'=>6),
       );
       //
       //wyciąganie galeri zdjęć
       $sql="SELECT id,name,user_id FROM gallery WHERE user_id=".$id_profile;
       $gallery_sql = @mysql_query($sql);
       $num_rows_gallery = mysql_num_rows($gallery_sql);
      // $building_gallery='<div id="option_a"><div class="min_foto"></div><div class="min_foto"></div><div class="min_foto"></div><div class="min_foto"></div><div class="min_foto"></div><div class="min_foto"></div></div>';
       $building_gallery='<div id="option_a">';
       if($num_rows_gallery!=0){
       $num_rows_gallery=($num_rows_gallery<7)?$view_count_image[$num_rows_gallery]['count']:$view_count_image[6]['count'];
        for($i=0;$i<$num_rows_gallery;$i++){  
        $building_gallery.='<img src="gallery/'.$id_profile.'/'.mysql_result($gallery_sql,$i,1).'?'.time().'" height="'.$view_count_image[$num_rows_gallery]['height'].'" width="'.$view_count_image[$num_rows_gallery]['width'].'">';
        }
         $building_gallery.='<div id="overlay_gallery"><div id="overlay_text_bg"></div><div id="overlay_text">GALERIA ZDJĘĆ</div><div class="overlay_gallery"></div><div class="overlay_gallery"></div><div class="overlay_gallery"></div><div class="overlay_gallery"></div><div class="overlay_gallery"></div></div></div>';
       }else{
      $building_gallery.='<img src="gallery/null_gallery.jpg" height="'.$view_count_image[1]['height'].'" width="'.$view_count_image[1]['width'].'">';    
      $building_gallery.='<div id="overlay_gallery"></div></div>';     
       };
        //wczytywanie z bazy zainteresowań
       $sql="SELECT name FROM hobby WHERE user_id=".$id_profile;
       $gosql = @mysql_query($sql);
       $hobby_result = @mysql_fetch_assoc($gosql);
       $hobby_result = explode(",", $hobby_result['name']); 
       $num_rows_hb=count($hobby_result);
       if($hobby_result[0]==""){
           $num_rows_hb=0;
       }
       $still_study=($row["still_study"])?1:0;
       $still_work=($row["still_work"])?1:0;
       $still_work_array_hight = array(
            0 => "pracował",
            1 => "pracuje"
       );
       $still_study_array_hight = array(
            0 => "studiował",
            1 => "studiuje"
       ); 
       $still_study_array_low = array(
            0 => "uczęszczał",
            1 => "uczy się"
       ); 
        $send_message=($grant_all)?NULL:'<input type="button" id="send_message" value="wyślij wiadomość">';
       //ustalanie czy wysłano zaproszenie
        $sql="SELECT id_friends FROM invitation WHERE user_id=".$id_profile;
        $invitation_sql = @mysql_query($sql);
        $invitation_result = @mysql_fetch_assoc($invitation_sql);
        $invitation_result = explode(",", $invitation_result['id_friends']); 
        $num_rows_invitation=count($invitation_result);
        $status_invitation='<input type="button" id="send_invitation" value="wyślij zaproszenie">';
        for($i=0;$i<$num_rows_invitation;$i++){  
        if($invitation_result[$i]==$id_login){
        $status_invitation='<input type="button" id="send_invitation" disabled=disabled class="invitation_sended" value="wysłano zaproszenie">';   
        }    
        }
        $invitation=($grant_all)?NULL:'<div id="but_send_card"><div>'.$send_message.$status_invitation.'</div></div>';
       //wczytywanie z bazy danych znajomych
        $sql="SELECT id_friends FROM friends WHERE user_id=".$id_login;
        $friends_sql = @mysql_query($sql);
        $friends_result = @mysql_fetch_assoc($friends_sql);
        $friends_result = explode(",", $friends_result['id_friends']); 
        $num_rows_friends=count($friends_result);
        for($i=0;$i<$num_rows_friends;$i++){  
        if(($friends_result[$i]==$id_profile)){
        $invitation=($grant_all)?NULL:'<div id="but_send_card"><div>'.$send_message.'<input type="button" id="send_invitation" class="invitation_sended" value="znajomy"></div></div>'; 
        }
        }
       $edit_avatar=($grant_all)?'<div id="avatar"><div id="loading_image"><div><span class="avatar_link">ładuje</span></div></div><div id="cnt_img"><img src="gallery/'.$this->validate_img_avatar($id_profile).'.jpg?'.time().'" height="130" width="110"></div><div id="image"><form id="image_upload" action="upload_file.php" method="POST" enctype="multipart/form-data">
       </div></div><div id="but_upload"><input type="hidden" name="form_id_login" value="'.$id_login.'"><div id="upload_link" class="but_upload"><div><span>edytuj avatar</span></div></div><div id="file_cont" class="but_upload"><input name="plik" type="file" id="plik"/></div></div></form>':'<div id="avatar"><div id="image"></div></div>';
      // $edit_info=($grant_all)?'<span class="actualize_link"><a href="x">zauktualizuj informacje</a></span>':NULL;
       $gallery='<a href="gallery_profile.php?surname='.urlencode($_GET["surname"]).'"><div id="gallery_option">'.$building_gallery.'</div></a>';
       $friend='<a href="friends_profile.php?surname='.urlencode($_GET["surname"]).'"><div id="friend_option"><span>Znajomi</span></div></a>';
       $specialization=($row["specialization"])?'"'.$row["specialization"].'"':NULL;
       $village=($row["village"])?'<span class="normal">mieszka w:</span><span class="where">'.strtoupper($row["village"][0]).substr($row["village"],1,(strlen($row["village"]))).'</span><br>':NULL;
       $degree=($row["degree_school"])?$still_study_array_hight[$still_study].' '.$specialization.' na:':$still_study_array_low[$still_study].'('.$specialization.') do:';
       $school=($row["school_name"])?'<span class="normal">'.$degree.'</span><span class="where">'.$row["school_name"].' w '.$row["school_place"].'</span>&nbsp;<span id="view_old_school_but">[pokaż poprzednie uczelnie]</span><br>':"";
       $work=($row["work_name"])?'<span class="normal">'.$still_work_array_hight[$still_work].' jako '.$row["profession"].' w:</span><span class="where">'.strtoupper($row["work_name"][0]).substr($row["work_name"],1,(strlen($row["work_name"]))).'</span>&nbsp;<span id="view_old_employee_but">[pokaż poprzednich pracodawców]</span><br>':"";
       $birth='<span class="normal">'.(($row["sex"]=="2")?"urodzony":"urodzona").':</span><span class="where">'.$this->convert_date($row["birth"]).' roku</span><br>';
       echo '<div id="centertop"><div id="cont_hidden"><input type="hidden" id="id_login" value="'.$id_login.'"><input type="hidden" id="id_profile" name="id_profile" value="'.$id_profile.'"></div><div id="cont_avatar">'.$edit_avatar.'
       </div><div id="cont_gallery_option">'.$gallery.'
       </div><div id="cont_friend_option">'.$friend.'</div>
       <div id="info">'.$invitation.'<div class="infotext"><span class="name">'.strtoupper($row["name"][0]).substr($row["name"],1,(strlen($row["name"]))).' '.strtoupper($row["surname"][0]).substr($row["surname"],1,(strlen($row["surname"]))).' </span><br>
       '.$village.$work.$birth.'<div id="view_old_employee">';
       for($i=0;$i<$num_rows_work;$i++){  
        for($j=0;$j<5;$j++){
            echo mysql_result($worksql,$i,$j);
            echo $view_old_employee_array_sg[$j];
        }
        echo '<br>';
       }
       echo '</div>'.$school.'<div id="view_old_school">';
       for($i=0;$i<$num_rows_sh;$i++){  
        for($j=0;$j<5;$j++){
            echo mysql_result($shgosql,$i,$j);
            echo $view_old_school_array_sg[$j];
        }
        echo '<br>';
       }
       echo '</div>
       </div></div>
        <!--[if IE ]>
        hwdp
        <![endif]-->
       <div id="hobby"><div class="infotext"><span class="hobbycont">Zainteresowania:('.$num_rows_hb.')</span><br>';
       if($num_rows_hb!=0){
        $i=0;
        foreach ($hobby_result as $value) {
        echo '<span class="rows_hobby">'.$value;
        $comma=(($i+1)==$num_rows_hb)?NULL:',';
        echo $comma;
        echo '</span>';
        ++$i;
        }    
       }else{
       echo '<span class="rows_hobby">nie wypełniono</span>';    
       }
       $link_edit=($grant_all)?'<br><a href="edit_profile.php">uzupełnij/edytuj wszystkie informacje</a>':NULL;
       echo $link_edit.'</div></div>
       </div>';

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
    function convert_date($date){
    $arr_month=array(
        "01" => "Stycznia",
        "02" => "Lutego",
        "03" => "Marca",
        "04" => "Kwietnia",
        "05" => "Maja",
        "06" => "Czerwca",
        "07" => "Lipca",
        "08" => "Sierpnia",
        "09" => "Września",
        "10" => "Października",
        "11" => "Listopada",
        "12" => "Grudnia",
    );    
    $arr_date = explode("-", $date);    
    return ((($arr_date[2][0]=="0")?$arr_date[2][1]:$arr_date[2]).' '.$arr_month[$arr_date[1]].' '.$arr_date[0]);
    }
}
?>
