function showLayer(layerID, layerHeight) {
	//안드로이드 전용..
	/*try{
		hideSlideLayer();
	} catch(e){}*/

	var innerLayer = $("#" + layerID);

	//백그라운드에서의 움직임을 방지
	$("body").css({ "overflow-y": "hidden" });

	if ($("#backgroundLayer").length == 0) {
		$("body").append($("<div/>").attr("id","backgroundLayer")
									.css({"position":"fixed"
									, "background": "#000"
									, "width": "100%"
									, "height": "100%"
									, "top":0
									, "left":0
									, "-ms-filter":"\"progid:DXImageTransform.Microsoft.Alpha(opacity=30)\""
									, "filter":"progid:DXImageTransform.Microsoft.Alpha(opacity=30)"
									, "opacity":"0.7"
									, "-moz-opacity":"0.7"
									, "z-index": "9000"
									, "overflow": "hidden"
									, "display": "none"
									})
		);
	}

	var border = parseInt(innerLayer.css("border-left-width")) + parseInt(innerLayer.css("border-right-width"));
	if (border == null)
		border = 0;

	var width = innerLayer.width() + border;
	var marginLeft = width / 2;
	var height, marginTop;

	if (layerHeight != null) {
		if (layerHeight.toString().indexOf("%") > -1)
			height = $(window).height() * (layerHeight.split("%")[0] / 100)
		else
			height = layerHeight;
	} else {
		innerLayer.css("height", "auto");
		height = innerLayer.height() + 3;
	}

	marginTop = height / 2

	innerLayer.css({
		"position": "fixed"
		, "display": "none"
		, "background": "#FFF"
		, "width": width + "px"
		, "height": height + "px"
		, "left": "50%"
		, "top": "50%"
		, "margin-left": -marginLeft + "px"
		, "margin-top": -marginTop + "px"
		// , "overflow": "hidden"
		, "-webkit-overflow-scrolling": "touch"
		, "z-index": "9001"
	});

	$("#backgroundLayer").show();
	innerLayer.show();

	//안드로이드에서 레이어 셋팅
	try {
		innerLayer.addClass("set_dialog_layer");
		Android.setWebPageLayer(true);
	} catch (e) {}
}

function hideLayer(layerID) {
	var innerLayer = $("#" + layerID);
	$("#backgroundLayer").hide();
	innerLayer.hide();
	$("body").css({ "overflow-y": "auto" });

	//안드로이드에서 레이어 셋팅
	try {
		innerLayer.removeClass("set_dialog_layer");
		Android.setWebPageLayer(false);
	} catch (e) { }
}

function getLayerTop(height){
	var innerHeight = ($(window).height() - height)/2;
	if (innerHeight < 0)
		innerHeight = 10;
	var top = $(window).scrollTop() + innerHeight;
	
	return top;
}

function getObjTop(objID){
	var height = $("#" + objID).height()
	return getLayerTop(height);
}

/* 콤마 허용 */
function onlyMoney(obj) {
	var oElement = obj;
	var charChk;

	for (var i = 0; i < oElement.value.length; i++) {
		charChk = oElement.value.charCodeAt(i);
		if ((charChk > 57 || charChk < 48) && charChk != 44 && charChk != 45) {
			alert("공백없이 숫자로만 입력해주세요.");
			oElement.value = oElement.value.substring(0, i);
			oElement.focus();
			return;
		} else {
			if (charChk == 45 && i != 0 ) {
				alert("음수표시( - )는 첫 자리수에만 올 수 있습니다.");
				oElement.value = oElement.value.substring(0, i);
				oElement.focus();
				return;
			}
		}
	}
	
	obj.value = obj.value.split(",").join("").money();
}

/* 소숫점 허용 */
/*function onlyFloat(obj) {
	var oElement = obj;
	var charChk;

	for (var i = 0; i < oElement.value.length; i++) {
		charChk = oElement.value.charCodeAt(i);
		if ((charChk > 57 || charChk < 48) && charChk != 46) {
			alert("공백없이 숫자로만 입력해주세요.");
			oElement.value = oElement.value.substring(0, i);
			oElement.focus();
			return;
		}
	}
}*/

