<?php 

class mysql{
    
    function mysql(){
        $this->mysql_connect();
    }
    
    function mysql_connect(){
        global $dbhost,$dbpasswd,$dbname,$dbuser;
        mysql_connect('localhost', $dbuser, $dbpasswd) or die("B��d w po�aczeniu z serwerem");
        mysql_select_db($dbname) or die("Blad przy wybieraniu bazy");
        unset($$dbpasswd);
        mysql_query('SET NAMES utf8');
    }
    
}

    
?> 