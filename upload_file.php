<?php 
if (($_FILES['plik']['size'] > 1000000) || ($_FILES['plik']['size']==0)) {
echo "error:Plik musi zajmować <br>poniżej<br> 1000 KB !!!";
}elseif(!(($_FILES['plik']['type']=="image/jpeg") || ($_FILES['plik']['type']=="IMAGE/JPEG") || ($_FILES['plik']['type']=="image/pjpeg"))){
echo "error:Plik musi być w formacie jpg !!!";
}else{
    $plik_tmp = $_FILES['plik']['tmp_name'];
    if(is_uploaded_file($plik_tmp)) {
        move_uploaded_file($plik_tmp,dirname(__FILE__).'/gallery/'.$_POST['form_id_login'].'.jpg');
        echo "1";
    }    
}
?>
