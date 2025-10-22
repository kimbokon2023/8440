<?php
/**
 * 외주 주문 상태 업데이트 (확인 처리)
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';
header("Content-Type: application/json");

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';
$confirm = $_REQUEST["strtmp"] ?? date("Y-m-d");

// $pdo는 bootstrap.php에서 이미 초기화됨
if (!isset($pdo) || !$pdo) {
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed'
    ], JSON_UNESCAPED_UNICODE);
    exit;
} 
 
 try{
     $sql = "select * from mirae8440.outorder where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
     $update_log=$row["update_log"];
	
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }
 
$session_name = $_SESSION["name"] ?? '';
$data = date("Y-m-d H:i:s") . " - " . $session_name . " ";
$update_log = $data . $update_log . "&#10"; // 개행문자 Textarea   	
 
 	 try{		 
    $pdo->beginTransaction();   
    $sql = "update mirae8440.outorder set ";
    $sql .="update_log=?, confirm=? "; 	
	$sql .= " where num=? LIMIT 1" ;         
	   
	$stmh = $pdo->prepare($sql); 

	$stmh->bindValue(1, $update_log, PDO::PARAM_STR);	 
	$stmh->bindValue(2, $confirm, PDO::PARAM_STR);	 
	$stmh->bindValue(3, $num, PDO::PARAM_STR);	 
    $stmh->execute();
    $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }      
	   

//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num
);

//json 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);