function onlyFloat() {
	var oElement = (arguments[0] != null) ? arguments[0] : this;
	var charChk, point = 0;

	var firstCharCode = oElement.value.charCodeAt(0);	//첫째자리

	for (var i = 0; i < oElement.value.length; i++) {
		var error = false;
		charChk = oElement.value.charCodeAt(i);

		if (charChk == 46) {	//소숫점
			point++;
		}

		if (i > 0) {
			var secondCharCode = oElement.value.charCodeAt(1);	//둘째자리

			switch (firstCharCode) {
				case 48:	//첫째자리가 0인경우
					if (secondCharCode != 46) {
						alert("소수점을 입력해주세요.");
						error = true;
					}
					break;
				case 45:	//첫째자리가 음수(-)인경우
					if (secondCharCode == 46) {	//둘째자리가 소수점인경우
						alert("0 다음에 소수점을 입력할 수 있습니다.");
						error = true;
					} else {
						if (i > 1) {
							if (secondCharCode == 48) {	//둘째자리가 0인경우
								if (oElement.value.charCodeAt(2) != 46) {
									alert("소수점을 입력해주세요.");
									error = true;
								}
							}
						}
					}
					break;
			}

			if (point > 1) {
				alert("이미 소수점이 입력되었습니다.");
				error = true;
			} else {
				if (!error) {
					if ((charChk > 57 || charChk < 48) && charChk != 46) {
						alert("공백없이 숫자로만 입력해주세요.");
						error = true;
					}
				}
			}
		} else {
			if ((firstCharCode > 57 || firstCharCode < 48) && firstCharCode != 45) {
				alert("공백없이 숫자로만 입력해주세요.");
				error = true;
			}
		}

		if (error) {
			oElement.value = oElement.value.substring(0, i);
			oElement.focus();
			return;
		}
	}
}

function onlyNumber() {
	var oElement = (arguments[0] != null) ? arguments[0] : this;
	var charChk;

	for (var i = 0; i < oElement.value.length; i++) {
		charChk = oElement.value.charCodeAt(i);
		if (charChk > 57 || charChk < 48) {
			alert("공백없이 숫자로만 입력해주세요.");
			oElement.value = oElement.value.substring(0, i);
			oElement.focus();
			return;
		}
	}
}
	
function autoHeight(obj, defaultHeight){
	var height = obj.scrollHeight;
	var thisObjHeight = 115;	//기본 높이값
	
	if (defaultHeight != null)
		thisObjHeight = defaultHeight;
	
	if (height > thisObjHeight)
		$(obj).css("height",obj.scrollHeight + "px");
	else
		$(obj).css("height",thisObjHeight + "px");
}
	
function ContentHeightCK(objID){
	var content = $("#" + objID);
	
	if (content.css("height").toLowerCase().replace("px","") < content.attr("scrollHeight"))
		content.css("height",content.attr("scrollHeight") + "px");
}
	
var maxTable = 0;
function autoScroll(tableID){
	var s_Table = $("#" + tableID).html().toLowerCase().split("</table>");
	$("#" + tableID + "_view").empty();
	
	for( var i=0;i<s_Table.length;i++){
		if (s_Table[i] != ""){
			$("#" + tableID + "_view").append(s_Table[i] + "</table>")
			window.setTimeout("fadeTable("+i+", '" + tableID + "')", (i*2000));
		}
	}
	maxTable = s_Table.length;
}

function fadeTable(i, tableID){
	if (i != 0 && i%3 == 0){
		$("#" + tableID + "_view table").each(function(e){
			if (e < i)
				$(this).css("display","none");
		})
	}
		
	$($("#" + tableID + "_view table")[i]).fadeIn("slow");
	
	if ((maxTable-2) == i){
		window.setTimeout("autoScroll('" + tableID + "')", 2000);
	}
}

function fGetXY(aTag){ 
	var oTmp = aTag; 
	var pt = new Point(0,0); 
	do { 
			pt.x += oTmp.offsetLeft; 
			pt.y += oTmp.offsetTop; 
			oTmp = oTmp.offsetParent; 
	} while(oTmp.tagName!="BODY" && oTmp.tagName!="HTML"); 
	return pt; 
}

function Point(iX, iY){ 
	this.x = iX; 
	this.y = iY; 
}

// 문자열 속성

//숫자 3자리마다 쉼표 추가
String.prototype.money = function() {
    var num = this.trim();

    while ((/(-?[0-9]+)([0-9]{3})/).test(num)) {
        num = num.replace((/(-?[0-9]+)([0-9]{3})/), "$1,$2");
    }

    return num;
};

//공백제거
String.prototype.trim = function() {
	return this.replace(/(^\s*)|(\s*$)/g, "");
};

//공백제거
String.prototype.Trim = function() {
	return this.replace(/(^\s*)|(\s*$)/g, "");
};

//특정 수만큼 0 채움
String.prototype.digits = function(cnt) {
	var digit = "";

	if (this.length < cnt) {
		for(var i = 0; i < cnt - this.length; i++) {
			digit += "0";
		}
	}

	return digit + this;
};

