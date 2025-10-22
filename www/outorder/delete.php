<?php
/**
 * 외주 주문 데이터 삭제
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화 (?? '' 형태)
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

header("Content-Type: application/json");

// 요청 변수 초기화 (?? '' 형태)
$num = $_REQUEST["num"] ?? '';

require_once includePath('lib/mydb.php');
$pdo = db_connect();	
 
   $upload_dir = '../uploads/'; ;   //물리적 저장위치   
 
   try{
     $pdo->beginTransaction();
     $sql = "delete from mirae8440.outorder where num = ?";  
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
echo json_encode($data, JSON_UNESCAPED_UNICODE);