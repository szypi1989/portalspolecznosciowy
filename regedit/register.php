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
    var $validate_lenght_password=34;
    var $validate_lenght_name=32;
    var $validate_lenght_surname=36;
    var $password;
    var $login;
    var $message='Dziękuje za zarejestrowanie się na stronie photoblogger.com.Oto link autoryzacyjny:http://localhost/loges5/index.php?authorization=';
    var $code=0;
    var $tab_errors=array();
    var $url='http://localhost/loges5/index.php?authorization=';
    var $constraints;
    function register(){
    }
    
    function create_data_request(){
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if($this->filter_validate_data(urldecode($_POST["login"]),urldecode($_POST["password"]),urldecode($_POST["email"]),urldecode($_POST["passwordctrl"]),urldecode($_POST["name"]),urldecode($_POST["surname"]))){
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
        $this->code=urlencode ($code_authorization);
        //dodawanie danych takich jak data urodzenia,płeć i kod autoryzacyjny do bazy danych
        $sql='INSERT INTO users_info(sex,birth,mail,code_authorization,name,surname) VALUES ('.mysql_real_escape_string($_POST["sex"]).',"'.mysql_real_escape_string($_POST["year"]).'-'.mysql_real_escape_string($_POST["month"]).'-'.mysql_real_escape_string($_POST["day"]).'","'.urldecode($_POST["email"]).'","'.$code_authorization.'","'.urldecode($_POST["name"]).'","'.urldecode($_POST["surname"]).'")';
        $gosql =@mysql_query($sql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_register=4;
            return false;
        }
        //wysyłanie linku autoryzacyjnego 
       // $authorization=new mail();
      //  $authorization->initialize('smtp.poczta.onet.pl','587','photoblogserwer@onet.pl','photoblog86060');
       // $authorization->send('Link autoryzacyjny',$this->message.$code_authorization,$_POST["email"]);
       // $adresat = $_POST['email']; 	// pod ten adres zostanie wysłana 							// wiadomosc
	//@$email = $_POST['email'];
	//$content = $this->message.$code_authorization;
	/*$header = 	"From: szypula89@szypi.pl \nContent-Type:".
			' text/plain;charset="iso-8859-2"'.
			"\nContent-Transfer-Encoding: 8bit";
	if (mail($adresat, 'Link autoryzacyjny', $content, $header)){
            return true;
        }else{
            return false;
        }
        
       }*/
        return true;
        }
        
    return false;
    }   
    }
    
    //funkcja filtruje dane,zwraca true jeśli dane są poprawne
    function filter_validate_data(&$login,&$password,&$email,$passwordcmp,$name,$surname){
    //filtrowanie danych
    $login=mysql_real_escape_string(urldecode($login));
    $password=mysql_real_escape_string(urldecode($password));
    $passwordcmp=mysql_real_escape_string(urldecode($passwordcmp));
    $email=mysql_real_escape_string(urldecode($email));
    $name=mysql_real_escape_string(urldecode($name));
    $surname=mysql_real_escape_string(urldecode($surname));
    $return_value=true;
        if(!$this->filter_validate_login($login)){
           $return_value=false;
        };
        if(!$this->filter_validate_name($name)){
           $return_value=false;
        };
        if(!$this->filter_validate_surname($surname)){
           $return_value=false;
        };
        if(!$this->filter_validate_password($password)){
           $return_value=false;
        };
        if(!$this->filter_validate_password($passwordcmp)){
           $return_value=false;
        };
        if(!$this->filter_validate_compare_password($password,$passwordcmp)){
           $return_value=false;;
        };
        if(empty($_POST["sex"])){
           $this->error_register=17;
           $this->tab_errors[]=17; 
           $return_value=false;
            return false;
        };
        if(empty($_POST["acceptance"])){
            $this->error_register=8;
            $this->tab_errors[]=8;
            $return_value=false;
        };
        if(!$this->filter_validate_email($email)){
            $return_value=false;
        }        
        return $return_value;
    }
    //filtruje zawartość loginu 
    //sprawdzenie poprawności loginu,sprawdzanie czy istnieje taki sam już w bazie danych
    function filter_validate_login($login){
        $login=urldecode($login);
        if(strlen($login)>$this->validate_lenght_login){
            $this->error_register=1;
             $this->tab_errors[]=1;
            return false;
        }elseif(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż]{4,'.($this->validate_lenght_login).'}[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż0-9]*$/D',$login)){
            $this->error_register=2;
            $this->tab_errors[]=2;
            return false;
        }elseif($this->check_control_available_login($login)){
            if($this->error_register==4){
                return false;
            }
            $this->error_register=3;
            $this->tab_errors[]=3;
            return false;
        }     
        return true;
   
       }
       
   function check_control_available_login($login){
       //funkcja sprawdza czy istnieje login w bazie danych taki sam jak w tablicy POST,jeśli tak zwraca true
       $sql='SELECT user_login FROM users WHERE user_login="'.mysql_real_escape_string(urldecode($login)).'"';
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
        $password=urldecode($password); 
        if(strlen($password)>$this->validate_lenght_password){
            $this->error_register=5;
            $this->tab_errors[]=5;
            return false;
        }elseif(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż0-9]{6,'.($this->validate_lenght_password).'}$/D',$password)){
            $this->error_register=6;
            $this->tab_errors[]=6;
            return false;
        }
        return true;   
    }
    function filter_validate_name($name){
        $name=urldecode($name); 
        if(strlen($name)>$this->validate_lenght_name){
            $this->error_register=12;
            return false;
        }elseif(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż]{4,'.($this->validate_lenght_name).'}$/D',$name)){
            $this->error_register=13;
            $this->tab_errors[]=13;
            return false;
        }
        return true;   
    }
    
     function filter_validate_surname($surname){
        $surname=urldecode($surname); 
        if(strlen($surname)>$this->validate_lenght_surname){
            $this->error_register=14;
            $this->tab_errors[]=14;
            return false;
        }elseif(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż]{3,'.($this->validate_lenght_surname).'}$/D',$surname)){
            $this->error_register=15;
            $this->tab_errors[]=15;
            return false;
        }
        return true;   
    }
    
    function filter_validate_compare_password($password1,$password2){
        $password1=urldecode($password1); 
        $password2=urldecode($password2);
        if($password1==$password2){
            return true;    
        }
        $this->error_register=11;
        $this->tab_errors[]=11;
        return false;   
    }
       
    function filter_validate_email($email){
        $email=urldecode($email);
        if(!preg_match('/^[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż.]{1,5}[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż0-9]{1,30}+\@[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż0-9]{2,38}+\.[a-zA-ZĄąĆćĘęŁłńŃÓóŚśŹźŻż.]{2,20}+$/D',$email)){
            $this->error_register=9;
            $this->tab_errors[]=9;
            return false; 
        }elseif(!$this->check_control_available_email($email)){
            $this->error_register=10;
            $this->tab_errors[]=10;
            return false;      
        }
        return true;
    }
    
    function check_control_available_email($email){
        $sql='SELECT mail FROM users_info WHERE mail="'.mysql_real_escape_string(urldecode($email)).'"';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_register=4;
            return false;
        }
        if(!empty($row["mail"])){
            $this->error_register=10;
            $this->tab_errors[]=10;
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