<?php
//class post services sending post data
class post{
    var $result_data;
    function post($host,$port,$path,$data,$content_type){
        $fp = fsockopen($host, $port, $errno, $errstr, 1);
        $out = "POST ".$path."?filtr=0,0&x=login HTTP/1.0\r\n";
        $out .= "Host: ".$host."\r\n";
        $out .= "Content-Type: ".$content_type ." \r\n";
        $out .= "User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
        $out .= "Content-Length: ".strlen($data)." \r\n";
        $out .= "\r\n";
        $out .= $data;
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        while (!feof($fp))
        {
            $this->result_data.= fgets($fp, 128);
        }
        fclose($fp);
    }
    
    function get_data(){
        //komplikacje:można użyć metody magicznej string
        return substr($this->result_data,880);
    }

}

?>