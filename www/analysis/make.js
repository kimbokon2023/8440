
 // 브라우저 호환(크로스브라우징)을 체크한후 페이지 로딩후 selectEvent() 함수를 실행 합니다.   셔터박스/린텔선택 창
    if ( window.addEventListener ) {
        window.addEventListener("load",selectEvent2,false);
    } else if ( window.attachEvent ) {
        window.attachEvent("onload",selectEvent2);
    } else {
        window.onload = selectEvent2;
    }
    function selectEvent2() {
        // 폼이름,셀렉트박스이름 으로 셀렉트박스에 접근합니다.
        // onchange 이벤트를 적용해줍니다. 
        document.selectForm2.ceilingbar.onchange = selectFun2;
		
    }
    // this.value 로 이벤트가 발생한 곳,자신(this)의 value값을 출력 합니다.
    function selectFun2() {		
        if ( this.value == '마감선택' ) {
			        $("#block1").hide();	
			        $("#guiderail_area").hide();		
			        $("#block4").hide();						
                    return false;
        }
        if ( this.value == '셔터박스' ) {			        
                    show_box();	
			        $("#block4").hide();					            
        }        
        if ( this.value == '린텔' ) {
			        $("#block4").show();	
                    show_lintel();				
        }
				
	
    }    

function reload_box() {

var tmp = $("#item_sel option:selected").val();              //jQuery로 선택된 값 읽기
var tmp2 = $("#ceilingbar option:selected").val();            //jQuery로 선택된 값 읽기

        if ( tmp2 == '마감선택' ) {
			        $("#block1").hide();	
			        $("#guiderail_area").hide();		
			        $("#block4").hide();						
                    return false;
        }
        if ( tmp2 == '셔터박스' ) {			        
                    show_box();	
			        $("#block4").hide();						
        }        
        if ( tmp2 == '린텔' ) {
			        $("#block4").show();	
                    show_lintel();	
					
        }    
}


$(document).ready(function(){
      var imgs;
      var img_count;
      var img_position = 1;
      
	  
/* 	  $("#order_title3").hide();	// 처음실행했을때 비상문 문자 없앰
	  $("#order_input3").hide();	// 처음실행했을때 비상문 입력창 */
      imgs = $(".slide ul");
      img_count = imgs.children().length;
	  FSSrail();
	  $("#block5").show();	 

    });



