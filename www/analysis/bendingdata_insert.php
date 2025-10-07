 <?php   
  session_start();   
 $level= $_SESSION["level"];
 if(!isset($_SESSION["level"]) || $level>=5) {
         echo "<script> alert('관리자 승인이 필요합니다.') </script>";
		 sleep(2);
         header ("Location:http://5130.co.kr/login/logout.php");
         exit;
   }
 $modify=$_REQUEST["modify"];
 $outputnum=$_REQUEST["outputnum"];
 $callback=$_REQUEST["callback"];  // 출고현황에서 호출했는지 체크함.
 
  if(isset($_REQUEST["page"]))
    $page=$_REQUEST["page"];
  else 
    $page=1;   // 1로 설정해야 함
 if(isset($_REQUEST["mode"]))  //modify_form에서 호출할 경우
    $mode=$_REQUEST["mode"];
 else 
    $mode="";
 
 if(isset($_REQUEST["num"]))
    $num=$_REQUEST["num"];
 else 
    $num="";

  if(isset($_REQUEST["sort"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $sort=$_REQUEST["sort"];
  else
   $sort="1";

 if(isset($_REQUEST["upnum"]))
    $upnum=$_REQUEST["upnum"];
 else 
    $upnum=0;

  if(isset($_REQUEST["search"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $search=$_REQUEST["search"];
  else
   $search="";
  if(isset($_REQUEST["find"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $find=$_REQUEST["find"];
  else
   $find="";
  if(isset($_REQUEST["process"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $process=$_REQUEST["process"];
  else
   $process="전체";
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];

// 이미지파일 저장에 대한 것

  if(isset($_REQUEST["copied_file_name"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $copied_file_name=$_REQUEST["copied_file_name"];
  else
   $copied_file_name="";

  if(isset($_REQUEST["uploaded_file"]))  //수정 버튼을 클릭해서 호출했는지 체크
   $uploaded_file=$_REQUEST["uploaded_file"];
  else
   $uploaded_file="";

  $files = $_FILES["upfile"];    //첨부파일	
  $count = count($files["name"]); 
  if($count>0)
  {
  $upload_dir = '../img/uploads/';   //물리적 저장위치    

			 $upfile_name     = $files["name"];         
			 $upfile_tmp_name = $files["tmp_name"];
			 $upfile_type     = $files["type"];
			 $upfile_size     = $files["size"];
			 $upfile_error    = $files["error"];
			 $file = explode(".", $upfile_name);
			 $file_name = $file[0];
			 $file_ext  = $file[1];

			 if (!$upfile_error)
			 {
			$new_file_name = date("Y_m_d_H_i_s");
			$new_file_name = $new_file_name."_";
			$copied_file_name = $new_file_name.".".$file_ext;      
			$uploaded_file = $upload_dir . $copied_file_name;

			if( $upfile_size  > 12000000 ) {

			print("
			 <script>
			   alert('업로드 파일 크기가 지정된 용량(10MB)을 초과합니다!<br>파일 크기를 체크해주세요! ');
			   history.back();
			 </script>
			");
			exit;
			}

			if (!move_uploaded_file($upfile_tmp_name, $uploaded_file))
			{
			print("<script>
					alert('파일을 지정한 디렉토리에 복사하는데 실패했습니다.');
					history.back();
				  </script>");
			 exit;
			}

	 }
  }
  
$steeltype=$_REQUEST["steeltype"];
$steel_alias=$_REQUEST["steel_alias"];

$a1=$_REQUEST["a1"];
$a2=$_REQUEST["a2"];
$a3=$_REQUEST["a3"];
$a4=$_REQUEST["a4"];
$a5=$_REQUEST["a5"];
$a6=$_REQUEST["a6"];
$a7=$_REQUEST["a7"];
$a8=$_REQUEST["a8"];
$a9=$_REQUEST["a9"];
$a10=$_REQUEST["a10"];
$a11=$_REQUEST["a11"];
$a12=$_REQUEST["a12"];
$a13=$_REQUEST["a13"];
$a14=$_REQUEST["a14"];
$a15=$_REQUEST["a15"];
$a16=$_REQUEST["a16"];
$a17=$_REQUEST["a17"];
$a18=$_REQUEST["a18"];
$a19=$_REQUEST["a19"];
$a20=$_REQUEST["a20"];
$a21=$_REQUEST["a21"];
$a22=$_REQUEST["a22"];
$a23=$_REQUEST["a23"];
$a24=$_REQUEST["a24"];
$a25=$_REQUEST["a25"];
$b1=$_REQUEST["b1"];
$b2=$_REQUEST["b2"];
$b3=$_REQUEST["b3"];
$b4=$_REQUEST["b4"];
$b5=$_REQUEST["b5"];
$b6=$_REQUEST["b6"];
$b7=$_REQUEST["b7"];
$b8=$_REQUEST["b8"];
$b9=$_REQUEST["b9"];
$b10=$_REQUEST["b10"];
$b11=$_REQUEST["b11"];
$b12=$_REQUEST["b12"];
$b13=$_REQUEST["b13"];
$b14=$_REQUEST["b14"];
$b15=$_REQUEST["b15"];
$b16=$_REQUEST["b16"];
$b17=$_REQUEST["b17"];
$b18=$_REQUEST["b18"];
$b19=$_REQUEST["b19"];
$b20=$_REQUEST["b20"];
$b21=$_REQUEST["b21"];
$b22=$_REQUEST["b22"];
$b23=$_REQUEST["b23"];
$b24=$_REQUEST["b24"];
$b25=$_REQUEST["b25"];
$c1=$_REQUEST["c1"];
$c2=$_REQUEST["c2"];
$c3=$_REQUEST["c3"];
$c4=$_REQUEST["c4"];
$c5=$_REQUEST["c5"];
$c6=$_REQUEST["c6"];
$c7=$_REQUEST["c7"];
$c8=$_REQUEST["c8"];
$c9=$_REQUEST["c9"];
$c10=$_REQUEST["c10"];
$c11=$_REQUEST["c11"];
$c12=$_REQUEST["c12"];
$c13=$_REQUEST["c13"];
$c14=$_REQUEST["c14"];
$c15=$_REQUEST["c15"];
$c16=$_REQUEST["c16"];
$c17=$_REQUEST["c17"];
$c18=$_REQUEST["c18"];
$c19=$_REQUEST["c19"];
$c20=$_REQUEST["c20"];
$c21=$_REQUEST["c21"];
$c22=$_REQUEST["c22"];
$c23=$_REQUEST["c23"];
$c24=$_REQUEST["c24"];
$c25=$_REQUEST["c25"];
$d1=$_REQUEST["d1"];
$d2=$_REQUEST["d2"];
$d3=$_REQUEST["d3"];
$d4=$_REQUEST["d4"];
$d5=$_REQUEST["d5"];
$d6=$_REQUEST["d6"];
$d7=$_REQUEST["d7"];
$d8=$_REQUEST["d8"];
$d9=$_REQUEST["d9"];
$d10=$_REQUEST["d10"];
$d11=$_REQUEST["d11"];
$d12=$_REQUEST["d12"];
$d13=$_REQUEST["d13"];
$d14=$_REQUEST["d14"];
$d15=$_REQUEST["d15"];
$d16=$_REQUEST["d16"];
$d17=$_REQUEST["d17"];
$d18=$_REQUEST["d18"];
$d19=$_REQUEST["d19"];
$d20=$_REQUEST["d20"];
$d21=$_REQUEST["d21"];
$d22=$_REQUEST["d22"];
$d23=$_REQUEST["d23"];
$d24=$_REQUEST["d24"];
$d25=$_REQUEST["d25"];
$e1=$_REQUEST["e1"];
$e2=$_REQUEST["e2"];
$e3=$_REQUEST["e3"];
$e4=$_REQUEST["e4"];
$e5=$_REQUEST["e5"];
$e6=$_REQUEST["e6"];
$e7=$_REQUEST["e7"];
$e8=$_REQUEST["e8"];
$e9=$_REQUEST["e9"];
$e10=$_REQUEST["e10"];
$e11=$_REQUEST["e11"];
$e12=$_REQUEST["e12"];
$e13=$_REQUEST["e13"];
$e14=$_REQUEST["e14"];
$e15=$_REQUEST["e15"];
$e16=$_REQUEST["e16"];
$e17=$_REQUEST["e17"];
$e18=$_REQUEST["e18"];
$e19=$_REQUEST["e19"];
$e20=$_REQUEST["e20"];
$e21=$_REQUEST["e21"];
$e22=$_REQUEST["e22"];
$e23=$_REQUEST["e23"];
$e24=$_REQUEST["e24"];
$e25=$_REQUEST["e25"];
$f1=$_REQUEST["f1"];
$f2=$_REQUEST["f2"];
$f3=$_REQUEST["f3"];
$f4=$_REQUEST["f4"];
$f5=$_REQUEST["f5"];
$f6=$_REQUEST["f6"];
$f7=$_REQUEST["f7"];
$f8=$_REQUEST["f8"];
$f9=$_REQUEST["f9"];
$f10=$_REQUEST["f10"];
$f11=$_REQUEST["f11"];
$f12=$_REQUEST["f12"];
$f13=$_REQUEST["f13"];
$f14=$_REQUEST["f14"];
$f15=$_REQUEST["f15"];
$f16=$_REQUEST["f16"];
$f17=$_REQUEST["f17"];
$f18=$_REQUEST["f18"];
$f19=$_REQUEST["f19"];
$f20=$_REQUEST["f20"];
$f21=$_REQUEST["f21"];
$f22=$_REQUEST["f22"];
$f23=$_REQUEST["f23"];
$f24=$_REQUEST["f24"];
$f25=$_REQUEST["f25"];
$g1=$_REQUEST["g1"];
$g2=$_REQUEST["g2"];
$g3=$_REQUEST["g3"];
$g4=$_REQUEST["g4"];
$g5=$_REQUEST["g5"];
$g6=$_REQUEST["g6"];
$g7=$_REQUEST["g7"];
$g8=$_REQUEST["g8"];
$g9=$_REQUEST["g9"];
$g10=$_REQUEST["g10"];
$g11=$_REQUEST["g11"];
$g12=$_REQUEST["g12"];
$g13=$_REQUEST["g13"];
$g14=$_REQUEST["g14"];
$g15=$_REQUEST["g15"];
$g16=$_REQUEST["g16"];
$g17=$_REQUEST["g17"];
$g18=$_REQUEST["g18"];
$g19=$_REQUEST["g19"];
$g20=$_REQUEST["g20"];
$g21=$_REQUEST["g21"];
$g22=$_REQUEST["g22"];
$g23=$_REQUEST["g23"];
$g24=$_REQUEST["g24"];
$g25=$_REQUEST["g25"];
$h1=$_REQUEST["h1"];
$h2=$_REQUEST["h2"];
$h3=$_REQUEST["h3"];
$h4=$_REQUEST["h4"];
$h5=$_REQUEST["h5"];
$h6=$_REQUEST["h6"];
$h7=$_REQUEST["h7"];
$h8=$_REQUEST["h8"];
$h9=$_REQUEST["h9"];
$h10=$_REQUEST["h10"];
$h11=$_REQUEST["h11"];
$h12=$_REQUEST["h12"];
$h13=$_REQUEST["h13"];
$h14=$_REQUEST["h14"];
$h15=$_REQUEST["h15"];
$h16=$_REQUEST["h16"];
$h17=$_REQUEST["h17"];
$h18=$_REQUEST["h18"];
$h19=$_REQUEST["h19"];
$h20=$_REQUEST["h20"];
$h21=$_REQUEST["h21"];
$h22=$_REQUEST["h22"];
$h23=$_REQUEST["h23"];
$h24=$_REQUEST["h24"];
$h25=$_REQUEST["h25"];

       
 require_once("../lib/mydb.php");
 $pdo = db_connect();
     
 if ($mode=="modify" ){ 
     $num_checked = count($_REQUEST['del_file']);
 
     if($num_checked==1)      // delete checked item
	 {
		    unlink($uploaded_file);
			$uploaded_file="";
			$copied_file_name="";
	  }
 
  // 데이터 수정구간	 
   try{
     $pdo->beginTransaction();
	  
		 $sql = "  update chandj.atbending set steeltype=?, steel_alias=?, copied_file_name=?, uploaded_file=?,";
	 	 
		 for ($j=0;$j<8;$j++) {
		   switch ($j) {
			 case 0:
			   $str='A';
			   break;
			 case 1:
			   $str='B';
			   break;			   
			 case 2:
			   $str='C';
			   break;			   
			 case 3:
			   $str='D';
			   break;			   
			 case 4:
			   $str='E';
			   break;			   
			 case 5:
			   $str='F';
			   break;			   
			 case 6:
			   $str='G';
			   break;			   
			 case 7:
			   $str='H';	
			   break;			   
		 }
	    for($i=0;$i<25;$i++) {
			$temp=$i+1;
  	        $sql .= strtolower($str) . $temp . "=? ,";
			
		}
		 }

     $sql = substr($sql,1,strlen($sql)-2);  // 마지막 , 제거

     $sql .= ", indate=? where num=? limit 1";	  

	 print $sql;
	 print "<br>";
	    

	   $stmh = $pdo->prepare($sql); 
	   $stmh->bindValue(1, $steeltype, PDO::PARAM_STR); 
	   $stmh->bindValue(2, $steel_alias, PDO::PARAM_STR); 
       $stmh->bindValue(3, $copied_file_name, PDO::PARAM_STR); 
       $stmh->bindValue(4, $uploaded_file, PDO::PARAM_STR);  
	   

	for ($j=0;$j<8;$j++) {
		 
	   switch ($j) {
			 case 0:
			   $str='A';
			   break;
			 case 1:
			   $str='B';
			   break;			   
			 case 2:
			   $str='C';
			   break;			   
			 case 3:
			   $str='D';
			   break;			   
			 case 4:
			   $str='E';
			   break;			   
			 case 5:
			   $str='F';
			   break;			   
			 case 6:
			   $str='G';
			   break;			   
			 case 7:
			   $str='H';	
			   break;			   
		 }	
		 
   	    $str=strtolower($str);
	    $arr=compact($str."1",$str."2",$str."3",$str."4",$str."5",$str."6",$str."7",$str."8",$str."9",$str."10",$str."11",$str."12",$str."13",$str."14",$str."15",$str."16",$str."17",$str."18",$str."19",$str."20",$str."21",$str."21",$str."22",$str."23",$str."24",$str."25");
	    for($i=0;$i<25;$i++) {
		 $temp= $str . ($i+1);
		 $cc=($j*25)+$i+5;
         $stmh->bindValue($cc, $arr[$temp], PDO::PARAM_STR);  
		 print $cc;
		}
		print "<br>";
		 } 	   	
     $now= date('Y-m-d'); //now();
	 
	 
		 
     $stmh->bindValue(205, $now, PDO::PARAM_STR);  		  // $num에 대한 값이 신규랑 다르다. 반드시 넣어야 에러가 없다
     $stmh->bindValue(206, $num, PDO::PARAM_STR);  		  // $num에 대한 값이 신규랑 다르다. 반드시 넣어야 에러가 없다
		 
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
	 
   try{
     $pdo->beginTransaction();
	  
		 $sql = "  insert into chandj.atbending (steeltype, steel_alias, copied_file_name, uploaded_file,";
	 	 
		 for ($j=0;$j<8;$j++) {
		   switch ($j) {
			 case 0:
			   $str='A';
			   break;
			 case 1:
			   $str='B';
			   break;			   
			 case 2:
			   $str='C';
			   break;			   
			 case 3:
			   $str='D';
			   break;			   
			 case 4:
			   $str='E';
			   break;			   
			 case 5:
			   $str='F';
			   break;			   
			 case 6:
			   $str='G';
			   break;			   
			 case 7:
			   $str='H';	
			   break;			   
		 }
	    for($i=0;$i<25;$i++) {
			$temp=$i+1;
  	        $sql .= strtolower($str) . $temp . " ,";
			
		}
		 }

     $sql = substr($sql,1,strlen($sql)-2);  // 마지막 , 제거

     $sql .= ", indate)";	 // 마지막에 등록일 추가
	 
	 $sql .= " values(?, ?, ?, ?, "; 
	 
	 for ($j=0;$j<8;$j++) {
		 
	    for($i=0;$i<25;$i++) {
       	 $sql .= " ? ,"; 			
		}
		 } 
      $sql = substr($sql,1,strlen($sql)-2);  // 마지막 , 제거

     $sql .= ",now() )";	
	 print $sql;
	 print "<br>";
	 print $steeltype;
	 print $a1;
	 
	 
	    

	   $stmh = $pdo->prepare($sql); 
	   $stmh->bindValue(1, $steeltype, PDO::PARAM_STR); 
	   $stmh->bindValue(2, $steel_alias, PDO::PARAM_STR); 
       $stmh->bindValue(3, $copied_file_name, PDO::PARAM_STR); 
       $stmh->bindValue(4, $uploaded_file, PDO::PARAM_STR);  
	   

	for ($j=0;$j<8;$j++) {
		 
	   switch ($j) {
			 case 0:
			   $str='A';
			   break;
			 case 1:
			   $str='B';
			   break;			   
			 case 2:
			   $str='C';
			   break;			   
			 case 3:
			   $str='D';
			   break;			   
			 case 4:
			   $str='E';
			   break;			   
			 case 5:
			   $str='F';
			   break;			   
			 case 6:
			   $str='G';
			   break;			   
			 case 7:
			   $str='H';	
			   break;			   
		 }	
		 
   	    $str=strtolower($str);
	    $arr=compact($str."1",$str."2",$str."3",$str."4",$str."5",$str."6",$str."7",$str."8",$str."9",$str."10",$str."11",$str."12",$str."13",$str."14",$str."15",$str."16",$str."17",$str."18",$str."19",$str."20",$str."21",$str."21",$str."22",$str."23",$str."24",$str."25");
	    for($i=0;$i<25;$i++) {
		 $temp= $str . ($i+1);
		 $cc=$j*25+$i+5;
         $stmh->bindValue($cc, $arr[$temp], PDO::PARAM_STR);  
		}

		 } 	 

  //   $now= date('Y-m-d'); //now(); 
   //  $stmh->bindValue(204, $now, PDO::PARAM_STR);  	 
     $stmh->execute();
     $pdo->commit(); 	 
	 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }   
   }
 	 
    if($mode=="modify") 
        header("Location:http://5130.co.kr/analysis/bendingdata_view.php?num=$num&upnum=$upnum&outputnum=$outputnum&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date&outworkplace=$item_outworkplace&sort=$sort&parentnum=$num&callback=$callback");   
   else 
	 header("Location:http://5130.co.kr/analysis/bendingdata_view.php?num=0&page=$page&search=$search&find=$find&process=$process&yearcheckbox=$yearcheckbox&year=$year&fromdate=$fromdate&todate=$todate&separate_date=$separate_date&outworkplace=$item_outworkplace&sort=$sort&parentnum=$num&callback=$callback");    

 ?>