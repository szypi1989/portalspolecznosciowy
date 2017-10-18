<?php 
//plik wykorzystywany dla systemu sesji.Funkcja jest uruchamiana po zniszczeniu sesji.
class clean_action_logout{
    function reset_message_control($sender_id){
    $sql='SELECT id FROM message_control WHERE sender_id='.$sender_id;
    $gosql =mysql_query($sql);    
    $row = @mysql_fetch_assoc($gosql);
        if(!empty($row["id"])){
        $sql="UPDATE message_control SET recipients_id='' WHERE sender_id=".$sender_id;
        $gosql = @mysql_query($sql);    
        }
    }   
     
}
?>
