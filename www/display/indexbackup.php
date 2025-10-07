
<?php

 session_start();

   $level= $_SESSION["level"];
   $id_name= $_SESSION["name"];   
 if(!isset($_SESSION["level"]) || $level>8) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(2);
         header ("Location:http://8440.co.kr/login/logout.php");
         exit;
   }  

 ?>
 <!DOCTYPE HTML>
 <html>
 <head>
 <meta charset="UTF-8">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <!-- JavaScript -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/alertify.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/default.min.css"/>
<!-- Semantic UI theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/semantic.min.css"/>
<!-- Bootstrap theme -->
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.12.0/build/css/themes/bootstrap.min.css"/> 
<link rel="stylesheet" href="../css/display.css" type="text/css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />

 <title> 미래기업 쟘공사 관리시스템 </title>
 </head>

 <?php
 
	  
	  
	  
  require_once("../lib/mydb.php");
  $pdo = db_connect();	

$now = date("Y-m-d",time()) ;
  
		$a="   where endworkday='$now' order by num desc ";  
  
	   $sql="select * from mirae8440.work " . $a; 	
   
	 try{  
	  $stmh = $pdo->query($sql);            // 검색조건에 맞는글 stmh
      $temp1=$stmh->rowCount();
	      
				$total_row = $temp1;     // 전체 글수			
	  
			 
			?>
		 
<body>
     <div  class="container-fluid"> 	 
<div id="top-menu">
<?php
    if(!isset($_SESSION["userid"]))
	{
?>
          <a href="../login/login_form.php">로그인</a> | <a href="../member/insertForm.php">회원가입</a>
<?php
	}
	else
	 {
?>
			<div class="row">
           <div class="col-6"> 
		         <h3 class="display-5 font-center text-left"> 	
		
<?php
	 }
?>
</h3>
</div> </div> 
<br>





<form id="board_form" name="board_form" method="get" action="index.php?mode=search&search=<?=$search?>&find=<?=$find?>&process=<?=$process?>&yearcheckbox=<?=$yearcheckbox?>&year=<?=$year?>&check=<?=$check?>&output_check=<?=$output_check?>&plan_output_check=<?=$plan_output_check?>&team_check=<?=$team_check?>&measure_check=<?=$measure_check?>&page=<?=$page?>&cursort=<?=$cursort?>&sortof=<?=$sortof?>&stable=<?=$stable?>&scale=10000">  

			<h1 class="animate__animated animate__bounce"> 쟘(Jamb) 금일 출고리스트 </H1> 

			<br>
			<div class="row">
			  <!--	<button id="btn-1" type="button" class="btn btn-default" >Open</button>
					  <!-- Modal -->
				  <div class="modal fade" id="myModal" role="dialog">
				  </div> 
				  </div>
			<div class="row">
           <div class="col">
		         <h5 class="display-5 text-left"> 	<div class="animate__animated animate__bounce animate__faster">	   
             총 <?= $total_row ?> 건의 출고예정이 있습니다.  </div>	  </h5>
        </div>   
		</div>		
<br>		
		<input type="hidden" id="check" name="check" value="<?=$check?>" size="5" > 		
		<input type="hidden" id="plan_output_check" name="plan_output_check" value="<?=$plan_output_check?>" size="5" > 				
		<input type="hidden" id="output_check" name="output_check" value="<?=$output_check?>" size="5" > 				
		<input type="hidden" id="team_check" name="team_check" value="<?=$team_check?>" size="5" > 				
		<input type="hidden" id="measure_check" name="measure_check" value="<?=$measure_check?>" size="5" > 				
		<input type="hidden" id="sqltext" name="sqltext" value="<?=$sqltext?>" > 				
	                
		<?php
			?>
        <div id="list_search5"></div>

        <div id="list_search5"></div> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <div id="list_search11">		
		    
      </div> <!-- end of list_search11  -->
<div id="list_search12">			  	
	  
      </div> <!-- end of list_search12  -->

	        <div class="row">
        <div class="col-1">
        <h5 class="display-5 font-center text-center text-muted"> No </h5>
        </div>
		<?php
		   if($check!='1')
			     print ' <div class="col-2">
							<h5 class="display-5 font-center text-center text-muted"> 출고일</h5> ';
	           else
				 			     print ' <div class="col-2">
							<h5 class="display-5 font-center text-center text-danger text-muted "> 출고예정일 </h5> ';
			?>							
        </div>
        <div class="col-6">
      <h5 class="display-5 font-center text-center text-muted"> 현장명 </h5>
        </div>
        <div class="col-2">
      <h5 class="display-5 font-center text-center text-muted"> 받는분 </h5>
        </div>

      </div>
    
    <?php  

			$start_num=$total_row;    // 페이지당 표시되는 첫번째 글순번
	       $item=array();
	       $man=array();
	       $sumStr=array();
		      $counter=0;
	       while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
		   $counter++;
			$item[$counter]=$row["workplacename"];   
			$man[$counter]=$row["worker"];   
			   
			  $num=$row["num"];

			  $checkstep=$row["checkstep"];
			  $workplacename=$row["workplacename"];
			  $address=$row["address"];
			  $firstord=$row["firstord"];
			  $firstordman=$row["firstordman"];
			  $firstordmantel=$row["firstordmantel"];
			  $secondord=$row["secondord"];
			  $secondordman=$row["secondordman"];
			  $secondordmantel=$row["secondordmantel"];
			  $chargedman=$row["chargedman"];
			  $orderday=$row["orderday"];
			  $measureday=$row["measureday"];
			  $drawday=$row["drawday"];
			  $deadline=$row["deadline"];
			  $delicompany=$row["delicompany"];
			  
			  $workday=$row["workday"];
			  $startday=$row["startday"];
			  $testday=$row["testday"];
			  $worker=$row["worker"];
			  $endworkday=$row["endworkday"];
			  $material1=$row["material1"];
			  $material2=$row["material2"];
			  $material3=$row["material3"];
			  $material4=$row["material4"];
			  $material5=$row["material5"];
			  $material6=$row["material6"];
			  $widejamb=$row["widejamb"];
			  $normaljamb=$row["normaljamb"];
			  $smalljamb=$row["smalljamb"];
			  $memo=$row["memo"];
			  $regist_day=$row["regist_day"];
			  $update_day=$row["update_day"];
			  $demand=$row["demand"];
			  
			  $sum[0] = $sum[0] + (int)$widejamb;
			  $sum[1] += (int)$normaljamb;
			  $sum[2] += (int)$smalljamb;
			  $sum[3] += (int)$widejamb + (int)$normaljamb + (int)$smalljamb;
			  
			  $workitem="";
				 if($widejamb!="")
					    $workitem="막판" . $widejamb . " "; 
				 if($normaljamb!="")
					    $workitem .="막(無)" . $normaljamb . " "; 					
				 if($smalljamb!="")
					    $workitem .="쪽쟘" . $smalljamb . " "; 												   
			   $sumStr[$counter]=$workitem;  	 
			  
			  $dis_text = "막판 : " . $sum[0] . " 세트, 막판(無) : " . $sum[1] . " 세트, 쪽쟘 : "  . $sum[2] . " 세트, 합계 : " . $sum[3] . " 세트" ; 

		      if($orderday!="0000-00-00" and $orderday!="1970-01-01"  and $orderday!="") $orderday = date("Y-m-d", strtotime( $orderday) );
					else $orderday="";
		      if($measureday!="0000-00-00" and $measureday!="1970-01-01" and $measureday!="")   $measureday = date("Y-m-d", strtotime( $measureday) );
					else $measureday="";
		      if($drawday!="0000-00-00" and $drawday!="1970-01-01" and $drawday!="")  $drawday = date("Y-m-d", strtotime( $drawday) );
					else $drawday="";
		      if($deadline!="0000-00-00" and $deadline!="1970-01-01" and $deadline!="")  $deadline = date("Y-m-d", strtotime( $deadline) );
					else $deadline="";
		      if($workday!="0000-00-00" and $workday!="1970-01-01"  and $workday!="")  $workday = date("Y-m-d", strtotime( $workday) );
					else $workday="";					
		      if($endworkday!="0000-00-00" and $endworkday!="1970-01-01" and $endworkday!="")  $endworkday = date("Y-m-d", strtotime( $endworkday) );
					else $endworkday="";	
		      if($demand!="0000-00-00" and $demand!="1970-01-01" and $demand!="")  $demand = date("Y-m-d", strtotime( $demand) );
					else $demand="";						
		      if($startday!="0000-00-00" and $startday!="1970-01-01" and $startday!="")  $startday = date("Y-m-d", strtotime( $startday) );
					else $startday="";		  			  	  
		      if($testday!="0000-00-00" and $testday!="1970-01-01" and $testday!="")  $testday = date("Y-m-d", strtotime( $testday) );
					else $testday="";		  
			  	  				  
			  $state_work=0;
			  if($row["checkbox"]==0) $state_work=1;
			  if(substr($row["workday"],0,2)=="20") $state_work=2;
			  if(substr($row["endworkday"],0,2)=="20") $state_work=3;	
              $draw_done="";			  
			  if(substr($row["drawday"],0,2)=="20") $draw_done = "OK";		 
              $measure_done="";			  
			  if(substr($row["measureday"],0,2)=="20") $measure_done = "OK";		 
    			  

			 ?>
			 
	<div class="row">
        <div class="col-1">
		  
          <h5 class="display-5 font-center text-center"> <?=$start_num?> </h5>
        </div>
        <div class="col-2"> 
		<?php

			     print ' <h5 class="display-5 font-center text-center"> <div class="animate__animated animate__bounce">' . $endworkday . ' </div> </h5> ';
	
			?>		  
		  
        </div>
        <div class="col-6">
        <h5 id="work" class="display-5 font-center text-left"> <div class="animate__animated animate__rubberBand"> <?=$workplacename?>  </div> </h5> 
		<?php //sleep(1); ?>
        </div>				
        <div class="col-2">
          <h5 class="display-5 font-center text-center"> <?=$worker?>  </h5>
        </div>			
      </div>	 
				  
</a>
            <div class="clear" > </div>
			<?php
			$start_num--;
			 } 
  } catch (PDOException $Exception) {
  print "오류: ".$Exception->getMessage();
  }  
 ?>
 <br>
 <br>
	<div class="row">
        <div class="col-11">
          <h3 class="display-6 font-center text-center">   
		
 </h3>
        </div>
     </div>
	</form>	
         </div> <!-- end of  container -->     
  
