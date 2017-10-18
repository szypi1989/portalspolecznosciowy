<?php
//blokada pliku dla innych serwisach
//klasa zarządza górną częscią projektu top
if (!defined('IN_SZYPI'))
{
	exit;
}

class top_main{     
    function top_main($id_login){
        global $session;
        echo '   <div id="top_menu">
         <div id="logo"><div id="childlogo"><span class="logo">Profile book</span></div></div>
         <div id="search"><form id="search_profile" action="search_profile.php" method="GET"><input type="text" id="searchin" name="searchin" value=""></input><input type="submit" id="search_button" value="szukaj"></input></form></div>
         <a href="'.$session->redirection_page.'?session_id='.$session->session_id.'"><div id="configure_link"><div id="logout"><span class="logout">Wyloguj się</span></div></a></div>
        </div>
';
    }
}
?>
