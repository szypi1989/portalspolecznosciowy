<?php  

class mail{
    static $_intialized=false;
    static $_smtpTransport;
    static $_swiftMailer;
    static $_username;
    static $_password;
    static $_smtp_host;
    static $_smtp_port;
    static $_smtp_ssl=false;
    public static function initialize($smtp_host,$smtp_port,$smtp_user,$smtp_password,$ssl=false)
    {
    require 'http://www.szypi.pl/projekt/regedit/'.'/swift/lib/swift_required.php';
    require 'http://www.szypi.pl/projekt/regedit/'.'/swift/lib/dependency_maps/cache_deps.php';
    require 'http://www.szypi.pl/projekt/regedit/'.'/swift/lib/dependency_maps/mime_deps.php';
    require 'http://www.szypi.pl/projekt/regedit/'.'/swift/lib/dependency_maps/message_deps.php';
    require 'http://www.szypi.pl/projekt/regedit/'.'/swift/lib/dependency_maps/transport_deps.php';
    self::$_smtp_host=$smtp_host;
    self::$_smtp_port=$smtp_port;
    self::$_username=$smtp_user;
    self::$_password=$smtp_password;
    self::$_smtp_ssl=$ssl;
    self::$_smtpTransport = Swift_SmtpTransport::newInstance(self::$_smtp_host);
    self::$_smtpTransport->setPort(self::$_smtp_port);
    self::$_smtpTransport->setUsername(self::$_username);
    self::$_smtpTransport->setPassword(self::$_password);
    if(self::$_smtp_ssl){
    self::$_smtpTransport->setEncryption( 'ssl' );    
    }
    self::$_swiftMailer = Swift_Mailer::newInstance( self::$_smtpTransport );     
    self::$_intialized = true;
    }
    
    public static function send( $subject, $msgBody, $receiver, $html = true )
    {
    if( !self::$_intialized )
    {
    self::initialize();
    }
     
    $message = Swift_Message::newInstance( $subject );
    $message->setFrom(self::$_username);
    $message->setTo($receiver);
    $message->setBody($msgBody);
    $message->setReplyTo(self::$_username,'d' ); 
    return self::$_swiftMailer->send( $message );
    }
}

?>