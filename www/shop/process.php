<meta charset="UTF-8">
<?php	   
  session_start();  
// DB에서 자재정보를 읽어온다.	   
// print_r($_REQUEST["source_num"]);

$tmp = array();	
$tmp['PO'] = $_REQUEST["PO"];
$tmp['CR'] = $_REQUEST["CR"];
$tmp['EGI'] = $_REQUEST["EGI"];
$tmp['HL304'] = $_REQUEST["HL304"];
$tmp['MR304'] = $_REQUEST["MR304"];
$tmp['etcsteel'] = $_REQUEST["etcsteel"];

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


// 기존데이터 삭제하고, 새로운 데이터로 만듬

function make_array($str) {    
	$tmp_num= implode (",", $str);
	$tmp = explode( ',', $tmp_num );
	return $tmp;	
}

$num = make_array($_REQUEST["source_num"]);
$sortorder = make_array($_REQUEST["sortorder"]);
$item = make_array($_REQUEST["source_item"]);
$spec = make_array($_REQUEST["source_spec"]);
$take = make_array($_REQUEST["source_take"]);

// print_r($item);

// 데이터 신규 등록하는 구간
$rec_num = count($num); 

require_once("../lib/mydb.php");
$pdo = db_connect();
$pdo->beginTransaction();
$sql = "delete from mirae8440.steelsource";   // 일단 기존데이터 지워준다.
$stmh = $pdo->prepare($sql); 
$stmh->execute();
$pdo->commit(); 
$pdo->beginTransaction();
$sql = "ALTER TABLE mirae8440.steelsource AUTO_INCREMENT=1";   // auto_increment 초기화
$stmh = $pdo->prepare($sql); 
$stmh->execute();
$pdo->commit(); 

for($i=0; $i<$rec_num; $i++) {	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.steelsource(sortorder, item, spec, take) ";
     
     $sql .= " values(?, ?, ?, ?)";
	 
     $stmh = $pdo->prepare($sql); 
     // $stmh->bindValue(1, $source_num[$i], PDO::PARAM_STR);  
     $stmh->bindValue(1,$sortorder[$i], PDO::PARAM_STR);  
     $stmh->bindValue(2,$item[$i], PDO::PARAM_STR);  
     $stmh->bindValue(3, $spec[$i], PDO::PARAM_STR);  
     $stmh->bindValue(4, $take[$i], PDO::PARAM_STR);    
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   	 
}		

header("Location:http://8440.co.kr/steel/settings.php"); 
?>

