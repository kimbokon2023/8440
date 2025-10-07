 <?php
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>5) {
          /*   alert("관리자 승인이 필요합니다."); */
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
   
$title_message = 'jamb 그리기'   ;
?>

 
 <?php include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php' ?>
 	 
	<script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
	<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
	<link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
	<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://8440.co.kr/jamb/jamb.js"></script>

	<title> <?=$title_message?> </title> 
 
</head>
 
 <body>   
  <? require_once($_SERVER['DOCUMENT_ROOT'] . '/common/modal.php'); ?>   
  
  <?php
  if( $menu!=='no') 
		require_once($_SERVER['DOCUMENT_ROOT'] . '/myheader.php'); ?>   
   

<?php
     $item=array();
	 
	 $item[]='선택';	 
	 $item[]='와이드쟘';	 
	 $item[]='와이드쟘-사이드(좌큼)';	 
	 $item[]='와이드쟘-사이드(우큼)';	 
	 $item[]='멍텅구리';	 
	 $item[]='멍텅구리-사이드(좌큼)';	 
	 $item[]='멍텅구리-사이드(우큼)';
	 $item[]='쪽쟘';
	 
     $item_counter=count($item);			 	 

     $whois=array();
	 
	 $whois[]='추영덕소장';	 
	 $whois[]='이만희소장';	 
	 $whois[]='손상민소장';	 
	 $whois[]='백석묵소장';	 
			 
     $whois_counter=count($whois);			 

     $bendrate=array();
	 
	 $bendrate[]='0.75';	 
	 $bendrate[]='0.6';	 
	 
     $bendrate_counter=count($bendrate);			 
	 
?>

<div class="container-fluid">  

 <div class="d-flex mt-3 mb-3 justify-content-center">  

쟘 형태  &nbsp;

 <select name="item" id="sel1"  >
           <?php		 

		   for($i=0;$i<$item_counter;$i++) {
			     if($item==$item[$i])
							print "<option selected value='" . $item[$i] . "'> " . $item[$i] .   "</option>";
					 else   
			   print "<option value='" . $item[$i] . "'> " . $item[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select> &nbsp;
		
&nbsp; 옵션 &nbsp;
&nbsp;
<input id="measuredraw" name="measuredraw" type='checkbox' checked value='실측치draw' /> &nbsp; 실측치draw &nbsp;
<input id="makedraw" name="makedraw" type='checkbox' value='제작치수draw' /> &nbsp; 제작치수draw &nbsp;
		 
&nbsp; 연신율 &nbsp;
&nbsp; <select name="item" id="sel3"  >
           <?php		 

		   for($i=0;$i<$bendrate_counter;$i++) {
			     if($bendrate==$bendrate[$i])
							print "<option selected value='" . $bendrate[$i] . "'> " . $bendrate[$i] .   "</option>";
					 else   
			   print "<option value='" . $bendrate[$i] . "'> " . $bendrate[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select> &nbsp;			 
		 
&nbsp; 스타일 &nbsp;  
    <select name="whois" id="sel2"  >
           <?php		 

		   for($i=0;$i<$whois_counter;$i++) {
			     if($whois==$whois[$i])
							print "<option selected value='" . $whois[$i] . "'> " . $whois[$i] .   "</option>";
					 else   
			   print "<option value='" . $whois[$i] . "'> " . $whois[$i] .   "</option>";
		   } 		   
		      	?>	  
	    </select> &nbsp;
		
</div>		

<div class="clear"></div>
<div id=jambmore>  

<span class="more">
  <span class="blind"></span>
 </span> 
  </div>
<div class="clear"></div>  

<div id=display_jambtype style="display:none" class=board> 

<div id=jambtype_col1 class=board style="display:none" >  </div>
<div id=jambtype_col2 class=board style="display:none" > 
				<div id=j_row1> 
				<script>
					load_format();
					</script>
					</div>	
					<div id=j_row2> 
					    <div id=title1> 	
						    제작치수 반영치 설정
						</div>
					    <div id=setdata> 			
									<script>						
										load_setdata();
									</script>							
						    </div>
							<div id=makeit>
							<input id="maketable" type="button" value="제작치수 반영 후 DATA 생성" onclick="javascript:maketable();" >
							
							</div>
					
                         
						</div>	
</div> 


</div>

<div class="clear"></div>



<div id="all_spread" >

<div class="clear"> </div>
<h4 style="color:red"> 제작치수 반영 DATA </h4>
<div id="spreadsheet" style="width:100%;"></div>
</div>
<div class="clear"> </div>
<div id=display_control> 

			<input type=hidden id="old_draw" name="old_draw">
			<input type=hidden id="new_draw" name="new_draw">

					<div id="goods_list">
							  <form>
								<table align='' border='1' cellspacing='0' cellpadding='0'>
								  <tr>
									<td><h4>화면에 그려낼 DATA 선택</h4></td>
								  </tr>
								  <tr>
									<td>
									  <table>
										<tr>
										  <td><input type="text" name="num" value="1" id="col1" class="num" style="width:100px; height:25px; font-size:16px; text-align:center;" /></td>
										  <td>
											<div>
											  <img src="../img/up_btn.png" alt="up" width="20" height="20" class="bt_up"/>
											</div>
											<div>
											  <img src="../img/down_btn.png" alt="down" width="20" height="20" class="bt_down" />
											</div>
										  </td>
										  <td>
										      <input id="drawline" type="button" value="그리기" onclick="javascript:select_jamb();" style="width:100px; height:30px; float:left;">
							                  <input id="alertangle" type="button" value="각도구하기" onclick="javascript:getAngle(200,200,800,400);" style="width:100px; height:30px; float:left;">
										  </td>
										  <td>
										  									<select name="Xscale" id="Xscale">
									   
																		   <option value='none'>X스케일 설정</option>
																		   <option value='2x1'>1/2</option>
																		   <option value='3x1'>1/3</option>
																		   <option value='5x1'>1/5</option>

																			</select>

																			<input type=hidden id="dis_xscale" name="dis_xscale">
																			<input type=text id="left_angle" name="left_angle" size="4" >
																			<input type=text id="right_angle" name="right_angle" size="4" >
																			<input type=text id="sin" name="sin" size="4" >
																			<input type=text id="cos" name="cos" size="4" >
										  
										  </td>
										</tr>
									  </table>
									</td>
								  </tr>
								</table>
							  </form>  
							</div>
</div>
<div class="clear"> </div>
<script>

   $(document).ready(function(){

	    $('.more').click(function(){
    if($('.more').hasClass('more')){
       $('.more').addClass('close').removeClass('more');
       $('.board').css('visibility', 'visible');
	    $('.board').show(); 
    }else if($('.close').hasClass('close')){
       $('.close').addClass('more').removeClass('close');  
       $('.board').css('visibility', 'hidden');
	   $('.board').hide(); 
    }
  });

    });
	
var data = [ [''],   
];

var table1 = jexcel(document.getElementById('spreadsheet'), {
    data:data,
   // csv:'https://8440.co.kr/jamb/jambdata.csv',
	csvHeaders:false,
    tableOverflow:true,   // 스크롤바 형성 여부
	tableHeight:'230px',
    rowResize:true,
    columnDrag:true,
 columns: [
        { title: '호기', type: 'text', width:'30' },
        { title: '층', type: 'text', width:'30' },
        { title: 'U', type: 'text', width:'30' },
		{ title: 'G', type: 'text', width:'30' },
        { title: 'MH1', type: 'text', width:'40' },
        { title: 'MH2', type: 'text', width:'40' },
        { title: 'JD1', type: 'text', width:'30' },
		{ title: 'JD2', type: 'text', width:'30' },
		{ title: 'OP1', type: 'text', width:'40' },
		{ title: 'OP2', type: 'text', width:'40' },
		{ title: 'R', type: 'text', width:'40' },
		{ title: 'K1', type: 'text', width:'30' },
		{ title: 'K2', type: 'text', width:'30' },
		{ title: '상판W', type: 'text', width:'40' },
		{ title: 'JB1', type: 'text', width:'30' },
		{ title: 'JB2', type: 'text', width:'30' },
		{ title: 'C1', type: 'text', width:'30' },
		{ title: 'C2', type: 'text', width:'30' },
		{ title: 'A1', type: 'text', width:'30' },
		{ title: 'A2', type: 'text', width:'30' },		
		{ title: 'B1', type: 'text', width:'30' },
		{ title: 'B2', type: 'text', width:'30' },				
		{ title: 'SIDE좌W', type: 'text', width:'55' },
		{ title: 'SIDE우W', type: 'text', width:'55' },
		{ title: 'LH1', type: 'text', width:'40' },
		{ title: 'LH2', type: 'text', width:'40' },
		{ title: 'RH1', type: 'text', width:'40' },
		{ title: 'RH2', type: 'text', width:'40' },
       // { type: 'calendar', width:'50' },
    ],
    
});



// 버튼을 누르면 숫자을 올려주고 내려주는 제이쿼리 함수
$(function(){ 
  $('.bt_up').click(function(){ 
    var n = $('.bt_up').index(this);
    var num = $(".num:eq("+n+")").val();
    var trlength =$('#spreadsheet tbody tr').length;
	if(num<trlength)
		  num = $(".num:eq("+n+")").val(num*1+1); 
    $('#x-coo').text(trlength);	//레코드 개수	
select_jamb(); // 도면그리기	
//	drawit_side();
  });
  
  $('.bt_down').click(function(){ 
    var n = $('.bt_down').index(this);

    var num = $(".num:eq("+n+")").val();
	if(num>1) 
       num = $(".num:eq("+n+")").val(num*1-1); 
select_jamb(); // 도면그리기	
  });
  
  $('.second_bt_up').click(function(){ 
    var n = $('.second_bt_up').index(this);
    var num = $(".num2:eq("+n+")").val();
    var trlength =$('#spreadsheet tbody tr').length;
	if(num<trlength)
		  num = $(".num2:eq("+n+")").val(num*1+1); 
    $('#x-coo').text(trlength);	//레코드 개수	
   second_select_jamb(); // 도면그리기	
//	drawit_side();
  });
  
  $('.second_bt_down').click(function(){ 
    var n = $('.second_bt_down').index(this);

    var num = $(".num2:eq("+n+")").val();
	if(num>1) 
       num = $(".num2:eq("+n+")").val(num*1-1); 
    second_select_jamb(); // 도면그리기	
  });  
  
  
}) 
  


</script>

<!--  <input type='button' onclick='buttonclick()' value= '3행3열 자료보기' > <br><br>
<div id="log"> </div>  -->

   <div id="canvas_outline" style ="width:4000px; height:1200px; border: 2px solid black;">
   <div id="title" style="width:1100px; height:35px; float:left; margin-left:10px;margin-top:10px; text-align:center; font-size:28px;"></div>
    <canvas id="canvas" width="4000" height="1100" style="margin-left:20px; margin-top:20px;">

    </canvas>
	</div>

<script>

function getAngle(x1, y1, x2, y2) {
	var rad = Math.atan2(y2 - y1, x2 - x1);
	var d=(rad*180)/Math.PI ;

	var ch;
	ch=d;
	if(ch>90) 
		  ch= 90-ch;
    // alert(ch.toFixed(1) +'degrees'); 	
	return Math.abs(ch.toFixed(1)) ;	
}

</script>


<h1> Jamb side(좌, 우) 설계도

 <table> 
										<tr>
										  <td><input type="text" name="num2" value="1" id="col2" class="num2" style="width:100px; height:25px; font-size:16px; text-align:center;" /></td>
										  <td>
											<div>
											  <img src="../img/up_btn.png" alt="up" width="20" height="20" class="second_bt_up"/>
											</div>
											<div>
											  <img src="../img/down_btn.png" alt="down" width="20" height="20" class="second_bt_down" />
											</div>
										  </td>
										  <td>
										  </td>
										  <td>										  
										  </td>
										</tr>
									  </table>

</h1>


<script>

var data = [    [''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],[''],
   [''],
 [''],
 [''],
 [''],
];

jexcel(document.getElementById('spreadsheet2'), {
   // data:data,
    csv:'https://8440.co.kr/jamb/jamb_test_left.csv',
	csvHeaders:true,   // 헤더를 불러옴
   // tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    columns: [
        { title: '동', type: 'text', width:'40' },
        { title: '층', type: 'text', width:'40' },
        { title: 'JB', type: 'text', width:'40' },
		{ title: 'H1', type: 'text', width:'40' },
        { title: 'H2', type: 'text', width:'40' },
        { title: 'G1', type: 'text', width:'40' },
        { title: 'G2', type: 'text', width:'40' },
		{ title: 'K1', type: 'text', width:'40' },
		{ title: 'K2', type: 'text', width:'40' },
		{ title: '좌(높이)', type: 'text', width:'40' },
		{ title: '', type: 'text', width:'40' },
		{ title: '1', type: 'text', width:'40' },
		{ title: '2', type: 'text', width:'40' },
		{ title: '우(높이)', type: 'text', width:'40' },
		{ title: '', type: 'text', width:'40' },
		{ title: '1', type: 'text', width:'40' },
		{ title: '2', type: 'text', width:'40' },
       // { type: 'calendar', width:'50' },
    ],
});


</script>


   <div id="canvas_sideoutline" style ="width:4000px; height:1600px; border: 2px solid black;">
   <div id="title2" style="width:1100px; height:35px; float:left; margin-left:10px;margin-top:10px; text-align:center; font-size:28px;"></div>
    <canvas id="canvas_side" width="4000px" height="1600px" style="margin-left:20px; margin-top:20px;">

    </canvas>
	</div>

</div>

</body>

<?php
  // echo ("<script language=javascript> drawit();</script>");  // 당해년도 화면에 초기세팅하기
?>
<script>
setTimeout(function() {
$("#sel1").val("와이드쟘").prop("selected", true);  // 강제로 와이드쟘 선택하기
load_widejamb();
//load_normaljamb_format();  
setTimeout(maketable, 100);	  	   
setTimeout(redraw, 100);	  	   
}, 1500);

$("#Xscale").change(function(){
select_jamb();
});

</script>
</html>

 
