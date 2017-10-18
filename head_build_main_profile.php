<?php
//klasa buduje główną stronę pofilu
class head_build_main_profile{
    var $avatar_image;
    var $content_image;
    
    function head_build_main_profile($id_login=null,$id_profile=null){
        $this->avatar_image=$this->validate_file($id_profile);  
        $this->content_image=$this->validate_file($id_login); 
        $this->create_data();  
    }
    
    function create_data(){
    echo '<head>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE10" />  
        <LINK rel="SHORTCUT ICON" href="ikona.ico">
        <meta http-equiv="content-type" content="application/x-www-form-urlencoded ; charset=utf-8">
         <script type="text/javascript" src="jquery-1.7.1.js"></script>
         <script type="text/javascript" src="global_function.js"></script>
         <script type="text/javascript" src="jquery.form.js"></script>
        <script type="text/javascript" src="jquery.corner.js"></script>
        <script type="text/javascript" src="top_menu.js"></script>
        <script type="text/javascript" src="main_profile.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="bodytp.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="profile_build_center.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="menu_left_build.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="top_profile.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="global_vars.css" />    
        <link rel="stylesheet" type="text/css" media="screen" href="footer_main.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="main_profile.css" />';
            echo '<style type="text/css">
            #avatar {
            position:relative;
            background-image: url("gallery/'.$this->avatar_image.'");
            width:110px;
            height:130px;
            margin-left:5px;
            margin-top:5px;
            border: 1px solid silver; 
            background-size:cover;
            filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(
            src="gallery/'.$this->avatar_image.'",
            sizingMethod="scale");
            }
            </style>';
 
        echo '</head>';
    }
    
    function validate_file($id=null){
        if(!(empty($id))){
        $test = file_exists('gallery/'.$id.'.jpg'); //sprawdzenie czy plik istnieje avataru       
        $var=$id.'.jpg'; 
            if(!$test){
                return $var='null.jpg';
            }
        }else{
            return $var='null.jpg';
        }
        return $var;
    }

}
?>
