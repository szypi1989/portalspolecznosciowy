<?php 
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
if($_SERVER['REQUEST_METHOD']=='GET'){
$sql="SELECT id,name FROM gallery WHERE user_id=".urldecode($_GET['id_user']);
$gallery_sql = @mysql_query($sql);
$sql="DELETE FROM gallery WHERE id=".(mysql_result($gallery_sql,$_GET['id_foto'],0));
$gosql = @mysql_query($sql);
if($gosql){
unlink(dirname(__FILE__).'/gallery/'.$_GET['id_user'].'/'.(mysql_result($gallery_sql,$_GET['id_foto'],1)));   
echo "zdjęcie zostało usunięte";
}else{
echo "error:brak dostępu do bazy danych,błąd przy usuwaniu zdjęcia";
}
}
?>
