function popupCenter(href, pop_name, w, h) {
    // 고유한 팝업 창 이름을 생성하기 위해 현재 시간을 이용
    var uniqueName = pop_name + '_' + new Date().getTime();

    // 화면 가로 위치
    var xPos = (window.innerWidth / 2) - (w / 2) + window.screenX;
    // 화면 세로 위치
    var yPos = (window.innerHeight / 2) - (h / 2) + window.screenY;

    window.open(href, uniqueName, "width=" + w + ", height=" + h + ", left=" + xPos + ", top=" + yPos + ", target=_blank, menubar=yes, status=yes, titlebar=yes, resizable=yes");
}


function customPopup(href, pop_name, w, h) {
    // 고유한 팝업 창 이름을 생성하기 위해 현재 시간을 이용
    var uniqueName = pop_name + '_' + Date.now();

    // 화면 가로 위치
    var xPos = (window.innerWidth / 2) - (w / 2) + window.screenX;
    // 화면 세로 위치
    var yPos = (window.innerHeight / 2) - (h / 2) + window.screenY;

    // 피처 수정: 주소 줄 제거 및 다른 옵션 설정
    var features = "width=" + w + ", height=" + h + ", left=" + xPos + ", top=" + yPos;
    features += ", menubar=no, status=no, titlebar=no, resizable=yes, toolbar=no, location=no, directories=no";

    // 새로운 팝업 창을 엽니다.
    window.open(href, uniqueName, features);
}

