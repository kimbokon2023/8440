 <?php session_start(); ?>
  
 <meta charset="utf-8">
 <script src="http://5130.co.kr/egimake/make.js"></script>
 <?php
 
 $modify=$_REQUEST["modify"];  
 $parentnum=$_REQUEST["parentnum"]; 	     // 리스트번호
 $upnum=$_REQUEST["upnum"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $num=$_REQUEST["num"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $text1=$_REQUEST["callname"];  // 전달인수가 변수명과 겹치면 오류가 발생해서 받아들이지 않는다.
 $text2=$_REQUEST["text2"];
 $ordercompany=$_REQUEST["ordercompany"];  
 $callname=$_REQUEST["callname"];  
 $cutwidth=$_REQUEST["cutwidth"];  
 $cutheight=$_REQUEST["cutheight"];  
 $number=$_REQUEST["number"];  
 $printside=$_REQUEST["printside"];  
 $direction=$_REQUEST["direction"];  
 $exititem=$_REQUEST["exititem"];  
 $intervalnum=$_REQUEST["intervalnum"];  
 $intervalnumsecond=$_REQUEST["intervalnumsecond"];  
 $memo=$_REQUEST["memo"];  
 $draw=$_REQUEST["draw"];  
 $drawbottom1=$_REQUEST["drawbottom1"];  
 $drawbottom2=$_REQUEST["drawbottom2"];  
 $drawbottom3=$_REQUEST["drawbottom3"];  
 $cover=$_REQUEST["cover"];  
 $exitinterval=$_REQUEST["exitinterval"];  
 $sort=$_REQUEST["sort"]; 
 $outputnum=$_REQUEST["outputnum"]; 
  
 $text3="";
 $text4="";
 $hinge="";

print "<script> alert($modify); </script>";

 require_once("../lib/mydb.php");
  $pdo = db_connect();
  if($modify=='1')
  {
     try{
        $sql = "select * from chandj.egimake where num=?";  // get target record
        $stmh = $pdo->prepare($sql); 
        $stmh->bindValue(1,$num,PDO::PARAM_STR); 
        $stmh->execute(); 
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
     } catch (PDOException $Exception) {
        $pdo->rollBack();
        print "오류: ".$Exception->getMessage();
     } 
	  
try{
        $pdo->beginTransaction();   
        $sql = "update chandj.egimake set text1=?, text2=? ,text3=?, text4=? ,hinge=?, ordercompany=? , callname=? ,cutwidth=? ,cutheight=? ,number=? ,printside=? ,direction=? ,exititem=? ,intervalnum=? ,intervalnumsecond=? ,memo=? ,draw=? ,drawbottom1=? ,drawbottom2=? ,drawbottom3=? ,upnum=?, exitinterval=?, cover=?, outputnum=?   ";
        $sql .= " where num=?  LIMIT 1"; 
 
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $text1, PDO::PARAM_STR);  
     $stmh->bindValue(2, $text2, PDO::PARAM_STR);  
     $stmh->bindValue(3, $text3, PDO::PARAM_STR);       
		
     $stmh->bindValue(4, $text4, PDO::PARAM_STR);        
     $stmh->bindValue(5, $hinge, PDO::PARAM_STR);         	 
     $stmh->bindValue(6, $ordercompany, PDO::PARAM_STR);         	 
     $stmh->bindValue(7, $callname, PDO::PARAM_STR);         	 
     $stmh->bindValue(8, $cutwidth, PDO::PARAM_STR);         	 
     $stmh->bindValue(9, $cutheight, PDO::PARAM_STR);         	 
     $stmh->bindValue(10, $number, PDO::PARAM_STR);         	 
     $stmh->bindValue(11, $printside, PDO::PARAM_STR);         	 
     $stmh->bindValue(12, $direction, PDO::PARAM_STR);         	 
     $stmh->bindValue(13, $exititem, PDO::PARAM_STR);         	 
     $stmh->bindValue(14, $intervalnum, PDO::PARAM_STR);         	 
     $stmh->bindValue(15, $intervalnumsecond, PDO::PARAM_STR);         	 
     $stmh->bindValue(16, $memo, PDO::PARAM_STR);         	 
     $stmh->bindValue(17, $draw, PDO::PARAM_STR);         	 
     $stmh->bindValue(18, $drawbottom1, PDO::PARAM_STR);         	 
     $stmh->bindValue(19, $drawbottom2, PDO::PARAM_STR);           	 
     $stmh->bindValue(20, $drawbottom3, PDO::PARAM_STR);           	 
     $stmh->bindValue(21, $upnum, PDO::PARAM_STR);           	 
     $stmh->bindValue(22, $exitinterval, PDO::PARAM_STR);           	 
     $stmh->bindValue(23, $cover, PDO::PARAM_STR);           	 
     $stmh->bindValue(24, $outputnum, PDO::PARAM_STR);           	 
     $stmh->bindValue(25, $num, PDO::PARAM_STR);          	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   

  }
elseif($modify=='2') // sorting 요청
{
	
	
	
}
else
{	

	
	 // 데이터 신규 등록하는 구간
	 
   try{
     $pdo->beginTransaction();
  	 
     $sql = "insert into chandj.egimake(text1, text2, text3, text4, hinge, ";	 
     $sql .= "ordercompany, callname,cutwidth,cutheight,number,printside,direction,exititem,intervalnum,intervalnumsecond,memo,draw,drawbottom1,drawbottom2,drawbottom3,upnum,exitinterval,cover,outputnum)"; //   
     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )"; //      
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $text1, PDO::PARAM_STR);  
     $stmh->bindValue(2, $text2, PDO::PARAM_STR);  
     $stmh->bindValue(3, $text3, PDO::PARAM_STR);       
		
     $stmh->bindValue(4, $text4, PDO::PARAM_STR);        
     $stmh->bindValue(5, $hinge, PDO::PARAM_STR);         	 
     $stmh->bindValue(6, $ordercompany, PDO::PARAM_STR);         	 
     $stmh->bindValue(7, $callname, PDO::PARAM_STR);         	 
     $stmh->bindValue(8, $cutwidth, PDO::PARAM_STR);         	 
     $stmh->bindValue(9, $cutheight, PDO::PARAM_STR);         	 
     $stmh->bindValue(10, $number, PDO::PARAM_STR);         	 
     $stmh->bindValue(11, $printside, PDO::PARAM_STR);         	 
     $stmh->bindValue(12, $direction, PDO::PARAM_STR);         	 
     $stmh->bindValue(13, $exititem, PDO::PARAM_STR);         	 
     $stmh->bindValue(14, $intervalnum, PDO::PARAM_STR);         	 
     $stmh->bindValue(15, $intervalnumsecond, PDO::PARAM_STR);         	 
     $stmh->bindValue(16, $memo, PDO::PARAM_STR);         	 
     $stmh->bindValue(17, $draw, PDO::PARAM_STR);         	 
     $stmh->bindValue(18, $drawbottom1, PDO::PARAM_STR);         	 
     $stmh->bindValue(19, $drawbottom2, PDO::PARAM_STR);           	 
     $stmh->bindValue(20, $drawbottom3, PDO::PARAM_STR);           	 
     $stmh->bindValue(21, $upnum, PDO::PARAM_STR);           	 
     $stmh->bindValue(22, $exitinterval, PDO::PARAM_STR);           	 
     $stmh->bindValue(23, $cover, PDO::PARAM_STR);           	 
     $stmh->bindValue(24, $outputnum, PDO::PARAM_STR);           	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }
}
   	   header("Location:http://5130.co.kr/egimake/write.php?num=$num&callname=$callname&cutwidth=$cutwidth&cutheight=$cutheight&ordercompany=$ordercompany&outworkplace=$ordercompany&number=$number&printside=$printside&direction=$direction&exititem=$exititem&intervalnum=$intervalnum&intervalnumsecond=$intervalnumsecond&memo=$memo&draw=$draw&drawbottom1=$drawbottom1&drawbottom2=$drawbottom2&drawbottom3=$drawbottom3&text2=$text2&sort=$sort&upnum=$upnum&exitinterval=$exitinterval&cover=$cover&outputnum=$outputnum&parentnum=$parentnum&hinge=$hinge");    // 신규가입일때는 리스트로 이동 ?>
