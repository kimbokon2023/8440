<!DOCTYPE html>
<html>
<script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<div id="spreadsheet"></div>

<script>
var changed = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
    $('#log').append('New change on cell ' + cellName + ' to: ' + value + '');
}

var beforeChange = function(instance, cell, x, y, value) {
    var cellName = jexcel.getColumnNameFromId([x,y]);
    $('#log').append('The cell ' + cellName + ' will be changed');
}

var insertedRow = function(instance) {
    $('#log').append('Row added');
}

var insertedColumn = function(instance) {  
  $('#log').append('Column added');
}

var deletedRow = function(instance) {
    $('#log').append('Row deleted');
}

var deletedColumn = function(instance) {
    $('#log').append('Column deleted');
}

var sort = function(instance, cellNum, order) {
    var order = (order) ? 'desc' : 'asc';
    $('#log').append('The column  ' + cellNum + ' sorted by ' + order + '');
}

var resizeColumn = function(instance, cell, width) {
    $('#log').append('The column  ' + cell + ' resized to width ' + width + ' px');
}

var resizeRow = function(instance, cell, height) {
    $('#log').append('The row  ' + cell + ' resized to height ' + height + ' px');
}

var selectionActive = function(instance, x1, y1, x2, y2, origin) {
    var cellName1 = jexcel.getColumnNameFromId([x1, y1]);
    var cellName2 = jexcel.getColumnNameFromId([x2, y2]);
    $('#log').append('The selection from ' + cellName1 + ' to ' + cellName2 + '');
}

var loaded = function(instance) {
    $('#log').append('New data is loaded');
}

var moveRow = function(instance, from, to) {
    $('#log').append('The row ' + from + ' was move to the position of ' + to + ' ');
}

var moveColumn = function(instance, from, to) {
    $('#log').append('The col ' + from + ' was move to the position of ' + to + ' ');
}

var blur = function(instance) {
    $('#log').append('The table ' + $(instance).prop('id') + ' is blur');
}

var focus = function(instance) {
    $('#log').append('The table ' + $(instance).prop('id') + ' is focus');
}

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

jexcel(document.getElementById('spreadsheet'), {
   // data:data,
    csv:'http://8440.co.kr/test_left.csv',
	csvHeaders:true,   // 헤더를 불러옴
   // tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    columns: [
        { title: '동', type: 'text', width:'60' },
        { title: '층', type: 'text', width:'60' },
        { title: 'JB', type: 'text', width:'60' },
		{ title: 'H1', type: 'text', width:'60' },
        { title: 'H2', type: 'text', width:'60' },
        { title: 'G1', type: 'text', width:'60' },
        { title: 'G2', type: 'text', width:'60' },
		{ title: 'K1', type: 'text', width:'60' },
		{ title: 'K2', type: 'text', width:'60' },
		{ title: '좌(높이)', type: 'text', width:'60' },
		{ title: '', type: 'text', width:'60' },
		{ title: '1', type: 'text', width:'60' },
		{ title: '2', type: 'text', width:'60' },
		{ title: '우(높이)', type: 'text', width:'60' },
		{ title: '', type: 'text', width:'60' },
		{ title: '1', type: 'text', width:'60' },
		{ title: '2', type: 'text', width:'60' },
       // { type: 'calendar', width:'50' },
    ],
    onchange: changed,
    onbeforechange: beforeChange,
    oninsertrow: insertedRow,
    oninsertcolumn: insertedColumn,
    ondeleterow: deletedRow,
    ondeletecolumn: deletedColumn,
    onselection: selectionActive,
    onsort: sort,
    onresizerow: resizeRow,
    onresizecolumn: resizeColumn,
    onmoverow: moveRow,
    onmovecolumn: moveColumn,
    onload: loaded,
    onblur: blur,
    onfocus: focus,
});


