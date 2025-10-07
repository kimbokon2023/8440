<?php

// 점검일마다 자료를 생성해 주는 루틴입니다.
// 점검일마다 자료를 생성해 주는 루틴입니다.

session_start();
require_once("./lib/mydb.php");
$pdo = db_connect();  

// 배열로 장비점검리스트 불러옴

$num_arr = array();
$checkdate_arr = array();
$item_arr = array();
$term_arr = array();
$check1_arr = array();
$check2_arr = array();
$check3_arr = array();
$check4_arr = array();
$check5_arr = array();
$check6_arr = array();
$check7_arr = array();
$check8_arr = array();
$check9_arr = array();
$check10_arr = array();
$writer_arr = array();

// 자료읽기
$sql="select * from mirae8440.myarealist " ;
	 try{  
// 레코드 전체 sql 설정
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {

	          array_push($num_arr,$row["num"]);	
	          array_push($checkdate_arr, $row["checkdate"]);	
	          array_push($item_arr, $row["item"]);
	          array_push($term_arr, $row["term"]);
	          array_push($check1_arr, $row["check1"]);	
	          array_push($check2_arr, $row["check2"]);		
	          array_push($check3_arr, $row["check3"]);		
	          array_push($check4_arr, $row["check4"]);		
	          array_push($check5_arr, $row["check5"]);		
	          array_push($check6_arr, $row["check6"]);		
	          array_push($check7_arr, $row["check7"]);		
	          array_push($check8_arr, $row["check8"]);		
	          array_push($check9_arr, $row["check9"]);		
	          array_push($check10_arr, $row["check10"]);		
	          array_push($writer_arr, $row["writer"]);	          
			}		 
   } catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}  


    
$todate=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.myarea order by num";

$nowday=date("Y-m-d");   // 현재일자 변수지정   

$counter=0;
$mcnum_arr=array();
$mcno_arr=array();
$mcname_arr=array();
$mcspec_arr=array();
$mcmaker_arr=array();
$mcmain_arr=array();
$mcsub_arr=array();
$qrcode_arr=array();
$questionstep_arr=array();

 try{  
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
		
	  $mcnum_arr[$counter] = $row["num"];
	  $mcno_arr[$counter] = $row["mcno"];
	  $mcname_arr[$counter] = $row["mcname"];
	  $mcspec_arr[$counter] = $row["mcspec"];
	  $mcmaker_arr[$counter] = $row["mcmaker"];
	  $mcmain_arr[$counter] = $row["mcmain"];
	  $mcsub_arr[$counter] = $row["mcsub"];
	  $qrcode_tmp = 'http://8440.co.kr/img/' . $qrcode . '.png' ;
	  $qrcode_arr[$counter] = 'http://8440.co.kr/img/' . $qrcode . '.png' ;
      $questionstep_arr[$counter]=$row["questionstep"];	  	  
	  
      $counter++;		 		
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
}	


// mcmain mcsub 찾아 정하기
for($i=0;$i<count($mcmain_arr);$i++)
{
	if($mcno_arr[$i] == $mcno)
	{
		$mcmain= $mcmain_arr[$i];
		$mcsub= $mcsub_arr[$i];
		// print '찾았다.';
	}
	// print $mcno_arr[$i];
}

    
$todate=date("Y-m-d");   // 현재일자 변수지정   

$sql = "select * from mirae8440.myarea order by num";

$nowday=date("Y-m-d");   // 현재일자 변수지정   
$TodayStr =date("Y-m-d");   // 현재일자 변수지정   