<script>
 var rowNum = <?php echo $counter; ?>; 
 var dataCount=0;
 
function check_level()
			  {
				window.open("check_level.php?nick="+document.member_form.nick.value,"NICKcheck", "left=200,top=200,width=300,height=100, scrollbars=no, resizable=yes");
			  }
$(document).ready(function(){
	
		  //   $("#myModal").modal();
	
    $("#without").change(function(){
        if($("#without").is(":checked")){
            $('#check').val('1');
            $('#search').val('');			 // search 입력란 비우기			
            $('#board_form').submit();	
        }else{
            $('#check').val('');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();						
        }
    });
    $("#outputlist").change(function(){
        if($("#outputlist").is(":checked")){
            $('#output_check').val('1');
           //  $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#output_check').val('');
          //   $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#plan_outputlist").change(function(){
        if($("#plan_outputlist").is(":checked")){
            $('#plan_output_check').val('1');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#plan_output_check').val('');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });	
	
    $("#team").change(function(){
        if($("#team").is(":checked")){
            $('#team_check').val('1');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();
			
        }else{
            $('#team_check').val('');
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();			
        }
    });		
    $("#notmeasure").change(function(){        // 미실측리스트 클릭시 동작	
        if($("#notmeasure").is(":checked")){
            $('#measure_check').val('1');		
            $('#board_form').submit();
			
        }else{
            $('#measure_check').val('');			
            $('#board_form').submit();			
        }
    });		
	/*
  $('#showall').click(function(){
            $('#search').val('');			 // search 입력란 비우기						
            $('#board_form').submit();		  
    });		
	*/
setTimeout(function() {
load();
//load_normaljamb_format();  
//setTimeout(maketable, 100);	  	   
//setTimeout(redraw, 100);	  	   
},10000);

});		

$("#btn-1").click(function(){
   $("#myModal").modal();
});

function fadeIn_Work() {
    $("#work").fadeIn();	
    setTimeout(fadeOut_Work, 2000);	  	   	
}

function fadeOut_Work() {	
	$("#work").fadeOut();	
}
// setInterval(fadeIn_Work, 4000);	  	   

$(".btn17").click(function(e){
     e.preventDefault();
    $(".box17").animate({ left:"85%" },1000,"easeOutQuint").animate({ left:"0" },1000,"easeOutQuint");
	$(".box17").html('dkfjdlksfjfsd');
	
    // setTimeout(out,4000)
});

function out(){
    $(".box17").clearQueue().hide();
}

 function sleep (delay) {
   var start = new Date().getTime();
   while (new Date().getTime() < start + delay);
}


function load (){   

 var arr1 = <?php echo json_encode($item);?> ;
 var arr2 = <?php echo json_encode($man);?> ;
 var arr3 = <?php echo json_encode($sumStr);?> ;
  dataCount++;  
 if(dataCount>rowNum)  {
	          dataCount=1;
						setTimeout(function(){
				location.reload();
				},3000); // 3000밀리초 = 3초  
      }

		alertify.alert("<h2>" + arr2[dataCount] +" 소장</h2>", "<h3><br>  <div class='animate__animated animate__heartBeat'>" + arr1[dataCount] + "</div> 		</h3> <br> <h3><br>  <div  class='animate__animated animate__heartBeat' style='color:blue;' >   "  + arr3[dataCount] + "</div> 		</h3>");

setTimeout("load()", 5000);


}

 





</script>



  </body>

  
  </html>