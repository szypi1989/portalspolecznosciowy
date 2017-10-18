<?php
//blokada pliku dla innych serwisach
//klasa zarządza górną częscią projektu top
if (!defined('IN_SZYPI'))
{
	exit;
}

class alert_main{     
    var $build_data=NULL;
    function alert_main(){
        $this->build_data='<div id="alert_menu"><div id="alert">
        <div id="al_contents"><span>Czy napewno chcesz wykonać tą operację?</span></div><div id="al_choose"><div id="al_yes"><span>Tak</span></div><div id="al_no"><span>Nie</span></div></div></div>
        </div>
        <div id="alert_menu_inf"><div id="alert_inf">
        <div id="al_contents_inf"><span>Operacja została wykonana</span></div><div id="al_choose_inf"><div id="al_ok_inf"><span>Ok</span></div></div></div>
        </div>';
        echo $this->build_data;
    }
}
?>