//글쓰기 체크
function max_write(maxByte, obj) {
	var temp;
	var maxLen = maxByte;

	var len = obj.value.length;

	for (i = 0; i < len; i++) {
		temp = obj.value.charAt(i);

		if (escape(temp).length > 4)
			maxLen -= 2;
		else
			maxLen--;

		if (maxLen < 0) {
			alert("내용은 영문 " + maxByte + "자, 한글 " + maxByte / 2 + "자 이내로 작성하셔야 합니다.");
			obj.value = obj.value.substring(0, i);
			obj.focus();
			break;
		}
	}
}

//길이체크
function maxlength_check(maxlen, obj, returnYN) {
	var len = obj.value.length;
	var result;
	
	if (len > maxlen){
		alert(maxlen + "자 이상 작성하실 수 없습니다.");
		obj.value = obj.value.substring(0,maxlen);
		result = false;
	} else
		result = true;
		
	if (returnYN != null && returnYN)
		return result;
}

//pos.x x좌표, pos.y y좌표
function cfGetEventPosition(evt) {
	var e = evt || window.event;
	var b = document.body;
	var scroll = cfGetScrollOffset();
	var pos = {
 			x : e.pageX || e.clientX+scroll.x-b.clientLeft,
			y : e.pageY || e.clientY+scroll.y-b.clientTop
		}
	return pos;
}

function cfGetScrollOffset(win) {
	if (!win) win = self;
	var x = win.pageXOffset || win.document.body.scrollLeft || document.documentElement.scrollLeft || 0;
	var y = win.pageYOffset || win.document.body.scrollTop || document.documentElement.scrollTop || 0;
	return { x:x, y:y };
}

	
// 가로, 세로 최대 사이즈 설정
//classObj class명
//maxWidth, maxHeight	: 가로 * 세로 최대값
var errorCnt = 0;	//Error횟수
function imageResize(classObj, maxWidth, maxHeight){
	$("." + classObj).each(function(i){
		errorCnt = 0;
		obj = $(this)
		setImageSize(obj, maxWidth, maxHeight);
	});
}

function setImageSize(obj, maxWidth, maxHeight){
	var width = obj.width();
	var height = obj.height();
	var resizeWidth, resizeHeight;

	// 가로나 세로의 길이가 최대 사이즈보다 크면 실행  
	if (width > maxWidth || height > maxHeight){
		// 가로가 세로보다 크면 가로는 최대사이즈로, 세로는 비율 맞춰 리사이즈
		if(width > height){
			resizeWidth = maxWidth;
			resizeHeight = parseInt(Math.round(parseFloat(height * resizeWidth) / parseFloat(width)));
			
			if (resizeHeight > maxHeight){
				resizeHeight = maxHeight;
				resizeWidth = parseInt(Math.round(parseFloat(width * resizeHeight) / parseFloat(height)));
			}
		// 세로가 가로보다 크면 세로는 최대사이즈로, 가로는 비율 맞춰 리사이즈
		}else{
			resizeHeight = maxHeight;
			resizeWidth = parseInt(Math.round(parseFloat(width * resizeHeight) / parseFloat(height)));
			
			if (resizeWidth > maxWidth){
				resizeWidth = maxWidth;
				resizeHeight = parseInt(Math.round(parseFloat(height * resizeWidth) / parseFloat(width)));
			}
		}
	// 최대사이즈보다 작으면 원본 그대로
	}else{
		resizeWidth = width;
		resizeHeight = height;
	}

	var maxErrorCnt = 3;
	//이미지 사이즈가 정상적으로 처리되지 않으면 1초 후 반복( 최대3회 )
	if ((resizeWidth < 50 || resizeHeight < 50) && errorCnt < maxErrorCnt){
		errorCnt++;
		window.setTimeout(function(){
				setImageSize(obj, maxWidth, maxHeight);
			},1000);
	}else
		obj.css({"width":resizeWidth, "height":resizeHeight}).show();
}
	
//Width만 리사이즈		
var _complate = true;
var _errorCount = 0;

