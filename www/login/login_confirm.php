<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

$id=$_REQUEST["uid"];
$pw=$_REQUEST["upw"];

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

$error='';

try{
    $sql="select * from mirae8440.member where id=?";
    $stmh=$pdo->prepare($sql);
    $stmh->bindValue(1,$id,PDO::PARAM_STR);
    $stmh->execute();
    $count=$stmh->rowCount();
} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();

}

$row=$stmh->fetch(PDO::FETCH_ASSOC);
if($count<1) 
	$error = "아이디가 틀립니다!";
 elseif($pw!=$row["pass"]) 
 	$error = "비밀번호가 틀립니다.!";        	
else
{		
	// 세션 시작	
	session_start();
	
	$DB = "mirae8440";
	$WebSite = "https://8440.co.kr/";
	
    $_SESSION["userid"]=$row["id"];
    $_SESSION["name"]=$row["name"];
    $_SESSION["nick"]=$row["nick"];
    $_SESSION["level"]=$row["level"];
    $_SESSION["ecountID"]=$row["ecountID"];
    $_SESSION["part"]=$row["part"];	
    $_SESSION["eworks_level"]=$row["eworks_level"];	
    $_SESSION["position"]=$row["position"];	
    $_SESSION["hp"]=$row["hp"];	
    $_SESSION["DB"]=$DB ;
    $level=$row["level"];
}
  	 	
$data=date("Y-m-d H:i:s") . " - " . $_SESSION["userid"] . " - " . $_SESSION["name"] ;	
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
$pdo->beginTransaction();
$sql = "insert into ".$DB.".logdata(data) values(?) " ;
$stmh = $pdo->prepare($sql); 
$stmh->bindValue(1, $data, PDO::PARAM_STR);   
$stmh->execute();
$pdo->commit(); 
 
$data = array(
		"id" => $id,
		"error" => $error,
		"level" => $level
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));	
	

?>