$(function() {
	
	// 검색 버튼
    $("#searchitem").on("click", function() {
        exe_search();
    });	

// 자재 출고 전화번호 검색 버튼	
    $("#searchtel").on("click", function() {
       exe_searchTel();
    });	
	
    $("#gunbbang").on("click", function() {
	  $("#material_list").hide();		
	  $("#guiderail_area").hide();		
      $("#detail").load("./gunbbang.php");
    });
    $("#screenexitmake").on("click", function() {
		$("#material_list").hide();		
		$("#guiderail_area").hide();		
        $("#detail").load("./screenexitmake.php");
    });	
    $("#egimake").on("click", function() {       // 철재방화 사이즈 산출 클릭시
	    $("#material_list").hide();		
		$("#guiderail_area").hide();		
        $("#detail").load("./egimake.php");
    });		
    $("#makeguiderail").on("click", function() {       // 가이드레일 제작 메뉴얼 클릭시
	    $("#material_list").hide();		
		$("#guiderail_area").hide();		
        $("#detail").load("./makeguiderail.php");
    });		
	
    $("#menu1").on("click", function() {       // 공사진행현황
        $("*").load("./work/list.php");
    });
    $("#menus1").on("click", function() {       // 공사진행현황
        $("*").load("../work/list.php");
    });
		//////////////////////////////////////////////////////////////////////////////////////////////////////// 자재산출 클릭시
    $("#show_list").on("click", function() {    // 자재산출 클릭시
	   show_one();   // 자재산출 클릭
	 });	
	 
function show_one() {
      hide_object();		
      $("#material_list").show();		
      var target = document.getElementById("item_sel"); 
      var sendData = target.options[target.selectedIndex].value ;				 

	  var ua = window.navigator.userAgent;
      var postData; 
	 

    var text2= document.getElementById("stwidth").value;
    var text3= document.getElementById("stheight").value;		
    var text4= document.getElementById("motormaker").value;		
    var ceilingbar= document.getElementById("ceilingbar").value;		
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(sendData);
				ceilingbar=encodeURI(ceilingbar);
				text4=encodeURI(text4);
            } else {
                postData = sendData;
            }
			
	var text1 = postData;
	text2=uncomma(text2); // 콤마가 있어서 숫자 변환이 안된다.
	text3=uncomma(text3); // 콤마가 있어서 숫자 변환이 안된다.
	
    document.getElementById("railheight").value = Number(text3) + 150;
	document.getElementById("railheight").value = comma(document.getElementById("railheight").value);	
	
          $("#material_list").load("./show_list.php?text1="+text1 +"&text2="+text2+"&text3="+text3+"&text4="+text4+"&ceilingbar="+ceilingbar);
 
}	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////// 스크린 기본 클릭
    $("#show_basic_screen").on("click", function() {    

	       $("#item_sel").val("스크린방화").prop("selected", true);   // 선택사항 변경
	       $("#motormaker").val("경동").prop("selected", true);   // 선택사항 변경
	       $("#power").val("220V").prop("selected", true);   // 선택사항 변경
	       $("#guiderailmaterial").val("SUS H/L 1.5T").prop("selected", true);   // 선택사항 변경

		   	$("#wa6").show();	 // 가이드레일 이미지

			$("#wa6").html("<img src='../img/guiderail/fss/rail1.jpg' class='maxsmall'>");
		    $("#wa7").show();	 // 양쪽레일 이미지
			
		    $("#ceilingbar").val("셔터박스").prop("selected", true);   // 선택사항 변경
		    $("#ceilingmaterial").val("전면EGI1.6T+1.2T").prop("selected", true);   // 천장마감재질 변경
		    $("#lin5").html("<img src='../img/box/fss/box1.jpg' class='maxsmall'>");		
		    $("#wr5").html("<img src='../img/Rmolding/Rmolding1.jpg' class='maxsmall2'>");		
		    $("#wr10").html("<img src='../img/Rcase/Rcase1.jpg' class='maxsmall3'>");		
	        $("#block5").show();			// 엘바	
		    $("#block6").show();			// T바	
		    $("#we5").html("<img src='../img/Lbar/Lbar1.jpg' class='maxsmall1'>");		
		    $("#we10").html("<img src='../img/Tbar/fssTbar1.jpg' class='maxsmall1'>");		
	        $("#Tbar").val("SUS H/L 1.2T").prop("selected", true);   // T바 재질		
            $("#Lbar").val("EGI 1.6T").prop("selected", true);   // L바 재질					
			
		   show_one();   // 자재산출 클릭
	   });	

	//////////////////////////////////////////////////////////////////////////////////////////////////////// 철재방화 기본 세팅 클릭
    $("#show_basic_egi").on("click", function() {    

	       $("#item_sel").val("철재방화EGI1.6T").prop("selected", true);   // 셔터형태 선택사항 변경
	       $("#motormaker").val("경동").prop("selected", true);   // 선택사항 변경
	       $("#power").val("220V").prop("selected", true);   // 선택사항 변경
	       $("#guiderailmaterial").val("SUS H/L 1.5T").prop("selected", true);   // 선택사항 변경

		   	$("#wa6").show();	 // 가이드레일 이미지

			$("#wa6").html("<img src='../img/guiderail/fst/rail1.jpg' class='maxsmall'>");  // 레일 이미지 불러오기
		    $("#wa7").show();	 // 양쪽레일 선택박스
			
		    $("#ceilingbar").val("셔터박스").prop("selected", true);   // 선택사항 변경
		    $("#ceilingmaterial").val("전면EGI1.6T+1.2T").prop("selected", true);   // 천장마감재질 변경
		    $("#lin5").html("<img src='../img/box/fst/box1.jpg' class='maxsmall'>");		
		 //   $("#wr5").html("<img src='../img/Rmolding/Rmolding1.jpg' class='maxsmall2'>");		
		 //   $("#wr10").html("<img src='../img/Rcase/Rcase1.jpg' class='maxsmall3'>");		
	        // $("#block5").show();			// 엘바	
		    $("#block6").show();			// T바	
		   // $("#we5").html("<img src='../img/Lbar/Lbar1.jpg' class='maxsmall1'>");		
		    $("#we10").html("<img src='../img/Tbar/fstTbar1.jpg' class='maxsmall1'>");		 // T바 이미지
	        $("#Tbar").val("SUS H/L 1.2T").prop("selected", true);   // T바 재질		
         //   $("#Lbar").val("EGI 1.6T").prop("selected", true);   // L바 재질					
			
		    $("#block5").hide();	 // L바 숨기기
		   show_one();   // 자재산출 클릭
	   });	

	
    $("#setenvbutton").on("click", function() {
	     hide_object();		
		$("#material_list").show();	
        $("#material_list").load("./setenv.php");
    });		
    $("#viewkdmotor").on("click", function() {
     window.open("../img/kdmotor.png",'경동모터 제원표',width=880,height=500); 
	 return false;
    });		
    $("#viewcontroler").on("click", function() {
     window.open("../img/kdcontroler.jpg",'연동제어기 치수',width=880,height=500); 
	 return false;
    });		
    $("#viewworkerlist").on("click", function() {
		
	 window.open("../list/workerlist.xlsx");
	 return false;
    });			
     $("#gotorail").on("click", function() {
	    hide_object();
      $("#guiderail_area").show();	
      var target = document.getElementById("item_sel"); 
         // alert(target.options[target.selectedIndex].value);
      var sendData = target.options[target.selectedIndex].value ;
            var ua = window.navigator.userAgent;
            var postData; 
            sendData = "./rail.php?rail=" + sendData +"&sel=1";    // 첫번째 레일선택

            // 윈도우라면 ? 
            if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(sendData);
            } else {
                postData = sendData;
            }	
        $("#guiderail_area").load(postData);
    });		
     $("#gotorailanother").on("click", function() {
	             show_rail();
    });			
	 $("#gotoRmolding").on("click", function() {    // GotoRmolding 버튼 클릭시
	             show_Rmolding();
    });	
	 $("#gotoRcase").on("click", function() {    // GotoRcase 버튼 클릭시
	             show_Rcase();
    });		
	 $("#gotoLbar").on("click", function() {    // GotoLbar 버튼 클릭시
	             show_Lbar();
    });		
	 $("#gotoTbar").on("click", function() {    // GotoTbar 버튼 클릭시
	             show_Tbar();
    });		
});	

