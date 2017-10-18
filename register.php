<?php
//blokada pliku dla innych serwisów
if (!defined('IN_SZYPI'))
{
	exit;
}
include_once('email.php');
class register{
    var $error_register=0;
    var $validate_lenght_login=28;
    var $validate_lenght_password=24;
    var $password;
    var $login;
    var $message='Dziękuje za zarejestrowanie się na stronie photoblogger.com.Oto link autoryzacyjny:http://localhost/loges/index.php?authorization=';
    
    function register(){
    
    }
    
    function create_data_request(){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if($this->filter_vadlidate_data($_POST["login"],$_POST["password"],$_POST["email"],$_POST["passwordctrl"])){
        //zapisywanie informacji do bazy danych
        //dodawanie nowego usera do bazy danych 
        $sql='INSERT INTO users(user_login,user_password,user_lastvisit) VALUES ("'.$_POST["login"].'","'.md5($_POST["password"]).'",'.time().')';
        $gosql =@mysql_query($sql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_register=4;
            return false;
        }
        //tworzenie kodu autoryzacyjnego z loginu i hasła
        $code_authorization=$this->create_code_authorization(md5($_POST["login"])).'$'.$this->create_code_authorization($_POST["password"]);
        //dodawanie danych takich jak data urodzenia,płeć i kod autoryzacyjny do bazy danych
        $sql='INSERT INTO users_info(sex,birth,mail,code_authorization) VALUES ('.mysql_real_escape_string($_POST["sex"]).','.mysql_real_escape_string($_POST["year"]).mysql_real_escape_string($_POST["month"]).mysql_real_escape_string($_POST["day"]).',"'.$_POST["email"].'","'.$code_authorization.'")';
        $gosql =@mysql_query($sql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_register=4;
            return false;
        }
        //wysyłanie linku autoryzacyjnego 
        $authorization=new mail();
        $authorization->initialize('smtp.poczta.onet.pl','587','photoblogserwer@onet.pl','photoblog86060');
        $authorization->send('Link autoryzacyjny',$this->message.$code_authorization,$_POST["email"]);
        return true;
        }
    return false;
    }   
    }
    
    //funkcja filtruje dane,zwraca true jeśli dane są poprawne
    function filter_vadlidate_data(&$login,&$password,&$email,$passwordcmp){
    //filtrowanie danych
    $login=mysql_real_escape_string($login);
    $password=mysql_real_escape_string($password);
    $email=mysql_real_escape_string($email);
        if(!$this->filter_validate_login($login)){
            return false;
        }elseif(!$this->filter_validate_password($password)){
            return false;
        }elseif(!$this->filter_validate_password($passwordcmp)){
            return false;
        }elseif(!$this->filter_validate_compare_password($password,$passwordcmp)){
            return false;
        }elseif(empty($_POST["sex"])){
            $this->error_register=7;
            return false;
        }elseif(empty($_POST["acceptance"])){
            $this->error_register=8;
            return false;
        }elseif(!$this->filter_validate_email($email)){
            return false;
        }        
        return true;
    }
    //filtruje zawartość loginu 
    //sprawdzenie poprawności loginu,sprawdzanie czy istnieje taki sam już w bazie danych
    function filter_validate_login($login){
        if(strlen($login)>$this->validate_lenght_login){
            $this->error_register=1;
            return false;
        }elseif(!preg_match('/^[a-zA-Z]{4,'.($this->validate_lenght_login).'}[a-zA-Z0-9]*$/D',$login)){
            $this->error_register=2;
            return false;
        }elseif($this->check_control_available_login($login)){
            if($this->error_register==4){
                return false;
            }
            $this->error_register=3;
            return false;
        }     
        return true;
   
       }
       
   function check_control_available_login($login){
       //funkcja sprawdza czy istnieje login w bazie danych taki sam jak w tablicy POST,jeśli tak zwraca true
       $sql='SELECT user_login FROM users WHERE user_login="'.$login.'"';
       $gosql = @mysql_query($sql);
       $row = @mysql_fetch_assoc($gosql);
       @mysql_free_result($gosql);
       if(!$gosql){
            $this->error_register=4;
            return false;
       }
       if(empty($row["user_login"])){
            return false;
       }
            return true;
   }
    //wadliwacja hasła,hasło nie może mieć miej znaków niż 6,i nie może zawierać znaków specjalnych
    function filter_validate_password($password){
        if(strlen($password)>$this->validate_lenght_password){
            $this->error_register=5;
            return false;
        }elseif(!preg_match('/^[a-zA-Z0-9]{6,'.($this->validate_lenght_password).'}$/D',$password)){
            $this->error_register=6;
            return false;
        }
        return true;   
    }
    
    function filter_validate_compare_password($password1,$password2){
        if($password1==$password2){
            return true;    
        }
        $this->error_register=11;
        return false;   
    }
       
    function filter_validate_email($email){
        if(!preg_match('/^[a-zA-Z0-9]+\@[a-zA-Z0-9.]+$/D',$email)){
            $this->error_register=9;
            return false; 
        }elseif(!$this->check_control_available_email($email)){
            return false;
        }
        return true;
    }
    
    function check_control_available_email($email){
        $sql='SELECT mail FROM users_info WHERE mail="'.$email.'"';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_register=4;
            return false;
        }
        if(!empty($row["mail"])){
            $this->error_register=10;
            return false;
        }
        return true;
    }
    //funkcja tworzy kod autoryzacyjny
    function create_code_authorization($data){         
        $even=false;
        $data_part1=NULL;
        $data_part2=NULL;
        
        for($i=0;$i<strlen($data);$i++){
            $even=!$even;
            if($even){
                $data_part1.=$data[$i];
            }else{
                $data_part2.=$data[$i];    
            }
        }
        $data_build=$data_part1.$data_part2;
        return $data_build;
    }
    //funkcja dekodująca kod autoryzacyjny
    //Dzieli ciąg na pół ,z wrzuca na przemian z part1 i part2 znaki
    function uncode_authorization($data){
        $password=$data;
        $even=false;
        $i1=0;
        $i2=0;
        $data_result=null;
        $a=strlen($password);
        //sprawdzanie parzystości ciągu,jeśli ciąg jest parzysty zwraca true
        $a=round($a/2);
        $c=strlen($password)-$a;
        $part1= substr($password,0,$a);
        $part2= substr($password,$a,$c);
        $even=false;
        for ($i = 0; $i < strlen($password); $i++) {
            $even=!$even;
                if($even){
                    $data_result.=$part1[$i1];
                    ++$i1;
                }else{
                    $data_result.=$part2[$i2];
                    ++$i2;    
                }
         }
            return $data_result;
    }
   
   
   
}