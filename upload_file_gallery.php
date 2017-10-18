<?php 
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php';
$mysql = new mysql();
$building_errors=NULL;
$error=false;
$sql="SELECT id,name,user_id FROM gallery WHERE user_id=".$_POST['form_id_login'];
$gallery_sql = @mysql_query($sql);
$num_rows_gallery = mysql_num_rows($gallery_sql);
$num_check=$num_rows_gallery;
$isset='1';
while(!(empty($isset))){
$sql='SELECT id FROM gallery WHERE user_id='.$_POST['form_id_login'].' AND name LIKE "'.($num_check+1).'.jpg"';
$check_control_name=@mysql_query($sql);
$row_control = @mysql_fetch_assoc($check_control_name);
$isset=$row_control['id'];
$num_check++;
};

if (($_FILES['plik']['size'] > 3000000) || ($_FILES['plik']['size']==0)) {
$building_errors="error:Plik musi zajmować poniżej 3000 KB !!!".$_FILES['plik']['size'];
$error=true;
}
if(!(($_FILES['plik']['type']=="image/jpeg") || ($_FILES['plik']['type']=="IMAGE/JPEG") || ($_FILES['plik']['type']=="image/pjpeg"))){
$building_errors.=($error)?"<br>Plik musi być w formacie jpg!!!":"error:Plik musi być w formacie jpg!!!";
$error=true;
}
if(!is_dir(dirname(__FILE__).'/gallery/'.$_POST['form_id_login'])){
mkdir (dirname(__FILE__).'/gallery/'.$_POST['form_id_login'], 0777);
}
if(!$error){
$plik_tmp = $_FILES['plik']['tmp_name'];
    if(is_uploaded_file($plik_tmp)) {
        move_uploaded_file($plik_tmp,dirname(__FILE__).'/gallery/'.$_POST['form_id_login'].'/'.($num_check).'.jpg');
        $sql='INSERT INTO gallery(name,user_id) VALUES("'.$num_check.'.jpg",'.$_POST['form_id_login'].')';
        $gosql = @mysql_query($sql);
        if(!$gosql){
        echo "error:błąd dostępu do bazy danych";
        }else{
        echo $num_check;
        }
    }   
}else{
    echo $building_errors;
}

?>
