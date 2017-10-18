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
//echo $_FILES['plik']['size'];
if (($_FILES['plik']['size'] > 3000000) || ($_FILES['plik']['size']==0)) {
$building_errors="error:Plik musi zajmować poniżej 3000 KB !!!";
$error=true;
}
if(!(($_FILES['plik']['type']=="image/jpeg") || ($_FILES['plik']['type']=="IMAGE/JPEG") || ($_FILES['plik']['type']=="image/pjpeg"))){
$building_errors.=($error)?"<br>Plik musi być w formacie jpg!!!":"error:Plik musi być w formacie jpg!!!";
$error=true;
}
if(!$error){
echo 'success';  
}else{
    echo $building_errors;
}
?>