/* function openExcelFile(strFilePath) {
    if (window.ActiveXObject) {
        try {
            var objExcel;
            objExcel = new ActiveXObject("Excel.Application");
            objExcel.Visible = true;
            objExcel.Workbooks.Open(strLocation, false, [readonly: true|false]);
        }
        catch (e) {
            alert (e.message);
        }
    }
    else {
        alert ("Your browser does not support this.");
    }
} */

function show_Lbar() {      // L바 버튼
	  hide_object();
      $("#guiderail_area").show();	
      var tmp = $("#item_sel option:selected").val();              // 서터종류 선택값
      var tmp2 = $("#ceilingbar option:selected").val();            // 천장마감 선택값
	  var sendData;
      sendData = "./Lbar.php";   // L바 
      $("#guiderail_area").load(sendData);
}	

function show_Tbar() {      // T바 버튼
	  hide_object();
      $("#guiderail_area").show();	
      var target = document.getElementById("item_sel"); 
         // alert(target.options[target.selectedIndex].value);
      var sendData = target.options[target.selectedIndex].value ;
            var ua = window.navigator.userAgent;
            var postData; 
            sendData = "./Tbar.php?rail=" + sendData ;   // 셔터 종류 전달

            // 윈도우라면 ? 
            if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(sendData);
            } else {
                postData = sendData;
            }	
      $("#guiderail_area").load(sendData);
}	

function show_rail() {
	 hide_object();
      $("#guiderail_area").show();	
      var target = document.getElementById("item_sel"); 
         // alert(target.options[target.selectedIndex].value);
      var sendData = target.options[target.selectedIndex].value ;
            var ua = window.navigator.userAgent;
            var postData; 
            sendData = "./rail.php?rail=" + sendData +"&sel=2";   // 두번째 레일선택

            // 윈도우라면 ? 
            if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(sendData);
            } else {
                postData = sendData;
            }	

        $("#guiderail_area").load(postData);
}

