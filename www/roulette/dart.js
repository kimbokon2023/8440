// 전체각도 표시
var angle;
var arcd;
var spinAngleStart;
var spinArcStart;

var gift_arr = [];

var giftgiftrndNum;

var options = [
                {
                  "name": "다트 쏘기",
                  "icon": "🎁"
                },
                {
                  "name": "다트 쏘기",
                  "icon": "🎁"
                },
                {
                  "name": "다트 쏘기",
                  "icon": "🎁"
                },				
                {
                  "name": "다트 쏘기",
                  "icon": "🎁"
                },	
                {
                  "name": "다트 쏘기",
                  "icon": "🎁"
                }				
              ];
			  		
					
// 배열수량					
var options_val =  [ 5, 3, 3, 3, 3 ];
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


var audio = new Audio("/roulette/333.mp3");
var audioSelection = new Audio("/roulette/333.mp3");

// 선택되면 배열에 1 넣는다
// 17개 배열 초기값 0으로 세팅
// person_arr = Array.from({length: 17}, () => 0);
person_arr = [];

var savedPerson ;

var rndNum;

var options_person = [
	{
	  "name": "이경묵",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "권영철",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "이소정",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "김수로",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "최장중",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "조경임",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "라나",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "김영무",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "소민지",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "안현섭",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "셔집",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "딥",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "이미래",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "까심",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "조성원",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "볼한",
	  "icon": "👨‍🎓"
	},
	{
	  "name": "김보곤",
	  "icon": "👨‍🎓"
	}
  ];

	
  var rolLength_p = options_person.length; // 해당 룰렛 콘텐츠 갯수
  
  startAngle = 0;
  arc_p = Math.PI / (options_person.length / 2);
  spinTimeout = null;

  spinArcStart = 10;
  spinTime = 0;
  spinTimeTotal = 0;
  
  var ctx_p ;


function drawRouletteWheel_p() {
  var canvas_person = document.getElementById("canvas_person");
  if (canvas_person.getContext) {
    var outsideRadius = 200;
    var textRadius = 160;
    var insideRadius = 125;

    ctx_p = canvas_person.getContext("2d");
    ctx_p.clearRect(0,0,500,500);

    ctx_p.strokeStyle = "black";
    ctx_p.lineWidth = 2;

    ctx_p.font = 'bold 16px Helvetica, Arial';

    for(var i = 0; i < options_person.length; i++) {
	  // 전역변수로 사용 angle
      angle = startAngle + i * arc_p;
	  
	 //  console.log("현재각도 angle : " + angle);
      //ctx_p.fillStyle = colors[i];
      ctx_p.fillStyle = getColor(i, options_person.length);

      ctx_p.beginPath();
      ctx_p.arc(250, 250, outsideRadius, angle, angle + arc_p, false);
      ctx_p.arc(250, 250, insideRadius, angle + arc_p, angle, true);
      ctx_p.stroke();
      ctx_p.fill();

		ctx_p.save();      
		ctx_p.shadowColor = "rgba(0,0,0,0.5)";
		ctx_p.shadowBlur = 5;
		ctx_p.shadowOffsetX = 5;
		ctx_p.shadowOffsetY = 5;

	  
      ctx_p.fillStyle = "white";
      ctx_p.translate(250 + Math.cos(angle + arc_p / 2) * textRadius, 
                    250 + Math.sin(angle + arc_p / 2) * textRadius);
      ctx_p.rotate(angle + arc_p / 2 + Math.PI / 2);
      var text = options_person[i].name;	  
      ctx_p.fillText(text, -ctx_p.measureText(text).width / 2, 0);
      ctx_p.restore();
    } 

    //Arrow
		ctx_p.fillStyle = "black";
		ctx_p.beginPath();
		ctx_p.moveTo(250 - 4, 250 - (outsideRadius + 5));
		ctx_p.lineTo(250 + 4, 250 - (outsideRadius + 5));
		ctx_p.lineTo(250 + 4, 250 - (outsideRadius - 5));
		ctx_p.lineTo(250 + 9, 250 - (outsideRadius - 5));
		ctx_p.lineTo(250 + 0, 250 - (outsideRadius - 13));
		ctx_p.lineTo(250 - 9, 250 - (outsideRadius - 5));
		ctx_p.lineTo(250 - 4, 250 - (outsideRadius - 5));
		ctx_p.lineTo(250 - 4, 250 - (outsideRadius + 5));
		ctx_p.fill();
  }
}

