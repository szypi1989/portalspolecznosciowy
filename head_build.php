<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}
class head_build{
           
    function head_build(){
    }
    
    function create_data($head_data_class,$id_login=null,$id_profile=null){
    include_once $head_data_class.'.php';
    $ob=new $head_data_class($id_login,$id_profile);
    }
    
 
}
?>
