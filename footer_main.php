<?php
//blokada pliku dla innych serwisach
//klasa zarządza górną częscią projektu top
if (!defined('IN_SZYPI'))
{
	exit;
}

class footer_main{     
    function footer_main(){
        global $session;
        echo '   <div id="footer_menu"><div id="copyright"><a href="https://www.facebook.com/mariusz.szypula"><span>&copy; Mariusz Szypuła</span></a></div></div>';
    }
}
?>