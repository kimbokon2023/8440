<?php include getDocumentRoot() . '/session.php';   

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  
  
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

  include 'request.php';	  

 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify"){
    $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea      
     try{
        $sql = "select * from mirae8440.eworks where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
	 
// 전자 결재에 보여질 내용 data 수정 update       

		$data = array(
			"which" => $which,
			"outdate" => $outdate,
			"requestdate" => $requestdate,
			"indate" => $indate,
			"outworkplace" => $outworkplace,
			"steel_item" => $steel_item,
			"spec" => $spec,
			"steelnum" => $steelnum,			
			"request_comment" => $request_comment,						
			"payment" => $payment ,						
			"supplier" => $supplier			
		);

		$contents = json_encode($data, JSON_UNESCAPED_UNICODE);

     try{
        $pdo->beginTransaction();   
        $sql = "update mirae8440.eworks set which=?, outdate=?, indate=?, outworkplace=?, steel_item=?, spec=?, steelnum=?, company=?, request_comment=?, payment=? ,first_writer=?, update_log=? , supplier=?, contents=? ";
        $sql .= " where num=?  LIMIT 1";		
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);  
     $stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $indate, PDO::PARAM_STR);  
     $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);  
     $stmh->bindValue(5, $steel_item, PDO::PARAM_STR);  
     $stmh->bindValue(6, $spec, PDO::PARAM_STR);  
     $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);  
     $stmh->bindValue(8, $company, PDO::PARAM_STR);  
     $stmh->bindValue(9, $request_comment, PDO::PARAM_STR);  
     $stmh->bindValue(10, $payment, PDO::PARAM_STR);  
     $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);  
     $stmh->bindValue(12, $update_log, PDO::PARAM_STR);  
     $stmh->bindValue(13, $supplier, PDO::PARAM_STR);  
     $stmh->bindValue(14, $contents, PDO::PARAM_STR);  

     $stmh->bindValue(15, $num, PDO::PARAM_STR);        
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 }
 else	
 {
	 
	 // 데이터 신규 등록하는 구간 
		$registdate = date("Y-m-d H:i:s");
        // Read and decode the JSON file
        $jsonString = file_get_contents(getDocumentRoot() . '/member/Company_approvalLine_.json');
        $approvalLines = json_decode($jsonString, true);

        // Default values for e_line_id and e_line
        $e_line_id = '';
        $e_line = '';
		$al_part="지원파트";
        // Check if decoded JSON is an array and process it
        if (is_array($approvalLines)) {
            foreach ($approvalLines as $line) {
                if ($al_part == $line['savedName']) {
                    foreach ($line['approvalOrder'] as $order) {
                        $e_line_id .= $order['user-id'] . '!';
                        $e_line .= $order['name'] . '!';
                    }
                    break;
                }
            }
        }

        // Set status based on the part
        $status = ($al_part == '제조파트') ? 'send' : 'send';
		$e_title = '부자재 구매 요청';
		$data = array(
			"which" => $which,
			"outdate" => $outdate,
			"requestdate" => $requestdate,
			"indate" => $indate,
			"outworkplace" => $outworkplace,
			"steel_item" => $steel_item,
			"spec" => $spec,
			"steelnum" => $steelnum,			
			"request_comment" => $request_comment,						
			"payment" => $payment,						
			"supplier" => $supplier			
		);

		$contents = json_encode($data, JSON_UNESCAPED_UNICODE);
		
		$eworks_item = '부자재구매';	 
		$author_id= $user_id;
		$author = $user_name;	 
	 
	 $first_writer=$_SESSION["name"] . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록 
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.eworks(which, outdate, indate, outworkplace, steel_item, spec, steelnum, company, request_comment, payment, first_writer, update_log, supplier , status,  e_line_id, e_line, e_title, contents, eworks_item, registdate, author_id, author ) ";
     
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $which, PDO::PARAM_STR);  
     $stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
     $stmh->bindValue(3, $indate, PDO::PARAM_STR);  
     $stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);  
     $stmh->bindValue(5, $steel_item, PDO::PARAM_STR);  
     $stmh->bindValue(6, $spec, PDO::PARAM_STR);  
     $stmh->bindValue(7, $steelnum, PDO::PARAM_STR);  
     $stmh->bindValue(8, $company, PDO::PARAM_STR);  
     $stmh->bindValue(9, $request_comment, PDO::PARAM_STR);  
     $stmh->bindValue(10, $payment, PDO::PARAM_STR);  
     $stmh->bindValue(11, $first_writer, PDO::PARAM_STR);  
     $stmh->bindValue(12, $update_log, PDO::PARAM_STR);  
     $stmh->bindValue(13, $supplier, PDO::PARAM_STR);  
	 
	// eworks 내용에 따른 추가 
	$stmh->bindValue(14, $status, PDO::PARAM_STR);          
	$stmh->bindValue(15, rtrim($e_line_id, '!'), PDO::PARAM_STR);
	$stmh->bindValue(16, rtrim($e_line, '!'), PDO::PARAM_STR);        
	$stmh->bindValue(17, $e_title, PDO::PARAM_STR);
	$stmh->bindValue(18, $contents, PDO::PARAM_STR);
	$stmh->bindValue(19, $eworks_item, PDO::PARAM_STR);	 
	$stmh->bindValue(20, $registdate, PDO::PARAM_STR);  
	$stmh->bindValue(21, $author_id, PDO::PARAM_STR);  
	$stmh->bindValue(22, $author, PDO::PARAM_STR);  	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }    

	// 신규레코드 번호 추출

		 try{
			 $sql = "select * from mirae8440.eworks order by num desc limit 1";
			 $stmh = $pdo->prepare($sql);  
			 $stmh->execute();                  
			 $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
			 $num=$row["num"];		 
			}
		   catch (PDOException $Exception) {
			   print "오류: ".$Exception->getMessage();
		  }	 
		
 // 임시키를 정상적인 번호로 돌려준다. 코드 주의
  try{
        $pdo->beginTransaction();   
        $sql = "update ".$DB.".picuploads set parentnum=? where parentnum=? ";
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
    		 
 $data = array(
		"num" =>  $num
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));   
  
 ?>
