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
    csv:'http://8440.co.kr/test.csv',
	csvHeaders:false,
   // tableOverflow:true,   // 스크롤바 형성 여부
    rowResize:true,
    columnDrag:true,
    columns: [
        { title: '호기', type: 'text', width:'60' },
        { title: '층', type: 'text', width:'60' },
        { title: 'U', type: 'text', width:'60' },
		{ title: 'G', type: 'text', width:'60' },
        { title: 'J', type: 'text', width:'60' },
        { title: 'K', type: 'text', width:'60' },
        { title: 'OP', type: 'text', width:'60' },
		{ title: 'A', type: 'text', width:'60' },
		{ title: 'B', type: 'text', width:'60' },
		{ title: 'C', type: 'text', width:'60' },
		{ title: 'D', type: 'text', width:'60' },
		{ title: 'E', type: 'text', width:'60' },
		{ title: 'H1', type: 'text', width:'60' },
		{ title: 'H2', type: 'text', width:'60' },
		{ title: 'F1', type: 'text', width:'60' },
		{ title: 'F2', type: 'text', width:'60' },
		{ title: 'R', type: 'text', width:'60' },
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
			
			var startX = 200;  // 처음 선을 그릴때 좌,우측 띄우고 하는 점을 정하기 위해서 startX,Y 설정 , div로 마진을 줌
			var startY = 80;	

		    var title1 = $('tr:eq(' + pos + ')>td:eq(1)').html();
		    var title2 = $('tr:eq(' + pos + ')>td:eq(2)').html();
			
		    var u = Number($('tr:eq(' + pos + ')>td:eq(3)').html());
		    var g = Number($('tr:eq(' + pos + ')>td:eq(4)').html());
		    var j = Number($('tr:eq(' + pos + ')>td:eq(5)').html());
		    var k = Number($('tr:eq(' + pos + ')>td:eq(6)').html());
		    var op = Number($('tr:eq(' + pos + ')>td:eq(7)').html());
		    var a = Number($('tr:eq(' + pos + ')>td:eq(8)').html());
		    var b = Number($('tr:eq(' + pos + ')>td:eq(9)').html());
		    var c = Number($('tr:eq(' + pos + ')>td:eq(10)').html());
		    var d = Number($('tr:eq(' + pos + ')>td:eq(11)').html());
		    var e = Number($('tr:eq(' + pos + ')>td:eq(12)').html());
		    var h1 = Number($('tr:eq(' + pos + ')>td:eq(13)').html());
		    var h2 = Number($('tr:eq(' + pos + ')>td:eq(14)').html());
		    var f1 = Number($('tr:eq(' + pos + ')>td:eq(15)').html());
		    var f2 = Number($('tr:eq(' + pos + ')>td:eq(16)').html());
		    var r = Number($('tr:eq(' + pos + ')>td:eq(17)').html());
			
				$('#title').text(title1 + '호기 ' + title2 + '층' + ' (' + a + ' x ' + b + ' mm)');		 // ??호기 ?? 층 세로 * 가로 mm
					
             ctx.fillStyle = "rgba(255, 255, 255,1 )";
             ctx.clearRect (0, 0, 3200, 1200);					

	
	                ctx.beginPath();
					ctx.setLineDash([0, 0]);
			        ctx.strokeStyle = '#000';						  
                    ctx.moveTo(startX, startY);
			        ctx.lineTo(startX + b, startY);  // B 
                    ctx.stroke();		
			        ctx.lineTo(startX + b, startY+c);  // C
                    ctx.stroke();			
			        ctx.lineTo(startX +b-h2, startY+c);  // H2
                    ctx.stroke();		
			        ctx.lineTo(startX +b-f2, startY+a-e);  // e
					
					// 각도구하기
					var Angle = getAngle(startX +b-h2, startY+c,startX +b-f2, startY+a-e);
					
                    ctx.stroke();	
			        ctx.lineTo(startX +b-f2, startY+a);  // f2
                    ctx.stroke();	
			        ctx.lineTo(startX +b-f2-op, startY+a);  // op
                    ctx.stroke();
			        ctx.lineTo(startX +b-f2-op, startY+a-e);  // op
                    ctx.stroke();
			        ctx.lineTo(startX + h1, startY+c);  // e ~ h1
                    ctx.stroke();
			        ctx.lineTo(startX, startY+c);  // e ~ h1
                    ctx.stroke();
			        ctx.lineTo(startX, startY);  // start point
                    ctx.stroke();

var bend1 = 0.75;   //벤딩에 대한 연신율을 계산한다. y에만 적용한다.
var bend2 = 0.75;
var bend3 = 0.75;

  // 절곡선을 그린다
  var offset=1;
  ctx.strokeStyle = '#09f';
  ctx.beginPath();
  ctx.setLineDash([2, 4]);
  ctx.lineDashOffset = -offset;
  
  ctx.moveTo(startX, startY+g-bend1);  //연신율 적용
  ctx.lineTo(startX +b, startY+g-bend1);       // 첫번째 절곡라인   
  ctx.stroke();  
	
   ctx.moveTo(startX, startY + g - bend1 + u - bend1 - bend2); // 두번째 절곡라인 연신율적용
  
   ctx.lineTo(startX +b, startY + g - bend1 + u - bend1 - bend2); // 두번째 절곡라인 연신율적용
   ctx.stroke();
   
   ctx.moveTo(startX +f1, startY + a - k - bend3); // 세번째 절곡라인 연신율적용  
   ctx.lineTo(startX +b - f2, startY + a - k - bend3); // 세번째 절곡라인 연신율적용
   ctx.stroke();  
   ctx.closePath();	


// 치수문자 화면출력
  ctx.beginPath();
  ctx.strokeStyle = '#000';
  ctx.setLineDash([0, 0]);
  ctx.font = '15px serif';
  ctx.strokeText('( B ) ' + b, (startX*2 +b)/2-10, startY-20);   // b치수 출력
  ctx.strokeText('( R ) ' + r, (startX*2 +b)/2-10, startY + g + u + 20);   // r치수 출력
  ctx.strokeText('( C ) ' + c, startX-70, startY + (g + u)/2);   // c치수 출력
  var aa=g-bend1;
  ctx.strokeText('( G ) ' + aa +' (연신율' + bend1 + '적용)', startX+b+10, startY + (g/2));   // g치수(연신율적용) 출력 
  var bb=u-bend1-bend2;
  var sumbend1=bend1 + bend2;
  var sumbend2=bend2 + bend3;
  var sumbendAll=bend1*2 + bend2*2 + bend3*2;  // A값을 내기 위한 것
  var jj=j-bend1-bend2;
  var kk=k-bend3;
  
  ctx.strokeText('( U ) ' + bb +' (연신율' + sumbend1 + '적용)', startX+b+10, startY + ((g+u)/2)+10);   // u치수(연신율적용) 출력 
  ctx.strokeText('( H2 ) ' + h2 , startX+b-h2+10, startY + c +20);   // h2치수  
  ctx.strokeText('( H1 ) ' + h1 , startX-60, startY + c +20);   // h1치수  
  ctx.strokeText('( A ) ' + a + ' (전체절단높이)', startX-150, startY + a/2);   // a치수 출력 (연신율적용된 값)
  ctx.strokeText('( J ) ' + jj +' (연신율' + sumbend2 + '적용)', startX+b, startY + a/2);   // j치수 출력 (연신율적용)
  ctx.strokeText('( E ) ' + e, startX +b-f2 + 10, startY+a-k-20);   // E치수 출력  
  ctx.strokeText('각도 ' + Angle + ' º', startX +b-f2 - 100, startY+a-k-140);   // Angle값 출력  
  ctx.strokeText('( K ) ' + kk +' (연신율' + bend3 + '적용)', startX +b-f2 + 10, startY+a-k+10);   // k치수 출력  
  ctx.strokeText('( F2 ) ' + f2, startX +b-f2 + 20, startY+a + 20);   // f2치수 출력  
  ctx.strokeText('( OP ) ' + op, (startX*2 +b)/2-10, startY+a + 20);   // op치수 출력  
  ctx.strokeText('( F1 ) ' + f1, startX , startY+a + 20);   // f1치수 출력  
   ctx.closePath();	
   
 //상판 단면도 그리기
   ctx.beginPath();
   ctx.moveTo(startX+b+300, startY + g - bend1 + u - bend1 - bend2); // 300거리 띄워서 시작점 잡기
  
   ctx.lineTo(startX +b + 300 + u, startY + g - bend1 + u - bend1 - bend2); // u값 그리기
   ctx.stroke();  
   ctx.lineTo(startX +b + 300 + u, startY + g - bend1 + u - bend1 - bend2 + g); // g값 그리기
   ctx.stroke();    
   ctx.moveTo(startX+b+300, startY + g - bend1 + u - bend1 - bend2); // 300거리 띄워서 시작점 잡기
   ctx.lineTo(startX +b + 300 , startY + g - bend1 + u - bend1 - bend2 + j); // g값 그리기
   ctx.stroke();    
   ctx.lineTo(startX +b + 300  + k, startY + g - bend1 + u - bend1 - bend2 + j); // k값 그리기
   ctx.stroke();   
   
   
   ctx.strokeText('( J ) ' + j , startX+320+b, startY + a/2);   // j치수 출력
   ctx.strokeText('( U ) ' + u , startX+b+300, startY + ((g+u)/2)-10);   // u치수 출력   
   ctx.strokeText('( G ) ' + g , startX +b + 300 + u + 10, startY + g - bend1 + u - bend1 - bend2 + g+10);   // g치수 출력   
   ctx.strokeText('( K ) ' + k , startX +b + 300  + k, startY + g - bend1 + u - bend1 - bend2 + j +20);   // k치수 출력   
   
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

<!-- <input id="col1" type="text" value="2" style="width:30px; height:25px; float:left; ">  
<div id="col2"  style="width:100px; height:25px; float:left; margin-left:20px; "> 번째 자료 </div> -->

<input id="drawline" type="button" value="그리기" onclick="javascript:drawit();" style="width:100px; height:30px; float:left;">
<input id="alertangle" type="button" value="각도구하기" onclick="javascript:getAngle(200,200,800,400);" style="width:100px; height:30px; float:left;">

<!--<div id="col3"  style="width:60px; height:25px; float:left; margin-left:20px; "> X좌표 : </div>
<input id="x-coo" type="text" value="1" size="5" style="width:50px; height:25px; float:left; "> 
<div id="col4"  style="width:60px; height:25px; float:left; margin-left:20px; "> Y좌표 : </div>
<input id="y-coo" type="text" value="1" size="5" style="width:50px; height:25px; float:left; "> -->
<br>
<br>
<br>
   <div id="canvas_outline" style ="width:3200px; height:1200px; border: 2px solid black;">
   <div id="title" style="width:1100px; height:35px; float:left; margin-left:10px;margin-top:10px; text-align:center; font-size:28px;"></div>
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

  // 각도구하기
  /*
function getAngle(X,Y){
var Bx=180;
var By=50; 
var Ax=0;
var Ay=0;
var r = Math.atan2(Bx-Ax, By-Ay);
 //alert(r+'radians');
 if (r < 0)
  r += Math.PI * 2;
 var d = r*180/Math.PI;
 while (d < 0)
  d += 360;
  alert(d+'degrees'); 
}
*/

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

 
