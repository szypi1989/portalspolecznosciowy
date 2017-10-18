<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

        <?php 
        define('IN_SZYPI', true);
        include 'mysql.php';
        include 'session.php';
        include 'config.php';
        include 'register.php';
        include 'post.php';
        include 'profile_build.php';
        include 'top_main.php';
        include 'head_build.php';
        $error_var_handler = array(
            0 => NULL,
            1 => "Brak wpisanych znaków",
            2 => "Błędny login lub hasło",
            3 => "Wykorzystano ilość prób z danego numer ip,następne logowanie dostępne dopiero za 1 godzinę",
            4 => "Błąd dostępu do bazy danych"
        );
        $pathInfo = pathinfo(__FILE__);
        $config_session_handel = array(
            "redirection_page" => $pathInfo['filename'].'.php',
            "limit_amount_login" => 6
        );       
        $head_build=new head_build();
        $mysql = new mysql();
        $session = new session($config_session_handel);
        $register = new register();
        $sessionmade = $session->session_start();
        if (!$sessionmade) {
                 if($_SERVER['REQUEST_METHOD']=='GET'){
                   // $_GET['authorization']=urlencode($_GET['authorization']); 
                    if(!empty($_GET['authorization'])){
                        //wyciąganie id,loginu,hasła użytkownika poprzez kod autoryzacyjny                       
                        $sql = 'SELECT users_info.user_id,users.user_login,users.user_password 
                        FROM users_info,users WHERE users_info.code_authorization ="'.mysql_real_escape_string($_GET['authorization']).'" AND users_info.user_id=users.user_id';
                        $gosqls = @mysql_query($sql);
                        $row = @mysql_fetch_assoc($gosqls);
                        @mysql_free_result($gosqls);
                        if(empty($row["id"])){
                            echo "Błędny kod autoryzacyjny dla wybranego loginu";
                            return;
                        }
                        //decodowanie kodu autoryzacyjnego
                        $data=explode("$", $_GET['authorization']);
                        $password=$register->uncode_authorization($data[1]);
                        if(!($row["user_password"]==md5($password))){
                            echo "Błędny kod autoryzacyjny dla wybranego loginu";
                            return false;
                        }
                        //wysyłanie danych z post data
                        $data = array ('login' => $row["user_login"], 'password' => $password,'authorization' => $_GET['authorization']);
                        $data = http_build_query($data);
                        $post=new post('localhost',80,'/loges/index.php',$data,'application/x-www-form-urlencoded');
                        echo ($post->get_data());
                        return;
                    }
                 }
            $head_build->create_data('head_build_login');
            echo '<body><div id="panel">
            <form method="POST">
                <label for="username">Nazwa użytkownika:</label> 
                <input type="text" id="username" name="login"> 
                <label for="password">Hasło:</label> 
                <input type="password" id="password" name="password"> 
                <div id="regedit_click"><p><a href="regedit/regedit.php">ZAREJESTRUJ SIĘ</a></p></div> 
                <div id="lower"> 
                    <input type="submit" value="Zaloguj"> 
                </div> 
            </form> 
            </div><br><br>Zaloguj się na konto testowe poprzez login:szypi1989, password:starer<br>
            prezentacja możliwości strony internetowej na youtube:https://www.youtube.com/watch?v=D7vw7MiBz0Y';
            echo $error_var_handler[$session->error_login];
        } else {
        if(!(empty($_POST['authorization']))){
         $sql ="UPDATE users_info SET active=1 WHERE user_id=".$session->get_session_user()." AND code_authorization='".$_POST['authorization']."'";
               $gosqls = @mysql_query($sql);
               @mysql_free_result($gosqls);
           }
             $sql ='SELECT name,surname FROM users_info WHERE user_id='.$session->get_session_user();
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
                    if((mysql_result($gosql,$i,0))==$session->get_session_user()){ 
                       break;
                    }                 
                    $c=$c+1;
                    }
                   Header("HTTP/1.1");
                   Header("Location:".'main_profile.php?surname='.$row['name'].'.'.$row['surname'].'.'.$i);    
        }
          echo'
    </body>
</html>';
        ?>
  