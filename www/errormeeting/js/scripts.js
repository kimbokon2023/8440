// This file is intentionally blank
// Use this file to add JavaScript to your project

let Objectcart = {};  // javascript 오브젝트 선언

$(document).ready(function(){
		 
	// deleteCookie('shopcart'); 
	
// 쿠키의 장바구니 수치를 불러옴

// allDelCookies('http://8440.co.kr/shop', '/');

reloadShopCart();

/*
if(isNum(getcartnum)==false || getcartnum<=0 ) // 숫자가 아닐때
	cartnum=0;

}

*/
	
		$("#saveBtn").click(function(){   	
		   // grid 배열 form에 전달하기						    						    
		  $("#board_form").submit(); 								 
		 });	
		 
		$("#Materialshow").click(function(){   	
		   // grid 배열 form에 전달하기						    						    
		  $("#Material").toggle(1000); 								 
		 });	
	 
	
// $('#myModal').on('shown.bs.modal', function () {
  // $('#myInput').focus();
// });		
	
});

    function zoomIn(event) {
      event.target.style.transform = "scale(1.2)"; //1.2배 확대
      event.target.style.zIndex = 1;
      event.target.style.transition = "all 0.5s";// 속도
    }
  
    function zoomOut(event) {
      event.target.style.transform = "scale(1)";
      event.target.style.zIndex = 0;
      event.target.style.transition = "all 0.5s";
    }

    function movetolist() {	
	location.href ='list.php';
	}
    function movetocart() {	
	location.href ='cart.php';
	}
    function movetodelivery() {	
	location.href ='delivery.php';
	}
	

function inputNumberFormat(obj) { 
    obj.value = comma(uncomma(obj.value)); 
} 
function comma(str) { 
    str = String(str); 
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'); 
} 
function uncomma(str) { 
    str = String(str); 
    return str.replace(/[^\d]+/g, ''); 
}

function date_mask(formd, textid) {

/*
input onkeyup에서
formd == this.form.name
textid == this.name
*/

var form = eval("document."+formd);
var text = eval("form."+textid);

var textlength = text.value.length;

if (textlength == 4) {
text.value = text.value + "-";
} else if (textlength == 7) {
text.value = text.value + "-";
} else if (textlength > 9) {
//날짜 수동 입력 Validation 체크
var chk_date = checkdate(text);

if (chk_date == false) {
return;
}
}
}

function checkdate(input) {
   var validformat = /^\d{4}\-\d{2}\-\d{2}$/; //Basic check for format validity 
   var returnval = false;

   if (!validformat.test(input.value)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else { //Detailed check for valid date ranges 
    var yearfield = input.value.split("-")[0];
    var monthfield = input.value.split("-")[1];
    var dayfield = input.value.split("-")[2];
    var dayobj = new Date(yearfield, monthfield - 1, dayfield);
   }

   if ((dayobj.getMonth() + 1 != monthfield)
     || (dayobj.getDate() != dayfield)
     || (dayobj.getFullYear() != yearfield)) {
    alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
   } else {
    //alert ('Correct date'); 
    returnval = true;
   }
   if (returnval == false) {
    input.select();
   }
   return returnval;
  }
  
function input_Text(){
    document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value)*1.1));   // 콤마를 계산해 주고 다시 붙여주고
  var copyText = document.getElementById("test");   // 클립보드 복사 
  copyText.select();
  document.execCommand("Copy");
}  

function captureReturnKey(e) {
    if(e.keyCode==13 && e.srcElement.type != 'textarea')
    return false;
}

function recaptureReturnKey(e) {
    if (e.keyCode==13)
        exe_search();
}
function Enter_Check(){
var tmp = $('input[name=search_opt]:checked').val();	
	
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13 && tmp== 1 )
      search_jamb();  // 잠 현장검색
	  
    if(event.keyCode == 13 && tmp== 2 )
      search_ceiling();  // 천장 현장 검색	      
}

function Choice_search() {
var tmp = $('input[name=search_opt]:checked').val();	
	if(tmp =='1' )
      search_jamb();  // 잠 현장검색	  
    if(tmp == '2' )
      search_ceiling();  // 천장 현장 검색	      
  
 // alert(tmp);
  }
  
