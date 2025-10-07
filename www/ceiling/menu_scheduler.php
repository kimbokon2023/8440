<?php session_start(); 
include '../common.php';  // php 함수들 모음

header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Pragma: no-cache"); // HTTP/1.0
header("Expires: 0"); // rfc2616 - Section 14.21   
// header("Content-Type: application/json");//json을 사용하기 위해 필요한 구문
ini_set('display_errors','0');  // 화면에 warning 없애기
//header("Refresh:0");  // reload refresh  

$user_name= $_SESSION["name"];
$user_id= $_SESSION["userid"];
isset($_REQUEST["SelectWork"])  ? $SelectWork=$_REQUEST["SelectWork"] :   $SelectWork=''; 
isset($_REQUEST["searchOpt"])  ? $searchOpt=$_REQUEST["searchOpt"] :   $searchOpt=''; 
isset($_REQUEST["partOpt"])  ? $partOpt=$_REQUEST["partOpt"] :   $partOpt='1'; 
?>

<!DOCTYPE html>
<meta charset="UTF-8">
<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/460/fabric.js"> </script>

<body>
<title> 조명/천장 작업스케줄러(SCHEDULER) </title>
<style>
   @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css");
   
* {
    margin: 0;
    padding: 0
}

.custom_calendar_table td {
    text-align: center;
}

.custom_calendar_table thead.cal_date th {
    font-size: 2rem;
	text-align: center;
	height: 40px;
	margin-bottom: 15px;	
}

.custom_calendar_table thead.cal_date th button {
    font-size: 2rem;
    background: none;
    border: none;
}

.custom_calendar_table thead.cal_week th {
    background-color: #A0A0A0;
	font-size: 2.0rem;
	height: 60px;
	margin-bottom: 15px;
    color: #fff;
	text-align: center;
}

.custom_calendar_table tbody td {
   /* cursor: pointer; */
}

.custom_calendar_table tbody td:nth-child(1) {
    color: red;
	height: 100px;	
}

.custom_calendar_table tbody td:nth-child(7) {
    color: #288CFF;
	height: 700px;	
}

.custom_calendar_table tbody td.select_day {
  /*  background-color: #288CFF;
    color: #fff; */
}   


	
</style>

 <?php
if(isset($_REQUEST["search"]))   //목록표에 제목,이름 등 나오는 부분
 $search=$_REQUEST["search"];
isset($_REQUEST["page"])  ? $page=$_REQUEST["page"] :   $page=1; // $_REQUEST["page"]값이 없을 때에는 1로 지정 
isset($_REQUEST["num"])  ? $num=$_REQUEST["num"] :   $num=''; 

  
if(isset($_REQUEST["SelectWork"]))  // 어떤 작업을 지시했는지 파악해서 돌려줌.
	$SelectWork=$_REQUEST["SelectWork"];
		else 
			$SelectWork="";   // 초기화	  
 
if(isset($_REQUEST["list"]))   //목록표에 제목,이름 등 나오는 부분
 $list=$_REQUEST["list"];
else
	  $list=0;
  
if(isset($_REQUEST["scale"]))   
 $scale=$_REQUEST["scale"];
else
	  $scale=10;	 
  
require_once("../lib/mydb.php");
$pdo = db_connect();	

  $page_scale = 10;   // 한 페이지당 표시될 페이지 수  10페이지
  $first_num = ($page-1) * $scale;  // 리스트에 표시되는 게시글의 첫 순번.
	 
  if(isset($_REQUEST["mode"]))
     $mode=$_REQUEST["mode"];
  else 
     $mode="";     
   
   if(isset($_REQUEST["find"]))   //목록표에 제목,이름 등 나오는 부분
   $find=$_REQUEST["find"];  
   
// 기간을 정하는 구간
$fromdate=$_REQUEST["fromdate"];	 
$todate=$_REQUEST["todate"];	 

if($fromdate=="")
{
	$fromdate=substr(date("Y-m-d",time()),0,4) ;
	$fromdate=$fromdate . "-01-01";
}
if($todate=="")
{
	$todate=substr(date("Y-m-d",time()),0,4) . "-12-31" ;
	$Transtodate=strtotime($todate.'+1 days');
	$Transtodate=date("Y-m-d",$Transtodate);
}
    else
	{
	$Transtodate=strtotime($todate);
	$Transtodate=date("Y-m-d",$Transtodate);
	}   

$now = date("Y-m-d");	     // 현재 날짜와 크거나 같으면 출고예정으로 구분
$nowtime = date("H:i:s");	 // 현재시간
	   
		  		 
			?>

</head>

<body>			
	
<? include '../myheader.php'; ?>   

<div class="card">
<div class="card-body">