function imageResizeWidth(classObj, maxWidth){
	
	var imgs = new Array();
	$("." + classObj).each(function(idx){
		var obj = $(this);
		
		var tmpImage = new Image();
		tmpImage.src = obj.attr("src");
		
		var width = obj.width();
		var height = obj.height();
		var resizeWidth, resizeHeight;
		imgs[idx] = false;

		if (width > maxWidth){
			resizeWidth = maxWidth;
			resizeHeight = parseInt(Math.round(parseFloat(height * resizeWidth) / parseFloat(width)));
		} else {
			resizeWidth = width;
			resizeHeight = height;
		}
		
		//var li = $("<li/>").text((idx+1).toString() + ". width : " + width + ", " + "height : " + height + ", resizeWidth : " + resizeWidth + ", " + "resizeHeight : " + resizeHeight );
		//$("#debug").append(li);
		
		if (resizeWidth > 200){
			imgs[idx] = true;
			obj.css({"width":resizeWidth, "height":resizeHeight}).show();
		}
	});
	
	_complate = true;
	for( i=0; i < imgs.length; i++){
		if (!imgs[i])
			_complate = false;
	}
		
	if ( !_complate && _errorCount < 2){
		_errorCount++;
		window.setTimeout("imageResizeWidth('" + classObj + "', " + maxWidth + ")",200);
	}
}

//마우스 우측버튼 금지
function rightMouse() {
	if (event.button == 2 || event.button == 3) {  
		//alert("마우스 오른쪽 버튼을 사용하실수 없습니다.");
		return false;  
	}
	return true;  
}
function rightMouseDenial(){
	document.oncontextmenu = new Function('return false');
	document.ondragstart = new Function('return false');
	document.onselectstart = new Function('return false');
	
	document.onmousedown = rightMouse;

	//ImageToolBox 금지
	$("img").each(function(){
		$(this).attr("galleryImg",false);
	});
}

function char_filter(obj){
	var filterchar= ";$%'\"<>+\\-:=";
	var len = obj.value.length;
	var temp;
	
	if ( !$(obj).attr("readonly") ){
		for (i = 0; i < len; i++) {
			temp = obj.value.charAt(i);
			if (filterchar.indexOf(temp) > -1){
				alert("특수문자 [ " + temp + " ]은(는) 사용할수 없는 문자 입니다.");
				obj.value = obj.value.substring(0, i);
				obj.focus();
				break;
			}
		}
	}
}

//입력창 Text길이 체크
function lengthCheck(length, obj, nextObjID){
	if (obj.value.length >= length){
		$("#" + nextObjID).focus();
	}
}

//입력항목 검사
function objValidate_mob(objID, alertMsg){
	if ( $("#" + objID).val() == ""){
		alert(alertMsg);
		$("#" + objID).focus();
		return true;
	}
	return false;
}

//입력항목 검사
function objValidate(objID, alertMsg) {
	if ($("#" + objID).val() == "") {
		if (isApp && !isIOS)
			showAndroidDialog(alertMsg, "$('#" + objID + "').focus()");
		else {
			alert(alertMsg);
			$("#" + objID).focus();
		}
		return true;
	}
	return false;
}

//머니형태를 숫자 형태로
function toNumber(val){
	var reslut = 0;
	if (val != null)
		reslut = Number(val.toString().split(",").join(""));
	return reslut;
}

//숫자인지 확인
function isNumber(s) {
  s += ''; // 문자열로 변환
  s = s.replace(/^\s*|\s*$/g, ''); // 좌우 공백 제거
  if (s == '' || isNaN(s)) return false;
  return true;
}

//영문과 숫자만 가능
function onlyEnglishNumber() {
	var oElement = (arguments[0] != null) ? arguments[0] : this;
	var charChk;

	for (var i = 0; i < oElement.value.length; i++) {
		charChk = oElement.value.charCodeAt(i);

		if ((charChk < 65 || charChk > 90) && (charChk < 97 || charChk > 122) && (charChk > 57 || charChk < 48)) {
			alert("영문과 숫자만 입력해주세요.");
			oElement.value = oElement.value.substring(0, i);
			oElement.focus();
			return;
		}
	}
}

//한글만 안됨
function onlyNotKorean() {
	var oElement = (arguments[0] != null) ? arguments[0] : this;
	var charChk;

	for (var i = 0; i < oElement.value.length; i++) {
		charChk = oElement.value.charCodeAt(i);

		if (!(charChk > 31 && charChk < 127)) {
			alert("한글은 사용할 수 없습니다.");
			oElement.value = oElement.value.substring(0, i);
			oElement.focus();
			return;
		}
	}
}

//모바일 페이지 주소창 감추기
function isUserAgent() {
	var ua = navigator.userAgent;
	if ((ua.match(/iPhone/i)) || (ua.match(/iPod/i)) || (ua.match(/iPad/i)) || (ua.match(/Android/i))) {
		window.addEventListener("load", function () { setTimeout(scrollTo, 0, 0, 1); }, false);
	}
}