function show_lintel() {
	  hide_object();
      $("#guiderail_area").show();	

      var sendData;
      sendData = "./lintel.php";   // 린텔선택
        $("#guiderail_area").load(sendData);
}
function show_box() {      // 셔터박스 선택하면 실행
	  hide_object();
      $("#guiderail_area").show();	
      var tmp = $("#item_sel option:selected").val();         //jQuery로 선택된 값 읽기
      var tmp2 = $("#ceilingbar option:selected").val();      //jQuery로 선택된 값 읽기
	  var sendData;
	  
    if(tmp=='스크린방화' || tmp=='제연커튼')
		
          sendData = "./box.php";   // 스크린용 셔터박스
        else
          sendData = "./stbox.php";   // 철재용 셔터박스
	  
        $("#guiderail_area").load(sendData);
}
function show_Rmolding() {      // R몰딩 선택하면 실행
	  hide_object();
      $("#guiderail_area").show();	
      var tmp = $("#item_sel option:selected").val();              //jQuery로 선택된 값 읽기
      var tmp2 = $("#ceilingbar option:selected").val();            //jQuery로 선택된 값 읽기
	  var sendData;
      sendData = "./Rmolding.php";   // 몰딩이미지
      $("#guiderail_area").load(sendData);
}
		
function show_Rcase() {      // R케이스 버튼
	  hide_object();
      $("#guiderail_area").show();	
      var tmp = $("#item_sel option:selected").val();              //jQuery로 선택된 값 읽기
      var tmp2 = $("#ceilingbar option:selected").val();            //jQuery로 선택된 값 읽기
	  var sendData;
      sendData = "./Rcase.php";   // R케이스 
      $("#guiderail_area").load(sendData);
}
		
    // 브라우저 호환(크로스브라우징)을 체크한후 페이지 로딩후 selectEvent() 함수를 실행 합니다.  // 셔터 종류 선택시 
    if ( window.addEventListener ) {
        window.addEventListener("load",selectEvent,false);
    } else if ( window.attachEvent ) {
        window.attachEvent("onload",selectEvent);
    } else {
        window.onload = selectEvent;
    }
    function selectEvent() {
        // 폼이름,셀렉트박스이름 으로 셀렉트박스에 접근합니다.  
        // onchange 이벤트를 적용해줍니다. 
        document.selectForm.item_sel.onchange = selectFun;
		
    }
    // this.value 로 이벤트가 발생한 곳,자신(this)의 value값을 출력 합니다.
    function selectFun() {
        $("#order_title3").hide();	
		$("#order_input3").hide();	
  			$("#block2").hide();	        // 삼각쫄대 감추기
  			$("#block3").hide();	        // 짜부가스켓 감추기
		    $("#block5").hide();			// 엘바	
		    $("#block6").hide();			// T바	
	      //  $("#block4").hide();	 // R몰딩 R케이스 화면
        if ( this.value == '셔터종류선택' ) {
            return false;
        }
        if ( this.value == '스크린방화' ) {
            FSSrail();
        }
        if ( this.value == '철재방화EGI1.6T' ) {
            FSTrail();
		}
        if ( this.value != '철재방화EGI1.6T' && this.value != '스크린방화' && this.value != '셔터종류선택') {     // 방화셔터가 아닐때
            $("#block6").show();			// T바	보여주기			
        }		
    }    	
  function FSSrail() {
  			$("#order_title3").show();	        
  			$("#order_input3").show();	      
  			$("#block2").show();	        // 삼각쫄대 보이기
  			$("#block3").show();	        // 짜부가스켓 보이기
		    $("#block5").show();			// 엘바	
		    $("#block6").show();			// T바	
  }
  function FSTrail() {
  			$("#order_title3").show();	        
			$("#order_input3").show();	     
  			$("#block3").show();	        // 짜부가스켓 보이기			
		    $("#block6").show();			// T바	
  }
  
function hide_object() {	
		// $("#material_list").hide();	
		$("#guiderail_area").hide();	
}
 
