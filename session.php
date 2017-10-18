<?php
//author Mariusz Szypuła
//rok produkcji 2010
//wszystkie prawa zastrzeżone
//edycja,kopiowanie klasy jest zastrzeżona
//klasa sesji,tworzy sesje dla danego użytkownika
//--> zabezpieczenia przeciw botom,ilość prób logowań(danego ip,ciastka) do danego loginu,oraz ilość logowań(danego ip,ciastka) do różnych logowań
//--> zabezpieczenia przez ciastka,jeśli są aktywne,jeśli numer ip jest zmienny,i użytkownik zaloguje się z tego samego komputera ale z innego..
//numer ip ,komputer i tak będzie zapisywał jego logowanie poprzez ciastka(w celu zabezpieczenia poprzez boty)
//--> raportowanie błędów głośnych(błędy nie do przyjęcia) i cichych(błędy do przyjęcia ,diagnozowanie błędów)
//--> sam obiekt zwraca id numer usera sesji poprzez __toString
//
//derektywa zabezpiecza plik,przed niepożądanym dostępie do kodu sesji
//usuwa sesję jeśli dopuszczalny czas zostanie przekroczony(zabezpieczenie w przypadku zapomnienia wylogowania)
if (!defined('IN_SZYPI'))
{
	exit;
}
class session{
    var $session_user='';
    var $ip = '';
    var $browser='';
    var $user;
    var $password;
    var $session_id;
    var $cookie_expire=3600;
    var $cookie_service=false;
    var $block_length_time=3600;
    var $time_limit_uselessness=1800;
    var $cookies_on=false;
    var $error_login=0;
    var $error_blind=array();
    var $redirection_vars=array();
    var $error_blind_array=array(0 => "The contents of the field <b>limit_amount_login</b> is not numeric type",
    1 => "file does not exist for field <b>redirection_page</b>",
    2 => "The contents of the field <b>cookie_service</b> is not boolean type",
    3 => "The contents of the field <b>limit_amount_all_login</b> is not numeric type",
    4 => "The contents of the field <b>cookie_expire</b> is not numeric type",
    5 => "Content <b>cookie_expire</b> is negative",
    6 => "The contents of the field <b>block_length_time</b> is not numeric type",
    7 => "Content <b>block_length_time</b> is negative");
    var $anonymous=false;
    var $cookie_id;
    var $first_time;
    var $last_time;
    var $amount_login=0;
    var $authorization;
    var $redirection_page;
    var $limit_amount_login=5;
    var $limit_amount_all_login=4;
    var $cookie_anonymous_name="anonymous";
    var $cookie_session;
    var $table_amount_login='ip_login';
    var $functions_arr_clean=array();
    var $arguments_arr_clean=array();
    //argumentem konstruktora jest tablica z danymi konfiguracyjnymi np:
    //----------------------------------------------------------------
    // $config_session_handel = array(
    //        "redirection_page" => $pathInfo['filename'].'.php',
    //        "limit_amount_login" => "6",
    //        "cookie_service" => true
    //   );
    //$session = new session($config_session_handel);
    //konstruktor sprawdza poprawność informacji konfiguracyjnych,wrzucanie cichych błędów do tablicy error_blind
    