/* 전화번호 자동 변환 시작*/
function autoHypenPhone(str, field) {
	var str;
	str = checkDigit(str);
	len = str.length;

	if (len == 8) {
		if (str.substring(0, 2) == 02) {
			error_numbr(str, field);
		} else {
			field.value = phone_format(1, str);
		}
	} else if (len == 9) {
		if (str.substring(0, 2) == 02) {
			field.value = phone_format(2, str);
		} else {
			error_numbr(str, field);
		}
	} else if (len == 10) {
		if (str.substring(0, 2) == 02) {
			field.value = phone_format(2, str);
		} else {
			field.value = phone_format(3, str);
		}
	} else if (len == 11) {
		if (str.substring(0, 2) == 02) {
			error_numbr(str, field);
		} else {
			field.value = phone_format(3, str);
		}
	} else {
		error_numbr(str, field);
	}
}
function checkDigit(num) {
	var Digit = "1234567890";
	var string = num;
	var len = string.length
	var retVal = "";
	for (i = 0; i < len; i++) {
		if (Digit.indexOf(string.substring(i, i + 1)) >= 0) {
			retVal = retVal + string.substring(i, i + 1);
		}
	}
	return retVal;
}
function phone_format(type, num) {
	if (type == 1) {
		return num.replace(/([0-9]{4})([0-9]{4})/, "$1-$2");
	} else if (type == 2) {
		return num.replace(/([0-9]{2})([0-9]+)([0-9]{4})/, "$1-$2-$3");
	} else {
		return num.replace(/(^01.{1}|[0-9]{3})([0-9]+)([0-9]{4})/, "$1-$2-$3");
	}
}
function error_numbr(str, field) {
	if (field.value != "") {
		alert("정상적인 번호가 아닙니다.");
		field.value = "";
		$(field).removeAttr("readonly");	//정상적인 번호가 아닐경우 읽기전용 해제
		//
		field.focus();
		return;
	}
}
function phoneNumberCheck(phone) {
	var rgEx = /(01[016789])[-](\d{4}|\d{3})[-]\d{4}$/g;
	var strValue = phone;
	var chkFlg = rgEx.test(strValue);
	if (!chkFlg) {
		return false;
	} else {
		return true;
	}
}
/* 전화번호 자동 변환 끝*/

/* 값 찍어보기 */
function printLog(tag, val) {
	var log = $("<div/>").text(tag + " : " + val);
	$("body").append(log);
}

/* 안드로이드 앱일경우 toast 메시지 발송 */
function alertAndroid(msg) {
	if (isApp && !isIOS)
		Android.toast(1, msg);
	else
		alert(msg);
}

//부모의 iframe Height을 변경
function resizeParent() {
	if ($(parent.phoneFrame)[0] != null) {
		hideMenuAndFooter();
		var documentHeight = $("#wrap").height() + $(".footer").height();
		parent.resizeHeight(documentHeight);
	}
}

function hideMenuAndFooter() {
	$(".footer, a.flick_home").hide();
}

//웹에서 네이티브 앱 실행
function execApp(appScheme, playLink, appStoreLink) {
	var frame_id = '__check_app__', $iframe = $('#' + frame_id), clickedAt = +new Date;

	if (!$iframe[0]) $iframe = $('<iframe id="' + frame_id + '" />').hide().appendTo('body');
	setTimeout(function () {
		if (+new Date - clickedAt < 2000) {
			var ua = navigator.userAgent.toLowerCase();
			if (/android/.test(ua)) {
				$iframe.attr('src', playLink);
			} else if (/iphone|ipad/.test(ua)) {
				$iframe.attr('src', appStoreLink);
			}
		}
	}, 500);
	$iframe.attr('src', appScheme);
}

//레이어 변경
function layerChange(showID, hideID) {
	$("#" + hideID).hide();
	$("#" + showID).show();
}


//쿠키관련..
function getCookie(cookieName) {
	var search = cookieName + "=";
	var cookie = document.cookie;

	// 현재 쿠키가 존재할 경우
	if (cookie.length > 0) {
		// 해당 쿠키명이 존재하는지 검색한 후 존재하면 위치를 리턴.
		startIndex = cookie.indexOf(cookieName);

		// 만약 존재한다면
		if (startIndex != -1) {
			// 값을 얻어내기 위해 시작 인덱스 조절
			startIndex += cookieName.length;

			// 값을 얻어내기 위해 종료 인덱스 추출
			endIndex = cookie.indexOf(";", startIndex);

			// 만약 종료 인덱스를 못찾게 되면 쿠키 전체길이로 설정
			if (endIndex == -1) endIndex = cookie.length;

			// 쿠키값을 추출하여 리턴
			return unescape(cookie.substring(startIndex + 1, endIndex));
		}
		else {
			// 쿠키 내에 해당 쿠키가 존재하지 않을 경우
			return false;
		}
	}
	else {
		// 쿠키 자체가 없을 경우
		return false;
	}
}

