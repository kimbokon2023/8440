<?php

if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>10) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  

header("Content-Type: application/json");

$tablename='vote';

// raw POST data를 가져옵니다.
$jsonData = json_decode(file_get_contents("php://input"), true);

$num = $jsonData['num']; // num 값을 가져옵니다.
$data = $jsonData['data']; // columns 데이터를 가져옵니다.
		  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

    try {
        $pdo->beginTransaction();
        
        // SQL 쿼리를 올바르게 수정합니다.
        $sql = "UPDATE " . $DB . "." . $tablename . " SET votelist = ? WHERE num = ?"; // 테이블명과 조건을 확인해야 합니다.
        
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, json_encode($data), PDO::PARAM_STR); // JSON 문자열로 저장
        $stmh->bindValue(2, $num, PDO::PARAM_INT); // num 값 바인딩
        
        $stmh->execute();
        
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

echo json_encode(["status" => "success", "num" => $num , "votelist" => json_encode($data)   ], JSON_UNESCAPED_UNICODE);
?>


