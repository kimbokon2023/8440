<?php require_once(includePath('session_header.php')); 

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문 받는측에서 필요한 정보임 ajax로 보내는 쪽에서 type : json       

$num=$_REQUEST["num"];   
if(isset($_REQUEST["tablename"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $tablename=$_REQUEST["tablename"];
   
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();   
   
   try{
     $pdo->beginTransaction();
     $sql = "delete from  ".$DB."." . $tablename . "  where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();   
     $pdo->commit();
                         
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));     
?>