function setCookie(cookieName, cookieValue, expireDate) {
	var today = new Date();
	today.setDate(today.getDate() + parseInt(expireDate));
	document.cookie = cookieName + "=" + escape(cookieValue) + "; path=/; expires=" + today.toGMTString() + ";";
}

function deleteCookie(cookieName) {
	var expireDate = new Date();
	//어제 날짜를 쿠키 소멸 날짜로 설정한다.
	expireDate.setDate(expireDate.getDate() - 1);
	document.cookie = cookieName + "= " + "; expires=" + expireDate.toGMTString() + "; path=/";
}

//바탕화면 투명..
function bgTransparentLayer(layerID) {
	showLayer(layerID);
	$("#" + layerID).css("background-color", "transparent");
}


// 피플앱 오픈으로 인한 비활성 메뉴 안내 메시지
function inactiveService(msg) {
	if (msg == "" || msg == null) {
		alertAndroid("해당 서비스는 피플앱이 종료된 이후 이용 가능합니다. 당분간 피플앱을 이용해 주세요.");
	} else {
		alertAndroid(msg);
	}
}
function inactiveServiceUrl(msg, url) {
	if (msg == "" || msg == null) {
		alertAndroid("해당 서비스는 피플앱이 종료된 이후 이용 가능합니다. 당분간 피플앱을 이용해 주세요.");
	} else {
		alertAndroid(msg);
	}
	if (url == "" || url == null) {
		history.back();
	} else {
		self.location.href = url;
	}
}
// 피플앱 오픈으로 인한 비활성 메뉴 안내 메시지 끝



// 팝업 두개 띄울 때 사용
function showLayerSub(layerID, layerHeight) {
	var innerLayer = $("#" + layerID);

	//백그라운드에서의 움직임을 방지
	$("body").css({ "overflow-y": "hidden" });

	if ($("#backgroundLayerSub").length == 0) {
		$("body").append($("<div/>").attr("id","backgroundLayerSub")
									.css({"position":"fixed"
									, "background": "#000"
									, "width": "100%"
									, "height": "100%"
									, "top":0
									, "left":0
									, "-ms-filter":"\"progid:DXImageTransform.Microsoft.Alpha(opacity=30)\""
									, "filter":"progid:DXImageTransform.Microsoft.Alpha(opacity=30)"
									, "opacity":"0.7"
									, "-moz-opacity":"0.7"
									, "z-index": "9003"
									, "overflow": "hidden"
									, "display": "none"
									})
		);
	}

	var border = parseInt(innerLayer.css("border-left-width")) + parseInt(innerLayer.css("border-right-width"));
	if (border == null)
		border = 0;

	var width = innerLayer.width() + border;
	var marginLeft = width / 2;
	var height, marginTop;

	if (layerHeight != null) {
		if (layerHeight.toString().indexOf("%") > -1)
			height = $(window).height() * (layerHeight.split("%")[0] / 100)
		else
			height = layerHeight;
	} else {
		innerLayer.css("height", "auto");
		height = innerLayer.height() + 3;
	}

	marginTop = height / 2

	innerLayer.css({
		"position": "fixed"
		, "display": "none"
		, "background": "#FFF"
		, "width": width + "px"
		, "height": height + "px"
		, "left": "50%"
		, "top": "50%"
		, "margin-left": -marginLeft + "px"
		, "margin-top": -marginTop + "px"
		, "overflow": "hidden"
		, "-webkit-overflow-scrolling": "touch"
		, "z-index": "9004"
	});

	$("#backgroundLayerSub").show();
	innerLayer.show();

	//안드로이드에서 레이어 셋팅
	try {
		innerLayer.addClass("set_dialog_layer");
		Android.setWebPageLayer(true);
	} catch (e) {}
}

function hideLayerSub(layerID) {
	var innerLayer = $("#" + layerID);
	$("#backgroundLayerSub").hide();
	innerLayer.hide();
	$("body").css({ "overflow-y": "auto" });

	//안드로이드에서 레이어 셋팅
	try {
		innerLayer.removeClass("set_dialog_layer");
		Android.setWebPageLayer(false);
	} catch (e) { }
}