    function session(array $array_config=NULL){   
    $this -> ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
    $this -> browser =mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']);
    //konfigurowanie danych
        if(!(empty($array_config))){
            foreach ($array_config as $key=>$value) {
                if(isset($key)){
                    $this->$key=$value;    
                }      
            }    
        }
        //sprawdzanie poprawności danych   
        //wrzucanie do tablicy error_blind błędów pól
        if(!(is_numeric($this->limit_amount_login))){
        $this->limit_amount_login=5;
        $this->error_blind[]=0;
        }else{
        $this->limit_amount_login=(int)$this->limit_amount_login;    
        }
        $this->error_blind[]=(file_exists($this->redirection_page))?NULL:1;
        $this->error_blind[]=(is_bool($this->cookie_service))?NULL:2;
        if(!(is_numeric($this->limit_amount_all_login))){
        $this->limit_amount_all_login=10;
        $this->error_blind[]=3;
        }else{
        $this->limit_amount_all_login=(int)$this->limit_amount_all_login;    
        }
        if(!(is_numeric($this->cookie_expire))){
        $this->cookie_expire=3600;
        $this->error_blind[]=4;
        }else{
            if($this->cookie_expire<0){
            $this->cookie_expire=3600;    
            $this->error_blind[]=5;
            }         
        }
        
        if(!(is_numeric($this->block_length_time))){
        $this->block_length_time=3600;
        $this->error_blind[]=6;
        }else{
            if($this->block_length_time<0){
            $this->block_length_time=3600;    
            $this->error_blind[]=7;
            }         
        }
        if(isset($_GET['session_id'])){  
        $sql='DELETE FROM session_action_data WHERE session_id="'.$_GET['session_id'].'"';
        $gosql = @mysql_query($sql);
        }
    }
    function session_start(){
        if(!empty($_GET['session_id'])){
        //usuwanie sesji użytkownika
        setcookie($this->cookie_anonymous_name,"",time()-3600);
        return $this->session_delete($_GET['session_id']);
        }
        if($_SERVER['REQUEST_METHOD']=='POST'){
            //filtrowanie danych pochodzących z tablicy POST
            if(empty($_POST['login']) || empty($_POST['password'])){
                $this->error_login=1;
                return false;
            }else{
            $_POST['login']=mysql_real_escape_string($_POST['login']);
            $_POST['password']=mysql_real_escape_string($_POST['password']);
            }
            //tworzenie ciastka dla zabezpieczenia przez botów
            if($this->cookie_service){
                if(empty($_COOKIE[$this->cookie_anonymous_name])){
                    $this->cookie_id=$this->ip.time();
                    setcookie($this->cookie_anonymous_name,$this->cookie_id, time() + $this->cookie_expire);
                }else{
                    $this->cookie_id=$_COOKIE[$this->cookie_anonymous_name];
                }    
            }       
            //zabezpieczenie przeciw botom,sprawdzanie ilości logowań ,poprzez ciastko i numer ip,jeśli uzna że bot istnieje da true
            $this->anonymous=$this->check_control_bot();         
            if($this->anonymous){
                return false;
            }
            return $this->session_create($_POST['login'],$_POST['password']);
        }else{       
       //sprawdzanie adresu ip i nazwy przeglądarki i wybór sesji poprzez sprawdzenie tych danych
       //$time_limit_uselessness=dopuszczalny czas bezczynności na serwisie 
       $last_time=time()-$this->time_limit_uselessness;
       $sql='SELECT session_user,session_id FROM sessions WHERE session_ip="'.$this->ip.'" AND session_browser="'.$this->browser.'" AND session_time>'.$last_time;
       $gosql = @mysql_query($sql);
       $row = @mysql_fetch_assoc($gosql);
       @mysql_free_result($gosql);
       if(!$gosql){
        $this->error_login=4;
        return false;
       }
       //sprawdzanie czy użytkownik nie jest anonimowy
       if(empty($row["session_user"])){
           return false;
       }
       $time=time();   
       $this->session_user=$row["session_user"];
       $this->session_id=$this->regenerate_id();
       $sql="UPDATE sessions SET session_time=".$time.",session_id='".$this->session_id."' WHERE session_ip='".$this->ip."' AND session_browser='".$this->browser."'";
       $gosql = mysql_query($sql); 
       if(!$gosql){
       return false;    
       }
       $sql="UPDATE hobby SET name='1' WHERE user_id=3";
       $gosql = mysql_query($sql);     
       //sprawdzanie czy istnieje obsługa ciastek
       $this->cookies_on=$this->cookie_service();
       //Dodatkowa wadliwacja użytkownika,sprawdzanie czy id sesji jest poprawny,jeśli nie jest poprawny usuwanie sesji    
        if($this->cookies_on){
            if(($row["session_id"]!=$_COOKIE["sid"]) || empty($_COOKIE["sid"])){
                return $this->session_delete($row["session_id"]);
            }
        }
       $sql='SELECT user_login FROM users WHERE user_id='.$row["session_user"];
       $gosql = @mysql_query($sql);
       $row = @mysql_fetch_assoc($gosql);
       @mysql_free_result($gosql);
      
       $sql='SELECT session_id FROM sessions WHERE session_user='.$this->session_user.' AND session_browser="'.$this->browser.'"';
       $gosql = @mysql_query($sql);
       $row = @mysql_fetch_assoc($gosql);
       @mysql_free_result($gosql);
       if(!$gosql){
            $this->error_login=4;
            return false;
        }
       // $this->session_id=$row["session_id"];
       if($this->cookie_service){
       setcookie('sid',$this->session_id, time() + $this->cookie_expire);    
       }   
       return true;
       }    
    }
    //funkcja tworzy sesję,sprawdza poprawność danych z tablicy POST
    function session_create($user,$password){
        $sql='SELECT user_id FROM users WHERE user_login="'.$user.'" AND user_password="'.md5($password).'"';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        if(!$gosql){
            $this->error_login=4;
            return false;
        }
        $this->session_id=$this->regenerate_id();
        if(empty($row["user_id"])){
            $this->error_login=2;
            $this->create_amount_login();
            return false;
        }else{
        //usuwanie ilość prób logowań dla wybranego loginu,ponieważ hasło zostało rozpoznane
        $this->delete_amount_login();    
        mysql_free_result($gosql);
            $this->session_user=$row["user_id"];
            $sql='INSERT INTO sessions(session_id,session_user,session_ip,session_browser,session_time) 
            VALUES("'.$this->session_id.'",'.$row["user_id"].',"'.$this -> ip.'","'.$this->browser.'",'.time().') 
            ON DUPLICATE KEY UPDATE session_id="'.$this->session_id.'",session_ip="'.$this -> ip.'",session_browser=
            "'.$this->browser.'",session_time='.time();
            $gosql =@mysql_query($sql);
            if(!$gosql){
                $this->error_login=4;
                return false;
            }
            //tworzenie ciasteczka id sesji
            if($this->cookie_service){
            setcookie('sid',$this->session_id, time() + $this->cookie_expire);
            setcookie($this->cookie_anonymous_name,"",time()-3600);
            }
            //przekierowywanie do strony głównej
            $this->redirection();
            return true;
        }
    }
    
