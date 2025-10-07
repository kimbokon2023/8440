<?php	   
  session_start();  
// php warning 안나오게 하는 방법
ini_set('display_errors', 'Off');

$tmp = array();	
$tmp['WJ'] = $_REQUEST["WJ"];
$tmp['NJ'] = $_REQUEST["NJ"];
$tmp['SJ'] = $_REQUEST["SJ"];
$tmp['WJ_HL'] = $_REQUEST["WJ_HL"];
$tmp['NJ_HL'] = $_REQUEST["NJ_HL"];
$tmp['SJ_HL'] = $_REQUEST["SJ_HL"];

$obj = (object) $tmp;

print $SelectWork . "<br>";
print_r($tmp);
print_r($obj);

write_ini_file($obj, 'estimate.ini', false); 

// ini 파일 write
function write_ini_file($assoc_arr, $path, $has_sections=FALSE) {
        $content = "";
        if ($has_sections) {
            $i = 0;
            foreach ($assoc_arr as $key=>$elem) {
                if ($i > 0) {
                    $content .= "\n";
                }
                $content .= "[".$key."]\n";
                foreach ($elem as $key2=>$elem2) {
                    if(is_array($elem2))
                    {
                        for($i=0;$i<count($elem2);$i++)
                        {
                            $content .= $key2."[] = \"".$elem2[$i]."\"\n";
                        }
                    } else if($elem2=="") {
                        $content .= $key2." = \n";
                    } else {
                        if (preg_match('/[^0-9]/i',$elem2)) {
                            $content .= $key2." = \"".$elem2."\"\n";
                        }else {
                            $content .= $key2." = ".$elem2."\n";
                        }
                    }
                }
                $i++;
            }
        }
        else {
            foreach ($assoc_arr as $key=>$elem) {
                if(is_array($elem))
                {
                    for($i=0;$i<count($elem);$i++)
                    {
                        $content .= $key."[] = \"".$elem[$i]."\"\n";
                    }
                } else if($elem=="") {
                    $content .= $key." = \n";
                } else {
                    if (preg_match('/[^0-9]/i',$elem)) {
                        $content .= $key." = \"".$elem."\"\n";
                    }else {
                        $content .= $key." = ".$elem."\n";
                    }
                }
            }
        }
 
        if (!$handle = fopen($path, 'w')) {
            return false;
        }
 
        $success = fwrite($handle, $content);
        fclose($handle);
 
        return $success;
    }	


?>

