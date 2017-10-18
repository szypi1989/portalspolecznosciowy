<?php
//klasa buduje główną stronę pofilu
class head_build_message_profile{
    var $avatar_image;
    var $content_image;
    
    function head_build_message_profile($id_login=null,$id_profile=null){
        $this->avatar_image=$this->validate_file($id_profile);  
        $this->content_image=$this->validate_file($id_login); 
        $this->create_data();  
    }
    
    function create_data(){
    echo '<head>
        <meta http-equiv="pragma" content="no-cache" />
        <LINK rel="SHORTCUT ICON" href="ikona.ico">
        <meta http-equiv="content-type" content="application/x-www-form-urlencoded ; charset=utf-8">
        <script type="text/javascript" src="jquery-1.7.1.js"></script>
        <script type="text/javascript" src="jquery.corner.js"></script>
        <script type="text/javascript" src="jquery.base64.js"></script>
        <script type="text/javascript" src="global_function.js"></script>
        <script type="text/javascript" src="top_menu.js"></script>
        <script type="text/javascript" src="message_profile.js"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="bodytp.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="profile_build_center.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="menu_left_build.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="top_profile.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="message_profile.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="footer_main.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="alert.css" />';
            echo '<style type="text/css">
            #avatar {
            float:left;
            position:relative;
            background-image: url("gallery/'.$this->avatar_image.'");
            width:130px;
            height:130px;
            margin:5px;
            border: 1px solid silver; 
            background-size:cover;  
            }
            img{
            background-image: url(gallery/ajax-loader.gif);
            background-size:cover;  
            }
           
            img.content_profile{
            float:left;
            position:relative;
            width:100%;
            height:100%;
            background-image: url(gallery/'.$this->content_image.');
            background-size:cover;  
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
