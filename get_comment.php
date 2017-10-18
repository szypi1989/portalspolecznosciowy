<?php 
//szypi creates
define('IN_SZYPI', true);
include 'mysql.php';
include 'config.php'; 
$mysql = new mysql();
$building_errors=NULL;
if($_SERVER['REQUEST_METHOD']=='GET'){
switch ($_GET["field"]){
case "get_comments_html":
    $sql="SELECT id FROM gallery WHERE user_id=".urldecode($_GET['id_profile']);
    $gallery_sql = @mysql_query($sql);
        if(!$gallery_sql){
        echo "error1:błąd w bazie danych";
        exit;
        }
    $sql="SELECT cm.*,us.surname as surname,us.name as name FROM comments cm
    JOIN gallery gl ON gl.id=cm.id_photo
    JOIN users_info us ON us.user_id=cm.sender_id
    WHERE cm.id_photo=".mysql_result($gallery_sql,urldecode($_GET['id_photo']),0)." AND gl.user_id=".urldecode($_GET["id_profile"])." AND cm.status=0 ORDER BY cm.time DESC";    
    $comment_sql = @mysql_query($sql);
        if(!$comment_sql){
        echo "error2:błąd w bazie danych";
        exit;
        }
    $num_rows = @mysql_num_rows($comment_sql);
    $building_comment=NULL;
        for($i=0;$i<$num_rows;$i++){ 
        $result = @mysql_fetch_assoc($comment_sql);    
        //budowanie linku dla wybranego usera
        $sqls ='SELECT user_id FROM users_info WHERE name="'.mysql_real_escape_string($result["name"]).'" AND surname="'.mysql_real_escape_string($result["surname"]).'"';
        $gosqls = @mysql_query($sqls);
        $seek=NULL;

            for($num_profile=0;$seek==NULL;$num_profile++)
            {
                if((mysql_result($gosqls,$num_profile,0))==$result["sender_id"]){ 
                break;
                }                 
            }
        $building_href='main_profile.php?surname='.$result['name'].'.'.$result['surname'].'.'.$num_profile;
        //exut budowanie linku
        $building_comment.='<div class="comment'.(((mysql_result($gosqls,$num_profile,0))==urldecode($_GET['id_profile']))?" personal":NULL).'"><div class="cmt_content"><div class="cmt_info"><div class="cmt_cont_img"><img src="gallery/'.validate_img_avatar($result['sender_id']).'.jpg"  height="30" width="30"></div><div class="cmt_surname"><a href="'.$building_href.'"><span>'.strtoupper($result["name"][0]).substr($result["name"],1,(strlen($result["name"]))).' '.strtoupper($result["surname"][0]).substr($result["surname"],1,(strlen($result["surname"]))).'</span></a></div><div class="cmt_time">'.$result['time'].'</div></div></div><div class="cmt_cont_context"><div class="cont_context">'
        .$result["contents"].'</div>'.((urldecode($_GET['id_user'])==urldecode($_GET['id_profile']))?'<div class="but_delete_comment"><span class="delete_cmt">[usuń]</span></div>':NULL).'</div></div></div></div>';      
        }
    //get_comments_html&
    echo (empty($building_comment))?"empty":$building_comment;
    break;  
    case "delete_comment":
    $sql="SELECT id FROM gallery WHERE user_id=".urldecode($_GET['id_user']);
    $gallery_sql = @mysql_query($sql);
    if(!$gallery_sql){
    echo "error3:błąd w bazie danych";
    exit;
    }    
    $sql="SELECT cm.* FROM comments cm
    JOIN gallery gl ON gl.id=cm.id_photo
    WHERE cm.id_photo=".mysql_result($gallery_sql,urldecode($_GET['id_photo']),0)." AND gl.user_id=".$_GET["id_user"]." AND cm.status=0 ORDER BY cm.time DESC";
    $sql_list_cm = @mysql_query($sql);  
    if(!$sql_list_cm){
    echo "error4:błąd w bazie danych";
    exit;
    } 
    $result = @mysql_fetch_assoc($sql_list_cm);
    $sql="DELETE FROM comments WHERE id=".mysql_result($sql_list_cm,urldecode($_GET['id_choose_photo']),0);
    $gosql = @mysql_query($sql);
    if(!$gosql){
    echo "error5:błąd w bazie danych";
    exit;
    }   
    break;
    case "get_info_photo":
    //UŻYCIE TYPU DANYCH JSON
    $sql="SELECT description,time FROM gallery WHERE user_id=".urldecode($_GET['id_user']);
    $gallery_sql = @mysql_query($sql); 
    $result['description']=mysql_result($gallery_sql,urldecode($_GET['id_photo']),0);
    $result['time']=mysql_result($gallery_sql,urldecode($_GET['id_photo']),1);
    echo json_encode($result);
    break;    
    case "append_comment":       
    $sql="SELECT id FROM gallery WHERE user_id=".urldecode($_GET['id_profile']);
    $gallery_sql = @mysql_query($sql);
    if(!$gallery_sql){
    $result['error']=2;
    exit;
    }      	 	 	
    $sql="INSERT INTO comments(id_photo,contents,sender_id,status) VALUES(".mysql_result($gallery_sql,urldecode($_GET['id_photo']),0).",'".urldecode($_GET['context'])."',".urldecode($_GET['id_user']).",0)";
    $gosql = @mysql_query($sql);
    if(!$gosql){
    $result['error']=1;
    echo json_encode($result);
    exit;
    }    
    //$sql="SELECT time FROM comments WHERE id_photo=".mysql_result($gallery_sql,urldecode($_GET['id_photo']),0)." AND sender_id=".urldecode($_GET['id_user'])." ORDER BY time DESC";
    $sql="SELECT us.surname as surname,us.name as name,cm.time as time FROM comments cm "
    . "JOIN users_info us ON us.user_id=cm.sender_id"
    . " WHERE id_photo=".mysql_result($gallery_sql,urldecode($_GET['id_photo']),0)." AND sender_id=".urldecode($_GET['id_user'])." ORDER BY time DESC";
    $sql_info_cm = @mysql_query($sql);  
    if(!$sql_info_cm){
    $result['error']=3;
    exit;
    } 
    $result = @mysql_fetch_assoc($sql_info_cm);
    echo json_encode($result);
    break;  
    }
   
}
/*function is_validate_contents($data){ 
$return_arrays=array();
$error_contents=array();
$filter_contents=NULL;
    if(strlen($data)>240){
    $error_contents[]=1; 
    $error_contents[]=2;
    } 
///FILTERING DATE
$filter_contents=$data;
///
$return_arrays['errors']=$error_contents;
$return_arrays['contents']=$filter_contents;
return $return_arrays;  
}
*/
function err_val(){
global $building_errors;
$building_errors="error:";
}
function validate_img_avatar($id){
        if(!(empty($id))){
        $test = file_exists('gallery/'.$id.'.jpg'); //sprawdzenie czy plik istnieje avataru       
        $var=$id; 
            if(!$test){
                return $var='null';
            }
        }else{
            return $var='null';
        }
        return $var;
} 
?>