// 마이클럽 회원 공지팝업 시작
function showLayerClub(layerID, code) {
	var innerLayer = $("#" + layerID + code);

	//백그라운드에서의 움직임을 방지
	$("body").css({ "overflow-y": "hidden" });

	if ($("#backgroundLayerClub"+code).length == 0) {
		$("body").append($("<div/>").attr("id","backgroundLayerClub"+code)
									.css({"position":"fixed"
									, "background": "#000"
									, "width": "100%"
									, "height": "100%"
									, "top":0
									, "left":0
									, "-ms-filter":"\"progid:DXImageTransform.Microsoft.Alpha(opacity=30)\""
									, "filter":"progid:DXImageTransform.Microsoft.Alpha(opacity=30)"
									, "opacity":"0.7"
									, "-moz-opacity":"0.7"
									, "z-index": "9003"
									, "overflow": "hidden"
									, "display": "none"
									})
		);
	}

	var border = parseInt(innerLayer.css("border-left-width")) + parseInt(innerLayer.css("border-right-width"));
	if (border == null)
		border = 0;

	var width = innerLayer.width() + border;
	var marginLeft = width / 2;
	var height, marginTop;

	innerLayer.css("height", "auto");
	height = innerLayer.height();

	marginTop = height / 2

	innerLayer.css({
		"position": "fixed"
		, "display": "none"
		, "background": "#FFF"
		, "width": width + "px"
		, "height": height + "px"
		, "left": "50%"
		, "top": "50%"
		, "margin-left": -marginLeft + "px"
		, "margin-top": -marginTop + "px"
		, "-webkit-overflow-scrolling": "touch"
		, "z-index": "9004"
	});

	$("#backgroundLayerClub"+code).show();
	innerLayer.show();

	//안드로이드에서 레이어 셋팅
	try {
		innerLayer.addClass("set_dialog_layer");
		Android.setWebPageLayer(true);
	} catch (e) {}
}

function hideLayerClub(layerID, code) {
	var innerLayer = $("#" + layerID + code);
	$("#backgroundLayerClub"+code).hide();
	innerLayer.hide();
	$("body").css({ "overflow-y": "auto" });

	//안드로이드에서 레이어 셋팅
	try {
		innerLayer.removeClass("set_dialog_layer");
		Android.setWebPageLayer(false);
	} catch (e) { }
}
// 마이클럽 회원 공지팝업 끝


/* 로그 스크립트 시작 */
function setAnalMemberLog(LogYN, LogType, SubType, Link, LinkType, Title, isRefresh) {
	if (LogYN == "Y") {
		$.ajax({
			type: "POST",
			url: "/mob/code_behind/setAnalMemberLog.asp",
			data: "LogType="+LogType+"&SubType="+SubType,
			success: function () {
				if (Link != null && Link != "") {
					if (LinkType == "openWindow") {
						if (isRefresh != null && isRefresh != "") {
							openWindow(Link, Title, isRefresh);
						} else {
							openWindow(Link, Title);
						}
					} else if (LinkType == "openWebBrowser") {
						openWebBrowser(Link);
					} else {
						self.location.href = Link;
					}
				}
			}
		});
	} else {
		if (Link != null && Link != "") {
			if (LinkType == "openWindow") {
				if (isRefresh != null && isRefresh != "") {
					openWindow(Link, Title, isRefresh);
				} else {
					openWindow(Link, Title);
				}
			} else if (LinkType == "openWebBrowser") {
				openWebBrowser(Link);
			} else {
				self.location.href = Link;
			}
		}
	}
}
/* 로그 스크립트 끝 */


/* 로그인 체크 및 로그 저장 */
// locationFilter(로그인필수여부, 맴버코드, 로그저장여부, 로그타입, 로그서브타입, 페이지이동주소, 새창열기함수명, 새창열기제목, 새창닫기새로고침여부)
function locationFilter(LoginYN, MemCode, LogYN, LogType, SubType, Link, LinkType, Title, isRefresh) {
	if (LoginYN == "Y" && (MemCode == "" || MemCode == null)) {
		if (confirm("로그인이 필요한 서비스 입니다. 로그인 하시겠습니까?")) {
			location.href = "/mob/login.asp";
		}
	} else {
		if (LogYN == "Y") {
			$.ajax({
				type: "POST",
				url: "/mob/code_behind/setAnalMemberLog.asp",
				data: "LogType="+LogType+"&SubType="+SubType,
				success: function () {
					if (Link != null && Link != "") {
						if (LinkType == "openWindow") {
							if (isRefresh != null && isRefresh != "") {
								openWindow(Link, Title, isRefresh);
							} else {
								openWindow(Link, Title);
							}
						} else if (LinkType == "openWebBrowser") {
							openWebBrowser(Link);
						} else {
							location.href = Link;
						}
					}
				}
			});
		} else {
			if (Link != null && Link != "") {
				if (LinkType == "openWindow") {
					if (isRefresh != null && isRefresh != "") {
						openWindow(Link, Title, isRefresh);
					} else {
						openWindow(Link, Title);
					}
				} else if (LinkType == "openWebBrowser") {
					openWebBrowser(Link);
				} else {
					location.href = Link;
				}
			}
		}
	}
}
/* 로그인 체크 및 로그 저장 끝 */