// 선을 그려주는 부분
 /*
        $(document).ready(function () {
            // 변수를 선언합니다.
            var canvas = document.getElementById('canvas');
            var context = canvas.getContext('2d');

            // 이벤트를 연결합니다.
            $(canvas).on({
                mousedown: function (event) {
                    // 위치를 얻어냅니다.
                    var position = $(this).offset();
                    var x = event.pageX - position.left;
                    var y = event.pageY - position.top;

                    // 선을 그립니다.
                    context.beginPath();
                    context.moveTo(x, y);
                },
                mouseup: function (event) {
                    // 위치를 얻어냅니다.
                    var position = $(this).offset();
                    var x = event.pageX - position.left;  // 이렇게 계산 안하고 event.offsetX로 하면된다.
                    var y = event.pageY - position.top;

                    // 선을 그립니다.
                    context.lineTo(x, y);
                    context.stroke();
                }
            });
        });
		 
*/
// 버튼을 누르면 숫자을 올려주고 내려주는 제이쿼리 함수
$(function(){ 
  $('.bt_up').click(function(){ 
    var n = $('.bt_up').index(this);
    var num = $(".num:eq("+n+")").val();
    var trlength =$('#spreadsheet tbody tr').length;
	if(num<trlength)
		  num = $(".num:eq("+n+")").val(num*1+1); 
    $('#x-coo').text(trlength);	//레코드 개수
	
    drawit(); // 도면그리기	
  });
  $('.bt_down').click(function(){ 
    var n = $('.bt_down').index(this);

    var num = $(".num:eq("+n+")").val();
	if(num>1) 
       num = $(".num:eq("+n+")").val(num*1-1); 
   drawit(); // 도면그리기
  });
}) 
  
  
  
function drawit() {

	        var canvas = document.getElementById('canvas');
            var ctx = canvas.getContext('2d');
			
			var pos = $('#col1').val();// 몇번째 데이터인지 불러온다.
				// alert(pos);
			
			var startX = 400;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = 80;	

		    var title1 = $('tr:eq(' + pos + ')>td:eq(1)').html();
		    var title2 = $('tr:eq(' + pos + ')>td:eq(2)').html();
			
			$('#title').text(title1 + '호기 ' + title2 + '층');
			
		    var jb = Number($('tr:eq(' + pos + ')>td:eq(3)').html());
		    var h1 = Number($('tr:eq(' + pos + ')>td:eq(4)').html());
		    var h2 = Number($('tr:eq(' + pos + ')>td:eq(5)').html());
		    var g1 = Number($('tr:eq(' + pos + ')>td:eq(6)').html());
		    var g2 = Number($('tr:eq(' + pos + ')>td:eq(7)').html());
		    var k1 = Number($('tr:eq(' + pos + ')>td:eq(8)').html());
		    var k2 = Number($('tr:eq(' + pos + ')>td:eq(9)').html());
		    var left_width = Number($('tr:eq(' + pos + ')>td:eq(10)').html());
		    // var space1 = Number($('tr:eq(' + pos + ')>td:eq(11)').html());
		    var left_height1 = Number($('tr:eq(' + pos + ')>td:eq(12)').html());
		    var left_height2 = Number($('tr:eq(' + pos + ')>td:eq(13)').html());
		    var right_width = Number($('tr:eq(' + pos + ')>td:eq(14)').html());
		   //  var space2 = Number($('tr:eq(' + pos + ')>td:eq(15)').html());
		    var right_height1 = Number($('tr:eq(' + pos + ')>td:eq(16)').html());
		    var right_height2 = Number($('tr:eq(' + pos + ')>td:eq(17)').html());
			
			var last_left_height=0;
			if(left_height1>left_height2)
				 last_left_height=left_height1;
			    else
					 last_left_height=left_height2;
	
	$('#title').text(title1 + '호기 ' + title2 + '층' + ' (' + last_left_height + ' x ' + left_width + ' mm)');		 // ??호기 ?? 층 세로 * 가로 mm
	
             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 3200, 1200);					

