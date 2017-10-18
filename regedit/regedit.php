<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

    <head>
        <LINK rel="SHORTCUT ICON" href="ikona.ico">
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="jquery-1.7.1.js"></script>
        <script type="text/javascript" src="regedit.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
    </head>
    <body> 

        <?php
        define('IN_SZYPI', true);
        include 'mysql.php';
        include 'config.php';
        include 'register.php';
        $register=new register();
        $error_var_handler = array(
            0 => NULL,
            1 => "Login zawiera zbyt dużo znaków",
            2 => "Błędny login,login musi się składać z 4 pierwszych liter i nie może zawierać znaków prócz liter i liczb",
            3 => "Login o tej nazwie już istnieje w bazie danych",
            4 => "Błąd dostępu do bazy danych",
            5 => "Zbyt długie hasło",
            6 => "Hasło musi się składać z minimum 6 znaków i nie posiadać znaków specjalnych",
            7 => "Brak wybranej płci",
            8 => "Brak zaakaceptowanego regulaminu,zaakceptuj regulamin",
            9 => "Błędny email",
            10 => "Adres email już istnieje w bazie danych",
            11 => "Hasła nie są identyczne",
            12 => "Zbyt długa nazwa imienia",
            13 => "Nazwa imienia musi się składać z minimum 4 znaków i nie posiadać znaków specjalnych oraz liczb",
            14 => "Zbyt długa nazwa nazwiska",
            15 => "Nazwa nazwiska musi się składać z minimum 3 znaków i nie posiadać znaków specjalnych oraz liczb",
            16 => "ciąg znaków nie może zawierać białych znaków",
            17 => "nie wybrano płci"
        );
        $mysql = new mysql();
        $register_successful=$register->create_data_request();
        if($register_successful){
        echo "Rejestracja została zakończona pomyślnie,proszę nacisnąć na <a href='../index.php'>link</a>
        aby się zalogować";   
        }else{
        if(!empty($register->tab_errors)){
            foreach ($register->tab_errors as $value) {
            echo $error_var_handler[$value].'<br>';
            }
        }
        //echo $error_var_handler[$register->error_register];
        echo '<div id="panel"><div id="panelrejestracji">
        <form method="POST" id="form_regedit">
        <label for="username">Nazwa użytkownika:</label> 
        <div class="login"><input type="text" id="username" name="login" value="'.((empty($_POST["login"]))?"":$_POST["login"]).'">
        <p></p></div>
        <label for="password">Hasło:</label> 
        <div class="password">
        <input type="password" id="password" name="password" value="'.((empty($_POST["password"]))?"":$_POST["password"]).'"><span></span></div>
        <label for="password">Wpisz ponownie hasło:</label> 
        <div class="passwordctrl">
        <input type="password" id="passwordctrl" name="passwordctrl" value="'.((empty($_POST["passwordctrl"]))?"":$_POST["passwordctrl"]).'"><br><span></span><span></span></div>
        <div class="name">
        <label for="name">Imię:</label>
        <input type="text" id="name" name="name" value="'.((empty($_POST["name"]))?"":$_POST["name"]).'"><p></p>
        </div>
        <div class="surname">
        <label for="surname">Nazwisko:</label>
        <input type="text" id="surname" name="surname" value="'.((empty($_POST["surname"]))?"":$_POST["surname"]).'"><p></p>
        </div>
        <div class="email">
        <label for="email">Adress email:</label>
        <input type="text" id="email" name="email" value="'.((empty($_POST["email"]))?"":$_POST["email"]).'"><p></p>
        </div>
        <div id="panelozn"><div id="sex">
        <label for="radio">płeć</label> 
        <input type="radio" id="sex" name="sex" value="2">Mężczyzna 
        <input type="radio" id="sex" name="sex" value="1">Kobieta
        <label for="birth">Data urodzenia</label></div>';

        echo '<select name="day">';
        for ($i = 1; $i <= 31; $i++) {
            $day=($i<10)? '0'.$i:$i;
            echo '<option value="'.$day.'">' . $day . '</option>';
        }
        echo '</select>';

        echo'<select name="month">
           <option value="01">Styczeń</option>
           <option value="02">Luty</option>
           <option value="03">Marzec</option>
           <option value="04">Kwiecień</option>
           <option value="05">Maj</option>
           <option value="06">Czerwiec</option>
           <option value="07">Lipiec</option>
           <option value="08">Sierpień</option>
           <option value="09">Wrzesień</option>
           <option value="10">Październik</option>
           <option value="11">Listopad</option>
           <option value="12">Grudzień</option>
           </select>';
        echo '<select name="year">'; 
        for ($i = 1945; $i <= 2003; $i++) {
            echo '<option value="'.$i.'">' . $i . '</option>';
        }
        echo '</select><br><br>';
        echo '<br><input type="checkbox" name="acceptance" id="acceptance" checked=off>Oświadczam, że chcę aby moje dany były publikowane na tym portalu i ponoszę całkowitą odpowiedzianość za pliki(fotografie) uploadowane na tym portalu.Fotografie uploadowane na tym portalu powinni zawierać prawa autorskie.';
        echo  '<br><input type="button" value="Zarejestruj" name="but_regedit" id="but_regedit"></div>';
        echo ' </form></div>';
        }
        ?>
    </body>
</html>