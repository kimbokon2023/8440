

<!-- canvas  선언 ( 크기를 미리 정해야 한다 ) -->
<!DOCTYPE HTML>
<html>

<body>
<canvas id ="canvas" width=500 height=500 ></canvas>
 <input type="button" id="hw" value="Hello world" />

</body>

 </html>

<script>

window.onload = function(){
    var hw = document.getElementById('hw');
    hw.addEventListener('click', function(){
        //alert('Hello world');
		init();
	var p = new Path2D('M10 10 h 80 v 80 h -80 Z');	
    })
}
function init(){
  clock();
  setInterval(clock,1000);
}

function clock(){
  var now = new Date();
  var ctx = document.getElementById('canvas').getContext('2d');
  ctx.save();
  ctx.clearRect(0,0,150,150);
  ctx.translate(75,75);
  ctx.scale(0.4,0.4);
  ctx.rotate(-Math.PI/2);
  ctx.strokeStyle = "black";
  ctx.fillStyle = "white";
  ctx.lineWidth = 8;
  ctx.lineCap = "round";

  // 시계판 - 시
  ctx.save();
  for (var i=0;i<12;i++){
    ctx.beginPath();
    ctx.rotate(Math.PI/6);
    ctx.moveTo(100,0);
    ctx.lineTo(120,0);
    ctx.stroke();
  }
  ctx.restore();

  // 시계판 - 분
  ctx.save();
  ctx.lineWidth = 5;
  for (i=0;i<60;i++){
    if (i%5!=0) {
      ctx.beginPath();
      ctx.moveTo(117,0);
      ctx.lineTo(120,0);
      ctx.stroke();
    }
    ctx.rotate(Math.PI/30);
  }
  ctx.restore();
  
  var sec = now.getSeconds();
  var min = now.getMinutes();
  var hr  = now.getHours();
  hr = hr>=12 ? hr-12 : hr;

  ctx.fillStyle = "black";

  // 시간 표시 - 시
  ctx.save();
  ctx.rotate( hr*(Math.PI/6) + (Math.PI/360)*min + (Math.PI/21600)*sec )
  ctx.lineWidth = 14;
  ctx.beginPath();
  ctx.moveTo(-20,0);
  ctx.lineTo(80,0);
  ctx.stroke();
  ctx.restore();

  // 시간 표시 - 분
  ctx.save();
  ctx.rotate( (Math.PI/30)*min + (Math.PI/1800)*sec )
  ctx.lineWidth = 10;
  ctx.beginPath();
  ctx.moveTo(-28,0);
  ctx.lineTo(112,0);
  ctx.stroke();
  ctx.restore();
  
  // 시간 표시 - 초
  ctx.save();
  ctx.rotate(sec * Math.PI/30);
  ctx.strokeStyle = "#D40000";
  ctx.fillStyle = "#D40000";
  ctx.lineWidth = 6;
  ctx.beginPath();
  ctx.moveTo(-30,0);
  ctx.lineTo(83,0);
  ctx.stroke();
  ctx.beginPath();
  ctx.arc(0,0,10,0,Math.PI*2,true);
  ctx.fill();
  ctx.beginPath();
  ctx.arc(95,0,10,0,Math.PI*2,true);
  ctx.stroke();
  ctx.fillStyle = "rgba(0,0,0,0)";
  ctx.arc(0,0,3,0,Math.PI*2,true);
  ctx.fill();
  ctx.restore();

  ctx.beginPath();
  ctx.lineWidth = 14;
  ctx.strokeStyle = '#325FA2';
  ctx.arc(0,0,142,0,Math.PI*2,true);
  ctx.stroke();

  ctx.restore();
}

function drawit() {
var img = new Image();

// 변수
// 스크롤될 이미지, 방향, 속도를 바꾸려면 변수값을 바꾼다.

img.src = 'https://mdn.mozillademos.org/files/4553/Capitan_Meadows,_Yosemite_National_Park.jpg';
var CanvasXSize = 800;
var CanvasYSize = 200;
var speed = 30; // 값이 작을 수록 빨라진다
var scale = 1.05;
var y = -4.5; // 수직 옵셋

// 주요 프로그램

var dx = 0.75;
var imgW;
var imgH;
var x = 0;
var clearX;
var clearY;
var ctx;

img.onload = function() {
    imgW = img.width*scale;
    imgH = img.height*scale;
    if (imgW > CanvasXSize) { x = CanvasXSize-imgW; } // 캔버스보다 큰 이미지
    if (imgW > CanvasXSize) { clearX = imgW; } // 캔버스보다 큰 이미지
    else { clearX = CanvasXSize; }
    if (imgH > CanvasYSize) { clearY = imgH; } // 캔버스보다 큰 이미지
    else { clearY = CanvasYSize; }
    // 캔버스 요소 얻기
    ctx = document.getElementById('canvas').getContext('2d');
    // 새로 그리기 속도 설정
    return setInterval(draw, speed);
}
}
function draw() {
    // 캔버스를 비운다
    ctx.clearRect(0,0,clearX,clearY);
    // 이미지가 캔버스보다 작거나 같다면 (If image is <= Canvas Size)
    if (imgW <= CanvasXSize) {
        // 재설정, 처음부터 시작
        if (x > (CanvasXSize)) { x = 0; }
        // 추가 이미지 그리기
        if (x > (CanvasXSize-imgW)) { ctx.drawImage(img,x-CanvasXSize+1,y,imgW,imgH); }
    }
    // 이미지가 캔버스보다 크다면 (If image is > Canvas Size)
    else {
        // 재설정, 처음부터 시작
        if (x > (CanvasXSize)) { x = CanvasXSize-imgW; }
        // 추가 이미지 그리기
        if (x > (CanvasXSize-imgW)) { ctx.drawImage(img,x-imgW+1,y,imgW,imgH); }
    }
    // 이미지 그리기
    ctx.drawImage(img,x,y,imgW,imgH);
    // 움직임 정도
    x += dx;
}
</script>