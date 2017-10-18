<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class form_edit_profile{
    var $array_hd_school=array(0 => "Od",1 => "Do",2 => "Specjalizacja"); 
    var $array_hd_work=array(0 => "Od",1 => "Do",2 => "Zawód"); 
    var $array_button=array(0 => "<br>",1 => "<br>",2 => '<div class="edit_but"><div class="school_edit_but"><span>[</span></div><div class="school_edit_but"><span class="edit_row_school">Edytuj</span></div><div class="school_edit_but"><span class="delete_row_school">Usuń</span></div><div class="school_edit_but"><span class="append_row_school">Dodaj</span></div><div class="school_edit_but"><span>]</span></div></div>'); 
    var $array_name_get_mysql=array("work" => array(0=>45,3=>25,5=>45),"school" => array(0=>45,3=>25,5=>45));
    //funkcja podczas gdy data zawiera zbyt dużo znaków wycina znaki poprzez wzorzec do tablicy array_name_get_mysql.
    function check_control_data_mysql($data,$name_get,$num_get){
    $len_validate=(isset($this->array_name_get_mysql[$name_get][$num_get]))?$this->array_name_get_mysql[$name_get][$num_get]:false;
    $data_control_len=((strlen($data)>$len_validate)&&($len_validate!=false))?substr($data,0,$this->array_name_get_mysql[$name_get][$num_get])."...":$data;
    return $data_control_len;
    }
    function form_edit_profile($id_login,$id_profile){
       $grant_all=($id_login==$id_profile)?true:false;
       $par=true;
       $par_work=true;
       $sql="SELECT name FROM hobby WHERE user_id=".$id_profile;
       $gosql = @mysql_query($sql);
       $building_hobby = @mysql_fetch_assoc($gosql);
       $sql="SELECT name,surname,village FROM users_info WHERE user_id=".$id_profile;
       $gosql = @mysql_query($sql);
       $row = @mysql_fetch_assoc($gosql);
       @mysql_free_result($gosql);
       $sql="SELECT wk.name,wk_inf.time_start,wk_inf.time_end,wk_inf.profession,wk_inf.work_id,wk.place FROM work_info wk_inf LEFT JOIN works wk ON wk_inf.work_id=wk.id WHERE wk_inf.user_id=".$id_profile;
       $worksql = @mysql_query($sql);
       $num_rows_work = mysql_num_rows($worksql);
       for($j=0;$j<$num_rows_work;$j++){
       //#KOD102 BUDOWANIE W TABLICY INFORMACJI DLA CHMURY PODPOWIEDZI
       $sql="SELECT wk_inf.user_id FROM work_info wk_inf LEFT JOIN works wk ON wk_inf.work_id=wk.id WHERE wk.id=".mysql_result($worksql,$j,4);
       $array_count_work[]=mysql_num_rows(@mysql_query($sql)); 
       }
       $sql="SELECT sch.name,sch_inf.time_start,sch_inf.time_end,sch_inf.specialization,sch_inf.school_id,sch.place as id_school FROM school_info sch_inf LEFT JOIN schools sch ON sch_inf.school_id=sch.id WHERE sch_inf.user_id=".$id_profile;
       $gosql = @mysql_query($sql);
       $num_rows = mysql_num_rows($gosql);
       for($j=0;$j<$num_rows;$j++){
       //#KOD102 BUDOWANIE W TABLICY INFORMACJI DLA CHMURY PODPOWIEDZI    
       $sql="SELECT sch_inf.user_id FROM school_info sch_inf LEFT JOIN schools sh ON sch_inf.school_id=sh.id WHERE sh.id=".mysql_result($gosql,$j,4);
       $array_count_school[]=mysql_num_rows(@mysql_query($sql));   
       }
       echo '<div id="cloud_info_school" class="cloud_info"><div id="name_info_school"><span id="school_name"></span></div><div id="count_info"><span id="count_content_school" class="count_content">uczęszcza:</span><span id="count_people_school" class="count_people">10</span><span class="count_content"> osób</span></div></div>';
      echo '<div id="cloud_info_school_place" class="cloud_info"><div id="place_info_school"><span id="school_place"></span></div></div>';
         echo '<div id="cloud_info_school_specialization" class="cloud_info"><div id="specialization_info_school"><span id="school_specialization"></span></div></div>';
       echo '<div id="cloud_info_work" class="cloud_info"><div id="name_info_work"><span id="work_name"></span></div><div id="count_info"><span id="count_content_work" class="count_content">pracuje:</span><span id="count_people_work" class="count_people">10</span><span class="count_content"> osób</span></div></div>';
       echo '<div id="cloud_info_work_place" class="cloud_info"><div id="place_info_work"><span id="work_place"></span></div></div>';
       echo '<div id="cloud_info_work_profession" class="cloud_info"><div id="profession_info_work"><span id="work_profession"></span></div></div>';
         echo'<div id="centertop"><div id="form">';
       if($grant_all){
       echo'<form method="POST">
        <div id="data_info_mysql_hidden">
            <div id="school_get_mysql">';
        for($j=0;$j<$num_rows;$j++){
            echo '<div class="rows_get_school">';
            for($i=0;$i<6;$i++){
            echo '<span class="school_get_mysql">'.mysql_result($gosql,$j,$i).'</span>';
            }    
            echo '<span class="school_get_mysql_count">'.$array_count_school[$j].'</span>';
            echo '</div>';
            echo '<br>';
        }
         echo '</div><div id="work_get_mysql">';
           for($j=0;$j<$num_rows_work;$j++){
            echo '<div class="rows_get_work">';
            for($i=0;$i<6;$i++){
            echo '<span class="work_get_mysql">'.mysql_result($worksql,$j,$i).'</span>';
            }    
            echo '<span class="work_get_mysql_count">'.$array_count_work[$j].'</span>';
            echo '</div>';
        }
         echo '</div>
        </div>
       <div id="content_edit"><span class="username"><b>Edycja profilu:'.strtoupper($row["name"][0]).substr($row["name"],1,(strlen($row["name"]))).' '.strtoupper($row["surname"][0]).substr($row["surname"],1,(strlen($row["surname"]))).'</b></span><span class="id_profile">'.$id_profile.'</span><span id="save_data">[zapisz dane]</span></div> 
       <div id="table_input">';
       /*
       * Kontener służy do edycji danych szkoły
       */
       echo '<div id="content_search_school">';
       echo '<div class="table_form">
            <div id="form_school_name" class="form_obiekt">
                        <div id="label_surname"><span>Szkoła:</span></div>
                        <div id="form_school_search" class="input_search">
                            <div class="form_school_search"></div>
                            <div class="form_school_search"></div>
                            <div class="form_school_search"></div>
                            <div class="form_school_search"></div>
                            <div class="form_school_search"></div>
                            <div class="form_school_search"></div>
                        </div>
                        <input type="text" id="school" value="nazwa uczelni"><input type="text" id="place_school" value="miejscowość">
            </div>
         </div>';
     echo '<div class="table_form">';        
     echo '<div id="school_obiekt" class="form_obiekt">';
            echo '<div class="school_info"><div class="school_time"><span>Data rozpoczęcia</span></div><select name="year_start_sch">'; 
            for ($i = 1945; $i <= 2016; $i++) {
                echo '<option value="'.$i.'">' . $i . '</option>';
            }
            echo '</select></div>';
            echo '<div class="school_info"><div class="school_time"><span>Data zakończenia</span></div><select name="year_finish_sch">'; 
            for ($i = 1945; $i <= 2016; $i++) {
                echo '<option value="'.$i.'">' . $i . '</option>';
            }
            echo '</select></div>';
            echo '<div class="school_info"><div class="school_type"><span>Specjalizacja</span></div>
            <div id="form_school_info_search" class="input_search">
            </div>
            <input type="text" id="specjalization" name="specjalization" value=""></div></div>';
       echo '<div id="exit_cont_school"><input type="button" id="exit_cont_but_school" name="exit_cont_but_school" value="anuluj"></div><div id="save_cont_school"><input type="button" id="save_cont_but_school" name="save_cont_but_school" value="zapisz"></div></div>
        </div>';
       /*
       * Kontener służy do edycji danych pracodawcy
       */
       echo '<div id="content_search_work">';
       echo '<div class="table_form">
            <div id="form_work_name" class="form_obiekt">
                        <div id="label_surname"><span>Pracodawca:</span></div>
                        <div id="form_work_search" class="input_search">
                            <div class="form_work_search"></div>
                            <div class="form_work_search"></div>
                            <div class="form_work_search"></div>
                            <div class="form_work_search"></div>
                            <div class="form_work_search"></div>
                            <div class="form_work_search"></div>
                        </div>
                        <input type="text" id="work" value="Nazwa firmy"><input type="text" id="place_work" value="miejscowość">
            </div>
         </div>';
     echo '<div class="table_form">';        
     echo '<div id="work_obiekt" class="form_obiekt">';
            echo '<div class="work_info"><div class="work_time"><span>Data rozpoczęcia</span></div><select name="year_start_wk">'; 
            for ($i = 1945; $i <= 2016; $i++) {
                echo '<option value="'.$i.'">' . $i . '</option>';
            }
            echo '</select></div>';
            echo '<div class="work_info"><div class="work_time"><span>Data zakończenia</span></div><select name="year_finish_wk">'; 
            for ($i = 1945; $i <= 2016; $i++) {
                echo '<option value="'.$i.'">' . $i . '</option>';
            }
            echo '</select></div>';
            echo '<div class="work_info"><div class="work_type"><span>Zawód</span></div>
            <div id="form_work_info_search" class="input_search">
            </div>
            <input type="text" id="profession" name="profession" value=""></div></div>';
       echo '<div id="exit_cont_work"><input type="button" id="exit_cont_but_work" name="exit_cont_but_work" value="anuluj"></div><div id="save_cont_work"><input type="button" id="save_cont_but_work" name="save_cont_but_work" value="zapisz"></div></div>
        </div>';      
       /*
        * 
        */
       echo '<div class="table_form">
            <div class="form_obiekt"><label for="surname">Imię i nazwisko:</label><br><div id="control_surname" class="control_error"><span></span></div><input type="text" id="name" name="name" value="'.$row["name"].'"><input type="text" id="surname" name="surname" value="'.$row["surname"].'">
            </div>
            <div id="form_schools_list" class="form_obiekt">';
            if($num_rows==0){
            echo '<label for="surname"><input type="button" id="append_school_button" value="+">Dodaj szkołę</label><br>'; 
            echo '<div class="empty_info"><span>brak wypełnionych miejsc szkół</span></div></div>';
            }else{
                echo '<label for="surname">Szkoły:</label><br>';
                echo '<div class="name_schools" id="name_schools_head">nazwa uczelni:</div>';
                for($i=0;$i<$num_rows;$i++)
                {
                //#KOD101B PRZEŁĄCZANIE BUDUWANIA ELEMENTÓW MIĘDZY NAZWĄ SZKOŁY/MIEJSCOWOŚCI    
                $par=!$par;
                if($par){
                echo "<div class='place_schools'>".$this->check_control_data_mysql(strtolower(mysql_result($gosql,$i,5)),"school",5)."</span></div>";    
                }else{
                echo '<div class="name_schools">'.$this->check_control_data_mysql(strtolower(mysql_result($gosql,$i,0)),"school",0).'</span></div>';  
                $i=$i-1;
                }
                }
                echo '</div>';
                echo '<div class="line"><hr></div>';
            }
       echo '<div id="form_work_name" class="form_obiekt">';
        if($num_rows_work==0){
        echo '<label for="surname"><input type="button" id="append_work_button" value="+">Dodaj firmy w których pracował:</label><br>';  
        echo '<div class="empty_info"><span>brak wypełnionych pracodawców</span></div></div>';
        }else{
            echo '<label for="surname">Firmy w których pracował:</label>
            <br><div class="name_works" id="name_works_head">nazwa pracodawcy:</div>';
            for($i=0;$i<$num_rows_work;$i++)
            {
            //#KOD101A PRZEŁĄCZANIE BUDUWANIA ELEMENTÓW MIĘDZY NAZWĄ FIRMY/MIEJSCOWOŚCI
            $par_work=!$par_work;
            if($par_work){
            echo "<div class='place_works'>".$this->check_control_data_mysql(strtolower(mysql_result($worksql,$i,5)),"work",5)."</span></div>";    
            }else{
            echo '<div class="name_works">'.$this->check_control_data_mysql(strtolower(mysql_result($worksql,$i,0)),"work",0).'</span></div>';  
            $i=$i-1;
            }
            }
            echo '</div>';
        }
//echo '</div>';
echo '<div class="form_obiekt"><label for="live">Zainteresowania:&nbsp;</label><br><div id="control_hobby" class="control_error"><span>przykład zapisu(hokey,golf)</span></div><br>
        <input id="hobby" type="text" name="hobby" value="'.$building_hobby['name'].'"><br>
      </div>';
echo '</div>';   
    echo '<div class="table_form">
            <div class="form_obiekt"><label for="live">Miejscowość:</label><br><div id="control_live" class="control_error"><span></span></div>
                <div id="form_live_search" class="input_search">
                <div class="form_live_search"></div>
                <div class="form_live_search"></div>
                <div class="form_live_search"></div>
                <div class="form_live_search"></div>
                <div class="form_live_search"></div>
                <div class="form_live_search"></div>
             </div>
             <input id="live" type="text" name="lives" value="'.$row["village"].'"><br>
    </div>';
           if($num_rows!=0){
           echo '<div id="form_schools_list_type" class="form_obiekt"><br>';
           for($j=1;$j<4;$j++){
                echo '<div class="form_schools_list_type">';
                        echo '<div class="form_schools_list_type_child"><span>'.$this->array_hd_school[$j-1].'</span></div>';
                    for($i=0;$i<$num_rows;$i++)
                    { 
                    $par=!$par;
                    if($par){
                    echo $this->array_button[$j-1];
                    }else{
                    echo '<span class="edit_info">'.$this->check_control_data_mysql(strtolower(mysql_result($gosql,$i,$j)),"school",$j).'</span><br>';
                    $i=$i-1;
                    }
                    }
                echo '</div>';         
           }
           echo '</div>';  
           echo '<div class="line"><hr></div>';
           }else{
               echo '<div class="empty_info" id="right_empty_info"></div>';
           };
           if($num_rows_work!=0){
           echo '<div id="form_works_list_type" class="form_obiekt"><br>';
           for($j=1;$j<4;$j++){
           echo '<div class="form_works_list_type">';
                echo '<div class="form_works_list_type_child"><span>'.$this->array_hd_work[$j-1].'</span></div>';
                    for($i=0;$i<$num_rows_work;$i++)
                    { 
                    $par=!$par;
                    if($par){
                    echo $this->array_button[$j-1];
                    }else{
                    echo '<span class="edit_info">'.$this->check_control_data_mysql(strtolower(mysql_result($worksql,$i,$j)),"work",$j).'</span><br>';
                    $i=$i-1;
                    }
                    }
                echo '</div>';         
           }
           echo '</div>'; 
           }else{
               echo '<div class="empty_info" id="right_empty_info">';
               echo'</div>';
           };
       echo '<div id="date_birth_cont" class="form_obiekt">';
       echo '<div><label for="live">Data urodzenia:&nbsp;</label><div><br>';
       echo '<div id="control_birth" class="control_error"><span>wprowadz datę</span></div><br>';
        echo '<select name="day">';
        for ($i = 1; $i <= 31; $i++) {
            $day=($i<10)? '0'.$i:$i;
            echo '<option value="'.$day.'">' . $day . '</option>';
        }
        echo '</select>';

        echo'<select name="month">
           <option value="01">Styczeń</option>
           <option value="02">Luty</option>
           <option value="03">Marzec</option>
           <option value="04">Kwiecień</option>
           <option value="05">Maj</option>
           <option value="06">Czerwiec</option>
           <option value="07">Lipiec</option>
           <option value="08">Sierpień</option>
           <option value="09">Wrzesień</option>
           <option value="10">Październik</option>
           <option value="11">Listopad</option>
           <option value="12">Grudzień</option>
           </select>';
        echo '<select name="year">'; 
        for ($i = 1945; $i <= date("Y"); $i++) {
            echo '<option value="'.$i.'">' . $i . '</option>';
        }
        echo '</select>';
       echo '</div>';
       echo '</div>';
       echo '</div></form>';
       }else{
       echo "nie można edytować profilu nie swojego";    
       }
       echo "</div></div>";

    }   
     
}
?>
