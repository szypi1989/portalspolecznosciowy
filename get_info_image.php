<?php 
//szypi creates
//Rozwiązanie kompresuje rozdzielczość pliku jpg dla wybranego formatu(1200-600)
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
if($_SERVER['REQUEST_METHOD']=='GET'){
    $sql="SELECT name FROM gallery WHERE user_id=".urldecode($_GET['id_user']);
    $gallery_sql = @mysql_query($sql);
   /* $data = getimagesize(dirname(__FILE__).'/gallery/'.$_GET['id_user'].'/'.(mysql_result($gallery_sql,$_GET['id_foto'],0)));
    if($data[0]>1200 || $data[1]>600){
       //echo ($data[0]/($data[0]-1200)).'c'.($data[1]/($data[1]-600)).'cc';
        echo $data[0].'d'.$data[1].'ee';
        if($data[0]>$data[1]){
        $i=$data[0]/($data[0]-1200);
        $j=$data[1]-($data[1]/$i);
        echo (1200)."-d".$j;
        }else{
        $i=$data[1]/($data[1]-600);
        $j=$data[0]-($data[0]/$i);   
        echo $j."-".(600);
        }
    }else{
    echo $data[0]."-".$data[1];
    }
    * */
    echo (mysql_result($gallery_sql,$_GET['id_foto'],0)).";".$_GET['id_foto'];
}
?>
