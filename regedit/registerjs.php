<?php
    define('IN_SZYPI', true);
    include 'mysql.php';
    include 'config.php';
    include 'register.php';
    $register=new register();
        $error_var_handler = array(
            0 => NULL,
            1 => "error:Login zawiera zbyt dużo znaków",
            2 => "error:Błędny login,login musi się składać z 4 pierwszych liter i nie może zawierać znaków prócz liter i liczb",
            3 => "error:Login o tej nazwie już istnieje w bazie danych",
            4 => "error:Błąd dostępu do bazy danych",
            5 => "error:Zbyt długie hasło",
            6 => "error:Hasło musi się składać z minimum 6 znaków i nie posiadać znaków specjalnych",
            7 => "error:Brak wybranej płci",
            8 => "error:Brak zaakaceptowanego regulaminu,zaakceptuj regulamin",
            9 => "error:Błędny email",
            10 => "error:Adres email już istnieje w bazie danych",
            11 => "error:Hasła nie są identyczne",
            12 => "error:Zbyt długa nazwa imienia",
            13 => "error:Nazwa imienia musi się składać z minimum 4 znaków i nie posiadać znaków specjalnych oraz liczb",
            14 => "error:Zbyt długa nazwa nazwiska",
            15 => "error:Nazwa nazwiska musi się składać z minimum 3 znaków i nie posiadać znaków specjalnych oraz liczb",
            16 => "error:ciąg znaków nie może zawierać białych znaków",
            17 => "error:nie wybrano płci"
        );
    $mysql = new mysql();
     if($_SERVER['REQUEST_METHOD']=='GET'){
         if($_GET["filter"]=='compare_password'){
             $arguments = array(mysql_real_escape_string(urldecode($_GET["password"])),mysql_real_escape_string(urldecode($_GET[$_GET["filter"]]))); 
         }else{
             $arguments =array(mysql_real_escape_string(urldecode($_GET[$_GET["filter"]])));
         }
         
         call_user_func_array(array($register,'filter_validate_'.urldecode($_GET["filter"])),$arguments);
         if($register->error_register!=0){
         echo $error_var_handler[$register->error_register];
         }else{
            if($_GET["filter"]=='login'){
                echo "Login jest poprawny.";
            }elseif($_GET["filter"]=='password'){
                echo "Hasło jest poprawne";
            }elseif($_GET["filter"]=='email'){
                echo "Email jest poprawny.";
            }elseif($_GET["filter"]=='compare_password'){
                echo "Ponownie wpisane hasło jest identyczne.";
            }
            elseif($_GET["filter"]=='name'){
                echo "Wpisane nazwa imienia jest poprawna.";
            }
            elseif($_GET["filter"]=='surname'){
                echo "Wpisana nazwa nazwiska jest poprawna.";
            }
            
         }
     }

?>