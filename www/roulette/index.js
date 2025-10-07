// 전체각도 표시
var angle;
var arcd;
var spinAngleStart;
var spinArcStart;

var gift_arr = [];

var giftgiftrndNum;

var options = [
                {
                  "name": "정선임계 고랭지 사과",
                  "icon": "🎁"
                },
                {
                  "name": "노란보따리 사과",
                  "icon": "🎁"
                },
                {
                  "name": "빨간보따리 사과",
                  "icon": "🎁"
                },				
                {
                  "name": "순천 배",
                  "icon": "🎁"
                },	
                {
                  "name": "예산 배",
                  "icon": "🎁"
                },				
                {
                  "name": "프리미엄선물세트(과일)",
                  "icon": "🎁"
                },	
                {
                  "name": "뜨라네 사과배 혼합세트",
                  "icon": "🎁"
                },	
                {
                  "name": "멜론",
                  "icon": "🎁"
                },
                {
                  "name": "홍삼", 
                  "icon": "🎁"
                },
                {
                  "name": "종합선물세트(멸치 등)",
                  "icon": "🎁"
                },
                {
                  "name": "스팸 8호",
                  "icon": "🎁"
                }
              ];
			  		
					
// 배열수량					
var options_val =  [ 2, 1, 1, 1, 1, 1, 1, 1, 2, 4, 2 ]; //22개 중 스팸5개 제외상품
var options_remain = 0 ;

var html;
displaytotal();
					
var rolLength = options.length; // 해당 룰렛 콘텐츠 갯수	

var startAngle = 0;
var arc = Math.PI / (options.length / 2);
var spinTimeout = null;

var spinArcStart = 10;
var spinTime = 0;
var spinTimeTotal = 0;

var ctx;

document.getElementById("spin").addEventListener("click", spin);


function spin() {

	//랜덤숫자부여 컨텐츠 숫자에 의거한 숫자 계산
	// 변수를 함수처럼 선언하는 방법
		
	  console.clear();  
	  console.log("rolLength " + rolLength);
	  console.log(gift_arr);
	  console.log(gift_arr.length);
	  
	while(true){
	  if( options_remain  == 0  ) // 수량이 남아있지 않으면 종료
		   break;
		search = 0;	  
		giftrndNum = giftrnd();	  // 난수발생 
		console.log('상품 rnd ' + giftrndNum );
		
		///////options_remain 이 숫자로 비교 분석해야 함.
		
		 for(var i=0; i <rolLength; i++ )
			{
			 if(i == giftrndNum && options_val[i] > 0)  // 선물수량이 남아있으면 
				{	           
					search = 1;		
					options_val[i] = options_val[i] - 1 ;  // 재고 1개 감소
                    
				}
			}
			if(search) // 찾았으면
				{
				 gift_arr.push(giftrndNum);
					gift_arr.sort(function (a, b) {
					  return a - b;
					});			 
				 
				 break;	   
				}
				
		}
		
		// console.log(gift_arr);	


		arcd = arc * 180 / Math.PI;
		// console.log("arcd " + arcd);
		spinAngleStart = giftrndNum * arcd + 10;
		spinTime = 0;
		//spinTimeTotal = Math.random() * 10 + 10 * 1000;
		spinTimeTotal = giftrndNum * arcd + 3610;
		
	 // console.log("spinAngleStart " + spinAngleStart);	
	 // console.log("spinTimeTotal " + spinTimeTotal);	
		
		rotateWheel();
}


function byte2Hex(n) {
  var nybHexString = "0123456789ABCDEF";
  return String(nybHexString.substr((n >> 4) & 0x0F,1)) + nybHexString.substr(n & 0x0F,1);
}

function RGB2Color(r,g,b) {
	return '#' + byte2Hex(r) + byte2Hex(g) + byte2Hex(b);
}

function getColor(item, maxitem) {
  var phase = 0;
  var center = 128;
  var width = 127;
  var frequency = Math.PI*2/maxitem;
  
  red   = Math.sin(frequency*item+2+phase) * width + center;
  green = Math.sin(frequency*item+0+phase) * width + center;
  blue  = Math.sin(frequency*item+4+phase) * width + center;
  
  return RGB2Color(red,green,blue);
}

