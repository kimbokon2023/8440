<?php

// php warning 안나오게 하는 방법
ini_set('display_errors', 'Off');

// DB이름 설정
$DB = "mirae8440.p_evaluation";

include 'common.php';

isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 
isset($_REQUEST["SelectWork"])  ? $SelectWork = $_REQUEST["SelectWork"] :   $SelectWork=""; 

if((int)$num == 0)
	$SelectWork="insert"; 

// var_dump($order_prod_des);
              
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	

include "_request.php";



if($SelectWork=="update")  {			


// var_dump($order_prod_cd);

	try {
		$pdo->beginTransaction();

		// where 구문이 있음 주의 update에 해당함.
		$sql = "update " . $DB . " set  txt1=?, txt2=?, txt3=?, txt4=?, txt5=?, txt6=?, txt7=?, txt8=?, txt9=?, txt10=?, txt11=?, txt12=?, txt13=?, txt14=?, txt15=?, txt16=?, txt17=?, txt18=?, txt19=?, txt20=?, txt21=?, txt22=?, txt23=? ";
		$sql .= " where num=? LIMIT 1 ";

		$stmh = $pdo->prepare($sql);

		$stmh->bindValue(1, $txt1, PDO::PARAM_STR);
		$stmh->bindValue(2, $txt2, PDO::PARAM_STR);
		$stmh->bindValue(3, $txt3, PDO::PARAM_STR);
		$stmh->bindValue(4, $txt4, PDO::PARAM_STR);
		$stmh->bindValue(5, $txt5, PDO::PARAM_STR);
		$stmh->bindValue(6, $txt6, PDO::PARAM_STR);
		$stmh->bindValue(7, $txt7, PDO::PARAM_STR);
		$stmh->bindValue(8, $txt8, PDO::PARAM_STR);
		$stmh->bindValue(9, $txt9, PDO::PARAM_STR);
		$stmh->bindValue(10, $txt10, PDO::PARAM_STR);
		$stmh->bindValue(11, $txt11, PDO::PARAM_STR);
		$stmh->bindValue(12, $txt12, PDO::PARAM_STR);
		$stmh->bindValue(13, $txt13, PDO::PARAM_STR);
		$stmh->bindValue(14, $txt14, PDO::PARAM_STR);
		$stmh->bindValue(15, $txt15, PDO::PARAM_STR);
		$stmh->bindValue(16, $txt16, PDO::PARAM_STR);
		$stmh->bindValue(17, $txt17, PDO::PARAM_STR);
		$stmh->bindValue(18, $txt18, PDO::PARAM_STR);
		$stmh->bindValue(19, $txt19, PDO::PARAM_STR);
		$stmh->bindValue(20, $txt20, PDO::PARAM_STR);
		$stmh->bindValue(21, $txt21, PDO::PARAM_STR);
		$stmh->bindValue(22, $txt22, PDO::PARAM_STR);
		$stmh->bindValue(23, $txt23, PDO::PARAM_STR);
		$stmh->bindValue(24, $num, PDO::PARAM_STR);

		$stmh->execute();
		$pdo->commit();
	} catch (PDOException $Exception) {
		$pdo->rollBack();
		print "오류: " . $Exception->getMessage();
	}
	 
			 	  
		
}   // end of $SelectWork if-statement
	

			
if( $SelectWork=="insert")  {	 // 선택에 따라 index로 또는 list로 분기한다. $num이 Null일때	
			
	// 데이터 신규 등록하는 구간		
	
try {
    $pdo->beginTransaction();

    $sql = "insert into " . $DB . " (txt1, txt2, txt3, txt4, txt5, txt6, txt7, txt8, txt9, txt10, txt11, txt12, txt13, txt14, txt15, txt16, txt17, txt18, txt19, txt20, txt21, txt22, txt23) ";
    $sql .= "values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmh = $pdo->prepare($sql);

    $stmh->bindValue(1, $txt1, PDO::PARAM_STR);
    $stmh->bindValue(2, $txt2, PDO::PARAM_STR);
    $stmh->bindValue(3, $txt3, PDO::PARAM_STR);
    $stmh->bindValue(4, $txt4, PDO::PARAM_STR);
    $stmh->bindValue(5, $txt5, PDO::PARAM_STR);
    $stmh->bindValue(6, $txt6, PDO::PARAM_STR);
    $stmh->bindValue(7, $txt7, PDO::PARAM_STR);
    $stmh->bindValue(8, $txt8, PDO::PARAM_STR);
    $stmh->bindValue(9, $txt9, PDO::PARAM_STR);
    $stmh->bindValue(10, $txt10, PDO::PARAM_STR);
    $stmh->bindValue(11, $txt11, PDO::PARAM_STR);
    $stmh->bindValue(12, $txt12, PDO::PARAM_STR);
    $stmh->bindValue(13, $txt13, PDO::PARAM_STR);
    $stmh->bindValue(14, $txt14, PDO::PARAM_STR);
    $stmh->bindValue(15, $txt15, PDO::PARAM_STR);
    $stmh->bindValue(16, $txt16, PDO::PARAM_STR);
    $stmh->bindValue(17, $txt17, PDO::PARAM_STR);
    $stmh->bindValue(18, $txt18, PDO::PARAM_STR);
    $stmh->bindValue(19, $txt19, PDO::PARAM_STR);
    $stmh->bindValue(20, $txt20, PDO::PARAM_STR);
    $stmh->bindValue(21, $txt21, PDO::PARAM_STR);
    $stmh->bindValue(22, $txt22, PDO::PARAM_STR);
    $stmh->bindValue(23, $txt23, PDO::PARAM_STR);

    $stmh->execute();
    $pdo->commit();
} catch (PDOException $Exception) {
    $pdo->rollBack();
    print "오류: " . $Exception->getMessage();
}
 	 
			 
// parentKey 추출

        $sql = "select * from  " . $DB . "  order by num desc";
		 try{  
			  $stmh = $pdo->query($sql);      // 검색조건에 맞는글 stmh
			  $temp = $stmh->rowCount();
			  $row = $stmh->fetch(PDO::FETCH_ASSOC);
			  $num=$row["num"];
			  
			 } catch (PDOException $Exception) {
					  print "오류: ".$Exception->getMessage();
					  }			  			 					  
	  // print "마지막 parentKey = " . $num;

// echo $num;		  

  
}   // end of $SelectWork if-statement 


if($SelectWork=="delete")  {   // data 삭제시 
   
  
   try{									// esmaindb의 자료를 삭제한다.
     $pdo->beginTransaction();
     $sql = "delete from  " . $DB . "  where num = ?";  
     $stmh = $pdo->prepare($sql);
     $stmh->bindValue(1,$num,PDO::PARAM_STR);      
     $stmh->execute();  

     $pdo->commit();
	 
     } catch (Exception $ex) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
   }

}


$data = [   'num' => $num ,
		    'dump' => $txt1
 ];
 
 echo json_encode($data, JSON_UNESCAPED_UNICODE);



?>