function transData(sendData) {
     var ua = window.navigator.userAgent;
     var postData; 
	 
     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(sendData);
            } else {
                postData = sendData;
            }		 
	 return postData;
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


	
function previewImage(targetObj, previewId) {
    var preview = document.getElementById(previewId); //div id   
    var ua = window.navigator.userAgent;
    if (ua.indexOf("MSIE") > -1) {//ie일때
        targetObj.select();
        try {
            var src = document.selection.createRange().text; // get file full path 
            var ie_preview_error = document
                    .getElementById("ie_preview_error_" + previewId);
            if (ie_preview_error) {
                preview.removeChild(ie_preview_error); //error가 있으면 delete
            }

            var img = document.getElementById(previewId); //이미지가 뿌려질 곳 

            img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"
                    + src + "', sizingMethod='scale')"; //이미지 로딩, sizingMethod는 div에 맞춰서 사이즈를 자동조절 하는 역할
        } catch (e) {
            if (!document.getElementById("ie_preview_error_" + previewId)) {
                var info = document.createElement("<p>");
                info.id = "ie_preview_error_" + previewId;
               info.innerHTML = "a";
                preview.insertBefore(info, null);
            }
       }
    } else { //ie가 아닐때
        var files = targetObj.files;
        for ( var i = 0; i < files.length; i++) {
            var file = files[i];
            var imageType = /image.*/; //이미지 파일일경우만.. 뿌려준다.
            if (!file.type.match(imageType))
                continue;
            var prevImg = document.getElementById("prev_" + previewId); //이전에 미리보기가 있다면 삭제
            if (prevImg) {
                preview.removeChild(prevImg);
            }
            var img = document.createElement("img"); //크롬은 div에 이미지가 뿌려지지 않는다. 그래서 자식Element를 만든다.

            img.id = "prev_" + previewId;

            img.classList.add("obj");

            img.file = file;

            img.style.width = '50px'; //기본설정된 div의 안에 뿌려지는 효과를 주기 위해서 div크기와 같은 크기를 지정해준다.

            img.style.height = '50px';         

            preview.appendChild(img);
            if (window.FileReader) { // FireFox, Chrome, Opera 확인.
                var reader = new FileReader();
                reader.onloadend = (function(aImg) {
                    return function(e) {
                        aImg.src = e.target.result;
                    };
                })(img);
                reader.readAsDataURL(file);
            } else { // safari is not supported FileReader
                //alert('not supported FileReader');
                if (!document.getElementById("sfr_preview_error_"
                        + previewId)) {
                    var info = document.createElement("p");
                    info.id = "sfr_preview_error_" + previewId;
                    info.innerHTML = "not supported FileReader";
                    preview.insertBefore(info, null);
                }

            }

        }

    }

} 

function drawbracket() {	

var brX = Number($("#brX").val());
var brY = Number($("#brY").val());
var spaceX = 200;   // 초기 x좌표 좌측 띄움
var spaceY = 100;  // 초기 y좌표 좌측 띄움
var boxspaceX = 100;
var boxspaceY = 150;
var axis = brY/2 ; // Arc radius

  var radius = brY/2/2 ; // Arc radius
  var startAngle = 0; // Starting point on circle
  var endAngle = Math.PI + (Math.PI * 2) / 2; // End point on circle
  var anticlockwise = true; // clockwise or anticlockwise
  // var anticlockwise = i % 2 == 0 ? false : true; // clockwise or anticlockwise


var boxwidth = brX+boxspaceX;  // sutter box width
var boxheight = brY+boxspaceY;  // sutter box height

// bracket 형상 그림
    ctx.beginPath();
    ctx.strokeStyle = "blue";
	ctx.moveTo(spaceX,spaceY);
	ctx.lineTo(spaceX+brX,spaceY);
	ctx.lineTo(spaceX+brX,spaceY+brY);
	ctx.lineTo(spaceX,spaceY+brY);
	ctx.lineTo(spaceX,spaceY);
	ctx.stroke();		
	
// bracket 텍스트 넣기
ctx.font = 'italic 22px Calibri';
ctx.fillText(brX + 'X'+brY +" Bracket",spaceX+brX*3/5,spaceY+brY/2);

// 샤프트 그리기
    ctx.beginPath();
    ctx.strokeStyle = "red";
    ctx.arc(spaceX+axis,spaceY+axis, radius, startAngle, endAngle, anticlockwise);
	ctx.stroke();		

//셔터박스 그리기
    ctx.beginPath();
    ctx.strokeStyle =  "black";
	ctx.moveTo(spaceX-boxspaceX*0.35,spaceY);
	ctx.lineTo(spaceX+boxwidth,spaceY);
	ctx.lineTo(spaceX+boxwidth,spaceY+boxheight);
	ctx.lineTo(spaceX-boxspaceX*0.35,spaceY+boxheight);
	ctx.lineTo(spaceX-boxspaceX*0.35,spaceY);
	ctx.stroke();	

}
//enter키로 form submit 막기
	$('input[type="text"]').keydown(function() {
    if (event.keyCode === 13) {
        event.preventDefault();
    }
});

function changeUri(tmpdata)
{
	  var ua = window.navigator.userAgent;
      var postData; 	 
	
	     if (ua.indexOf('MSIE') > 0 || ua.indexOf('Trident') > 0) {
                postData = encodeURI(tmpdata);
            } else {
                postData = tmpdata;
            }

      return postData;
}