function drawRouletteWheel() {
  var canvas = document.getElementById("canvas");
  if (canvas.getContext) {
    var outsideRadius = 200;
    var textRadius = 160;
    var insideRadius = 125;

    ctx = canvas.getContext("2d");
    ctx.clearRect(0,0,500,500);

    ctx.strokeStyle = "black";
    ctx.lineWidth = 2;

    ctx.font = 'bold 16px Helvetica, Arial';

    for(var i = 0; i < options.length; i++) {
	  // 전역변수로 사용 angle
      angle = startAngle + i * arc;
	  
	  // console.log("현재각도 angle : " + angle);
      //ctx.fillStyle = colors[i];
      ctx.fillStyle = getColor(i, options.length);

      ctx.beginPath();
      ctx.arc(250, 250, outsideRadius, angle, angle + arc, false);
      ctx.arc(250, 250, insideRadius, angle + arc, angle, true);
      ctx.stroke();
      ctx.fill();

        ctx.save();
		ctx.shadowColor = "rgba(0,0,0,0.1)";
		ctx.shadowBlur = 5;
		ctx.shadowOffsetX = 5;
		ctx.shadowOffsetY = 5;
      ctx.fillStyle = "white";
      ctx.translate(250 + Math.cos(angle + arc / 2) * textRadius, 
                    250 + Math.sin(angle + arc / 2) * textRadius);
      ctx.rotate(angle + arc / 2 + Math.PI / 2);
      var text = options[i].name;	  
      ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
      ctx.restore();
    } 

    //Arrow
		ctx.fillStyle = "black";
		ctx.beginPath();
		ctx.moveTo(250 - 4, 250 - (outsideRadius + 5));
		ctx.lineTo(250 + 4, 250 - (outsideRadius + 5));
		ctx.lineTo(250 + 4, 250 - (outsideRadius - 5));
		ctx.lineTo(250 + 9, 250 - (outsideRadius - 5));
		ctx.lineTo(250 + 0, 250 - (outsideRadius - 13));
		ctx.lineTo(250 - 9, 250 - (outsideRadius - 5));
		ctx.lineTo(250 - 4, 250 - (outsideRadius - 5));
		ctx.lineTo(250 - 4, 250 - (outsideRadius + 5));
		ctx.fill();
  }
}

function rotateWheel() {
	// 속도를 올리려면 숫자를 크게
  spinTime += 20;
  
  var degrees = startAngle * 180 / Math.PI + 90;
  var arcd = arc * 180 / Math.PI;  
  // index가 결국 랜덤숫자를 정하는 것임
  var index = Math.floor((360 - degrees % 360) / arcd);
  
    // 특정위치에 정지하도록 설정 StartAngle
  if(spinTime >= spinTimeTotal  && index == giftrndNum  ) {
		stopRotateWheel();
    return;
  }
  
  // 결국 StartAngle을 지정해서 끝내야 함.
  var tmp = easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
  var spinAngle = spinAngleStart - tmp ;
  startAngle += (spinAngle * Math.PI / 180);
 
 //  console.log("easeOut " + tmp);	
 
  drawRouletteWheel();
  spinTimeout = setTimeout('rotateWheel()', 20);
}

function stopRotateWheel() {
  clearTimeout(spinTimeout);  
  var degrees = startAngle * 180 / Math.PI + 90;
  var arcd = arc * 180 / Math.PI;
  
  // index가 결국 랜덤숫자를 정하는 것임
  var index = Math.floor((360 - degrees % 360) / arcd);
  
  // console.log(startAngle);
  // console.log(arcd);
  // console.log("선택결과 " + index);
  
      
  var text = options[index];
  $("#giftlist").append(savedPerson + '님 (' + text.name + ')' + '<br>');
  // 선정된 직원수 화면표시  
  $("#gift_script").html('<h4 class="text-center"> 선물 선택결과(' + gift_arr.length + ') </h4>');
  
  // 음성출력
  var word = savedPerson + ',님, ' + text.name + ', 축하합니다.' ;  
  speech(word);
  // 화면에 수량표시
  displaytotal();
    
  ctx.save();
  ctx.font = 'bold 30px Helvetica, Arial';  
 //  var musicText = "./media/"+text.name;
  text = text.icon + " " + text.name;

  ctx.fillText(text, 250 - ctx.measureText(text).width / 2, 250 + 10);
  audioSelection.pause();
};

function easeOut(t, b, c, d) {
  var ts = (t/=d)*t;
  var tc = ts*t;
  return b+c*(tc + -3*ts + 3*t);
}

function displaytotal() {
		html = '<h5 class="text-start"> ';
        options_remain = 0;   
		for(var i = 0; i< options_val.length; i++ )
		{
			if(options_val[i] > 0)
			 {
				html +=  options[i].name + ' ' +  options_val[i] + 'EA, ';	
				options_remain +=  Number(options_val[i]);
			 }
		}
		   // console.log(options_remain);
			html += '</h5> <br> <h5 class="text-center"> 남은 선물 개수 : ' + options_remain + 'EA';
			html += ' </h5>';
		$("#display").html(html);
}

function giftrnd(){
	  var min = Math.ceil(0);
	  var max = Math.floor(rolLength);
	  
	  var result = Math.floor(Math.random() * (max - min)) + min;
	  if(result>max)
		  result = Math.floor(rolLength);
	  return result;
}

function speech(word){
	 $("#text").val(word);
	const selectLang = document.getElementById("select-lang")
	const text = document.getElementById("text")
	speak(text.value, {
		rate: 1.2,
		pitch: 1.5,
		lang: selectLang.options[selectLang.selectedIndex].value
	}) 	 
}

drawRouletteWheel();