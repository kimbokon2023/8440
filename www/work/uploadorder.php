<?php

session_start();

header("Content-Type: application/json");  //json을 사용하기 위해 필요한 구문

function conv_num($num) {
$number = (int)str_replace(',', '', $num);
return $number;
}

function pipetocomma($str) {
$strtmp = str_replace('|', ',', $str);
return $strtmp;
}

isset($_REQUEST["col1"])  ? $col1=$_REQUEST["col1"] :   $col1=''; 
isset($_REQUEST["col2"])  ? $col2=$_REQUEST["col2"] :   $col2=''; 
isset($_REQUEST["col3"])  ? $col3=$_REQUEST["col3"] :   $col3=''; 
isset($_REQUEST["col4"])  ? $col4=$_REQUEST["col4"] :   $col4=''; 
isset($_REQUEST["col5"])  ? $col5=$_REQUEST["col5"] :   $col5=''; 
isset($_REQUEST["col6"])  ? $col6=$_REQUEST["col6"] :   $col6=''; 
isset($_REQUEST["col7"])  ? $col7=$_REQUEST["col7"] :   $col7=''; 
isset($_REQUEST["col8"])  ? $col8=$_REQUEST["col8"] :   $col8=''; 
isset($_REQUEST["col9"])  ? $col9=$_REQUEST["col9"] :   $col9=''; 
isset($_REQUEST["col10"])  ? $col10=$_REQUEST["col10"] :   $col10=''; 
isset($_REQUEST["col11"])  ? $col11=$_REQUEST["col11"] :   $col11=''; 
isset($_REQUEST["col12"])  ? $col12=$_REQUEST["col12"] :   $col12=''; 
isset($_REQUEST["col13"])  ? $col13=$_REQUEST["col13"] :   $col13=''; 
isset($_REQUEST["col14"])  ? $col14=$_REQUEST["col14"] :   $col14=''; 
isset($_REQUEST["col15"])  ? $col15=$_REQUEST["col15"] :   $col15=''; 
isset($_REQUEST["col16"])  ? $col16=$_REQUEST["col16"] :   $col16=''; 
isset($_REQUEST["col17"])  ? $col17=$_REQUEST["col17"] :   $col17=''; 
isset($_REQUEST["col18"])  ? $col18=$_REQUEST["col18"] :   $col18=''; 
isset($_REQUEST["col19"])  ? $col19=$_REQUEST["col19"] :   $col19=''; 

$colarr1 = explode(",",$col1[0]);
$colarr2 = explode(",",$col2[0]);
$colarr3 = explode(",",$col3[0]);
$colarr4 = explode(",",$col4[0]);
$colarr5 = explode(",",$col5[0]);
$colarr6 = explode(",",$col6[0]);
$colarr7 = explode(",",$col7[0]);
$colarr8 = explode(",",$col8[0]);
$colarr9 = explode(",",$col9[0]);
$colarr10 = explode(",",$col10[0]);
$colarr11 = explode(",",$col11[0]);
$colarr12 = explode(",",$col12[0]);
$colarr13 = explode(",",$col13[0]);
$colarr14 = explode(",",$col14[0]);
$colarr15 = explode(",",$col15[0]);
$colarr16 = explode(",",$col16[0]);
$colarr17 = explode(",",$col17[0]);
$colarr18 = explode(",",$col18[0]);
$colarr19 = explode(",",$col19[0]);


$orderday = date("Y-m-d"); // 현재날짜 2022-01-20 형태로 지정	

require_once("../lib/mydb.php");	
$pdo = db_connect();
	 // 데이터 신규 등록하는 구간