function Enter_CheckTel(){
        // 엔터키의 코드는 13입니다.
    if(event.keyCode == 13){
      exe_searchTel();  // 실행할 이벤트
    }
}

function deldate(){	

document.getElementById("indate").value  = "";
var _today = new Date();   

printday=_today.format('yyyy-MM-dd');   
document.getElementById("outdate").value  = printday;
$("input[name='which']:radio[value='1']").attr("checked", true) ;

}  



// _today.format   사용하려면 아래 내용이 함께 포함되어야 합니다.

Date.prototype.format = function (f) {

    if (!this.valueOf()) return " ";



    var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];

    var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];

    var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

    var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    var d = this;



    return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function ($1) {

        switch ($1) {

            case "yyyy": return d.getFullYear(); // 년 (4자리)

            case "yy": return (d.getFullYear() % 1000).zf(2); // 년 (2자리)

            case "MM": return (d.getMonth() + 1).zf(2); // 월 (2자리)

            case "dd": return d.getDate().zf(2); // 일 (2자리)

            case "KS": return weekKorShortName[d.getDay()]; // 요일 (짧은 한글)

            case "KL": return weekKorName[d.getDay()]; // 요일 (긴 한글)

            case "ES": return weekEngShortName[d.getDay()]; // 요일 (짧은 영어)

            case "EL": return weekEngName[d.getDay()]; // 요일 (긴 영어)

            case "HH": return d.getHours().zf(2); // 시간 (24시간 기준, 2자리)

            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2); // 시간 (12시간 기준, 2자리)

            case "mm": return d.getMinutes().zf(2); // 분 (2자리)

            case "ss": return d.getSeconds().zf(2); // 초 (2자리)

            case "a/p": return d.getHours() < 12 ? "오전" : "오후"; // 오전/오후 구분

            default: return $1;

        }

    });

};

String.prototype.string = function (len) { var s = '', i = 0; while (i++ < len) { s += this; } return s; };

String.prototype.zf = function (len) { return "0".string(len - this.length) + this; };

Number.prototype.zf = function (len) { return this.toString().zf(len); };
				
$("#catagory").on("change", function(){
    //selected value
    opt = $("option:selected", this).val();
    //selected option element
	
	if(opt=="부엉이")
		$("#dporder").val('1');
	if(opt=="비둘기십자가")
		$("#dporder").val('2');
	if(opt=="달마")
		$("#dporder").val('3');
	if(opt=="아이언맨")
		$("#dporder").val('4');
	if(opt=="호랑이")
		$("#dporder").val('5');
	if(opt=="에펠탑")
		$("#dporder").val('6');
	if(opt=="돈나무")
		$("#dporder").val('7');
	if(opt=="사과")
		$("#dporder").val('8');
	if(opt=="모스크")
		$("#dporder").val('9');
	if(opt=="스파이더맨")
		$("#dporder").val('10');
	if(opt=="오페라하우스")
		$("#dporder").val('11');
});

function isNum(s) {
  s += ''; // 문자열로 변환
  s = s.replace(/^\s*|\s*$/g, ''); // 좌우 공백 제거
  if (s == '' || isNaN(s)) return false;
  return true;
}


function setCookie (cookie_name, value, minutes) {
    const exdate = new Date();
    exdate.setMinutes(exdate.getMinutes() + minutes);
    // const cookie_value = escape(value) + ((minutes == null) ? '' : '; expires=' + exdate.toUTCString());
    const cookie_value = value + ((minutes == null) ? '' : '; expires=' + exdate.toUTCString()); // 암호화 끔
    document.cookie = cookie_name + '=' + cookie_value;
}

function getCookie(cookie_name) {
    var x, y;
    var val = document.cookie.split(';');
  
    for (var i = 0; i < val.length; i++) {
      x = val[i].substr(0, val[i].indexOf('='));
      y = val[i].substr(val[i].indexOf('=') + 1);
      x = x.replace(/^\s+|\s+$/g, ''); // 앞과 뒤의 공백 제거하기
      if (x == cookie_name) {
        // return unescape(y); // unescape로 디코딩 후 값 리턴
        return y; // 암호화 끔
      }
    }
  }

function deleteCookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

// 쇼핑카트 badge수정 수량나타냄
function reloadShopCart() {  

		console.log(document.cookie);

		let getcart = getCookie('shopcart'); 

		console.log(getcart);


		if(getcart!=null)  // shopcart가 null인 경우
			{	
			 console.log('null 없음 자료있음');
				
			Objectcart = JSON.parse(getcart);

			let Cartcount=Object.keys(Objectcart).length;

			console.log(getcart);
			console.log(Cartcount);
			$("#cartsave").val(Cartcount);
			$("#cartnum").text(Cartcount);

			$("#cartdisplay").text(Cartcount);
			}	
		else
				{
				// 장바구니 0으로 표시
				let Cartcount=0;
					$("#cartsave").val(Cartcount);
					$("#cartnum").text(Cartcount);

					$("#cartdisplay").text(Cartcount);

				}	
	
}

/*

for (key in shoppingCartList) {
    $('#orderList').append($('<div />', {
        class: 'orderMenu',
        id: key // 여기서 shoppingCartList의 key는 menuNo
    }).append($('<div />', {
        class: 'orderMenuName',
        text: shoppingCartList[key]["menuName"]
    })).append($('<div />', {
        class: 'orderMenuPrice',
        text: shoppingCartList[key]["totalPrice"],
        id: 'price' + key
    })).append($('<div />', {
        class: 'orderQuantity',
    }).append($('<div />', {
        text: '▼',
        class: 'orderMenuQuantityDec',
        click: function() {
                // 개수 증가                 
        }
    })).append($('<div />', {
        text: shoppingCartList[key]["count"],
        class: 'orderMenuQuantity',
        id: "cntNum" + key
    })).append($('<div />', {
        text: '▲',
        class: 'orderMenuQuantityInc',
        id: 'orderMenuIc',
        click: function() {
                // 개수 감소
        }
    }))).append($('<div />', {
        class: 'orderMenuCancel', 
        text: 'CANCEL',
        click: function() {
                // 해당 메뉴 삭제
        }
    })));
}

*/


// 쿠키 전체 삭제하기
const allDelCookies = (domain, path) => {
  // const doc = document;
  domain = domain || document.domain;
  path = path || '/';

  const cookies = document.cookie.split('; '); // 배열로 반환
  console.log(cookies);
  const expiration = 'Sat, 01 Jan 1972 00:00:00 GMT';

  // 반목문 순회하면서 쿠키 전체 삭제
  if (!document.cookie) {
    alert('삭제할 쿠키가 없습니다.');
  } else {
    for (i = 0; i < cookies.length; i++) {
      // const uname = cookies[i].split('=')[0];
      // document.cookie = `${uname}=; expires=${expiration}`;
      document.cookie = cookies[i].split('=')[0] + '=; expires=' + expiration;
      // document.cookie = cookies[i].split('=')[0] + '=; expires=' + expiration + '; domain =' + domain;
    }
   // alert('쿠키 전부 삭제완료!!');
  }
};


// 주문하기 이후 장바구니에서 삭제하기
function cartdel(num) {			

// 쿠키 내용 변경하기	
// 쿠키 불러옴
let getcart = getCookie("shopcart");

	if(getcart!=null)
	{	
		Objectcart = JSON.parse(getcart);

		console.log('쿠키삭제 장바구니 내용변경');
		// 특정 id의 값이 같은 것을 삭제함.
		const idx = Objectcart.findIndex(function(item) {return item.id === num});
		Objectcart.splice(idx,1);

		 console.log(Objectcart);

		setCookie ('shopcart', JSON.stringify(Objectcart), 3600);   // 쿠키에 저장함

		reloadShopCart(); // 장바구니 수량 화면 수정

	}

 }


// 넘어온 값이 빈값인지 체크합니다.
// !value 하면 생기는 논리적 오류를 제거하기 위해
// 명시적으로 value == 사용
// [], {} 도 빈값으로 처리
var isEmpty = function(value){
	if( value == "" || value == null || value == undefined || ( value != null && typeof value == "object" && !Object.keys(value).length ) ){
	  return true;  
		}else{
              return false;
            }
          };