function getYearMonth(){   // 2021-01형태 리턴
    var now = new Date();
    var year = now.getFullYear();
    var month = now.getMonth() + 1;    //1월이 0으로 되기때문에 +1을 함.
    var date = now.getDate();

if(month % 2 == 0){ // 달(0~11)을 2로 나눈 나머지 없으면 홀수달
 // 홀수달 실행

    month = month >=10 ? month : "0" + month;
    date  = date  >= 10 ? date : "0" + date;
     // ""을 빼면 year + month (숫자+숫자) 됨.. ex) 2018 + 12 = 2030이 리턴됨.

    //console.log(""+year + month + date);
    return today = ""+year + "-" + month ; 
}else{
    //짝수달 실행 코드
	
    month = month >=10 ? month : "0" + month;
    date  = date  >= 10 ? date : "0" + date;
     // ""을 빼면 year + month (숫자+숫자) 됨.. ex) 2018 + 12 = 2030이 리턴됨.

    //console.log(""+year + month + date);
    return today = ""+year + "-" + month ;	

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

// 특정일자의 요일을 돌려주는 함수
function getDayOfWeek(datestr){ //ex) getDayOfWeek('2022-06-13')

    const week = ['일', '월', '화', '수', '목', '금', '토'];

    const dayOfWeek = week[new Date(datestr).getDay()];

    return dayOfWeek;
}

// 특정날짜 기간을 입력받고 그 기간의 데이터를 배열로 돌려줌
function getDatesStartToLast(startDate, lastDate) {
	var regex = RegExp(/^\d{4}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/);
	if(!(regex.test(startDate) && regex.test(lastDate))) return "Not Date Format";
	var result = [];
	var curDate = new Date(startDate);
	while(curDate <= new Date(lastDate)) {
		result.push(curDate.toISOString().split("T")[0]);
		curDate.setDate(curDate.getDate() + 1);
	}
	return result;
}


function EGI_list_click() {
    $("#Bigsearch").val("EGI");    
}

function PO_list_click() {
    $("#Bigsearch").val("PO");
}

function CR_list_click() {
    $("#Bigsearch").val("CR"); 
}

function HL304_list_click() {
    $("#Bigsearch").val("304 HL");
    
}

function MR304_list_click() {
    $("#Bigsearch").val("304 MR");
    
}

function VB_list_click() {
    $("#Bigsearch").val("VB");
    
}

function MR201_list_click() {
    $("#Bigsearch").val("201 2B MR");
    
}

function size1000_1950_list_click() {
    $("#search").val("1.2*1000*1950");
    $("#board_form").submit();
}

function size1000_2150_list_click() {
	$("#search").val("1.2*1000*2150").attr("selected", "selected") ;
	 $("#board_form").submit(); 
}

function size42150_list_click() {
	$("#search").val("1.2*1219*2150").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size1000_8_list_click() {
	$("#search").val("1.2*1000*2438").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size4_8_list_click() {
	$("#search").val("1.2*1219*2438").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}


function size4_2600_list_click() {
	$("#search").val("1.2*1219*2600").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}


function size1000_2700_list_click() {
	$("#search").val("1.2*1000*2700").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size4_2700_list_click() {
	$("#search").val("1.2*1219*2700").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size4_3000_list_click() {
	$("#search").val("1.2*1219*3000").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size4_3200_list_click() {
	$("#search").val("1.2*1219*3200").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size4_4000_list_click() {
	$("#search").val("1.2*1219*4000").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

 function size16_4_1680_list_click() {
	$("#search").val("1.6*1219*1680").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size23_4_1680_list_click() {
	$("#search").val("2.3*1219*1680").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

 function size16_4_1950_list_click() {
	$("#search").val("1.6*1219*1950").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}

function size23_4_1950_list_click() {
	$("#search").val("2.3*1219*1950").attr("selected", "selected") ;
	 $("#board_form").submit();	 
}



function size12_4_1680_list_click() {
	$("#Bigsearch").val("CR").attr("selected", "selected") ;			
	$("#search").val("1.2*1219*1680").attr("selected", "selected") ;
	 $("#board_form").submit(); 
}
function size12_4_1950_list_click() {
	$("#Bigsearch").val("CR").attr("selected", "selected") ;			
	$("#search").val("1.2*1219*1950").attr("selected", "selected") ;
	 $("#board_form").submit(); 
}
function size12_4_8_list_click() {
	$("#Bigsearch").val("CR").attr("selected", "selected") ;			
	$("#search").val("1.2*1219*2438").attr("selected", "selected") ;
	 $("#board_form").submit(); 
}

function size16_4_1680_list_click() {
	$("#Bigsearch").val("CR").attr("selected", "selected") ;	
	$("#search").val("1.6*1219*1680").attr("selected", "selected") ;
	 $("#board_form").submit(); 
}
function size16_4_1950_list_click() {
	$("#Bigsearch").val("CR").attr("selected", "selected") ;		
	$("#search").val("1.6*1219*1950").attr("selected", "selected") ;
	 $("#board_form").submit(); 
}
function size16_4_8_list_click() {
	$("#Bigsearch").val("CR").attr("selected", "selected") ;		
	$("#search").val("1.6*1219*2438").attr("selected", "selected") ;
	 $("#board_form").submit(); 
}

function size23_4_1680_list_click() {
	$("#Bigsearch").val("PO").attr("selected", "selected") ;
	$("#search").val("2.3*1219*1680").attr("selected", "selected") ;
	 $("#board_form").submit();
}
function size23_4_1950_list_click() {
	$("#Bigsearch").val("PO").attr("selected", "selected") ;	
	$("#search").val("2.3*1219*1950").attr("selected", "selected") ;
	 $("#board_form").submit();
}
function size23_4_8_list_click() {
	$("#Bigsearch").val("PO").attr("selected", "selected") ;	
	$("#search").val("2.3*1219*2438").attr("selected", "selected") ;
	 $("#board_form").submit();
}

function size32_4_1680_list_click() {
	$("#Bigsearch").val("PO").attr("selected", "selected") ;	
	$("#search").val("3.2*1219*1680").attr("selected", "selected") ;
	 $("#board_form").submit();
}

function size15_4_2150_list_click() {
	$("#Bigsearch").val("304 HL").attr("selected", "selected") ;	
	$("#search").val("1.5*1219*2150").attr("selected", "selected") ;
	 $("#board_form").submit();
}
function size15_4_8_list_click() {
	$("#Bigsearch").val("304 HL").attr("selected", "selected") ;	
	$("#search").val("1.5*1219*2438").attr("selected", "selected") ;
	 $("#board_form").submit();
}
function size20_4_8_list_click() {
	$("#Bigsearch").val("EGI").attr("selected", "selected") ;	
	$("#search").val("2.0*1219*2438").attr("selected", "selected") ;
	 $("#board_form").submit();
}







function EGI_click() {
	$("#item").val("EGI").attr("selected", "selected") ;
}
function PO_click() {
	$("#item").val("PO").attr("selected", "selected") ;
}
function CR_click() {
	$("#item").val("CR").attr("selected", "selected") ;
}
function HL304_click() {
	$("#item").val("304 HL").attr("selected", "selected") ;
}
function MR304_click() {
	$("#item").val("304 MR").attr("selected", "selected") ;
}
function VB_click() {
	$("#item").val("VB").attr("selected", "selected") ;
}
function MR201_click() {
	$("#item").val("201 2B MR").attr("selected", "selected") ;
}

function size1000_1950_click() {
	$("#spec").val("1.2*1000*1950").attr("selected", "selected") ;
	 
}
function size1000_2150_click() {
	$("#spec").val("1.2*1000*2150").attr("selected", "selected") ;
	 
}

function size42150_click() {
	$("#spec").val("1.2*1219*2150").attr("selected", "selected") ;
		 
}

function size1000_8_click() {
	$("#spec").val("1.2*1000*2438").attr("selected", "selected") ;
		 
}

function size4_8_click() {
	$("#spec").val("1.2*1219*2438").attr("selected", "selected") ;
		 
}


function size4_2600_click() {
	$("#spec").val("1.2*1219*2600").attr("selected", "selected") ;
		 
}


function size1000_2700_click() {
	$("#spec").val("1.2*1000*2700").attr("selected", "selected") ;
		 
}

function size4_2700_click() {
	$("#spec").val("1.2*1219*2700").attr("selected", "selected") ;
		 
}

function size4_3000_click() {
	$("#spec").val("1.2*1219*3000").attr("selected", "selected") ;
		 
}

function size4_3200_click() {
	$("#spec").val("1.2*1219*3200").attr("selected", "selected") ;
		 
}

function size4_4000_click() {
	$("#spec").val("1.2*1219*4000").attr("selected", "selected") ;
		 
}

 function size16_4_1680_click() {
	$("#spec").val("1.6*1219*1680").attr("selected", "selected") ;
		 
}

function size23_4_1680_click() {
	$("#spec").val("2.3*1219*1680").attr("selected", "selected") ;
		 
}

 function size16_4_1950_click() {
	$("#spec").val("1.6*1219*1950").attr("selected", "selected") ;
		 
}

function size23_4_1950_click() {
	$("#spec").val("2.3*1219*1950").attr("selected", "selected") ;
		 
}



function size12_4_1680_click() {
	$("#item").val("CR").attr("selected", "selected") ;			
	$("#spec").val("1.2*1219*1680").attr("selected", "selected") ;
	 
}
function size12_4_1950_click() {
	$("#item").val("CR").attr("selected", "selected") ;			
	$("#spec").val("1.2*1219*1950").attr("selected", "selected") ;
	 
}
function size12_4_8_click() {
	$("#item").val("CR").attr("selected", "selected") ;			
	$("#spec").val("1.2*1219*2438").attr("selected", "selected") ;
	 
}

function size16_4_1680_click() {
	$("#item").val("CR").attr("selected", "selected") ;	
	$("#spec").val("1.6*1219*1680").attr("selected", "selected") ;
	 
}
function size16_4_1950_click() {
	$("#item").val("CR").attr("selected", "selected") ;		
	$("#spec").val("1.6*1219*1950").attr("selected", "selected") ;
	 
}
function size16_4_8_click() {
	$("#item").val("CR").attr("selected", "selected") ;		
	$("#spec").val("1.6*1219*2438").attr("selected", "selected") ;
	 
}

function size23_4_1680_click() {
	$("#item").val("PO").attr("selected", "selected") ;
	$("#spec").val("2.3*1219*1680").attr("selected", "selected") ;
}
function size23_4_1950_click() {
	$("#item").val("PO").attr("selected", "selected") ;	
	$("#spec").val("2.3*1219*1950").attr("selected", "selected") ;
}
function size23_4_8_click() {
	$("#item").val("PO").attr("selected", "selected") ;	
	$("#spec").val("2.3*1219*2438").attr("selected", "selected") ;
}

function size32_4_1680_click() {
	$("#item").val("PO").attr("selected", "selected") ;	
	$("#spec").val("3.2*1219*1680").attr("selected", "selected") ;
}

function size15_4_2150_click() {
	$("#item").val("304 HL").attr("selected", "selected") ;	
	$("#spec").val("1.5*1219*2150").attr("selected", "selected") ;
}
function size15_4_8_click() {
	$("#item").val("304 HL").attr("selected", "selected") ;	
	$("#spec").val("1.5*1219*2438").attr("selected", "selected") ;
}
function size20_4_8_click() {
	$("#item").val("EGI").attr("selected", "selected") ;	
	$("#spec").val("2.0*1219*2438").attr("selected", "selected") ;
}



// 기준요일에 따른 주차구하는 함수.

// 해당 주차 / 해당주차 시작날짜 / 해당주차 끝나는날짜를 리턴.

function searchPeriodCalculation(cYear, cMonth) {

// let cYear = document.getElementById("choiceYear").value;

// let cMonth = document.getElementById("choiceMonth").value.replace(/(^0+)/, "") - 1;

        // 날짜형으로 데이트 포맷

        let date = new Date(cYear, cMonth - 1);


        // 월요일을 중심으로한 주차 구하기( JS기준 : 일요일 0 월요일 1 ~ 토요일 6 )

        let firstDay = new Date(date.getFullYear(), date.getMonth(), 1);

        let lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);



        let weekObj = null;

        let weekObjArray = new Array();

        let weekStand = 8;  // 월요일 고정

        let firstWeekEndDate = true;

        let thisMonthFirstWeek = firstDay.getDay();



        for(var num = 1; num <= 6; num++) {



            // 마지막월과 첫번째월이 다른경우 빠져나온다.

            if(lastDay.getMonth() != firstDay.getMonth()) {

                break;

            }



            weekObj = new Object();



            // 한주의 시작일은 월의 첫번째 월요일로 설정

            if(firstDay.getDay() <= 1) {



                // 한주의 시작일이 일요일이라면 날짜값을 하루 더해준다.

                if(firstDay.getDay() == 0) { firstDay.setDate(firstDay.getDate() + 1); }



                weekObj.weekStartDate =

                      firstDay.getFullYear().toString()

                    + "-"

                    + numberPad((firstDay.getMonth() + 1).toString(), 2)

                    + "-"

                    + numberPad(firstDay.getDate().toString() , 2);

            }



            if(weekStand > thisMonthFirstWeek) {

                if(firstWeekEndDate) {

                    if((weekStand - firstDay.getDay()) == 1) {

                        firstDay.setDate(firstDay.getDate() + (weekStand - firstDay.getDay()) - 1);

                    }

                    if((weekStand - firstDay.getDay()) > 1) {

                        firstDay.setDate(firstDay.getDate() + (weekStand - firstDay.getDay()) - 1)

                    }

                    firstWeekEndDate = false;

                } else {

                    firstDay.setDate(firstDay.getDate() + 6);

                }

            } else {

                firstDay.setDate(firstDay.getDate() + (6 - firstDay.getDay()) + weekStand);

            }



            // 월요일로 지정한 데이터가 존재하는 경우에만 마지막 일의 데이터를 담는다.

            if(typeof weekObj.weekStartDate !== "undefined") {



                weekObj.weekEndDate =
                      firstDay.getFullYear().toString()
                    + "-"
                    + numberPad((firstDay.getMonth() + 1).toString(), 2)
                    + "-"
                    + numberPad(firstDay.getDate().toString(), 2);                    

                weekObjArray.push(weekObj);

            }



            firstDay.setDate(firstDay.getDate() + 1);

        }

        // console.log( weekObjArray );
		
		return weekObjArray;

    }

// 매주 금요일 추출
function searchFriday(cYear, cMonth) {

        // 날짜형으로 데이트 포맷
        let date = new Date(cYear, cMonth - 1);
        // 월요일을 중심으로한 주차 구하기( JS기준 : 일요일 0 월요일 1 ~ 토요일 6 )
        let firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        let lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        let weekObj = null;
        let weekObjArray = new Array();
        let weekStand = 8;  // 월요일 고정
        let firstWeekEndDate = true;
        let thisMonthFirstWeek = firstDay.getDay();

        for(var num = 1; num <= 6; num++) {
            // 마지막월과 첫번째월이 다른경우 빠져나온다.
            if(lastDay.getMonth() != firstDay.getMonth()) {
                break;
            }
            weekObj = new Object();
            // 한주의 시작일은 월의 첫번째 월요일로 설정
            if(firstDay.getDay() <= 1) {
                // 한주의 시작일이 일요일이라면 날짜값을 하루 더해준다.
                if(firstDay.getDay() == 0) { firstDay.setDate(firstDay.getDate() + 1); }
                weekObj.weekStartDate =
                      firstDay.getFullYear().toString()
                    + "-"
                    + numberPad((firstDay.getMonth() + 1).toString(), 2)
                    + "-"
                    + numberPad(firstDay.getDate().toString() , 2);
				}

            if(weekStand > thisMonthFirstWeek) {
                if(firstWeekEndDate) {
                    if((weekStand - firstDay.getDay()) == 1) {
                        firstDay.setDate(firstDay.getDate() + (weekStand - firstDay.getDay()) - 1);
                    }
                    if((weekStand - firstDay.getDay()) > 1) {
                        firstDay.setDate(firstDay.getDate() + (weekStand - firstDay.getDay()) - 1)
                    }
                    firstWeekEndDate = false;
                } else {
                    firstDay.setDate(firstDay.getDate() + 6);
                }

            } else {
                firstDay.setDate(firstDay.getDate() + (6 - firstDay.getDay()) + weekStand);
            }
            // 월요일로 지정한 데이터가 존재하는 경우에만 마지막 일의 데이터를 담는다.
            if(typeof weekObj.weekStartDate !== "undefined") {
                weekObj.weekEndDate =
                      firstDay.getFullYear().toString()
                    + "-"
                    + numberPad((firstDay.getMonth() + 1).toString(), 2)
                    + "-"
                    + numberPad(firstDay.getDate().toString(), 2);
					// Friday
                weekObj.weekFriday =
                      firstDay.getFullYear().toString()
                    + "-"
                    + numberPad((firstDay.getMonth() + 1).toString(), 2)
                    + "-"
                    + numberPad(firstDay.getDate().toString()-2, 2);
					
                weekObjArray.push(weekObj);
            }
            firstDay.setDate(firstDay.getDate() + 1);
        }
        // console.log( weekObjArray );		
		return weekObjArray;
    }

// 월, 일 날짜값 두자리( 00 )로 변경

function numberPad(num, width) {

	num = String(num);

	return num.length >= width ? num : new Array(width - num.length + 1).join("0") + num;

}

function dateFormat(date) {
	let dateFormat2 = date.getFullYear() +
		'-' + ( (date.getMonth()+1) < 9 ? "0" + (date.getMonth()+1) : (date.getMonth()+1) )+
		'-' + ( (date.getDate()) < 9 ? "0" + (date.getDate()) : (date.getDate()) );
	return dateFormat2;
}



var imgObj = new Image();
function showImgWin(imgName) {
imgObj.src = imgName;
setTimeout("createImgWin(imgObj)", 100);
}
function createImgWin(imgObj) {
if (! imgObj.complete) {
setTimeout("createImgWin(imgObj)", 100);
return;
}
imageWin = window.open("", "imageWin",
"width=" + imgObj.width + ",height=" + imgObj.height);
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
  
 function getCurrentDateTime() {
    var currentDate = new Date();
    var year = currentDate.getFullYear();
    var month = currentDate.getMonth() + 1;
    var day = currentDate.getDate();
    var hours = currentDate.getHours(); // 24시간 형식을 사용
    var minutes = currentDate.getMinutes();
    var seconds = currentDate.getSeconds();

    // 달, 일, 시간, 분, 초를 두 자리 숫자로 표시합니다.
    month = month < 10 ? "0" + month : month;
    day = day < 10 ? "0" + day : day;
    hours = hours < 10 ? "0" + hours : hours;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    // 현재 날짜와 시간을 반환합니다.
    var currentDateTime = year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":" + seconds;
    return currentDateTime;
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

// 월, 일 날짜값 두자리( 00 )로 변경
function numberPad(num, width) {
	num = String(num);
	return num.length >= width ? num : new Array(width - num.length + 1).join("0") + num;
}

function dateFormat(date) {
	let dateFormat2 = date.getFullYear() +
		'-' + ( (date.getMonth()+1) < 9 ? "0" + (date.getMonth()+1) : (date.getMonth()+1) )+
		'-' + ( (date.getDate()) < 9 ? "0" + (date.getDate()) : (date.getDate()) );
	return dateFormat2;
}

// 메뉴로그 기록 남김
// 호출방법
// $(document).ready(function(){    
   // // 방문기록 남김
   // var title = '<     ?php echo $title_message; ?    >';
   // saveMenuLog(title);
// });
function saveMenuLog(title) {            
    var formData = new FormData();
    formData.append('menu', title);

    $.ajax({
        enctype: 'multipart/form-data', // file을 서버에 전송하려면 이렇게 해야 함 주의
        processData: false,    
        contentType: false,      
        cache: false,           
        timeout: 600000,             
        url: "/insert_logmenu.php",
        type: "post",        
        data: formData,          
        dataType: "json",     
        success: function(data){
            console.log(data);
        },
        error: function(jqxhr, status, error) {
            console.log(jqxhr, status, error);
            alert("An error occurred: " + error); // Display error message
        }                    
    });
}

// 잠시만 기다려주세요 모달 창을 표시하는 함수
function showWaitingModal() {
	var modal = $('<div>').attr('id', 'waitingModal').css({
		position: 'fixed',
		top: '50%',
		left: '50%',
		transform: 'translate(-50%, -50%)',
		backgroundColor: '#fff',
		padding: '20px',
		borderRadius: '10px',
		boxShadow: '0 0 10px rgba(0, 0, 0, 0.5)',
		zIndex: 5000,
		textAlign: 'center'
	}).text('화면을 로딩중입니다. 잠시만 기다려 주세요.');

	var overlay = $('<div>').attr('id', 'overlay').css({
		position: 'fixed',
		top: 0,
		left: 0,
		width: '100%',
		height: '100%',
		backgroundColor: 'rgba(0, 0, 0, 0.5)',
		zIndex: 9999
	});

	$('body').append(overlay).append(modal); // 모달과 오버레이를 body에 추가
}
// 저장 중 모달 창을 표시하는 함수
function showSavingModal() {
	var modal = $('<div>').attr('id', 'savingModal').css({
		position: 'fixed',
		top: '50%',
		left: '50%',
		transform: 'translate(-50%, -50%)',
		backgroundColor: '#fff',
		padding: '20px',
		borderRadius: '10px',
		boxShadow: '0 0 10px rgba(0, 0, 0, 0.5)',
		zIndex: 10000,
		textAlign: 'center'
	}).text('저장 중입니다. 잠시만 기다려 주세요.');

	var overlay = $('<div>').attr('id', 'overlay').css({
		position: 'fixed',
		top: 0,
		left: 0,
		width: '100%',
		height: '100%',
		backgroundColor: 'rgba(0, 0, 0, 0.5)',
		zIndex: 9999
	});

	$('body').append(overlay).append(modal); // 모달과 오버레이를 body에 추가
}

// 저장이 완료되면 모달 창을 숨기는 함수
function hideSavingModal() {
	$('#savingModal').remove(); // 모달 삭제
	$('#waitingModal').remove(); // 모달 삭제
	$('#overlay').remove(); // 오버레이 삭제
}


function showMsgModal(type) {
    var message = '';
    var scale = 1; // 기본 배율

    switch (type) {
        case 1:
            message = '이미지를 업로드 중입니다. 잠시만 기다려 주세요!';
            break;
        case 2:
            message = '파일을 업로드 중입니다. 잠시만 기다려 주세요!';
            break;
        case 3:
            message = '데이터를 처리 중입니다. 잠시만 기다려 주세요!';
            break;
        case 4:
            message = '다운로드 중입니다. 잠시만 기다려 주세요!';
            break;
        case 10:
            message = '다운로드 파일은 Ctrl+j 키를 누르면 볼 수 있습니다';
            scale = 2; // 배율 조정
            break;
        default:
            message = '작업을 처리 중입니다. 잠시만 기다려 주세요!';
    }

    if ($('#msgModal').length > 0 || $('#msgOverlay').length > 0) {
        return;
    }

    var modal = $('<div>').attr('id', 'msgModal').css({
        position: 'fixed',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        backgroundColor: '#fff',
        padding: 20 * scale + 'px',
        borderRadius: 10 * scale + 'px',
        boxShadow: '0 0 ' + 10 * scale + 'px rgba(0, 0, 0, 0.5)',
        zIndex: 10000,
        textAlign: 'center',
        fontSize: 16 * scale + 'px'
    }).text(message);

    var overlay = $('<div>').attr('id', 'msgOverlay').css({
        position: 'fixed',
        top: 0,
        left: 0,
        width: '100%',
        height: '100%',
        backgroundColor: 'rgba(0, 0, 0, 0.5)',
        zIndex: 9999
    });

    $('body').append(overlay).append(modal);

    $('button').prop('disabled', true);
}


// 메시지 모달 숨기기 함수
function hideMsgModal() {
    // 모달이 없으면 처리 중단
    if ($('#msgModal').length === 0 || $('#msgOverlay').length === 0) {
        return;
    }

    $('#msgModal').remove();
    $('#msgOverlay').remove();

    // 모든 버튼 다시 활성화
    $('button').prop('disabled', false);
}