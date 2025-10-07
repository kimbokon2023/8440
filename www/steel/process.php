<?php	   
  session_start();  
// php warning 안나오게 하는 방법
ini_set('display_errors', 'Off');

// 콤마 제거 함수
function removeCommas($value) {
    return str_replace(',', '', $value);
}

$tmp = array();	
$tmp['PO'] = removeCommas($_REQUEST["PO"]);
$tmp['CR'] = removeCommas($_REQUEST["CR"]);
$tmp['EGI'] = removeCommas($_REQUEST["EGI"]);
$tmp['HL201'] = removeCommas($_REQUEST["HL201"]);
$tmp['MR201'] = removeCommas($_REQUEST["MR201"]);
$tmp['HL304'] = removeCommas($_REQUEST["HL304"]);
$tmp['MR304'] = removeCommas($_REQUEST["MR304"]);
$tmp['etcsteel'] = removeCommas($_REQUEST["etcsteel"]);
$tmp['I3HL304'] = removeCommas($_REQUEST["I3HL304"]);
$tmp['I3MR304'] = removeCommas($_REQUEST["I3MR304"]);
$tmp['VB304'] = removeCommas($_REQUEST["VB304"]);
$tmp['BEAD304'] = removeCommas($_REQUEST["BEAD304"]);

$obj = (object) $tmp;

print $SelectWork . "<br>";
print_r($tmp);
print_r($obj);

write_ini_file($obj, 'settings.ini', false); 

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

