 <?php session_start(); 
 
 function trans_date($tdate) {
		      if($tdate!="0000-00-00" and $tdate!="1900-01-01" and $tdate!="")  $tdate = date("Y-m-d", strtotime( $tdate) );
					else $tdate="";							
				return $tdate;	
}

  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"] ?? '';
  else 
    $page=1;   // 1로 설정해야 함
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"] ?? '';
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"] ?? '';
 else 
    $num="";


 if(isset($_REQUEST["data"]))
    $data=$_REQUEST["data"] ?? '';
 else 
    $data="";

 if(isset($_REQUEST["deldata"]))  {
			$deldata=$_REQUEST["deldata"];
			$data=$deldata;  
      }  // sql문장에 영향을 주기위해 data값을 deldata 넣어줌.
 else 
    $deldata="";	
	 
 require_once("../lib/mydb.php");
 $pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440.ceiling where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC); 	
 
  $update_log=$row["update_log"];
 					
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }		
    
 require_once("../lib/mydb.php");
 $pdo = db_connect();
    
    $date=date("Y-m-d H:i:s") . " - "  . $_SESSION["name"] . "  " ;	
	$update_log = $date . $update_log . "&#10";  // 개행문자 Textarea
	 try{		 
    $pdo->beginTransaction();   
    $sql = "update mirae8440.ceiling set ";
    $sql .="update_log=?, " . $data . "=? "; 
  
	 $sql .= " where num=? LIMIT 1" ;        
	   
     $stmh = $pdo->prepare($sql); 

     $stmh->bindValue(1, $update_log, PDO::PARAM_STR);  
	 
	 if($deldata=='') 
         $update_day=date("Y-m-d"); // 현재날짜 2020-01-20 형태로 지정	 
		 else
		   $update_day='';
		   
     $stmh->bindValue(2, $update_day, PDO::PARAM_STR);  
	 $stmh->bindValue(3, $num, PDO::PARAM_STR);
	 
     $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                   
       

   //  header("Location:http://8440.co.kr/mceiling/view.php?num=$num&page=$page&search=$search&find=$find&list=1&process=$process&yearcheckbox=$yearcheckbox&year=$year&sortof=$sortof&cursort=$cursort&stable=1&check=$check&output_check=$output_check&plan_output_check=$plan_output_check&team_check=$team_check&measure_check=$measure_check&check_draw=$check_draw");   
 
 ?>