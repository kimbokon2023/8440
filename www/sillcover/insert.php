<?php include $_SERVER['DOCUMENT_ROOT'] . '/session.php';   

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

$tablename = 'sillcover';
  
  // 임시저장된 첨부파일을 확정하기 위해 검사하기  
isset($_REQUEST["timekey"])  ? $timekey=$_REQUEST["timekey"] :  $timekey='';   // 신규데이터에 생성할때 임시저장키  

 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

include '_request.php';	  

$searchtag = $outdate . ' ' . $indate . ' ' . $workplace . ' ' . $comment . ' ' . $first_writer . ' ' . $update_log ; 

require_once("../lib/mydb.php");
$pdo = db_connect();

if ($mode == "modify") {
    $data = date("Y-m-d H:i:s") . " - " . $_SESSION["name"] . "  ";
    $update_log = $data . $update_log . "&#10";  // 개행문자 Textarea

    try {
		$sql = "select * from {$DB}.{$tablename} where num=? ";  
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_INT);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

    try {
        $pdo->beginTransaction();
        $sql = "UPDATE  {$DB}.{$tablename}  
                SET outdate = ?, indate = ?, workplace = ?, comment = ?, 
                    first_writer = ?, update_log = ?, is_deleted = ?, searchtag = ?
                WHERE num = ? LIMIT 1";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $outdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $indate, PDO::PARAM_STR);
        $stmh->bindValue(3, $workplace, PDO::PARAM_STR);
        $stmh->bindValue(4, $comment, PDO::PARAM_STR);
        $stmh->bindValue(5, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(6, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(7, $is_deleted, PDO::PARAM_INT);
        $stmh->bindValue(8, $searchtag, PDO::PARAM_STR);
        $stmh->bindValue(9, $num, PDO::PARAM_INT);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }
} 

if ($mode == "insert") {
   
       $first_writer = $user_id ;

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO  {$DB}.{$tablename} (outdate, indate, workplace, comment, 
                                       first_writer, update_log, searchtag)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $outdate, PDO::PARAM_STR);
        $stmh->bindValue(2, $indate, PDO::PARAM_STR);
        $stmh->bindValue(3, $workplace, PDO::PARAM_STR);
        $stmh->bindValue(4, $comment, PDO::PARAM_STR);
        $stmh->bindValue(5, $first_writer, PDO::PARAM_STR);
        $stmh->bindValue(6, $update_log, PDO::PARAM_STR);
        $stmh->bindValue(7, $searchtag, PDO::PARAM_STR);

        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: " . $Exception->getMessage();
    }

    // Retrieve the ID of the newly inserted record
    try {
        $sql = "SELECT * FROM {$DB}.{$tablename}  ORDER BY num DESC LIMIT 1";
        $stmh = $pdo->prepare($sql);
        $stmh->execute();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $num = $row["num"];
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }


	// 신규데이터인 경우 첨부파일/첨부이미지 추가한 것이 있으면 parentid 변경해줌	 
	   try{
			$pdo->beginTransaction();   
			$sql = "update {$DB}.picuploads set parentnum=? where parentnum=?";
			$stmh = $pdo->prepare($sql); 
			$stmh->bindValue(1, $num, PDO::PARAM_STR);  
			$stmh->bindValue(2, $timekey, PDO::PARAM_STR);   
			$stmh->execute();
			$pdo->commit(); 
			} catch (PDOException $Exception) {
			   $pdo->rollBack();
			   print "오류: ".$Exception->getMessage();
		   }                	 
	 
}
    
if ($mode == "delete") {
    try {
        $pdo->beginTransaction();  // 트랜잭션 시작
        $query = "UPDATE " . $DB . ".{$tablename}  SET is_deleted=1 WHERE num=? LIMIT 1";
        $stmh = $pdo->prepare($query);
        $params = [$num];
        $stmh->execute($params);
        $pdo->commit();  // 데이터 변경 사항을 커밋
    } catch (PDOException $Exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();  // 오류 발생 시 롤백
        }
        print "오류: " . $Exception->getMessage();
    }
	
   // 첨부파일 삭제
   try{									
		 $pdo->beginTransaction();
		 $sql1 = "delete from {$DB}.picuploads where parentnum = ? and tablename = ? ";  
		 $stmh1 = $pdo->prepare($sql1);
		 $stmh1->bindValue(1,$num, PDO::PARAM_STR);      		 
		 $stmh1->bindValue(2,$tablename, PDO::PARAM_STR);      
		 $stmh1->execute();  

		 $pdo->commit();
		 
		 } catch (Exception $ex) {
			$pdo->rollBack();
			print "오류: ".$Exception->getMessage();
	   }                         
}
			 
			 
 $data = array( "num" =>  $num );

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
  
 ?>
