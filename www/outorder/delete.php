<?php
/**
 * 외주 주문 데이터 삭제
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';

// 세션 시작
if (!isset($_SESSION)) {
    session_start();
}

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
   
  
   // 첨부파일 삭제
   try{
     $sql = "select * from mirae8440.fileuploads where parentid = ? ";
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1,$num,PDO::PARAM_STR); 
     $stmh->execute();
     $count = $stmh->rowCount();              
 
       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		   $savename = $row["savename"];

			   $upload_dir = '../uploads/';    //물리적 저장위치   
			   $made_name = $upload_dir . $savename;
			   unlink($made_name); 
				
			   try{									
				 $pdo->beginTransaction();
				 $sql1 = "delete from mirae8440.fileuploads where savename = ?";  
				 $stmh1 = $pdo->prepare($sql1);
				 $stmh1->bindValue(1,$savename,PDO::PARAM_STR);      
				 $stmh1->execute();  

				 $pdo->commit();
				 
				 } catch (Exception $ex) {
					$pdo->rollBack();
					print "오류: ".$Exception->getMessage();
			   } 
     }
   }catch (PDOException $Exception) {
        print "오류: ".$Exception->getMessage();
   }   
   
   //   header("Location:http://8440.co.kr/outorder/list.php");
   
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"num" =>  $num
);

//json 출력
echo json_encode($data, JSON_UNESCAPED_UNICODE);