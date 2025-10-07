<?php\nrequire_once __DIR__ . '/../common/functions.php';
if(!isset($_SESSION))      
    session_start(); 
if(isset($_SESSION["DB"]))
    $DB = $_SESSION["DB"];  
$level = $_SESSION["level"];
$user_name = $_SESSION["name"];
$user_id = $_SESSION["userid"]; 

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

$tablename = "cost";
   
$num=$_REQUEST["num"];
$page=$_REQUEST["page"];
	 
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

    try {
        $pdo->beginTransaction();   
        $sql = "update " . $DB . "." . $tablename . " set is_deleted=? ";
        $sql .= " where num=? LIMIT 1";     
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1, true, PDO::PARAM_STR);  
        $stmh->bindValue(2, $num, PDO::PARAM_STR);  // Binding the $num variable

        $stmh->execute();
        $pdo->commit(); 
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }    
 
//      header("Location:http://8440.co.kr/request/list.php");// 

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num,
		"page" =>  $page
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   

?>