<div class="container">			
	<div class ="d-flex align-items-center p-1justify-content-center" >
		<h1 class="h4 text-white">  작업일정(생산예정일 기준 다음날) </h1>		
	</div>
	<div class ="d-flex align-items-center p-1 justify-content-center" > 
 <form name="Form">
    <input type=hidden id="fromdate" name="fromdate" value="2021-12-18">
    <input type=hidden id="todate" name="todate" value="2021-12-18">
    <input type=hidden id="weekend" name="weekend" value="">
    <input type=hidden id="partOpt" name="partOpt" value="<?=$partOpt?>">
    <input type=hidden id="weekcalandarVal" name="weekcalandarVal" >
</form>

 <span class="input-group-text bg-white"> 

	<button type="button" class="button btn btn-secondary" onclick="week_calandar(-1)"> < </button>
	<button type="button" class="button btn btn-secondary" onclick="set_day()" > Today </button>
	<button type="button" class="button btn btn-secondary" onclick="week_calandar(1)"> > </button>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 업체별 출고량 주간 스케줄
   <?print $radiopart; ?>   
	 </span>
   </div>
<div class ="d-flex align-items-center p-1 justify-content-center" > 
	 
	<div id="calandarTitle"></div> 	  
		<div id="calwrap col1" style="float:left;width:50px;height:900px;margin-left:10px;"> 

		</div>
		<div id="calwrap col2" style="float:left;width:1750px;height:900px;"> 	
		 <div id="calandar"></div> 
	 </div>
		  
</div>
</div>
</div>
</div>
</body>
</html>		  

<script> 
let dayArray = new Array(); 

var nowDate = new Date();
function calendarMaker(target, date) {
    if (date == null || date == undefined) {
        date = new Date();
    }
    nowDate = date;
    if ($(target).length > 0) {
        var year = nowDate.getFullYear();
        var month = nowDate.getMonth() + 1;
        var date = nowDate.getDate();
        var day = nowDate.getDay();
        $(target).empty().append(assembly(year, month, date));
    } else {
        console.error("custom_calendar Target is empty!!!");
        return;
    }

    var thisMonth = new Date(nowDate.getFullYear(), nowDate.getMonth(), 1);
    var thisLastDay = new Date(nowDate.getFullYear(), nowDate.getMonth() + 1, 0);

    var tag = "<tr>";
    var cnt = 0;
    // 주간 창 만들어주기
    for (i = 0; i < 7 ; i++) {
        tag += "<td> <canvas id='myChart" + (i) + "' width='245' height='700'></canvas> </td>";  // myChart 그려주기
        cnt++;
    }
    // for (i = 0; i < thisMonth.getDay(); i++) {
        // tag += "<td></td>";
        // cnt++;
    // }

    // //날짜 채우기
    // for (i = 1; i <= thisLastDay.getDate(); i++) {
        // if (cnt % 7 == 0) { tag += "<tr>"; }

        // tag += "<td>" + i + "</td>";
        // cnt++;
        // if (cnt % 7 == 0) {
            // tag += "</tr>";
        // }
    // }
    $(target).find("#custom_set_date").append(tag);
    calMoveEvtFn();

    function assembly(year, month, date) {
        var calendar_html_code =
            "<table class='custom_calendar_table'>" +
            "<colgroup>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "<col style='width:250px;'/>" +
            "</colgroup>" +
            "<thead class='cal_date'>" +
            "<th class='colTitle' colspan='7'> </th>" +           
            "</thead>" +
            "<thead  class='cal_week'>" +
            "<th>" + dayArray[0] + "(일)</th>" +
            "<th>" + dayArray[1] + "(월)</th>" +
            "<th>" + dayArray[2] + "(화)</th>" +
            "<th>" + dayArray[3] + "(수)</th>" +
            "<th>" + dayArray[4] + "(목)</th>" +
            "<th>" + dayArray[5] + "(금)</th>" +
            "<th>" + dayArray[6] + "(토)</th>" +			 
            "</thead>" +
            "<tbody id='custom_set_date'>" +
            "</tbody>" +
            "</table>";
        return calendar_html_code;
    }

    function calMoveEvtFn() {
        //전달 클릭
        $(".custom_calendar_table").on("click", ".prev", function () {
            nowDate = new Date(nowDate.getFullYear(), nowDate.getMonth() - 1, nowDate.getDate());
            calendarMaker($(target), nowDate);
        });
        //다음날 클릭
        $(".custom_calendar_table").on("click", ".next", function () {
            nowDate = new Date(nowDate.getFullYear(), nowDate.getMonth() + 1, nowDate.getDate());
            calendarMaker($(target), nowDate);
        });
        //일자 선택 클릭
        $(".custom_calendar_table").on("click", "td", function () {
            $(".custom_calendar_table .select_day").removeClass("select_day");
            $(this).removeClass("select_day").addClass("select_day");
        });
    }
}

