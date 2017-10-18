<?php
    define('IN_SZYPI', true);
    include 'mysql.php';
    include 'config.php'; 
    $mysql = new mysql();
    $array_errors=array(
            0 => NULL,
            1 => "Pole nazwa szkoły musi składać się z polskich liter(minimum 3) i nie zawierać znaków specjalnych oraz liczb",
            2 => "Pole miejsce szkoły musi składać się z polskich liter(minimum 4) i nie zawierać znaków specjalnych oraz liczb",
            3 => "Pole nazwa specjalizacji musi składać się z polskich liter(minimum 4) i nie zawierać znaków specjalnych oraz liczb",
            4 => "Pole nazwa firmy musi składać się z polskich liter(minimum 3) i nie zawierać znaków specjalnych oraz liczb",
            5 => "Pole miejsce firmy musi składać się z polskich liter(minimum 4) i nie zawierać znaków specjalnych oraz liczb",
            6 => "Pole nazwa zawodu musi składać się z polskich liter(minimum 4) i nie zawierać znaków specjalnych oraz liczb"
     );
    
     if($_SERVER['REQUEST_METHOD']=='GET'){
        switch ($_GET["field"]){
                case "edit_school":  
                $array_errors_check=filter_validate_edit_school(mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"])))));               
                if(empty($array_errors_check)){
                $sql="SELECT id FROM schools WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_old"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_old"]))))."'";
                $gosql = @mysql_query($sql);
                $school_old = @mysql_fetch_assoc($gosql);
                $sql="SELECT id,name FROM schools WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"]))))."'";
                $gosql = @mysql_query($sql);
                $school = @mysql_fetch_assoc($gosql);
                    if((empty($school["name"]))){       
                    $sql="DELETE FROM school_info WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))." AND school_id=".mysql_real_escape_string($school_old["id"])." AND time_start=".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_old"]))))." AND time_end=".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_old"]))))." AND specialization='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_old"]))))."'";
                    $gosql = mysql_query($sql);
                    if(!$gosql){
                    echo 'error1';
                    return;
                    }
                    $sql="INSERT INTO schools(name,place) VALUES('".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"]))))."')";
                    $gosql = @mysql_query($sql);
                    if(!$gosql){
                    echo "error2";
                    return;
                    }   
                    $sql="SELECT id FROM schools WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"]))))."'";
                    $gosql = @mysql_query($sql);
                    if(!$gosql){
                    echo "error3";
                    return;
                    }
                    $row = @mysql_fetch_assoc($gosql);
                    $sql="INSERT INTO school_info(user_id,specialization,school_id,time_start,time_end) VALUES('".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"]))))."','".$row["id"]."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."')";
                    $gosql = @mysql_query($sql);
                    if(!$gosql){
                    echo "error4";
                    return;
                    }              
                    echo "Dodano uczelnię dla użytkownika";
                    }else{
                        $sql="SELECT user_id FROM school_info WHERE school_id=".mysql_real_escape_string($school["id"])." AND user_id=".mysql_real_escape_string($_GET["id_profile"])." AND specialization LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"]))))."' AND time_start LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."' AND time_end LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."'";
                        $gosql = @mysql_query($sql);
                        $id_school = @mysql_fetch_assoc($gosql);
                        if((!(empty($id_school["user_id"])))){
                        echo 'ERROR!!!,';
                        echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                        echo 'Dane nie są zmienione lub takie same już istnieją w bazie danych!!!';
                        }else{
                        //$sql="SELECT id FROM school_info WHERE specialization LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"]))))."' AND time_start LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."' AND time_end LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."'";
                        $sql="UPDATE school_info SET school_id=".mysql_real_escape_string($school["id"]).",specialization='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"]))))."',time_start='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."',time_end='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."' WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))." AND school_id=".mysql_real_escape_string($school_old["id"]);
                        $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error5";
                        return;
                        }
                        echo "Zmieniono informacje dla wybranej uczelni";
                        }
                    }
                }else{
                echo 'ERROR!!!,';
                echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                $building_errors="";
                $i=0;
                foreach ($array_errors_check as $value) {
                    $i++;
                    if(!empty($building_errors)){
                    $building_errors.=','.$i.')'.$array_errors[$value];
                    }else{
                    $building_errors.=$i.')'.$array_errors[$value];    
                    }
                }
                echo $building_errors;
                }
                    break;
            case "append_school": 
                $array_errors_check=filter_validate_edit_school(mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"])))));               
                if(empty($array_errors_check)){
                $sql="SELECT id,name FROM schools WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"]))))."'";
                $gosql = @mysql_query($sql);
                $school = @mysql_fetch_assoc($gosql);
                    if((empty($school["name"]))){
                        $sql="INSERT INTO schools(name,place) VALUES('".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"]))))."')";
                        $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error6";
                        return;
                        }
                        $sql="SELECT id FROM schools WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"]))))."'";
                        $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error7";
                        return;
                        }
                        $row = @mysql_fetch_assoc($gosql);
                        $sql="INSERT INTO school_info(user_id,specialization,school_id,time_start,time_end) VALUES('".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"]))))."','".$row["id"]."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."')";
                        $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error8";
                        return;
                        }           
                        echo "Dodano uczelnię dla użytkownika";            
                    }else{
                        $sql="SELECT user_id FROM school_info WHERE school_id=".mysql_real_escape_string($school["id"])." AND user_id=".mysql_real_escape_string($_GET["id_profile"])." AND specialization LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"]))))."' AND time_start LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."' AND time_end LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."'";
                        $gosql = @mysql_query($sql);
                        $id_school = @mysql_fetch_assoc($gosql);
                        if((!(empty($id_school["user_id"])))){
                        echo 'ERROR!!!,';
                        echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                        echo 'Dane nie są zmienione lub takie same już istnieją w bazie danych!!!';
                        }else{
                            $sql="SELECT id FROM schools WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_new"]))))."'";
                            $gosql = @mysql_query($sql);
                            if(!$gosql){
                            echo "error9";
                            return;
                            }
                            $row = @mysql_fetch_assoc($gosql);
                            $sql="INSERT INTO school_info(user_id,specialization,school_id,time_start,time_end) VALUES('".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_new"]))))."','".$row["id"]."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."')";
                            $gosql = @mysql_query($sql);
                            if(!$gosql){
                            echo "error10";
                            return;
                            }
                            echo "Dodano uczelnię do bazy danych dla użytkownika";
                            }
                    }
                }else{
                echo 'ERROR!!!,';
                echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                $building_errors="";
                $i=0;
                foreach ($array_errors_check as $value) {
                    $i++;
                    if(!empty($building_errors)){
                    $building_errors.=','.$i.')'.$array_errors[$value];
                    }else{
                    $building_errors.=$i.')'.$array_errors[$value];    
                    }
                }
                echo $building_errors;
                }
                    break;
            case "delete_school": 
                        $sql="SELECT id FROM schools WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_school_old"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_school_old"]))))."'";
                        $gosql = @mysql_query($sql);
                        echo $sql;
                        $school_old = @mysql_fetch_assoc($gosql);
                        $sql="DELETE FROM school_info WHERE user_id=".mysql_real_escape_string($_GET["id_profile"])." AND school_id=".mysql_real_escape_string($school_old['id'])." AND time_start='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_old"]))))."' AND time_end='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_old"]))))."' AND specialization='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["specialization_old"]))))."'";
                        $gosql = @mysql_query($sql);
                        echo "usunięto wybraną szkołę";
                    break;
            case "delete_work": 
                        $sql="SELECT id FROM works WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_old"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_old"]))))."'";
                        $gosql = @mysql_query($sql);
                        $work_old = @mysql_fetch_assoc($gosql);
                        $sql="DELETE FROM work_info WHERE user_id=".mysql_real_escape_string($_GET["id_profile"])." AND work_id=".$work_old['id']." AND time_start='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_old"]))))."' AND time_end='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_old"]))))."' AND profession='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_old"]))))."'";
                        $gosql = @mysql_query($sql);
                        echo "usunięto wybrane miejsce pracy";
                    break;
                //OPCJE DOTYCZĄCE EDYCJI MIEJSCA PRACY
                case "edit_work":  
                $array_errors_check=filter_validate_edit_work(mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"])))));               
                if(empty($array_errors_check)){
                $sql="SELECT id FROM works WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_old"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_old"]))))."'";
                $gosql = @mysql_query($sql);
                $school_old = @mysql_fetch_assoc($gosql);
                $sql="SELECT id,name FROM works WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"]))))."'";
                $gosql = @mysql_query($sql);
                $school = @mysql_fetch_assoc($gosql);
                    if((empty($school["name"]))){       
                    $sql="DELETE FROM work_info WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))." AND work_id=".mysql_real_escape_string($school_old["id"])." AND time_start=".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_old"]))))." AND time_end=".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_old"]))))." AND profession='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_old"]))))."'";
                    $gosql = mysql_query($sql);
                        if(!$gosql){
                        echo 'error11';
                        return;
                        }
                    $sql="INSERT INTO works(name,place) VALUES('".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"]))))."')";
                    $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error12";
                        return;
                        }   
                    $sql="SELECT id FROM works WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"]))))."'";
                    $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error13";
                        return;
                        }
                    $row = @mysql_fetch_assoc($gosql);
                    $sql="INSERT INTO work_info(user_id,profession,work_id,time_start,time_end) VALUES('".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"]))))."','".$row["id"]."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."')";
                    $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error14";
                        return;
                        }
              
                    echo "Dodano miejsce pracy dla użytkownika";
                    }else{
                    $sql="SELECT user_id FROM work_info WHERE work_id=".mysql_real_escape_string($school["id"])." AND user_id=".mysql_real_escape_string($_GET["id_profile"])." AND profession LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"]))))."' AND time_start LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."' AND time_end LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."'";
                    $gosql = @mysql_query($sql);
                    $id_work = @mysql_fetch_assoc($gosql);
                    if((!(empty($id_work["user_id"])))){
                    echo 'ERROR!!!,';
                    echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                    echo 'Dane nie są zmienione lub takie same już istnieją w bazie danych!!!';
                    }else{
                    $sql="UPDATE work_info SET work_id=".mysql_real_escape_string($school["id"]).",profession='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"]))))."',time_start=".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"])))).",time_end=".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))." WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))." AND work_id=".mysql_real_escape_string($school_old["id"]);
                    $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error15";
                        return;
                        }
                    echo "Zmieniono informacje dla wybranego miejsca pracy";
                    }
                    }
                }else{
                echo 'ERROR!!!,';
                echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                $building_errors="";
                $i=0;
                foreach ($array_errors_check as $value) {
                    $i++;
                    if(!empty($building_errors)){
                    $building_errors.=','.$i.')'.$array_errors[$value];
                    }else{
                    $building_errors.=$i.')'.$array_errors[$value];    
                    }
                }
                echo $building_errors;
                }  
                    break;
                case "append_work":  
                $array_errors_check=filter_validate_edit_work(mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"])))),mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"])))));               
                if(empty($array_errors_check)){
                $sql="SELECT id,name FROM works WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"]))))."'";
                $gosql = @mysql_query($sql);
                $school = @mysql_fetch_assoc($gosql);
                        if((empty($school["name"]))){
                            $sql="INSERT INTO works(name,place) VALUES('".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"]))))."')";
                            $gosql = @mysql_query($sql);
                            if(!$gosql){
                            echo "error16";
                            return;
                            }
                            $sql="SELECT id FROM works WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"]))))."'";
                            $gosql = @mysql_query($sql);
                            if(!$gosql){
                            echo "error17";
                            return;
                            }
                            $row = @mysql_fetch_assoc($gosql);
                            $sql="INSERT INTO work_info(user_id,profession,work_id,time_start,time_end) VALUES('".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"]))))."','".$row["id"]."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."')";
                            $gosql = @mysql_query($sql);
                            if(!$gosql){
                            echo "error18";
                            return;
                            }           
                            echo "Dodano miejsce pracy dla użytkownika";            
                        }else{
                            $sql="SELECT user_id FROM work_info WHERE work_id=".mysql_real_escape_string($school["id"])." AND user_id=".mysql_real_escape_string($_GET["id_profile"])." AND profession LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"]))))."' AND time_start LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."' AND time_end LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."'";
                            $gosql = @mysql_query($sql);
                            $id_work = @mysql_fetch_assoc($gosql);
                            if((!(empty($id_work["user_id"])))){
                            echo 'ERROR!!!,';
                            echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                            echo 'Dane nie są zmienione lub takie same już istnieją w bazie danych!!!';
                            }else{
                                $sql="SELECT id FROM works WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name_work_new"]))))."' AND place LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["place_work_new"]))))."'";
                                $gosql = @mysql_query($sql);
                                if(!$gosql){
                                echo "error19";
                                return;
                                }
                                $row = @mysql_fetch_assoc($gosql);
                                $sql="INSERT INTO work_info(user_id,profession,work_id,time_start,time_end) VALUES('".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"]))))."','".$row["id"]."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_start_new"]))))."','".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year_finish_new"]))))."')";
                                $gosql = @mysql_query($sql);
                                if(!$gosql){
                                echo "error20";
                                return;
                                }
                                echo "Dodano miejsce pracy dla użytkownika";
                            }
                        } 
                    }else{
                echo 'ERROR!!!,';
                echo '- - - - - - - - - - - - - - - - - - - - - - - - ,';
                $building_errors="";
                $i=0;
                foreach ($array_errors_check as $value) {
                    $i++;
                    if(!empty($building_errors)){
                    $building_errors.=','.$i.')'.$array_errors[$value];
                    }else{
                    $building_errors.=$i.')'.$array_errors[$value];    
                    }
                }
                echo $building_errors;
                } 
                    break;
                    case "save_hobby":  
                    if(filter_validate_hobby(mysql_real_escape_string(trim(strtolower(urldecode($_GET["hobby"])))))!=0){
                    echo "Pole zainteresowania musi składać się z polskich liter(minimum 4(każde zainteresowanie)),nie zawierać znaków specjalnych oraz liczb
                        i zainteresowania mają być wypełnione po przecinku";    
                    }else{
                    $sql="SELECT name FROM hobby WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))));
                    $gosql = @mysql_query($sql);
                        if(!$gosql){
                        echo "error21";
                        return;
                        }
                    $row = @mysql_fetch_assoc($gosql);
                        $data_hb=explode(",", mysql_real_escape_string(trim(strtolower(urldecode($_GET["hobby"])))));
                        $hobby_build=NULL;
                        foreach ($data_hb as $key=>$value) {
                        $hobby_build.=trim($value);  
                        $hobby_build.=",";
                        }
                        $hobby_build[strlen($hobby_build)-1]=" ";
                        $hobby_build=trim($hobby_build);
                        if(empty($row['name'])){
                        $sql="INSERT INTO hobby(user_id,name) VALUES('".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))))."','".$hobby_build."')";
                        $gosql = @mysql_query($sql);
                        }else{
                        $sql="UPDATE hobby SET name='".$hobby_build."' WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))));
                        $gosql = @mysql_query($sql);
                        }
                    }
                    break;
                    case "save_live":  
                    if(filter_validate_live(mysql_real_escape_string(trim(strtolower(urldecode($_GET["live"])))))!=0){
                    echo "Miejscowość musi składać się z polskich liter(minimum 3) i nie zawierać znaków specjalnych oraz liczb";    
                    }else{
                    $sql="UPDATE users_info SET village='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["live"]))))."' WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))));
                    $gosql = @mysql_query($sql);
                    $sql="SELECT name FROM vilage WHERE name LIKE '".mysql_real_escape_string(trim(strtolower(urldecode($_GET["live"]))))."'";
                    $gosql = @mysql_query($sql);
                    $row = @mysql_fetch_assoc($gosql);
                        if(empty($row['name'])){
                        $sql="INSERT INTO vilage(name) VALUES('".mysql_real_escape_string(trim(strtolower(urldecode($_GET["live"]))))."')";
                        $gosql = @mysql_query($sql);    
                        }
                    }
                    break;
                    case "save_name":  
                    if(filter_validate_name(mysql_real_escape_string(trim(strtolower(urldecode($_GET["name"])))))!=0){
                    echo "Imię musi składać się z polskich liter(minimum 4) i nie zawierać znaków specjalnych,liczb oraz przerw";    
                    }else{
                    $sql="UPDATE users_info SET name='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["name"]))))."' WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))));
                    $gosql = @mysql_query($sql);
                    }
                    break;
                    case "save_surname":  
                    if(filter_validate_surname(trim(strtolower($_GET["surname"])))!=0){
                    echo "Nazwisko musi składać się z polskich liter(minimum 3) i nie zawierać znaków specjalnych,liczb oraz przerw";     
                    }else{
                    $sql="UPDATE users_info SET surname='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["surname"]))))."' WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))));
                    $gosql = @mysql_query($sql);
                    }
                    break;
                    case "save_birth":    
                    $sql="UPDATE users_info SET birth='".mysql_real_escape_string(trim(strtolower(urldecode($_GET["year"])).'-'.mysql_real_escape_string(urldecode($_GET["month"])).'-'.mysql_real_escape_string(urldecode($_GET["day"]))))."' WHERE user_id=".trim(strtolower(urldecode(mysql_real_escape_string($_GET["id_profile"]))));
                    $gosql = @mysql_query($sql);
                    if(!$gosql){
                    echo "error:błąd bazy danych(save_birth)";
                    }else{
                    echo 'Zmieniono datę urodzenia';    
                    }
                    break;
        }
     }
      function filter_validate_name($name){
         $name=htmlentities($name);
            $name=str_replace ("&nbsp;", " ", $name);
            $name=str_replace ("&oacute;", "ó", $name);
        if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż]{4,20}$/D',$name)){
            return 1;
        }     
        return 0;  
       }
       function filter_validate_surname($surname){
            $surname=htmlentities($surname);
            $surname=str_replace ("&nbsp;", " ", $surname);
            $surname=str_replace ("&oacute;", "ó", $surname);
        if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż]{3,20}$/D',$surname)){
            return 1;
        }     
        return 0;  
       }
        function filter_validate_live($live){
            $live=htmlentities($live);
            $live=str_replace ("&nbsp;", " ", $live);
            $live=str_replace ("&oacute;", "ó", $live);
        if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż\s]{3,30}$/D',$live)){
            return 1;
        }     
        return 0;  
       }
       function filter_validate_hobby($hobby){
            $hobby=htmlentities($hobby);
            $hobby=str_replace ("&nbsp;", " ", $hobby);
            $hobby=str_replace ("&oacute;", "ó", $hobby);
        $hobby_result = explode(",",$hobby);
            foreach ($hobby_result as $value) {
                if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż\s]{'.real_alignment_length_string_utf($value,4).','.real_alignment_length_string_utf($value,40).'}$/D',$value)){
                return 1;
                }
            }     
        return 0;  
       }
        function filter_validate_edit_school($name,$place,$specjalization){
            $name=htmlentities($name);
            $name=str_replace ("&nbsp;", " ", $name);
            $name=str_replace ("&oacute;", "ó", $name);
            $place=htmlentities($place);
            $place=str_replace ("&nbsp;", " ", $place);
            $place=str_replace ("&oacute;", "ó", $place);
            $specjalization=htmlentities($specjalization);
            $specjalization=str_replace ("&nbsp;", " ", $specjalization);
            $specjalization=str_replace ("&oacute;", "ó", $specjalization);
                $return_value=array();
                if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃóÓŚśŹźŻż\s]{'.real_alignment_length_string_utf($name,3).','.real_alignment_length_string_utf($name,60).'}$/D',$name)){
                $return_value[]=1;
                } 
                if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż\s]{'.real_alignment_length_string_utf($place,4).','.real_alignment_length_string_utf($place,30).'}$/D',$place)){
                $return_value[]=2;
                } 
                if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż\s]{'.real_alignment_length_string_utf($specjalization,4).','.real_alignment_length_string_utf($specjalization,40).'}$/D',$specjalization)){
                $return_value[]=3;
                }               
        return $return_value;  
       }
       function filter_validate_edit_work($name,$place,$profession){
                $name=htmlentities($name);
                $name=str_replace ("&nbsp;", " ", $name);
                $name=str_replace ("&oacute;", "ó", $name);
                $place=htmlentities($place);
                $place=str_replace ("&nbsp;", " ", $place);
                $place=str_replace ("&oacute;", "ó", $place);
                $profession=htmlentities($profession);
                $profession=str_replace ("&nbsp;", " ", $profession);
                $profession=str_replace ("&oacute;", "ó", $profession);
                $return_value=array();
                if($profession[2]=="ł"){
                $return_value[]=4;
                }
                if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃóÓŚśŹźŻż\s]{'.real_alignment_length_string_utf($name,3).','.real_alignment_length_string_utf($name,30).'}$/D',$name)){
                $return_value[]=4;
                } 
                if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż\s]{'.real_alignment_length_string_utf($place,4).','.real_alignment_length_string_utf($place,30).'}$/D',$place)){
                $return_value[]=5;
                } 
                if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż\s]{'.real_alignment_length_string_utf($profession,4).','.real_alignment_length_string_utf($profession,20).'}$/D',$profession)){
                $return_value[]=6;
                }               
        return $return_value;  
       }
       //funkcja dodaje dodaje dodatkową długość w przypadku specjalnych znaków które używają więcej bajtów.(np.litery ł używa 2 bajty) 
       function real_alignment_length_string_utf($data,$length){
       return ($length+(strlen($data)-mb_strlen($data,'utf-8'))); 
       }
       function filter_compres_to_form_match($value,$url_code=false){
        //   echo ord(mysql_real_escape_string(trim(strtolower(urldecode($_GET["profession_new"][2])))));  
       }
?>