for($i=0;$i<count($colarr1);$i++) {	
if($colarr1[$i]!='')
{
   try{
	 // | -> , 로 변환함  
     $colarr1[$i] = pipetocomma($colarr1[$i]);
     $colarr2[$i] = pipetocomma($colarr2[$i]);
     $colarr3[$i] = pipetocomma($colarr3[$i]);
     $colarr4[$i] = pipetocomma($colarr4[$i]);
     $colarr5[$i] = pipetocomma($colarr5[$i]);
     $colarr6[$i] = pipetocomma($colarr6[$i]);
     $colarr7[$i] = pipetocomma($colarr7[$i]);
     $colarr8[$i] = pipetocomma($colarr8[$i]);
     $colarr9[$i] = pipetocomma($colarr9[$i]);
     $colarr10[$i] = pipetocomma($colarr10[$i]);
     $colarr11[$i] = pipetocomma($colarr11[$i]);
     $colarr12[$i] = pipetocomma($colarr12[$i]);
     $colarr13[$i] = pipetocomma($colarr13[$i]);
     $colarr14[$i] = pipetocomma($colarr14[$i]);
     $colarr15[$i] = pipetocomma($colarr15[$i]);
     $colarr16[$i] = pipetocomma($colarr16[$i]);
     $colarr17[$i] = pipetocomma($colarr17[$i]);
     $colarr18[$i] = pipetocomma($colarr18[$i]);
     $colarr19[$i] = pipetocomma($colarr19[$i]);
	 
	 if(trim($colarr9[$i]) == '유')
		   $handrib = "(손끼임 방지쫄대)";
	     else
			$handrib = "";	 
	   
     $firstord = $colarr1[$i] ; 	   
     $tmp1 =  explode("  ",$colarr4[$i]); 	  
	 $firstordman = $tmp1[0];
	 $firstordmantel = $tmp1[2];	 
	 
	 $secondord = '우성스틸' ;
	 $secondordman = '정우성대리' ;
	 $secondordmantel = '010-4615-5787' ;	 
	 
	 
     $tmp1 =  explode("  ",$colarr5[$i]); 	  
	 $chargedman = $tmp1[0];
	 $chargedmantel = $tmp1[2];
	 
	 $hpi =  $colarr6[$i] ;
	 $workplacename =  $colarr7[$i] . $handrib ;
	 $address =  $colarr8[$i] ;
	 $material1 =  $colarr12[$i] ;
	 
	 $widetmp =  explode("\r",$colarr10[$i]); 	   
	 $firstwide = $widetmp[0];
	 $secondwide = $widetmp[1];
	 
	 $widesutmp =  explode("\r",$colarr13[$i]); 	     // 수량을 301 311 등 나눠서 넣는 배열을 분석해서 넣어준다
	 $firstwidesu = isset($widesutmp[0]) ? trim($widesutmp[0]) : '';
	 $secondwidesu = isset($widesutmp[1]) ? trim($widesutmp[1]) : '';
	 $testday = $colarr18[$i] ;
	 $checkstep = "" ;
	 
	 // 변수 초기화
	 $widejamb = '';
	 $normaljamb = '';
	 
	 if(trim($firstwide)=='311' || trim($firstwide)=='310')
		   $widejamb = $firstwidesu ;
	 if(trim($firstwide)=='301')
		   $normaljamb = $secondwidesu ; 
	 
	 if(trim($secondwide) =='311' || trim($secondwide)=='310')
		   $widejamb = $secondwidesu ;
	 if(trim($secondwide) =='301')
		   $normaljamb = $secondwidesu ;	 
	
	 if(conv_num($colarr14[$i]) > 0 )
	    $smalljamb = $colarr14[$i] ;
      else		  
	    $smalljamb = '';

	 $memo = '검사일 : ' . $colarr18[$i] . ' ' . $handrib ;

	 $first_writer=$_SESSION["name"] . " _" . date("Y-m-d H:i:s");  // 최초등록자 기록 	 
	   
     $pdo->beginTransaction();
  	 
     $sql = "insert into mirae8440.work(" ;
     $sql .="orderday, firstord, firstordman, firstordmantel, secondord, secondordman, secondordmantel, chargedman, chargedmantel, hpi, workplacename, address, material1,  widejamb, normaljamb, smalljamb, memo, regist_day, first_writer, checkstep, testday  ";     
     $sql .= ") ";
	 

     $sql .= " values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "; // 총 10
     $sql .=        "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )  ";  // 총 9
	   
     $stmh = $pdo->prepare($sql); 
     $stmh->bindValue(1, $orderday, PDO::PARAM_STR);             
     $stmh->bindValue(2, $firstord, PDO::PARAM_STR);             
     $stmh->bindValue(3, $firstordman, PDO::PARAM_STR);             
     $stmh->bindValue(4, $firstordmantel, PDO::PARAM_STR);             
     $stmh->bindValue(5, $secondord, PDO::PARAM_STR);                     	 
     $stmh->bindValue(6, $secondordman, PDO::PARAM_STR);                     	 
     $stmh->bindValue(7, $secondordmantel, PDO::PARAM_STR);                     	 
     $stmh->bindValue(8, $chargedman, PDO::PARAM_STR);                     	 
     $stmh->bindValue(9, $chargedmantel, PDO::PARAM_STR);                     	 
     $stmh->bindValue(10, $hpi, PDO::PARAM_STR);                     	 
     $stmh->bindValue(11, $workplacename, PDO::PARAM_STR);                     	 
     $stmh->bindValue(12, $address, PDO::PARAM_STR);                     	 
     $stmh->bindValue(13, $material1, PDO::PARAM_STR);                     	 
     $stmh->bindValue(14, $widejamb, PDO::PARAM_STR);                     	 
     $stmh->bindValue(15, $normaljamb, PDO::PARAM_STR);                     	 
     $stmh->bindValue(16, $smalljamb, PDO::PARAM_STR);                     	 
     $stmh->bindValue(17, $memo, PDO::PARAM_STR);                     	 
     $stmh->bindValue(18, $regist_day, PDO::PARAM_STR);                     	 
     $stmh->bindValue(19, $first_writer, PDO::PARAM_STR);                     	 	 
     $stmh->bindValue(20, $checkstep, PDO::PARAM_STR);                     	 	 
     $stmh->bindValue(21, $testday, PDO::PARAM_STR);                     	 	 
	 
     $stmh->execute();
     $pdo->commit(); 
     } catch (PDOException $Exception) {
          $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
     }     
  }
}
//각각의 정보를 하나의 배열 변수에 넣어준다.
$data = array(
		"colarr9" =>         $colarr9,
);

//json 출력
echo(json_encode($data, JSON_UNESCAPED_UNICODE));

?>