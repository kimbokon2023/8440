 <?php   
session_start();    

require_once("../lib/mydb.php");
$pdo = db_connect();  

if(isset($_REQUEST["mode"]))
 $mode=$_REQUEST["mode"];
else 
 $mode=""; 

if(isset($_REQUEST["num"]))
 $num=$_REQUEST["num"];
else 
 $num=""; 

if($mode!='modify')
{ 
	$cookie=  json_decode($_COOKIE["ordercart"]); ;  

	var_dump($cookie);

	$recordnum = $cookie!=null ? count($cookie) : 0 ;

	var_dump($recordnum);

	if($cookie!=null) // 쿠키가 있을때만 실행
	foreach ($cookie as $coo) {
	 try{
	   $sql = "select * from mirae8440.shopitem order by dporder asc";

	   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
	   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
				  $num=$row["num"];

		if($coo->id == $num)  // 작품등록번호가 같은 경우 처리
		{		
				  $dporder=$row["dporder"];			  			  		  
				  $sale=$row["sale"];			  
				  $price=$row["price"];
				  $saleprice=$row["saleprice"];
				  $quantity = $coo->quantity ;
				  
				  $orderlist .= "id" . $num . " quantity" .  $quantity . " saleprice" .  $saleprice . " ," ;

			} // end of if statement `작품번호 같은가? 
		  }
		 }catch (PDOException $Exception) {
		   print "오류: ".$Exception->getMessage();
		 }		 
		  $count++;	 
		} // end of while 레코드수	 

	$payment = '결재확인중';			
	// 마지막 콤마 제거
	$orderlist = substr($orderlist, 0, -1);
	var_dump($orderlist);	
	$deliveryfee= $_REQUEST["deliveryfeeval"];  // 3000원으로 배송비 일단 세팅함   
}
  else
  {
   $payment= $_REQUEST["payment"];  
   $orderlist= $_REQUEST["orderlist"];    
   $deliveryfee= $_REQUEST["deliveryfee"];  // 3000원으로 배송비 일단 세팅함   
  }

$password = $_REQUEST["password"];
$state = $_REQUEST["state"];
$orderdate = $_REQUEST["orderdate"];
$name= $_REQUEST["name"]; 
$tel= $_REQUEST["tel"]; 
$receivename= $_REQUEST["receivename"]; 
$receivetel= $_REQUEST["receivetel"]; 
$email= $_REQUEST["email"]; 
$address= $_REQUEST["address"]; 
$address2= $_REQUEST["address2"]; 
$request= $_REQUEST["request"]; 
$code= $_REQUEST["code"];


print '번호 : ' . $num;
print $request;


require_once("../lib/mydb.php");
$pdo = db_connect();
      
 if ($mode=="modify"){
      
     try{
        $pdo->beginTransaction();   
        $sql = " update mirae8440.shop set password=?, orderlist=?, name=?, tel=?, receivename=?, receivetel=?, email=?, address=?, address2=?, request=?, code=?, deliveryfee=?, state=?, payment=? ";		
        $sql .= " where num=? LIMIT 1";		

     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $password, PDO::PARAM_STR);  
     $stmh->bindValue(2, $orderlist, PDO::PARAM_STR);  
     $stmh->bindValue(3, $name, PDO::PARAM_STR);  
     $stmh->bindValue(4, $tel, PDO::PARAM_STR);  
     $stmh->bindValue(5, $receivename, PDO::PARAM_STR);  
     $stmh->bindValue(6, $receivetel, PDO::PARAM_STR);  
     $stmh->bindValue(7, $email, PDO::PARAM_STR);  
     $stmh->bindValue(8, $address, PDO::PARAM_STR);  
     $stmh->bindValue(9, $address2, PDO::PARAM_STR);  
     $stmh->bindValue(10, $request, PDO::PARAM_STR);  
     $stmh->bindValue(11, $code, PDO::PARAM_STR);  
     $stmh->bindValue(12, $deliveryfee, PDO::PARAM_STR);    // orderdate 삭제 수정할 필요없음     
     $stmh->bindValue(13, $state, PDO::PARAM_STR);  
     $stmh->bindValue(14, $payment, PDO::PARAM_STR);  

    $stmh->bindValue(15, $num, PDO::PARAM_STR);           //고유키값이 같나?의 의미로 ?로 num으로 맞춰야 합니다. where 구문 
	 
	 $stmh->execute();
     $pdo->commit(); 
        } catch (PDOException $Exception) {
           $pdo->rollBack();
           print "오류: ".$Exception->getMessage();
       }                         
       
 } else	{
	 
	 // 데이터 신규 등록하는 구간		 
	 $state = '주문완료';
   try{ 
	   
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.shop(password, orderlist, name, tel, receivename, receivetel, email, address, address2, request, code, deliveryfee, orderdate, state, payment) ";
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $password, PDO::PARAM_STR);  
     $stmh->bindValue(2, $orderlist, PDO::PARAM_STR);  
     $stmh->bindValue(3, $name, PDO::PARAM_STR);  
     $stmh->bindValue(4, $tel, PDO::PARAM_STR);  
     $stmh->bindValue(5, $receivename, PDO::PARAM_STR);  
     $stmh->bindValue(6, $receivetel, PDO::PARAM_STR);  
     $stmh->bindValue(7, $email, PDO::PARAM_STR);  
     $stmh->bindValue(8, $address, PDO::PARAM_STR);  
     $stmh->bindValue(9, $address2, PDO::PARAM_STR);  
     $stmh->bindValue(10, $request, PDO::PARAM_STR);  
     $stmh->bindValue(11, $code, PDO::PARAM_STR);  
     $stmh->bindValue(12, $deliveryfee, PDO::PARAM_STR);  
     $stmh->bindValue(13, date("Y-m-d H:i:s"), PDO::PARAM_STR);  
     $stmh->bindValue(14, $state, PDO::PARAM_STR);  
     $stmh->bindValue(15, $payment, PDO::PARAM_STR);  
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
if($mode=="modify")
  {	
   print 'admin 이동 닫기';
   	echo("<script language='javascript'>opener.document.location.reload();	</script>"); 
   	echo("<script language='javascript'>self.close(); 	</script>");    
  }
	 else	
	 {		 
      header("Location:http://8440.co.kr/shop/delivery.php");  
	 }
 ?>
 

 

