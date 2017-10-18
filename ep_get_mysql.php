<?php
    define('IN_SZYPI', true);
    include 'mysql.php';
    include 'config.php'; 
    $mysql = new mysql();
     if($_SERVER['REQUEST_METHOD']=='GET'){
        $getter=urldecode($_GET["value"]);
        $getter=htmlentities($getter);
        $getter=str_replace ("&nbsp;", " ", $getter);
        $getter=str_replace ("&oacute;", "ó", $getter);
        switch ($_GET["field"]){
            case "live":
            $sql="(SELECT LOWER(name),LOCATE('".mysql_real_escape_string(strtolower($getter))."',name) as locate FROM vilage WHERE name LIKE '".mysql_real_escape_string(strtolower($getter))."%' ORDER BY name LIMIT 6) UNION 
            (SELECT LOWER(name),LOCATE('".mysql_real_escape_string(strtolower($getter))."',name) as locate FROM vilage WHERE name LIKE '%".mysql_real_escape_string(strtolower($getter))."%' ORDER BY name LIMIT 6)";
            $gosql = @mysql_query($sql);
            $num_rows = mysql_num_rows($gosql);
            $num_rows=($num_rows>6)?6:$num_rows;
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?';':',';
             echo strtolower(mysql_result($gosql,$i,0).$comma);
            }
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?NULL:',';
             echo strtolower(mysql_result($gosql,$i,1).$comma);
            }
            @mysql_free_result($gosql);
            break;
            case "school":
            $sql="(SELECT LOWER(name),LOCATE('".mysql_real_escape_string(strtolower($getter))."',name) as locate FROM schools WHERE name LIKE '".mysql_real_escape_string(strtolower($getter))."%' ORDER BY name LIMIT 6) UNION 
            (SELECT LOWER(name),LOCATE('".mysql_real_escape_string(strtolower($getter))."',name) as locate FROM schools WHERE name LIKE '%".mysql_real_escape_string(strtolower($getter))."%' ORDER BY name LIMIT 6)";
            $gosql = @mysql_query($sql);
            $num_rows = mysql_num_rows($gosql);
            $num_rows=($num_rows>6)?6:$num_rows;
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?';':',';
             echo strtolower(mysql_result($gosql,$i,0).$comma);
            }
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?NULL:',';
             echo strtolower(mysql_result($gosql,$i,1).$comma);
            }
            @mysql_free_result($gosql);
            break;
            case "work":
            $sql="(SELECT LOWER(name),LOCATE('".mysql_real_escape_string(strtolower($getter))."',name) as locate FROM works WHERE name LIKE '".mysql_real_escape_string(strtolower($getter))."%' ORDER BY name LIMIT 6) UNION 
            (SELECT LOWER(name),LOCATE('".mysql_real_escape_string(strtolower($getter))."',name) as locate FROM works WHERE name LIKE '%".mysql_real_escape_string(strtolower($getter))."%' ORDER BY name LIMIT 6)";
            $gosql = @mysql_query($sql);
            $num_rows = mysql_num_rows($gosql);
            $num_rows=($num_rows>6)?6:$num_rows;
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?';':',';
             echo strtolower(mysql_result($gosql,$i,0).$comma);
            }
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?NULL:',';
             echo strtolower(mysql_result($gosql,$i,1).$comma);
            }
            @mysql_free_result($gosql);
            break;
            case "school_info":
            $sql="SELECT LOWER(sch_inf.specialization),LOWER(sch.name) sp FROM school_info sch_inf LEFT JOIN schools sch ON sch_inf.school_id=sch.id WHERE LOWER(sch.name) LIKE '".mysql_real_escape_string(strtolower($getter))."'";
            $gosql = @mysql_query($sql);
            $num_rows = mysql_num_rows($gosql);
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?NULL:',';
             echo strtolower(mysql_result($gosql,$i,0).$comma);
            }         
            break;
            
             case "work_info":
            $sql="SELECT LOWER(wk_inf.profession),LOWER(wk.name) pf FROM work_info wk_inf LEFT JOIN works wk ON wk_inf.work_id=wk.id WHERE LOWER(wk.name) LIKE '".mysql_real_escape_string(strtolower($getter))."'";
            $gosql = @mysql_query($sql);
            $num_rows = mysql_num_rows($gosql);
            for($i=0;$i<$num_rows;$i++)
            {
             $comma=(($i+1)==$num_rows)?NULL:',';
             echo strtolower(mysql_result($gosql,$i,0).$comma);
            }         
            break;
        }
     }
?>