    function session_delete($session_id){
        //wadliwacja użytkownika przed usunięciem sesji
        $session_id=mysql_real_escape_string($session_id);
        //sprawdzanie adresu ip i nazwy przeglądarki i wybór sesji poprzez sprawdzenie tych danych
        $sql='SELECT session_user,session_id FROM sessions WHERE session_ip="'.$this->ip.'" AND session_browser="'.$this->browser.'"';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_login=4;
            return false;
        }
        //sprawdzanie czy użytkownik nie jest anonimowy
        if(!(empty($row["session_user"]))){
            //usuwanie sesji jeśli użytkownik jest zgodny z numerem ip
            $sql='DELETE FROM sessions WHERE session_ip="'.$this->ip.'" AND session_browser="'.$this->browser.'"';
            $gosql = @mysql_query($sql);
            if(!$gosql){
                $this->error_login=4;
                return false;
            }
            //wykonywanie czynności przed wylogowywaniem
            $this->clean_action_logout();
            //przekierowywanie do strony głównej
            $this->redirection();
            //$this->redirection("index.php?asdfasdf=".$this->redirection_vars["message_control"]);
            return false;
        }else{
            return false;
        }
      
    }
    function regenerate_id(){
        $time=time();
        return $this->ip.$time.";".$this->session_user;
    }
    
    //funkcja kontroluje logowania
    //sprawdza czy loguje się użytkownik a nie bot
    //gdy użytkownik po x(limit_amount_login) razie nie może się zalogować,blokuje dostęp do dalszych działań na y(block_length_time) czas dla xusera
    //podczas ciągłęgo logowania dla różnych userów ,blokuje dostęp na określony czas,dodaje numer ip do czarnej listy
    function check_control_bot(){
        $this->last_time=time();
        //sprawdzanie czarnej listy przeciw botom
        $sql='SELECT login_ip FROM black_list_ip WHERE login_ip="'.$this->ip.'" AND 
        (logins="" OR (logins LIKE "%,'.$_POST["login"].',%" OR logins LIKE "%'.$_POST["login"].'%" 
        OR logins LIKE "'.$_POST["login"].',%" OR logins LIKE "%,'.$_POST["login"].'")) 
        AND (DATEDIFF(time_end,DATE(NOW()))>=0 OR time_end IS NULL)';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosql);
        if(!empty($row["login_ip"])){
            $this->error_login=6;
            return true;
        }else{
        $sql='DELETE FROM black_list_ip WHERE login_ip="'.$this->ip.'" AND time_end < DATE(NOW())';
        $gosql = @mysql_query($sql);
        }        
        //zapytanie poniższe sprawa z ilu loginów user o danym adresie ip próbuje się zalogować,
        //jeśli więcej niż x(limit_amount_all_login) wtedy komputer uznaje jego za bota,zwracają true
        $cookie_sql=($this->cookie_service() && (!empty($_COOKIE[$this->cookie_anonymous_name])))?' 
        AND cookie="'.mysql_real_escape_string($_COOKIE[$this->cookie_anonymous_name]).'") OR ((SELECT MIN(first_time) 
        FROM ip_login WHERE login_ip="'.$this->ip.'")>'.($this->last_time-$this->block_length_time).' AND 
        cookie="'.mysql_real_escape_string($_COOKIE[$this->cookie_anonymous_name]).'"':NULL;
        $sql='SELECT COUNT(*) as ilosc FROM ip_login WHERE ((SELECT MIN(first_time) FROM ip_login WHERE 
        login_ip="'.$this->ip.'")>'.($this->last_time-$this->block_length_time).' AND login_ip="'.$this->ip.'"'.$cookie_sql.')';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_login=4;
            return true;
        }
        if(!empty($row["ilosc"])){
            if((int)$row["ilosc"]>$this->limit_amount_all_login){
                $this->error_login=5;
                return true;
            } 
        }
        //sprawdzanie czy istnieje obsługa ciastek,gdy istnieje to dopisywana jest wartość do bazy danych;
        //zapytanie stwierdza czy minął już czas od ostatniego logowania
        $cookie_sql=($this->cookie_service() && (!empty($_COOKIE[$this->cookie_anonymous_name])) ) ? ' 
        AND cookie="'.mysql_real_escape_string($_COOKIE[$this->cookie_anonymous_name]).'") OR (first_time
        > '.$this->last_time.' AND login="'.$_POST["login"].'" AND cookie="'.mysql_real_escape_string($_COOKIE[$this->cookie_anonymous_name]).'"' : NULL;
        
        $sql='SELECT last_time,first_time FROM '.$this->table_amount_login.' WHERE (first_time > '.($this->last_time-$this->block_length_time).' 
        AND login_ip="'.$this->ip.'" AND login="'.$_POST["login"].'"'.$cookie_sql.')';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        @mysql_free_result($gosql);
        if(!$gosql){
            $this->error_login=4;
            return true;
        }
        //komputer daje tutaj true jeśli powyższe zapytanie stwierdzi,że pierwszy czas(próby logowania z danego loginu i tego ip) nadal jest większy
        //od tego czasu ,minus x czas,czyli uruchamia się kontrola czy dany adres ip nie jest botem 
        if(!empty($row["last_time"])){
            if(!empty($row["first_time"])){
                $this->first_time=$row["first_time"];
            }else{
                $this->first_time=time();
            }
            //dodanie do zapytania ,sprawdzenie ciastek
            //dodawanie do zapytań zabezpieczeń poprzez ciastko,nawet jeśli użytkownik zmieni adres ip to i tak system sprawdzi bot poprzez ciastko
            $cookie_sql=($this->cookie_service() && (!empty($_COOKIE[$this->cookie_anonymous_name])) ) ? ') OR 
            (cookie="'.mysql_real_escape_string($_COOKIE[$this->cookie_anonymous_name]).'" AND login="'.$_POST["login"].'"':NULL;
            $sql='SELECT amount FROM '.$this->table_amount_login.' WHERE (login_ip="'.$this->ip.'" AND login="'.$_POST["login"].'"'.$cookie_sql.')';
            $gosql = @mysql_query($sql);
            $row = @mysql_fetch_assoc($gosql);
            @mysql_free_result($gosql);
            if(!$gosql){
            $this->error_login=4;
            return true;
            }
            if(empty($row["amount"])){
            return false;   
            }else{
            $this->amount_login=$row["amount"];    
            }
            //wybranie ilości prób logowania ,za pomocą ciastek,jeśli nie mineła 1 godzina od pierwszego logowania,
            //jeśli przekroczy 5 prób logowania,uruchomi się zabezpieczenie przeciw botom,komputer uzna że już istnieje bot
            if($row["amount"]>$this->limit_amount_login){
                $this->error_login=3;
                return true;
            }else{
                return false;
            }
        }else{
            //usuwanie danych z tabeli,i danie dostępu do logowania dla danego adresu ip
            $this->delete_amount_login();
           /// setcookie($this->cookie_anonymous_name,"",time()-3600);
            return false;
        }
    }
    
    function create_amount_login(){
        //sprawdzanie ilości logowań
        $sql='SELECT amount FROM '.$this->table_amount_login.' WHERE login_ip="'.$this->ip.'" AND login="'.$_POST["login"].'"';
        $gosql = @mysql_query($sql);
        $row = @mysql_fetch_assoc($gosql);
        if(!$gosql){
            $this->error_login=4;
            return false;
        }
        $this->amount_login=$row["amount"];
        //funkcja dodaje do tabeli dane ,jak ilość prób logowania przez u�ytkownika z danym numerem ip i ciastkiem
        $first_time=((!empty($this->first_time))) ? $this->first_time : time();
        $last_time=((!empty($this->last_time))) ? $this->last_time : time();
        $sql='INSERT INTO '.$this->table_amount_login.'(login_ip,login,cookie,first_time,last_time,amount) 
        VALUES("'.$this->ip.'","'.$_POST["login"].'","'.$this->cookie_id.'",'.$first_time.','.$last_time.','.++$this->amount_login.')
        ON DUPLICATE KEY UPDATE login_ip="'.$this->ip.'",login="'.$_POST["login"].'",cookie="'.$this->cookie_id.'",
        first_time='.$first_time.',last_time='.$last_time.',amount='.$this->amount_login;
        $gosql = @mysql_query($sql);
        @mysql_free_result($gosql);  
        if(!$gosql){
            $this->error_login=4;
            return false;
        }
    }
    //funkcja usuwa dane z tabeli jak ilość prób logowania z danego numer ip/loginu lub z danego ciastka
    //usuwanie danych z tabeli daje dostęp userowi o adresie ip x,do możliwości ponownego zalogowania się
    //sprawdzanie obsługi ciastek
    function delete_amount_login(){
        $cookie_sql=($this->cookie_service() && (!empty($_COOKIE[$this->cookie_anonymous_name])) ) ? ') OR 
        (cookie="'.mysql_real_escape_string($_COOKIE[$this->cookie_anonymous_name]).'" AND login="'.$_POST["login"].'"':NULL;
        $sql='DELETE FROM '.$this->table_amount_login.' WHERE (login_ip="'.$this->ip.'" AND login="'.$_POST["login"].'"'.$cookie_sql.')';
        $gosql = @mysql_query($sql);
        if(!$gosql){
            $this->error_login=4;
            return false;
        }  
        if($this->cookie_service){
        setcookie($this->cookie_anonymous_name,"",time()-3600);
        }
    }
    //funkcja zwraca aktualny numer sesji użytkownika,typu string
    function get_id(){
        return $this->session_id;
    }
    //zwracanie numer id użytkownika
    function get_session_user(){
        return (int)$this->session_user;
    }   
    function cookie_service(){
        //zwraca true,jeśli jest włączona obsługa ciastek
        if($this->cookie_service){
            setcookie('test','now', time() + 3600);
            if(!empty($_COOKIE["test"])){
                return true;
            }    
        }
        return false;
    }  
    function redirection($page='index.php'){
        $page=(empty($this->redirection_page))?$page:$this->redirection_page;
        Header("HTTP/1.1");
        Header("Location:".$page);
    }
    //zwraca numer id sesji
    function __toString()
    {
       return (int)$this->session_user;
    }
    //funkcja służy do wykonywania czynności przed wylogowaniem
    function clean_action_logout(){   
       foreach ($this -> functions_arr_clean as $class=>$function) {
            if(isset($class)){
            include $class.".php"; 
            $object_class = new $class(); 
            call_user_func_array(array($object_class,$function),(isset($this -> arguments_arr_clean[$function])?$this -> arguments_arr_clean[$function]:array()));   
            }     
        }           
    }
  
}
?>