$counter=0;
$num_arr=array();
$mcno_arr=array();
$mcname_arr=array();
$mcspec_arr=array();
$mcmaker_arr=array();
$mcmain_arr=array();
$mcsub_arr=array();
$qrcode_arr=array();

 try{  
 
   $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
   $rowNum = $stmh->rowCount();  

   while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {	
	  $num=$row["num"];
	  $mcno =$row["mcno"];
	  $mcname =$row["mcname"];
	  $mcspec =$row["mcspec"];
	  $mcmaker =$row["mcmaker"];
	  $mcmain =$row["mcmain"];
	  $mcsub =$row["mcsub"];
	  $qrcode =$row["qrcode"];
		
	  $num_arr[$counter] = $row["num"];
	  $mcno_arr[$counter] = $row["mcno"];
	  $mcname_arr[$counter] = $row["mcname"];
	  $mcspec_arr[$counter] = $row["mcspec"];
	  $mcmaker_arr[$counter] = $row["mcmaker"];
	  $mcmain_arr[$counter] = $row["mcmain"];
	  $mcsub_arr[$counter] = $row["mcsub"];
	  $qrcode_tmp = 'http://8440.co.kr/img/' . $qrcode . '.png' ;
	  $qrcode_arr[$counter] = 'http://8440.co.kr/img/' . $qrcode . '.png' ;
	  // print $qrcode_tmp;
   
      $counter++;	
	 		
      }
     }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }	
?> 					
			
  <form id=Form1 name="Form1">
    <input type=hidden id="table" name="table" >
    <input type=hidden id="command" name="command" >
    <input type=hidden id="field" name="field" >
    <input type=hidden id="strtmp" name="strtmp" >
    <input type=hidden id="recnum" name="recnum" >    
    <input type=hidden id="datanum" name="datanum" >  <!-- Data 수 여러개 1,2,3,4 이런식으로 넣을때 사용함 -->
    <input type=hidden id="fieldarr" name="fieldarr[]" >  <!-- Data 수 여러개 1,2,3,4 이런식으로 넣을때 사용함 -->
    <input type=hidden id="arr" name="arr[]" >  <!-- Data 수 여러개 1,2,3,4 이런식으로 넣을때 사용함 -->
  </form>  
			
        
<script>

