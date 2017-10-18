<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();

if($_SERVER['REQUEST_METHOD']=='GET'){
    switch ($_GET["field"]){
    case "set_description":
    $sql="SELECT id FROM gallery WHERE user_id=".urldecode($_GET['id_user']);
    $gallery_sql = @mysql_query($sql);    
    if(!$gallery_sql){
    echo "error1";    
    exit;
    } 
    $sql='UPDATE gallery SET description="'.urldecode($_GET['context']).'" WHERE id='.mysql_result($gallery_sql,urldecode($_GET['id_photo']),0);
    $gosql =mysql_query($sql);
    if(!$gosql){
    echo "error2";    
    exit;
    } 
    break; 
    } 
}
?>