var hangawiGreetings = [
    "풍성한 한가위, 건강과 행복이 가득하길 바랍니다. 가정에 웃음과 행복이 넘치는 한가위 되세요.  랄라랄랄루루랄라랄랄루루",
    "밝은 달처럼 행복이 가득한 명절 보내세요. 넉넉한 마음으로 한가위 보내시고 행복 가득하세요.  뚜루뚜루뚜뚜루루루뚜루뚜",
    "가족과 함께 즐거운 추석 보내시길 바랍니다. 하늘만큼 큰 복이 가득한 한가위 되세요.  찌리찌리짜짜찌찌리리찌짜",
    "한가위 보름달처럼 마음도 둥글고 밝게! 넉넉한 마음으로 행복한 추석 보내세요.  루루라라루루루라라루루라라",
    "달빛처럼 은은한 기쁨이 가득한 추석 되세요. 한가위엔 마음도 풍성하고 따뜻한 날 되세요.  라라리리라라라리리라라리리",
    "즐겁고 풍요로운 추석 보내세요. 한가위 달처럼 환한 웃음 가득하시길 바랍니다.  두비두비두바바두비두바바",
    "가족들과 행복한 한가위 보내세요! 보름달처럼 풍성한 한가위 되시길 기원합니다.  빠라빠라빰빰빠라빠라빰빰",
    "건강하고 행복 가득한 한가위 되세요. 한가위엔 행복과 웃음이 넘치는 날 되시길!  띠리띠리리리띠띠리리리띠",
    "달처럼 밝고 풍요로운 추석 되시길 바랍니다. 가족과 함께 즐겁고 행복한 한가위 보내세요.  뚜뚜루루루뚜뚜뚜루루루뚜뚜",
    "풍성한 한가위, 가정에 행복과 평안이 가득하길! 가을처럼 풍성하고 행복한 한가위 되세요!  찌찌빠빠찌찌찌찌빠빠찌찌"
];

function spin_person() {    
    console.clear();  
    console.log("rolLength_p " + rolLength_p);
	$("#person_su").text(rolLength_p);
    // console.log(person_arr);  
    // console.log(person_arr.length);
  
    while(true){
        if(rolLength_p  == person_arr.length)       
            break;
        search = 0;      
        rndNum = rnd();      
        console.log('rnd ' + rndNum );
        for(var i=0; i <rolLength_p; i++) {
            if(person_arr[i] == rndNum ) {
                search = 1;            
            }
        }
        if(!search) {
            person_arr.push(rndNum);
            person_arr.sort(function (a, b) {
                return a - b;
            });             

            break;       
        }           
    }
    
    // 오디오 파일 변경 (캐시 방지용으로 쿼리 스트링 추가)
    audio.src = "/roulette/333.mp3" + "?v=" + new Date().getTime();  // 캐시 방지
    var isPlaying = audio.currentTime > 0 && !audio.paused && !audio.ended 
        && audio.readyState > audio.HAVE_CURRENT_DATA;

    if (!isPlaying) {
        audio.play();
        audio.volume = 1;
    }         

    arcd_p = arc_p * 180 / Math.PI;
    spinAngleStart = rnd() * arcd_p + 10;
    spinTime = 0;
    spinTimeTotal = rnd() * arcd_p + 3610;
    
    rotateWheel_p();

    // 랜덤 덕담 선택 후 읽어주는 기능 추가
    var randomGreeting = hangawiGreetings[Math.floor(Math.random() * hangawiGreetings.length)];
    speech(randomGreeting);
}


function rotateWheel_p() {
	// 속도를 올리려면 숫자를 크게
  spinTime += 20;
  
  var degrees = startAngle * 180 / Math.PI + 90;
  var arcd_p = arc_p * 180 / Math.PI;  
  // index가 결국 랜덤숫자를 정하는 것임
  var index = Math.floor((360 - degrees % 360) / arcd_p);
  
    // 특정위치에 정지하도록 설정 StartAngle
  if(spinTime >= spinTimeTotal  && index == rndNum  ) {
		stoprotateWheel_p();
    return;
  }
  
  // 결국 StartAngle을 지정해서 끝내야 함.
  var tmp = easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
  var spinAngle = spinAngleStart - tmp ;
  startAngle += (spinAngle * Math.PI / 180);
 
 //  console.log("easeOut " + tmp);	
 
  drawRouletteWheel_p();
  spinTimeout = setTimeout('rotateWheel_p()', 20);
}

function stoprotateWheel_p() {
  clearTimeout(spinTimeout);  
  var degrees = startAngle * 180 / Math.PI + 90;
  var arcd_p = arc_p * 180 / Math.PI;
  
  // index가 결국 랜덤숫자를 정하는 것임
  var index = Math.floor((360 - degrees % 360) / arcd_p);
  
  // console.log(startAngle);
  // console.log(arcd_p);
  // console.log("선택결과 " + index);
    
  var text = options_person[index];
  $("#personlist").append('<div class="d-flex  justify-content-center mt-1"><span class="badge bg-secondary text-center">' + text.name + '</span></div>');
  // 직원이름 저장
  savedPerson = text.name ;
  // 선정된 직원수 화면표시
  $("#person_script").html('<h4 class="text-center"> 선택된 분(' + person_arr.length + ') </h4>');
  
  ctx_p.save();
  ctx_p.font = 'bold 25px Helvetica, Arial';

 //  var musicText = "./media/"+text.name;
  text = text.icon + " " + text.name;
  
  setTimeout(function(){}, 100);

  var isPlaying = audioSelection.currentTime > 0 && !audioSelection.paused && !audioSelection.ended 
    && audioSelection.readyState > audioSelection.HAVE_CURRENT_DATA;

    // if (!isPlaying) {
      // audioSelection.play();
      // audioSelection.volume = 1.0;
    // };


  ctx_p.fillText(text, 250 - ctx_p.measureText(text).width / 2, 250 + 10);
  ctx_p.restore();
  
  ctx.restore();  

}

function rnd() {
	  var min = Math.ceil(0);
	  var max = Math.floor(rolLength_p );
	  
	  var result = Math.floor(Math.random() * (max - min)) + min;
	  if(result>max)
		  result = Math.floor(rolLength_p );
	  return result;
}

drawRouletteWheel_p();