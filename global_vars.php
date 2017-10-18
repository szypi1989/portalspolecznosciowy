<?php
//blokada pliku dla innych serwisach
if (!defined('IN_SZYPI'))
{
	exit;
}

class global_vars{   
    var $user_href;
    var $profile_href;
    var $building_html=NULL;
    function global_vars($id_login,$id_profile,$data=NULL){
        $sql ='SELECT name,surname FROM users_info WHERE user_id='.$id_login;
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosqls); 
        //obliczanie ile istnieje tych samych osób o tym samym nazwisku
        $sql ='SELECT user_id FROM users_info WHERE name="'.$row['name'].'" AND surname="'.$row['surname'].'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($i=0;$seek==NULL;$i++)
        {
        if((mysql_result($gosql,$i,0))==$id_login){ 
        break;
        }                 
        $c=$c+1;
        }
        $this->user_href='main_profile.php?surname='.$row['name'].'.'.$row['surname'].'.'.$i; 
        
        $sql ='SELECT name,surname FROM users_info WHERE user_id='.$id_profile;
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosqls); 
        //obliczanie ile istnieje tych samych osób o tym samym nazwisku
        $sql ='SELECT user_id FROM users_info WHERE name="'.$row['name'].'" AND surname="'.$row['surname'].'"';
        $gosql = @mysql_query($sql);
        $seek=NULL;
        $c=0;
        for($i=0;$seek==NULL;$i++)
        {
        if((mysql_result($gosql,$i,0))==$id_profile){ 
        break;
        }                 
        $c=$c+1;
        }
        $this->profile_href='main_profile.php?surname='.$row['name'].'.'.$row['surname'].'.'.$i; 
    }
    function building_html(){
    global $session;
    $this->building_html.='<div id="array_global_vars">';    
    $this->building_html.='<div id="user_href" class="array_global_vars">';
    $this->building_html.=$this->user_href;
    $this->building_html.='</div>';
    $this->building_html.='<div id="profile_href" class="array_global_vars">';
    $this->building_html.=$this->profile_href;
    $this->building_html.='</div>';
    $this->building_html.='<div id="global_id_login" class="array_global_vars">';
    $this->building_html.=$session->get_session_user();
    $this->building_html.='</div>';
    $this->building_html.='</div>';
    return $this;
    }
}
?>