// 장비리스트 DB생성 기록하기
function creatOfiiceDB()
{ 

// 주간점검일자 추출해서 찾기
	 var arr1 = <?php echo json_encode($num_arr);?> ;
	 var checkdate = <?php echo json_encode($checkdate_arr);?> ;
	 var mcno = <?php echo json_encode($mcno_arr);?> ;   // 장비수량 장비명 laser01 ex)
	 var term = <?php echo json_encode($term_arr);?> ;
	 var mcmain_arr = <?php echo json_encode($mcmain_arr);?> ;
	 var mcsub_arr = <?php echo json_encode($mcsub_arr);?> ;
	 var TodayStr = '<?php echo $TodayStr; ?>' ;
	 
		
	 makeweekdate = 0;
	 makemonthdate = 0;
	 maketwomonthdate = 0;
	 makesixmonthdate = 0;	
	 
	 var Nocheckdate = true ;  // 해당 날짜가 있으면 생성을 중지한다.
	 
	 var Today = new Date();
	 
	 // const Today = );
	 // var Today = new Date('2022-12-23');  // 특정일자로 강제 생산할때 사용할 수 있도록 코딩
	 
	 // 해당날짜가 있으면 생성하지 않는다.
	 for(i=0;i<checkdate.length;i++)
       if(checkdate[i] == TodayStr )
	   {
		   Nocheckdate = false; 
	   }
	   
   
	 console.log(dateFormat(Today));	 
	 console.log(Today.getFullYear());	 
	 console.log(Today.getMonth()+1);	 
	 console.log(Nocheckdate);	 	 
	 
    var year = Today.getFullYear();
    var month = Today.getMonth() + 1;    //1월이 0으로 되기때문에 +1을 함.	 	 
	 
	 result = searchFriday(year, month); 
	 
	 monthdate = result[2]['weekFriday'];  // 금요일	
	 tmp = monthdate.substr(5,2);  // 월추출
	 console.log('월추출 ' + tmp);	 
	 console.log('월간 금요일 : ' + monthdate);	 
	 console.log('오늘 일자 : ' + dateFormat(Today));	 
	 if(monthdate==dateFormat(Today))
	    makemonthdate = 1 ;
	 if((tmp=='02' || tmp=='04' || tmp=='06' || tmp=='08' || tmp=='10' || tmp=='12') && monthdate==dateFormat(Today))
	    maketwomonthdate = 1 ;   // 2개월 데이터추출	 
	 if((tmp=='06' || tmp=='12') && monthdate==dateFormat(Today))
	    makesixmonthdate = 1 ;   // 6개월 데이터추출
	

// 오늘이 금요일이면 자료가 없다면 일자/주간이 없다면 생성함		
	if(getDayOfWeek(Today) =='금' && Nocheckdate )
	{	
	   makeweekdate = 1;		 
       console.log('금요일 찾음'); 
	   for(i=0;i<checkdate.length;i++)
	     {
		   if(checkdate[i]==dateFormat(Today))  // 오늘이 주간 점검일과 같으면
		    {
			 makeweekdate = 0;
			 makemonthdate = 0;
			 maketwomonthdate = 0;
			 makesixmonthdate = 0;	
			 break;			 
			}
		 }
		 
	 console.log('주간 점검일 ' + makeweekdate);
	 console.log('월 점검일 ' + makemonthdate);
	 console.log('2월 점검일 ' + maketwomonthdate);
	 console.log('6월 점검일 ' + makesixmonthdate);		
	 
	 
	 console.log('mcno ' + mcno);		

	 
		 
   if(makeweekdate== 1)  // 기존데이터가 '주간점검' 데이터를 없다면 생성한다
	  {
       for(i=0;i<mcno.length;i++)			  		
			writeOfficeDB('checkdate', dateFormat(Today), 'item', mcno[i] , 'term', '주간','writer', mcmain_arr[i],'writer2', mcsub_arr[i]);
	   }
   if(makemonthdate== 1)  // 기존데이터가 '1개월점검' 데이터를 없다면 생성한다
	  {
       for(i=0;i<mcno.length;i++)			  		
			writeOfficeDB('checkdate', dateFormat(Today), 'item', mcno[i] , 'term', '1개월','writer', mcmain_arr[i],'writer2', mcsub_arr[i]);
	   }
   if(maketwomonthdate== 1)  // 기존데이터가 '2개월점검' 데이터를 없다면 생성한다
	  {
       for(i=0;i<mcno.length;i++)			  		
			writeOfficeDB('checkdate', dateFormat(Today), 'item', mcno[i] , 'term', '2개월','writer', mcmain_arr[i],'writer2', mcsub_arr[i]);
	   }
   if(makesixmonthdate== 1)  // 기존데이터가 '6개월점검' 데이터를 없다면 생성한다
	  {
       for(i=0;i<mcno.length;i++)			  		
			writeOfficeDB('checkdate', dateFormat(Today), 'item', mcno[i] , 'term', '6개월','writer', mcmain_arr[i],'writer2', mcsub_arr[i]);
	   }	
	 }  // end of if
}
		
function writeOfficeDB(fn1,fv1,fn2,fv2,fn3,fv3,fn4,fv4,fn5,fv5) {        
       var fn = new Array();
       var fv = new Array();
	   
	   fn.push(fn1,fn2,fn3,fn4,fn5);
	   fv.push(fv1,fv2,fv3,fv4,fv5);
    // DB 수정 		
	   $("#table").val('myarealist');
	   // $("#command").val('update');
	   $("#command").val('insert');
	   // $("#command").val('delete');  // insert, delete, update
	   $("#fieldarr").val(fn);			   
	   $("#arr").val(fv);
	   // $("#recnum").val(num);
	   // $("#arr").val('free');
   
   // data저장을 위한 ajax처리구문
	$.ajax({
		url: "../proDB_arr.php",
		type: "post",		
		data: $("#Form1").serialize(),
		dataType:"json",
		success : function( data ){
			console.log( data);
		},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
		} 			      		
	   });		


}		


// 1초후 실행하기 주간/월간 등 데이터를 확인해서 생성한다.	
setTimeout(function() {
  creatOfiiceDB();
}, 500);
    
 </script>