var bend1 = 1.2;   //벤딩에 대한 연신율을 계산한다. y에만 적용한다.
var bend2 = 0.6;
var bend3 = 0.6;
	                ctx.beginPath();
					ctx.setLineDash([0, 0]);
			        ctx.strokeStyle = '#000';						  
                    ctx.moveTo(startX, startY);
			        ctx.lineTo(startX + left_height2, startY);  // left height 2 
                    ctx.stroke();		
					// left height2 + 날개 20(상수) - 1번 절곡 연신율을
					var firstwing=Number($('#wings').val());
					var wing= g1 + firstwing - bend1;
			        ctx.lineTo(startX + left_height2, startY+wing);  // G1 + wing - bend1
                    ctx.stroke();			
			        ctx.lineTo(startX + left_height1, startY+left_width-g1-h1+bend2+bend3*2);  // 좌측 우측 하단 단차지점 (연신율 적용)
                    ctx.stroke();		
			        ctx.lineTo(startX + left_height1, startY+left_width);  // 좌측 우측 하단 내림 (연신율 적용)
                    ctx.stroke();	
			        ctx.lineTo(startX, startY+left_width);  // 하단 1번 면 그리기
                    ctx.stroke();
			        ctx.lineTo(startX, startY);  // 좌측 0,0으로 올림 
                    ctx.stroke();
					
  // 절곡선을 그린다
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([2, 4]);
  ctx.lineDashOffset = -offset;
 // alert(firstwing);
  ctx.moveTo(startX, startY + firstwing - bend1);  // 위에서 첫번째 절곡라인 연신율 적용
  ctx.lineTo(startX + left_height2, startY + firstwing - bend1);       // 첫번째 절곡라인  날개  
   ctx.moveTo(startX, startY + firstwing - bend1 + k1 - bend1);  
  ctx.lineTo(startX + left_height2, startY + firstwing - bend1 + k1 - bend1); // 두번째 절곡라인 (9도만 접는 부위는 연신율 적용안함) 
  ctx.stroke();    
 
  ctx.moveTo(startX, startY + firstwing - bend1 + k1 - bend1 + jb - bend1);  
  ctx.lineTo(startX + left_height1, startY + firstwing - bend1 + k1 - bend1 + jb - bend1); // 세번째 절곡라인 
  ctx.stroke();    
  
  ctx.moveTo(startX, startY + firstwing - bend1 + k1 - bend1 + jb - bend1+h1-bend2-bend3);  
  ctx.lineTo(startX + left_height1, startY + firstwing - bend1 + k1 - bend1 + jb - bend1+h1-bend2-bend3); // 네번째 절곡라인  
  ctx.stroke();    
  
  
  ctx.closePath();	

// 치수문자 화면출력

  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  ctx.font = '28px serif';
  ctx.strokeText('( Side 좌 2번 Height ) ' + left_height2, (startX*2 +left_height2)/2-200, startY-20);   // 출력
  firstwing=firstwing-bend1;
  ctx.font = '17px serif';
  ctx.strokeText('( 날개 ) ' + firstwing +' (연신율' + bend1 + '적용)', startX + left_height2+20, startY + firstwing - bend1-15);   // 첫번째 절곡 날개치수 출력
  var dis_second= k1-bend1;
  var dis_bend1=bend1;
  ctx.strokeText('( K1 ) ' + dis_second+' (연신율' + dis_bend1 + '적용)', startX + left_height2+20, startY + firstwing - bend1 + k1 - bend1-15);   // k1치수 출력 늘어난치수
  jb=jb-bend2*2;
  var dis_bend2=bend2*2;
  ctx.strokeText('( JB ) ' + jb +' (연신율' + dis_bend2 + '적용)', startX + left_height1 + 25 , startY + firstwing - bend1 + k1 - bend1 + jb/2 - bend1 - 15);   // jb치수(연신율적용) 출력 
  var dis_bend3=bend3*2; 
  h1 = h1-dis_bend3;  
  ctx.strokeText('( H1 ) ' + h1 +' (연신율' + dis_bend3 + '적용)', startX + left_height1 + 20, startY + firstwing - bend1 + k1 - bend1 + jb - bend1+h1-bend2-bend3-15);   // jb치수(연신율적용) 출력 
  g1=g1-bend3*2;
 ctx.strokeText('( G1 ) ' + g1 +' (연신율' + dis_bend3 + '적용)', startX + left_height1 + 20, startY + firstwing - bend1 + k1 - bend1 + jb - bend1+h1-bend2-bend3 + g1);   // jb치수(연신율적용) 출력 
    
  // side 단면도 그리기
   ctx.beginPath();
   var xpos=startX-200;
   var ypos=startY + firstwing  ;  //첫번째 절곡라인
   ctx.moveTo(xpos, ypos); // 300거리 띄워서 시작점 잡기
  
   ctx.lineTo(xpos+firstwing  , ypos ); // 좌측날개 그리기
   ctx.stroke();
   
   ctx.lineTo(xpos+firstwing, ypos + k1 ); // k1값 그리기
   ctx.stroke();

   var ypos=startY + firstwing +k1 ;  //
   var sinA=Math.sin(8) * jb;
   var cosA=Math.cos(8) * jb;
   // alert('sinA' + sinA + 'sinB' + cosA);
   ctx.lineTo(xpos + k1 - Math.abs(cosA.toFixed(1)) , ypos + Math.abs(sinA.toFixed(1)) ); // jb값 그리기
   ctx.stroke();
   ctx.closePath();	   
   
   
   
   
}



