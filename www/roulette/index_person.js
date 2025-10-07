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