function week_calandar(week) {
	day.setDate(day.getDate()+week*7);
	var title = day.getFullYear() + "-" + (day.getMonth()+1) + "-" + day.getDate();
	$('#fromdate').val(title);	
	var data = ""
	for(var i=0 ; i<7 ; i++) {
		data += day.getDate() + "|";
		dayArray[i] = day.getDate();
        var fromdate = day.getFullYear() + "-" + (day.getMonth()+1) + "-" + dayArray[i];		
		$('#fromdate').val(fromdate);
		$('#todate').val(fromdate);
		$('#weekend').val(i);          // 요일에 대한 정보를 함께 넘겨준다.
        displayResult();    // php에 전송해서 결과값 받기			
		day.setDate(day.getDate()+1);
	}
	const tmp = day.getFullYear() + "-" + (day.getMonth()+1)+ "-" + (day.getDate()-1);
	title += " ~ " + tmp;	
	$('#todate').val(tmp);	
	day.setDate(day.getDate()-7);
	// document.getElementById("calandarTitle").innerHTML = title + "<br />" + data;
	calendarMaker($("#calandar"), new Date());
	$('.colTitle').text(title);	
	$('#weekcalandarVal').val(week);

}

function set_day() {
	day = new Date();
	day.setDate(day.getDate()-day.getDay());
	week_calandar(0);
}
	
	//no1 버튼을 클릭 했을떄 이벤트
function displayResult(){
		var phpSel=$('#partOpt').val();
		let urlSel;
	    $.ajax({
			url: "workerdata.php",
    	  	type: "post",		
   			data: $("Form").serialize(),
   			dataType:"json",
		}).done(function(data){
		console.log(data);				
		    const weekend = data["weekend"];		
			drawGraph('all',weekend ,data["date_arr"]);	// 배열로 전달함.		
 		});           
	}	
	

$(document).ready(function(){	
	console.clear();
	$('input[name="radiopart"]').change(function(){
		  var temp = $(':radio[name="radiopart"]:checked').val();	      		
		 $("#partOpt").val(temp);
	     week_calandar(0);
	});

	//tbody 안에 있는 내용 지우기
	$('#no2').click(function(){
	    $("#input_data").empty();
	});

	set_day();
		
	var day = new Date();
	day.setDate(day.getDate()-day.getDay());		   
		var ctx0 = document.getElementById('myChart0');
		var ctx1 = document.getElementById('myChart1');
		var ctx2 = document.getElementById('myChart2');
		var ctx3 = document.getElementById('myChart3');
		var ctx4 = document.getElementById('myChart4');
		var ctx5 = document.getElementById('myChart5');
		var ctx6 = document.getElementById('myChart6');	
});


function drawGraph(item, weekend, arr) {
  
 if(item=='all') {
        console.log(arr);
        let canvas;	
		canvas = new fabric.Canvas('myChart' + weekend);		
	    for(var i=0; i< arr.length; i++) {	
         if(i % 2 == 0)		
		  {
			var text1 = new fabric.Text(arr[i], { left: 50, top: 10+i*30 , fontSize: 16} );		
			text1.set({fill: '#0000FF'})
			canvas.add(text1);	
		  }
         if(i % 2 == 1)		
		  {
			var text1 = new fabric.Text(arr[i], { left: 50, top: 10+i*30 , fontSize: 16} );		
			text1.set({fill: '#000'})
			canvas.add(text1);	
		  }		  
		}		
  }	
}
	
function getToday(){   // 2021-01-28 형태리턴
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;    //1월이 0으로 되기때문에 +1을 함.
    var date = now.getDate();

    month = month >=10 ? month : "0" + month;
    date  = date  >= 10 ? date : "0" + date;
     // ""을 빼면 year + month (숫자+숫자) 됨.. ex) 2018 + 12 = 2030이 리턴됨.

    //console.log(""+year + month + date);
    return today = ""+year + "-" + month + "-" + date; 
}

function getCurrentTime(){   // 01:00:00 형태리턴  현재시간 리턴
	var today = new Date();   

	var hours = ('0' + today.getHours()).slice(-2); 
	var minutes = ('0' + today.getMinutes()).slice(-2);
	var seconds = ('0' + today.getSeconds()).slice(-2); 

	var timeString = hours + ':' + minutes  + ':' + seconds;
	return timeString;
	console.log(timeString);
}

function msToTime(duration) {
  var milliseconds = parseInt((duration % 1000) / 100),
    seconds = Math.floor((duration / 1000) % 60),
    minutes = Math.floor((duration / (1000 * 60)) % 60),
    hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

  hours = (hours < 10) ? "0" + hours : hours;
  minutes = (minutes < 10) ? "0" + minutes : minutes;
  seconds = (seconds < 10) ? "0" + seconds : seconds;

  return hours + ":" + minutes + ":" + seconds + "." + milliseconds;
}

function secToTime(duration) {
  var seconds = Math.floor(duration % 60),
    minutes = Math.floor((duration / 60) % 60),
    hours = Math.floor((duration / (60 * 60)) % 24);

  hours = (hours < 10) ? "0" + hours : hours;
  minutes = (minutes < 10) ? "0" + minutes : minutes;
  seconds = (seconds < 10) ? "0" + seconds : seconds;

  return hours + ":" + minutes + ":" + seconds;
}


  </script>