// 로그인 여부 체크 및 로그인 페이지 이동 옵션
function loginFilter(Type) {
	if (Type == "alert") {
		alert("로그인이 필요한 서비스 입니다.");
	} else {
		if (confirm("로그인이 필요한 서비스 입니다. 로그인 하시겠습니까?")) {
			location.href = "/mob/login.asp";
		}
	}
}


// 팝업 ESC로 닫기 용
function hideLayerClass(layerClass) {
	$("#backgroundLayer").hide();
	$("." + layerClass).hide();
	$("body").css({ "overflow-y": "auto" });
}

// ESC키 이벤트
$(document).keyup(function(e) {
	if (e.keyCode == 27) { // escape key maps to keycode 27
		hideLayerClass('layer_web');
	}
});


///////////////  팝업 시작
// 창열기
function openWin( winName ) {
	var blnCookie = getCookie( winName );
	//var obj = eval( "window." + winName );
	if( !blnCookie ) {
		if (winName == "popup_layer") {
			showLayer(winName);
		} else if (winName == "popup_layer_sub") {
			showLayerSub(winName);
		} else if (winName == "popup_slide") {
			$("#popup_slide").show();
			$("body").css({ "overflow-y": "hidden" });
		} else if (winName == "banner_main") {
			$("#banner_main").show();
		} else {
			showLayer(winName);
		}
		//    try {
		// 		Android.setWebPageLayer(false);
		// 	} catch (e) { }

		//레이어 높에 리사이즈
		if (winName != "banner_main") {
			$("#"+winName).css({height:($("#"+winName+" > div").height()+$("#"+winName+" > img").height())+"px"});
		}
	}
}
// 창닫기
function closeWin(winName, expiredays) { 
	setCookie( winName, "done" , expiredays);
	//eval( "window." + winName );
	if (winName == "popup_layer") {
		hideLayer(winName);
	} else if (winName == "popup_layer_sub") {
		hideLayerSub(winName);
	} else if (winName == "popup_slide") {
		$("#popup_slide").hide();
		$("body").css({ "overflow-y": "auto" });
	} else if (winName == "banner_main") {
		$("#banner_main").hide();
	} else {
		hideLayer(winName);
	}
}
function closeWinAt00(winName, expiredays) { 
	setCookieAt00( winName, "done" , expiredays); 
	var obj = eval( "window." + winName );
	obj.style.display = "none";
}
// 쿠키 가져오기
function getCookie( name ) {
	var nameOfCookie = name + "=";
	var x = 0;
	while ( x <= document.cookie.length ) {
		var y = (x+nameOfCookie.length);
		if ( document.cookie.substring( x, y ) == nameOfCookie ) {
			if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
				endOfCookie = document.cookie.length;
			return unescape( document.cookie.substring( y, endOfCookie ) );
		}
		x = document.cookie.indexOf( " ", x ) + 1;
		if ( x == 0 )
			break;
	}
	return "";
}

// 24시간 기준 쿠키 설정하기
// expiredays 후의 클릭한 시간까지 쿠키 설정
function setCookie( name, value, expiredays ) { 
	var todayDate = new Date(); 
	todayDate.setDate( todayDate.getDate() + expiredays ); 
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

// 00:00 시 기준 쿠키 설정하기
// expiredays 의 새벽  00:00:00 까지 쿠키 설정
function setCookieAt00( name, value, expiredays ) {
	var todayDate = new Date();
	todayDate = new Date(parseInt(todayDate.getTime() / 86400000) * 86400000 + 54000000);
	if ( todayDate > new Date() ) {
		expiredays = expiredays - 1;
	}
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}
///////////////  팝업 끝


// 빌리보드 유튜브 보기 (PC용)
function playYoutubeWeb(seq, YBMCode) {
	var embed_url = "https://www.youtube.com/embed/"+seq;
	if (seq == "") {
		alert("유튜브 재생 시 필요한 데이터가 누락되었습니다.");
		return false;
	}
	if (confirm("LTE, 5G 로 시청시 통신요금이 부과될 수 있습니다. Wi-Fi 연결을 확인 해 주시기 바랍니다.")) {
		$.ajax({
			type: "POST",
			url: "/mob/youtube/code_behind/setYoutubeViewCount.asp",
			data: "YBMCode="+YBMCode,
			success: function() {
				window.open('about:blank').location.href=embed_url;
			},
			error:function() {
				window.open('about:blank').location.href=embed_url;
			}
		});
	}
}