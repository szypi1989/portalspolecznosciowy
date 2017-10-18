<?php  
include_once('email.php');
class authorization extends mail{
    var $message = array(
        0 => 'Dziękuje za zarejestrowanie się na stronie photoblogger.com.Oto link autoryzacyjny:<a href="localhost/loges/index.php?authorization=',
        1 => '">link autoryzacyjny</a>',
    );
    var $message_build;
    var $mail;
    function authorization($smtp_host,$smtp_port,$smtp_user,$smtp_password,$ssl=false){
    $this::$_smtp_host=$smtp_host;
    $this::$_smtp_port=$smtp_port;
    $this::$_username=$smtp_user;
    $this::$_password=$smtp_password;
    $this::$_smtp_ssl=$ssl;
    }
}
////$mail=new mail();
//$mail::$_username='photoblogserwer';
//$mail::$_password='Photoblog86060';
//$mail::send('wiadomoscsssssssss','headers','rafal.szypula@vp.pl');


?>