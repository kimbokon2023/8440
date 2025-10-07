<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

$WebSite = "http://8440.co.kr/";

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문  

isset($_REQUEST["which"])  ? $which = $_REQUEST["which"] : $which=""; 
isset($_REQUEST["search_opt"])  ? $search_opt = $_REQUEST["search_opt"] : $search_opt=""; 
isset($_REQUEST["ceilingcode"])  ? $ceilingcode = $_REQUEST["ceilingcode"] : $ceilingcode=""; 
   
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

  if(isset($_REQUEST["Bigsearch"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $Bigsearch=$_REQUEST["Bigsearch"];
  else
   $Bigsearch="";

include '__request.php';	// __ 언더바 두개임 주의
			  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();	
     
 if ($mode=="modify"){
      
     try{
        $sql = "select * from mirae8440.steel where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
       
    $data=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $data . $update_log . "&#10";  // 개행문자 Textarea    
			  
/* 	print "접속완료"	  ; */

 try{
	$pdo->beginTransaction();   
	$sql = "update mirae8440.steel set which=?, outdate=?, indate=?, outworkplace=?, item=?, spec=?, steelnum=?, company=?, comment=?, model=?, first_writer=?, update_log=?, search_opt=?, bad_choice=?, ";
	$sql .= " used_width_1=?, used_width_2=?, used_width_3=?, used_width_4=?, used_width_5=?, used_length_1=?, used_length_2=?, used_length_3=?, used_length_4=?, used_length_5=?, used_num_1=?, used_num_2=?, used_num_3=?, used_num_4=?, used_num_5=? , supplier=? , method=?, bonDone=? , lcDone=? ";
	$sql .= " where num=?  LIMIT 1";		

	$stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $which, PDO::PARAM_STR);  
	$stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
	$stmh->bindValue(3, $indate, PDO::PARAM_STR);  
	$stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);  
	$stmh->bindValue(5, $item, PDO::PARAM_STR);  
	$stmh->bindValue(6, $spec, PDO::PARAM_STR);  
	$stmh->bindValue(7, $steelnum, PDO::PARAM_STR);  
	$stmh->bindValue(8, $company, PDO::PARAM_STR);  
	$stmh->bindValue(9, $comment, PDO::PARAM_STR);  
	$stmh->bindValue(10, $model, PDO::PARAM_STR);  
	$stmh->bindValue(11, $first_writer, PDO::PARAM_STR);  	 
	$stmh->bindValue(12, $update_log, PDO::PARAM_STR);  
	$stmh->bindValue(13, $search_opt, PDO::PARAM_STR);  
	$stmh->bindValue(14, $bad_choice, PDO::PARAM_STR);  
	$stmh->bindValue(15, $used_width_1, PDO::PARAM_STR); 
	$stmh->bindValue(16, $used_width_2, PDO::PARAM_STR); 
	$stmh->bindValue(17, $used_width_3, PDO::PARAM_STR); 
	$stmh->bindValue(18, $used_width_4, PDO::PARAM_STR); 
	$stmh->bindValue(19, $used_width_5, PDO::PARAM_STR); 
	$stmh->bindValue(20, $used_length_1, PDO::PARAM_STR); 
	$stmh->bindValue(21, $used_length_2, PDO::PARAM_STR); 
	$stmh->bindValue(22, $used_length_3, PDO::PARAM_STR); 
	$stmh->bindValue(23, $used_length_4, PDO::PARAM_STR); 
	$stmh->bindValue(24, $used_length_5, PDO::PARAM_STR); 
	$stmh->bindValue(25, $used_num_1, PDO::PARAM_STR); 
	$stmh->bindValue(26, $used_num_2, PDO::PARAM_STR); 
	$stmh->bindValue(27, $used_num_3, PDO::PARAM_STR); 
	$stmh->bindValue(28, $used_num_4, PDO::PARAM_STR); 
	$stmh->bindValue(29, $used_num_5, PDO::PARAM_STR); 	 
	$stmh->bindValue(30, $supplier, PDO::PARAM_STR); 	 
	$stmh->bindValue(31, $method, PDO::PARAM_STR); 	 
	$stmh->bindValue(32, $bonDone, PDO::PARAM_STR); 	 
	$stmh->bindValue(33, $lcDone, PDO::PARAM_STR); 	 
    $stmh->bindValue(34, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }  
 } else	{	 
 
	// 데이터 신규/ 복사 구간		 
    $first_writer=$_SESSION["name"] . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록 
	 
try{
	$pdo->beginTransaction();

	$sql = "insert into mirae8440.steel(which, outdate, indate, outworkplace, item, spec, steelnum, company, comment, model, first_writer, update_log, search_opt, bad_choice, ";
	$sql .= "used_width_1,used_width_2,used_width_3,used_width_4,used_width_5,used_length_1,used_length_2,used_length_3,used_length_4,used_length_5,used_num_1,used_num_2,used_num_3,used_num_4,used_num_5,supplier,method, bonDone, lcDone) ";

	$sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

	$stmh = $pdo->prepare($sql); 
	$stmh->bindValue(1, $which, PDO::PARAM_STR);  
	$stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
	$stmh->bindValue(3, $indate, PDO::PARAM_STR);  
	$stmh->bindValue(4, $outworkplace, PDO::PARAM_STR);  
	$stmh->bindValue(5, $item, PDO::PARAM_STR);  
	$stmh->bindValue(6, $spec, PDO::PARAM_STR);  
	$stmh->bindValue(7, $steelnum, PDO::PARAM_STR);  
	$stmh->bindValue(8, $company, PDO::PARAM_STR);  
	$stmh->bindValue(9, $comment, PDO::PARAM_STR);  
	$stmh->bindValue(10, $model, PDO::PARAM_STR);  
	$stmh->bindValue(11, $first_writer, PDO::PARAM_STR);  
	$stmh->bindValue(12, $update_log, PDO::PARAM_STR);  
	$stmh->bindValue(13, $search_opt, PDO::PARAM_STR);  
	$stmh->bindValue(14, $bad_choice, PDO::PARAM_STR);  
	$stmh->bindValue(15, $used_width_1, PDO::PARAM_STR); 
	$stmh->bindValue(16, $used_width_2, PDO::PARAM_STR); 
	$stmh->bindValue(17, $used_width_3, PDO::PARAM_STR); 
	$stmh->bindValue(18, $used_width_4, PDO::PARAM_STR); 
	$stmh->bindValue(19, $used_width_5, PDO::PARAM_STR); 
	$stmh->bindValue(20, $used_length_1, PDO::PARAM_STR); 
	$stmh->bindValue(21, $used_length_2, PDO::PARAM_STR); 
	$stmh->bindValue(22, $used_length_3, PDO::PARAM_STR); 
	$stmh->bindValue(23, $used_length_4, PDO::PARAM_STR); 
	$stmh->bindValue(24, $used_length_5, PDO::PARAM_STR); 
	$stmh->bindValue(25, $used_num_1, PDO::PARAM_STR); 
	$stmh->bindValue(26, $used_num_2, PDO::PARAM_STR); 
	$stmh->bindValue(27, $used_num_3, PDO::PARAM_STR); 
	$stmh->bindValue(28, $used_num_4, PDO::PARAM_STR); 
	$stmh->bindValue(29, $used_num_5, PDO::PARAM_STR); 	 
	$stmh->bindValue(30, $supplier, PDO::PARAM_STR); 	 
	$stmh->bindValue(31, $method, PDO::PARAM_STR); 	 
	$stmh->bindValue(32, $bonDone, PDO::PARAM_STR); 	 
	$stmh->bindValue(33, $lcDone, PDO::PARAM_STR); 	 	
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   

   }
   
// echo $which . " " . $search_opt . " " . $ceilingcode ; 

// 레이져완료일을 철판출고를 등록할시 등록해 주는 루틴임.	 
if($which=='2' && $search_opt=='2')
{
 try{
	$sql = "select * from mirae8440.ceiling where num=?";
	$stmh = $pdo->prepare($sql);  
	$stmh->bindValue(1, $ceilingcode, PDO::PARAM_STR);       // 천장 선택한 레코드 
	$stmh->execute();            

	$row = $stmh->fetch(PDO::FETCH_ASSOC); 	

	$update_log=$row["update_log"];
 					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }		
  
    $date=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $date . $update_log . "&#10";  // 개행문자 Textarea		
	
 // try{		  
	// $pdo->beginTransaction();   
	// $sql = "update mirae8440.ceiling set ";
	// $sql .="update_log=?, lclaser_date=?, eunsung_laser_date=? "; 

	// $sql .= " where num=? LIMIT 1" ;        

	// $stmh = $pdo->prepare($sql); 

	// $stmh->bindValue(1, $update_log, PDO::PARAM_STR);  
		 
		 // if($deldata=='') 
			 // $update_day=date("Y-m-d"); // 현재날짜 2020-01-20 형태로 지정	 
			 // else
			   // $update_day='';
			   
		 // $stmh->bindValue(2, $outdate, PDO::PARAM_STR);  
		 // $stmh->bindValue(3, $outdate, PDO::PARAM_STR);  
		 // $stmh->bindValue(4, $ceilingcode, PDO::PARAM_STR);
		 
		 // $stmh->execute();
		 // $pdo->commit(); 
        // } catch (PDOException $Exception) {
           // $pdo->rollBack();
           // print "오류: ".$Exception->getMessage();
       // }      
}       

 if ($mode!="modify"){
	 try{
		 $sql = "select * from mirae8440.steel order by num desc limit 1";
		 $stmh = $pdo->prepare($sql);  
		 $stmh->execute();                  
		 $row = $stmh->fetch(PDO::FETCH_ASSOC);	 
		 $num=$row["num"];		 
		}
	   catch (PDOException $Exception) {
		   print "오류: ".$Exception->getMessage();
	  }
   }

 $data = array(
		"num" =>  $num
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));  
?>