</script>
<body>

<!--  <input type='button' onclick='buttonclick()' value= '3행3열 자료보기' > <br><br>
<div id="log"> </div>  -->
<div id="goods_list">
  <form>
    <table align='' border='1' cellspacing='0' cellpadding='0'>
      <tr>
        <td>DATA 선택</td>
      </tr>
      <tr>
        <td>
          <table>
            <tr>
              <td><input type="text" name="num" value="1" id="col1" class="num" style="width:100px; height:25px; font-size:16px; text-align:center;" /></td>
              <td>
                <div>
                  <img src="https://placehold.it/20x20" alt="up" width="20" height="20" class="bt_up"/>
                </div>
                <div>
                  <img src="https://placehold.it/20x20" alt="down" width="20" height="20" class="bt_down" />
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </form>  
</div>

<input id="wings" type="text" value="20" style="width:80px; height:22px; float:left; " >  
<div id="col2"  style="width:150px; height:25px; float:left; margin-left:20px; "> 좌우날개 치수 </div> 

<input id="drawline" type="button" value="그리기" onclick="javascript:drawit();" style="width:100px; height:30px; float:left;">

<!--<div id="col3"  style="width:60px; height:25px; float:left; margin-left:20px; "> X좌표 : </div>
<input id="x-coo" type="text" value="1" size="5" style="width:50px; height:25px; float:left; "> 
<div id="col4"  style="width:60px; height:25px; float:left; margin-left:20px; "> Y좌표 : </div>
<input id="y-coo" type="text" value="1" size="5" style="width:50px; height:25px; float:left; "> -->
<br>
<br>
<br>
   <div id="canvas_outline" style ="width:3200px; height:1200px; border: 2px solid black;">
   <div id="title" style="width:1400px; height:35px; float:left; margin-left:10px;margin-top:10px; text-align:center; font-size:28px;"></div>
    <canvas id="canvas" width="3100" height="1100" style="margin-left:20px; margin-top:20px;">

    </canvas>
	</div>
</body>
<script>
// 버튼 클릭시 Row 값 가져오기
		function buttonclick(){ 
		var data1= $('tr:eq(2)>td:eq(2)').html();
			/*
			var str = ""
			var tdArr = new Array();	// 배열 선언
			var checkBtn = $(this);
			
			// checkBtn.parent() : checkBtn의 부모는 <td>이다.
			// checkBtn.parent().parent() : <td>의 부모이므로 <tr>이다.
			var tr = checkBtn.parent().parent();
			var td = tr.children();
			
			console.log("클릭한 Row의 모든 데이터 : "+tr.text());
			
			var no = td.eq(0).text();
			var userid = td.eq(1).text();
			var name = td.eq(2).text();
			var email = td.eq(3).text();
			
			
			// 반복문을 이용해서 배열에 값을 담아 사용할 수 도 있다.
			td.each(function(i){	
				tdArr.push(td.eq(i).text());
			});
			
			console.log("배열에 담긴 값 : "+tdArr);
			
			str +=	" * 클릭된 Row의 td값 = No. : <font color='red'>" + no + "</font>" +
					", 아이디 : <font color='red'>" + userid + "</font>" +
					", 이름 : <font color='red'>" + name + "</font>" +
					", 이메일 : <font color='red'>" + email + "</font>";		
			*/
	
          // alert(data1);	

	  
		}
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
</html>

 
