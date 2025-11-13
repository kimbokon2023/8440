var activeTab;

// ajax 중복처리를 위한 구문
var ajaxRequest = null;
var ajaxRequest1 = null;
var ajaxRequest2 = null;  
var ajaxRequest3 = null;  
var ajaxRequest4 = null;  
var ajaxRequest5 = null;  

var isModalOpen = false; // 모달 창 상태를 추적하는 전역 변수

var Eworks_dataTable; // DataTables 인스턴스 전역 변수
var Eworks_pageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

// DataTable 초기화 함수
function initDataTable() {
    if (!$('#myEworks_Table').length) return;
    
    // DataTable이 이미 초기화되어 있는지 확인
    if ($.fn.DataTable.isDataTable('#myEworks_Table')) {
        return;
    }
    
    try {
        if (typeof $.fn.DataTable === 'undefined') {
            // console.warn('DataTable 라이브러리가 로드되지 않았습니다.');
            return;
        }

        Eworks_dataTable = $('#myEworks_Table').DataTable({
            "paging": true,
            "ordering": true,
            "searching": false,
            "pageLength": 50,
            "lengthMenu": [25, 50, 100, 200, 500, 1000],
            "language": {
                "lengthMenu": "Show _MENU_ entries",
                "search": "Live Search:"
            },
            "columns": [
                { "data": "checkbox", "orderable": false },   // 첫 번째 열 (체크박스)
                { "data": "type" },                           // 두 번째 열 (구분)
                { "data": "date" },                           // 세 번째 열 (작성일시)
                { "data": "author" },                           
                { "data": "state" },                           
                { "data": "progress" },                           
                { "data": "refer" },                           
                { "data": "title" }, 
                {
                    "data": "optionalColumn",
                    "visible": false       // 초기 상태에서 열의 가시성 설정				
                }
            ],
        });

        // 페이지 번호 복원 (초기 로드 시)
        var savedPageNumber = getCookie('Eworks_pageNumber');
        if (savedPageNumber) {
            Eworks_dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }

        // 페이지 변경 이벤트 리스너
        Eworks_dataTable.on('page.dt', function() {
            var pageNumber = Eworks_dataTable.page.info().page + 1;
            setCookie('Eworks_pageNumber', pageNumber, 10); // 쿠키에 페이지 번호 저장
        });

        // 페이지 길이 셀렉트 박스 변경 이벤트 처리
        $('#myEworks_Table_length select').on('change', function() {
            var selectedValue = $(this).val();
            Eworks_dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경

            // 변경 후 현재 페이지 번호 복원
            savedPageNumber = getCookie('Eworks_pageNumber');
            if (savedPageNumber) {
                Eworks_dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
            }
        });
    } catch (error) {
        console.error('DataTable 초기화 중 오류가 발생했습니다:', error);
    }
}

// 페이지 로드 시 DataTable 초기화 시도
$(document).ready(function() {
    if ($('#myEworks_Table').length) {
        // DataTable 라이브러리가 로드될 때까지 대기
        if (typeof $.fn.DataTable !== 'undefined') {
            initDataTable();
        } else {
            // 라이브러리가 아직 로드되지 않은 경우 잠시 후 재시도
            setTimeout(function() {
                if (typeof $.fn.DataTable !== 'undefined') {
                    initDataTable();
                }
            }, 500);
        }
    }
});

// DataTable 라이브러리 로드 상태 확인 함수
function isDataTableAvailable() {
    return typeof $.fn.DataTable !== 'undefined' && $.fn.DataTable !== null;
}

// DataTable 인스턴스 확인 함수
function isDataTableInitialized() {
    return typeof Eworks_dataTable !== 'undefined' && Eworks_dataTable !== null;
}

function Eworks_restorePageNumber() {
    try {
        // DataTable이 정의되어 있는지 확인
        if (!isDataTableInitialized()) {
            console.warn('DataTable이 초기화되지 않았습니다.');
            return;
        }
        
        var savedPageNumber = getCookie('Eworks_pageNumber');
        if (savedPageNumber) {
            Eworks_dataTable.page(parseInt(savedPageNumber) - 1).draw('page');
        }
    } catch (error) {
        console.error('페이지 번호 복원 중 오류가 발생했습니다:', error);
    }
}	

$(document).ready(function () {	
	// 전자결재를 위해 띄우는 창	

	if ($(".sideBanner").length > 0) {
	  var floatPosition = parseInt($(".sideBanner").css('top'));
	  // 이제 floatPosition을 사용하거나 다른 작업을 수행할 수 있습니다.
	} else {
	  // "sideBanner" 클래스가 존재하지 않는 경우에 대한 처리를 여기에 추가할 수 있습니다.
	}


	if ($(".sideBanner").length > 0) {
	  $(window).scroll(function() {
		// 모바일에선 나타나지 않게 하기
		// 모바일에서는 스크롤 애니메이션 비활성화 (토글로 제어)
		if (window.innerWidth <= 768) {
			return;
		}

		// 현재 스크롤 위치
		var currentTop = $(window).scrollTop();
		var bannerTop = currentTop + floatPosition + "px";

		//이동 애니메이션
		$(".sideBanner").stop().animate({
		  "top" : bannerTop
		}, 500);

	  }).scroll();
	}

	// 전자결재 알림을 위해 띄우는 창
	// 기본 위치(top)값

	if ($(".sideEworksBanner").length > 0) {
	  var eworks_floatPosition = parseInt($(".sideEworksBanner").css('top'));
	  // 이제 floatPosition을 사용하거나 다른 작업을 수행할 수 있습니다.
	} else {
	  // "sideEworksBanner" 클래스가 존재하지 않는 경우에 대한 처리를 여기에 추가할 수 있습니다.
	}


	if ($(".sideEworksBanner").length > 0) {
	  $(window).scroll(function() {
		// 모바일에서는 스크롤 애니메이션 비활성화 (토글로 제어)
		if (window.innerWidth <= 768) {
			return;
		}

		// 현재 스크롤 위치
		var currentTop = $(window).scrollTop();
		var bannerTop = currentTop + eworks_floatPosition + "px";

		//이동 애니메이션
		$(".sideEworksBanner").stop().animate({
		  "top" : bannerTop
		}, 500);

	  }).scroll();
	}
	
	var sideEworksBanners = document.getElementsByClassName('sideEworksBanner');
	if (sideEworksBanners.length > 0) {
		Array.from(sideEworksBanners).forEach(function(sideEworksBanner) {
			sideEworksBanner.addEventListener('click', function() {
				seltab(3); // 원래의 기능 실행
				this.style.display = 'none'; // .sideEworksBanner 요소를 숨김
			});
		});
	}

	 var spinner = document.getElementById('spinner');
	 if(spinner)
		$("#spinner").hide();
	
      var dropdownVisible = false; // 드롭다운 메뉴 상태 변수
	  
			 // #home-menu 요소가 존재할 때만 이벤트 리스너 추가
		if ($("#home-menu").length > 0) {
		  // 드롭다운 메뉴를 표시하는 이벤트 리스너
		  $("#home-menu").hover(
			function () {
			  // 드롭다운 메뉴를 표시하고 상태를 true로 변경
			  $(".sitemap-dropdown").css("display", "block");
			  dropdownVisible = true;
			},
			function () {
			  // 마우스가 벗어날 때 상태를 유지하도록 조건을 추가
			  if (!dropdownVisible) {
				$(".sitemap-dropdown").css("display", "none");
			  }
			}
		  );

		  // .sitemap-dropdown 요소에 호버하는 이벤트 리스너 추가
		  $(".sitemap-dropdown").hover(
			function () {
			  // 드롭다운 메뉴 내부에 있을 때 상태를 true로 변경
			  dropdownVisible = true;
			},
			function () {
			  // 드롭다운 메뉴를 떠날 때 상태를 false로 변경
			  dropdownVisible = false;
			  $(".sitemap-dropdown").css("display", "none");
			}
		  );

		  // 다른 nav li에 호버하는 이벤트 리스너 추가
		  $("nav-item div").not("#home-menu").hover(
			function () {
			  // 다른 nav li에 호버되면 .sitemap-dropdown를 숨김
			  dropdownVisible = false;
			  $(".sitemap-dropdown").css("display", "none");
			}
		  );
		}
		
   });
   
$(document).ready(function(){
	
    // showdate 요소와 showframe 요소가 페이지에 존재하는지 확인
    var showdate = document.getElementById('showdate');
    var showframe = document.getElementById('showframe');
    
    // 요소가 존재하지 않으면 나머지 코드는 실행하지 않음
    if (!showdate || !showframe) {
        return;
    }

    var hideTimeout; // 프레임을 숨기기 위한 타이머 변수

    // 요소가 존재한다면 이벤트 리스너를 추가
    showdate.addEventListener('mouseenter', function(event) {
        clearTimeout(hideTimeout);  // 이미 설정된 타이머가 있다면 취소
        showframe.style.top = (showdate.offsetTop + showdate.offsetHeight) + 'px';
        showframe.style.left = showdate.offsetLeft + 'px';
        showframe.style.display = 'block';
    });

    showdate.addEventListener('mouseleave', startHideTimer);

    showframe.addEventListener('mouseenter', function() {
        clearTimeout(hideTimeout);  // 이미 설정된 타이머가 있다면 취소
    });

    showframe.addEventListener('mouseleave', startHideTimer);

    // 타이머를 시작하는 함수
    function startHideTimer() {
        hideTimeout = setTimeout(function() {
            showframe.style.display = 'none';
        }, 50);  // 300ms 후에 프레임을 숨깁니다.
    }
});	
	
$(document).ready(function(){		
    // showalign 요소와 showalignframe 요소가 페이지에 존재하는지 확인
    var showalign = document.getElementById('showalign');
    var showalignframe = document.getElementById('showalignframe');
    
    // 요소가 존재하지 않으면 나머지 코드는 실행하지 않음
    if (!showalign || !showalignframe) {
        return;
    }

    var hideTimeoutAlign; // 프레임을 숨기기 위한 타이머 변수

    // 요소가 존재한다면 이벤트 리스너를 추가
    showalign.addEventListener('mouseenter', function(event) {
        clearTimeout(hideTimeoutAlign);  // 이미 설정된 타이머가 있다면 취소
        showalignframe.style.top = (showalign.offsetTop + showalign.offsetHeight) + 'px';
        showalignframe.style.left = showalign.offsetLeft + 'px';
        showalignframe.style.display = 'block';
    });

    showalign.addEventListener('mouseleave', startAlignHideTimer);

    showalignframe.addEventListener('mouseenter', function() {
        clearTimeout(hideTimeoutAlign);  // 이미 설정된 타이머가 있다면 취소
    });

    showalignframe.addEventListener('mouseleave', startAlignHideTimer);

    // 타이머를 시작하는 함수
    function startAlignHideTimer() {
        hideTimeoutAlign = setTimeout(function() {
            showalignframe.style.display = 'none';
        }, 50);  // 300ms 후에 프레임을 숨깁니다.
    }
	
});	

$(document).ready(function(){	
	
    // showextract 요소와 showextractframe 요소가 페이지에 존재하는지 확인
    var showextract = document.getElementById('showextract');
    var showextractframe = document.getElementById('showextractframe');
    
    // 요소가 존재하지 않으면 나머지 코드는 실행하지 않음
    if (!showextract || !showextractframe) {
        return;
    }

    var hideTimeoutextract; // 프레임을 숨기기 위한 타이머 변수 

    // 요소가 존재한다면 이벤트 리스너를 추가
    showextract.addEventListener('mouseenter', function(event) {
        clearTimeout(hideTimeoutextract);  // 이미 설정된 타이머가 있다면 취소
        showextractframe.style.top = (showextract.offsetTop + showextract.offsetHeight) + 'px';
        showextractframe.style.left = showextract.offsetLeft + 'px';
        showextractframe.style.display = 'block';
    });

    showextract.addEventListener('mouseleave', startextractHideTimer);

    showextractframe.addEventListener('mouseenter', function() {
        clearTimeout(hideTimeoutextract);  // 이미 설정된 타이머가 있다면 취소
    });

    showextractframe.addEventListener('mouseleave', startextractHideTimer);

    // 타이머를 시작하는 함수
    function startextractHideTimer() {
        hideTimeoutextract = setTimeout(function() {
            showextractframe.style.display = 'none';
        }, 50);  // 300ms 후에 프레임을 숨깁니다.
    }
	
});

$(document).ready(function(){	
    // showsearchtool 요소와 showsearchtoolframe 요소가 페이지에 존재하는지 확인
    var showsearchtool = document.getElementById('showsearchtool');
    var showsearchtoolframe = document.getElementById('showsearchtoolframe');
    
    // 요소가 존재하지 않으면 나머지 코드는 실행하지 않음
    if (!showsearchtool || !showsearchtoolframe) {
        return;
    }

    var hideTimeoutextract; // 프레임을 숨기기 위한 타이머 변수

    // 요소가 존재한다면 이벤트 리스너를 추가
    showsearchtool.addEventListener('mouseenter', function(event) {
        clearTimeout(hideTimeoutextract);  // 이미 설정된 타이머가 있다면 취소
        showsearchtoolframe.style.top = (showsearchtool.offsetTop + showsearchtool.offsetHeight) + 'px';
        showsearchtoolframe.style.left = showsearchtool.offsetLeft + 'px';
        showsearchtoolframe.style.display = 'block';
    });

    showsearchtool.addEventListener('mouseleave', startextractHideTimer);

    showsearchtoolframe.addEventListener('mouseenter', function() {
        clearTimeout(hideTimeoutextract);  // 이미 설정된 타이머가 있다면 취소
    });

    showsearchtoolframe.addEventListener('mouseleave', startextractHideTimer);
	
    // 타이머를 시작하는 함수
    function startextractHideTimer() {
        hideTimeoutextract = setTimeout(function() {
            showsearchtoolframe.style.display = 'none';
        }, 50);  // 300ms 후에 프레임을 숨깁니다.
    }	
});
	
$(document).ready(function(){		

/////////////////////////////////////////////// 전자 결재관련
			
	$("#closeModalxBtn, #closeEworksBtn").click(function() {
		$('#eworks_form').modal('hide'); // 모달 숨김

		// .sideEworksBanner 요소가 존재하면 해당 요소를 보이게 설정 (PC만)
		if ($('.sideEworksBanner').length > 0 && window.innerWidth > 768) {
			$('.sideEworksBanner').css('display', 'block');
		}
	});

	$("#closeModaldetailBtn, #closesecondModalBtn").click(function(){ 	    
		$('#eworks_viewmodal').modal('hide');					
	});
	
	 
	// eworks 전자결재 창에서 작성창 실행	
	$("#eworks_WindowBtn").click( function() {	
        $('#status').val('draft');	
		viewEworks_detail('',1);			  
	});		// end of click function	

// 홈페이지에서 전자결재를 클릭하면 최초 실행되는 것	
// 전자결재를 클릭하면 최초 실행되는 것		
// 전자결채 클릭하면 나오는 첫 화면 (조회/등록 등) 전자결재 초기
viewEworks_detail = function(e_num, eworksPage)
{	
   $("#eworksPage").val(eworksPage); 
   
	// 화면 초기화 작업 contents의 내용 초기화
	initializeContents();   

  if(e_num=='' || e_num==null)
    {
       $('#status').val('draft');		  
       $('#e_num').val('');		  
	}
	
	console.log(e_num);
	id= e_num;
		
	// 뒷배경 누를때 안닫히게
	$('#eworks_viewmodal').modal( {
	backdrop: 'static', keyboard : false });
			
	var btnClear = document.querySelectorAll('.btnClear');
			btnClear.forEach(function(btn){
				btn.addEventListener('click', function(e){
					btn.parentNode.querySelector('input').value = "";
					e.preventDefault(); // 기본 이벤트 동작 막기
				})
			})		
	
		$('#e_num').val(id); 
		
		 e_num = Number(id);
			
			// console.log('클릭된 순번 : ');
			// console.log(e_num);
			
			// 서버에 저장하는 방식이 아닌 자바스크립트로 input hidden 스타일로 넣어야 기억한다.
			// 서버에 저장하고 php에서 불러오면 계속 지난 정보를 갖고 있다. 주의하자
					

		  var URL =	"/eworks/load_listone.php";	  // 1개만 로드함
		  // 중복호출 금지	
		  if (ajaxRequest3 !== null) {
			ajaxRequest3.abort(); 
		  }		   
		 // data 전송해서 php 값을 넣기 위해 필요한 구문
		ajaxRequest3 = $.ajax({
				url: URL,
				type: "post",		
				data: $("#eworks_board_form").serialize(),
				dataType:"json",	
				success : function(data){	
		
				// console.log('data result: ');
				// console.log( data);
				// 거래처 납기일 등 기록하기		
						
			  e_title= data["e_title"];
			  eworks_item= data["eworks_item"];
			  contents= data["contents"];
			  registdate= data["registdate"];
			  status= data["status"];
			  e_line= data["e_line"];
			  e_line_id= data["e_line_id"];
			  e_confirm = data["e_confirm"];
			  e_confirm_id = data["e_confirm_id"];
			  r_line= data["r_line"];
			  r_line_id= data["r_line_id"];
			  recordtime= data["recordtime"];
			  author= data["author"];		
			  author_id= data["author_id"];		
			  done= data["done"];	

			  // 디버깅: 받은 데이터 확인
			  console.log('=== load_listone.php 응답 데이터 ===');
			  console.log('eworks_item:', eworks_item);
			  console.log('author:', author);
			  console.log('author_id:', author_id);
			  console.log('e_line:', e_line);
			  console.log('e_line_id:', e_line_id);
			  console.log('registdate:', registdate);

		// 결재 테이블 생성 및 추가
        createApprovalTable(data.e_line, data.e_confirm);				
			  
			  $('#status').val(status); 

				switch (status) {					
					   case 'draft' :
						  statusStr = "작성";
					   break;					
					   case 'send' :
						  statusStr = "상신";
					   break;					
					   case 'noend' :
						  statusStr = "미결";						  
					   break;					
					   case 'ing' :
						  statusStr = "진행";						  
					   break;									
					   case 'end' :
						  statusStr = "결재완료";
					   break;
					   case 'reject' :
						  statusStr = "반려";
					   break;
					   case 'wait' :
						  statusStr = "보류";
					   break;
					   case 'refer' :
						  statusStr = "참조";
					   break;
					   default :
						  statusStr = "작성";
					   break;	
					}
									
				// if (typeof e_line !== 'undefined') 
				// console.log(typeof e_line);
				// console.log(typeof e_confirm);
				// console.log('여기까지');
				
				// 결재라인은 1차결재를 한사람은 결재권한을 잃고 버튼이 나오지 않게 만든다.
				
				var searchresult = 0;
				console.log('e_line_id' , e_line_id);
				if (e_line_id !== null && e_line_id !== '') {
				var searchname = e_line_id.split("!");  // 결재인

					// 'searchname' 배열이 비어 있지 않고 유효한 데이터를 포함하는 경우
					if (searchname.length > 0 && searchname[0] !== '') {
						for (var i = 0; i < searchname.length; i++) {
							if (checkString(searchname[i], $('#user_id').val())) {
								searchresult = 1;
								// console.log('searchresult = 1');
							}
						}
					} else {
						// 'searchname' 배열이 비어 있거나 유효한 데이터가 없는 경우
						console.log('No valid data in searchname array');
					}
				}
		
				
				// 2) 결재시간에 결재가 된 것이 있는지 찾는다.
				
				//	 var e_confirm= '최장중 부장 2023-01-23 18:10:10 : 김봉민 대표 2023-01-23 18:10:10 ';
				//	 var e_time = e_confirm.split(":");   // 결재시간	
				name_var = '';			
				if(!isNull(e_confirm))
					{
					
					var e_name = e_line_id.split("!");  // 결재인
					var e_time = e_confirm_id.split("!");   // 결재시간	
					// console.log('e_name.length');				 
					// console.log(e_name.length);		
					
					for (var i = 0; i < e_name.length; i++) {
							// console.log('변수변화 점검');
						
						
						if (searchresult = 1 && checkString(e_name[i], $('#user_id').val()) && (typeof e_time[i] === 'undefined'  || e_time[i] === '') ) {
					//		console.log('결재라인이 있는데 결재시간이 없습니다. 결재가능 상태');
							// name_var를 지정하면 결재가 가능한 상태입니다.
							name_var = $('#user_id').val();
							break;
						}
					}
					
				// 마지막으로 이름이 포함되어 있으면 결재권을 박탈한다.						
					for (var i = 0; i < e_time.length; i++) {
						if(checkString(e_time[i], $('#user_id').val()))							
							name_var = '';
							break;
					  }
		        
				}
				else
				{
					if(searchresult == 1 )
						name_var = $('#user_id').val();
				}
					
				if(isNull(e_confirm))
					e_confirm="";
				
				e_prograss = "(" + statusStr + ") " + e_confirm ;		
							
			// 화면 초기화 (모달과 hidden 필드 모두)
			$('#eworks_viewmodal #registdate, #eworks_board_form #registdate').val(''); 	
			$('#eworks_viewmodal #e_title, #eworks_board_form #e_title').val(''); 					
			$('#eworks_viewmodal #contents, #eworks_board_form #contents').val(''); 	
			$('#eworks_viewmodal #author, #eworks_board_form #author').val(''); 	
			$('#eworks_viewmodal #author_id, #eworks_board_form #author_id').val(''); 	
			$('#eworks_viewmodal #e_line, #eworks_board_form #e_line').val(''); 	
			$('#eworks_viewmodal #e_line_id, #eworks_board_form #e_line_id').val(''); 	
			$('#eworks_board_form #e_confirm').val(''); 	
			$('#eworks_board_form #e_confirm_id').val(''); 	
			$('#eworks_viewmodal #r_line, #eworks_board_form #r_line').val(''); 	
			$('#eworks_viewmodal #r_line_id, #eworks_board_form #r_line_id').val(''); 		
			$('#eworks_viewmodal #e_prograss').val(''); 	
			$('#eworks_board_form #done').val(''); 
							
				if(eworks_item==='연차')
				{
				var textarea = document.getElementById("contents");
				if (textarea) {
					textarea.style.display = "none";
					var jsonContent = JSON.parse(contents); // Assuming 'contents' is a JSON string
					var htmlContainer = document.getElementById('htmlContainer');

					// Create a mapping of keys to Korean
					var keyMapping = {																		
						"author" : "신청인",
						"al_askdatefrom": "시작일",
						"al_askdateto": "종료일",
						"al_item": "연차 종류",
						"al_usedday": "신청 기간 산출",
						"al_content": "신청 사유"						
					};

					var tableHtml = '<div class="d-flex justify-content-center">  <table class="table table-bordered" style="width:40%;">  <tbody>';					
					// Iterate over the jsonContent to create table rows
					for (var key in jsonContent) {
						if (jsonContent.hasOwnProperty(key) && keyMapping[key]) { // Check if key is in the mapping
							tableHtml += '<tr>';							
							tableHtml += '<td class="text-center fw-bold" >' + keyMapping[key] + '</td>'; // Use the Korean mapping
							tableHtml += '<td class="text-start  text-dark" >' + (jsonContent[key] === null ? '' : jsonContent[key]) + '</td>';
							tableHtml += '</tr>';
						}
					}					
					tableHtml += '</tbody> </table> </div> ';

					htmlContainer.innerHTML = tableHtml;

					// Set all input elements inside htmlContainer to readonly
					var inputs = htmlContainer.querySelectorAll('input, textarea, select');
					inputs.forEach(function(input) {
						if (input.tagName === 'SELECT') {
							input.setAttribute('disabled', true);
						} else {
							input.setAttribute('readonly', true);
						}
					});
				}
					
				}
		else if (eworks_item === '원자재구매') {
				var textarea = document.getElementById("contents");
				if (textarea) {
					textarea.style.display = "none";
					var jsonContent = JSON.parse(contents); // Assuming 'contents' is a JSON string
					var htmlContainer = document.getElementById('htmlContainer');

					// Create a mapping of keys to Korean
					var keyMapping = {																		
						"outworkplace": "현장명",
						"company": "사급업체명",
						"supplier": "공급업체명",
						"steel_item": "철판종류",
						"spec": "규격(사이즈)",
						"steelnum": "수량",
						"request_comment": "비고"						
					};

					var tableHtml = '<table class="table table-bordered" style="width:60%;">  <tbody>';

					// Iterate over the jsonContent to create table rows
					for (var key in jsonContent) {
						if (jsonContent.hasOwnProperty(key) && keyMapping[key]) { // Check if key is in the mapping
							tableHtml += '<tr>';
							tableHtml += '<td class="text-center" >' + keyMapping[key] + '</td>'; // Use the Korean mapping
							tableHtml += '<td class="text-start  text-primary" >' + (jsonContent[key] === null ? '' : jsonContent[key]) + '</td>';
							tableHtml += '</tr>';
						}
					}

					tableHtml += '</tbody> </table>';

					htmlContainer.innerHTML = tableHtml;

					// Set all input elements inside htmlContainer to readonly
					var inputs = htmlContainer.querySelectorAll('input, textarea, select');
					inputs.forEach(function(input) {
						if (input.tagName === 'SELECT') {
							input.setAttribute('disabled', true);
						} else {
							input.setAttribute('readonly', true);
						}
					});
				}
			}
		else if (eworks_item === '부자재구매') {
				var textarea = document.getElementById("contents");
				if (textarea) {
					textarea.style.display = "none";
					var jsonContent = JSON.parse(contents); // Assuming 'contents' is a JSON string
					var htmlContainer = document.getElementById('htmlContainer');

					// Create a mapping of keys to Korean
					var keyMapping = {																		
						"outworkplace": "물품명",						
						"supplier": "공급업체명",						
						"spec": "규격",
						"steelnum": "수량",
						"payment": "구매방식",
						"request_comment": "비고"						
					};

					var tableHtml = '<table class="table table-bordered" style="width:60%;">  <tbody>';

					// Iterate over the jsonContent to create table rows
					for (var key in jsonContent) {
						if (jsonContent.hasOwnProperty(key) && keyMapping[key]) { // Check if key is in the mapping
							tableHtml += '<tr>';
							tableHtml += '<td class="text-center" >' + keyMapping[key] + '</td>'; // Use the Korean mapping
							tableHtml += '<td class="text-start  text-primary" >' + (jsonContent[key] === null ? '' : jsonContent[key]) + '</td>';
							tableHtml += '</tr>';
						}
					}

					tableHtml += '</tbody> </table>';

					htmlContainer.innerHTML = tableHtml;

					// Set all input elements inside htmlContainer to readonly
					var inputs = htmlContainer.querySelectorAll('input, textarea, select');
					inputs.forEach(function(input) {
						if (input.tagName === 'SELECT') {
							input.setAttribute('disabled', true);
						} else {
							input.setAttribute('readonly', true);
						}
					});
				}
			}
						
			else if (eworks_item === '품의서') {
				var textarea = document.getElementById("contents");
				if (textarea) {
					textarea.style.display = "none";
					var jsonContent = JSON.parse(contents); // Assuming 'contents' is a JSON string
					var htmlContainer = document.getElementById('htmlContainer');

					// Create a mapping of keys to Korean
					var keyMapping = {																		
						"e_title": "품의서",						
						"indate": "작성일",						
						"author": "기안자",						
						"outworkplace": "품의서 제목",	
						"al_content": "품의서 내역",	
						"request_comment": "품의 사유",						
						"suppliercost": "예상 비용"						
					};

					var tableHtml = '<table class="table table-bordered" style="width:60%;">  <tbody>';

					// Iterate over the jsonContent to create table rows
					for (var key in jsonContent) {
						if (jsonContent.hasOwnProperty(key) && keyMapping[key]) { // Check if key is in the mapping
							tableHtml += '<tr>';
							if (key  == "suppliercost")
								tableHtml += '<td class="text-center text-danger fw-bold" style="width:20%;" >' + keyMapping[key] + '</td>'; // Use the Korean mapping
							else
								tableHtml += '<td class="text-center fw-bold" style="width:20%;" >' + keyMapping[key] + '</td>'; // Use the Korean mapping
							
							// 개행 문자를 <br> 태그로 변환하여 td 요소에 삽입
							var value = jsonContent[key] === null ? '' : jsonContent[key].replace(/\n/g, "<br>");
							if (key == "suppliercost")
								tableHtml += '<td class="text-start text-danger fw-bold">' + value + ' 원 </td>';
							else
								tableHtml += '<td class="text-start">' + value + '</td>';

							tableHtml += '</tr>';
						}
					}
					
					tableHtml += '</tbody> </table>';
					htmlContainer.innerHTML = tableHtml;

					// Set all input elements inside htmlContainer to readonly
					var inputs = htmlContainer.querySelectorAll('input, textarea, select');
					inputs.forEach(function(input) {
						if (input.tagName === 'SELECT') {
							input.setAttribute('disabled', true);
						} else {
							input.setAttribute('readonly', true);
						}
					});
				}
			}						
			else if (eworks_item === '연장근무') {
				var textarea = document.getElementById("contents");
				if (textarea) {
					textarea.style.display = "none";
					var jsonContent = JSON.parse(contents);
					var htmlContainer = document.getElementById('htmlContainer');

					// 연장근무 필드 매핑 (request_overtime/index.php의 모달 form과 동일한 구조)
					var keyMapping = {
						"author": "신청인",
						"al_part": "부서",
						"ot_type": "근무유형",
						"al_askdatefrom": "작업일자",
						"ot_start_time": "시작시간",
						"ot_end_time": "종료시간",
						"al_usedday": "연장시간",
						"al_content": "신청사유"
					};

					// 깔끔한 테이블 생성 (중앙 정렬, 60% 너비)
					var tableHtml = '<div class="d-flex justify-content-center">';
					tableHtml += '<table class="table table-bordered" style="width:60%;">';
					tableHtml += '<tbody>';

					// 필드 순서대로 출력
					var fieldOrder = ["author", "al_part", "ot_type", "al_askdatefrom", "ot_start_time", "ot_end_time", "al_usedday", "al_content"];
					
					fieldOrder.forEach(function(key) {
						if (jsonContent.hasOwnProperty(key) && keyMapping[key]) {
							tableHtml += '<tr>';
							tableHtml += '<td class="text-center fw-bold bg-light" style="width:30%;">' + keyMapping[key] + '</td>';
							
							var value = jsonContent[key] === null ? '' : jsonContent[key];
							
							// 특정 필드에 대한 특별 처리
							if (key === "al_usedday") {
								// 연장시간에 '시간' 단위 추가
								tableHtml += '<td class="text-start ps-3">' + value + ' 시간</td>';
							} else if (key === "al_content") {
								// 신청사유는 개행 문자 처리
								value = value.replace(/\n/g, "<br>");
								tableHtml += '<td class="text-start ps-3">' + value + '</td>';
							} else {
								tableHtml += '<td class="text-start ps-3">' + value + '</td>';
							}
							
							tableHtml += '</tr>';
						}
					});

					tableHtml += '</tbody></table></div>';
					htmlContainer.innerHTML = tableHtml;

					// readonly 처리 (폼 요소가 있을 경우를 대비)
					var inputs = htmlContainer.querySelectorAll('input, textarea, select');
					inputs.forEach(function(input) {
						if (input.tagName === 'SELECT') {
							input.setAttribute('disabled', true);
						} else {
							input.setAttribute('readonly', true);
						}
					});
				}
			}
		else if (eworks_item === '연구개발계획서') {
			var textarea = document.getElementById("contents");
			if (textarea) {
				textarea.style.display = "none";
				var jsonContent = JSON.parse(contents);
				var htmlContainer = document.getElementById('htmlContainer');

				var keyMapping = {
					"e_title": "연구개발계획서",
					"indate": "작성일",
					"author": "기안자",
					"outworkplace": "제목",
					"al_content": "내역"
				};

				var tableHtml = '<table class="table table-bordered" style="width:80%;"><tbody>';

				for (var key in jsonContent) {
					if (jsonContent.hasOwnProperty(key) && keyMapping[key]) {
						tableHtml += '<tr>';
						tableHtml += '<td class="text-center fw-bold" style="width:20%;">' + keyMapping[key] + '</td>';
						var value = jsonContent[key] === null ? '' : jsonContent[key].replace(/\n/g, "<br>");
						tableHtml += '<td class="text-start">' + value + '</td>';
						tableHtml += '</tr>';
					}
				}

				tableHtml += '</tbody></table>';

				// 첨부파일 영역 추가
				var fileTableHtml = '<div id="fileArea" class="mt-3"></div>';

				htmlContainer.innerHTML = tableHtml + fileTableHtml;

				// 첨부파일 비동기 로드
				const tablename = "eworks";  // 고정값				

				$.ajax({
					url: "/filedrive/load_filelist.php",
					type: "GET",
					data: { tablename: tablename, num: e_num, item: 'attached' },
					dataType: "json",
					success: function (data) {
						var fileArea = document.getElementById("fileArea");
						if (!fileArea) return;

						if (Array.isArray(data) && data.length > 0) {
							var list = "<h6 class='fw-bold'>첨부파일</h6><ul class='list-group'>";
							data.forEach(function (file) {
								const name = file.realname || '첨부파일';
								const link = file.link || '#';
								list += `<li class="list-group-item">
									<a href="#" onclick="popupCenter('${link}', 'filePopup', 800, 600); return false;">
										<i class="bi bi-paperclip"></i> ${name}
									</a>
								</li>`;
							});
							list += "</ul>";
							fileArea.innerHTML = list;
						} else {
							fileArea.innerHTML = '<div class="text-muted">첨부파일 없음</div>';
						}
					}
				});

				// Readonly 처리
				var inputs = htmlContainer.querySelectorAll('input, textarea, select');
				inputs.forEach(function(input) {
					if (input.tagName === 'SELECT') {
						input.setAttribute('disabled', true);
					} else {
						input.setAttribute('readonly', true);
					}
				});
			}
		}

// 2025 5 12 추가된 것 '연구노트' 와 '프로젝트 개발진행 노트' 두개 모두 처리 2025 7 13 추가
else if (eworks_item === '연구노트' ) {

		var jsonContent = JSON.parse(contents); // JSON 문자열을 객체로 변환
		var htmlContainer = document.getElementById('htmlContainer');

		// 키 매핑
		var keyMapping = {
			"e_title": "연구노트",
			"indate": "작성일",
			"author": "기안자",
			"outworkplace": "제목",
			"al_content": "내용"
		};

		var tableHtml = '<table class="table table-bordered" style="width:80%;"><tbody>';

		for (var key in jsonContent) {
			if (jsonContent.hasOwnProperty(key) && keyMapping[key] && key !== 'al_content') {
				tableHtml += '<tr>';
				tableHtml += '<td class="text-center fw-bold" style="width:20%;">' + keyMapping[key] + '</td>';
				var value = jsonContent[key] === null ? '' : jsonContent[key].replace(/\n/g, "<br>");
				tableHtml += '<td class="text-start">' + value + '</td>';
				tableHtml += '</tr>';
			}
		}

		tableHtml += '</tbody></table>';

		// summernote 내용을 별도로 div에 출력
		var contentHtml = jsonContent["al_content"] || '';
		var summerHtml = `
			<div class="mt-3">
				<h6 class="fw-bold">내용</h6>
				<div class="border p-3" style="min-height:150px;text-align:left;">
					${contentHtml}
				</div>
			</div>
		`;

		// 첨부파일 영역 추가
		var fileTableHtml = '<div id="fileArea" class="mt-3"></div>';

		// 최종 렌더링
		htmlContainer.innerHTML = tableHtml + summerHtml + fileTableHtml;

		// 첨부파일 비동기 로드
		const tablename = "eworks";
		$.ajax({
			url: "/filedrive/load_filelist.php",
			type: "GET",
			data: { tablename: tablename, num: e_num, item: 'attached' },
			dataType: "json",
			success: function (data) {
				var fileArea = document.getElementById("fileArea");
				if (!fileArea) return;

				if (Array.isArray(data) && data.length > 0) {
					var list = "<h6 class='fw-bold'>첨부파일</h6><ul class='list-group'>";
					data.forEach(function (file) {
						const name = file.realname || '첨부파일';
						const link = file.link || '#';
						list += `<li class="list-group-item">
							<a href="#" onclick="popupCenter('${link}', 'filePopup', 800, 600); return false;">
								<i class="bi bi-paperclip"></i> ${name}
							</a>
						</li>`;
					});
					list += "</ul>";
					fileArea.innerHTML = list;
				} else {
					fileArea.innerHTML = '<div class="text-muted">첨부파일 없음</div>';
				}
			}
		});
	}		
// 2025 7 13 추가된 것
else if (eworks_item === '개발프로젝트 연구노트' ) {

		var jsonContent = JSON.parse(contents); // JSON 문자열을 객체로 변환
		var htmlContainer = document.getElementById('htmlContainer');

		// 키 매핑
		var keyMapping = {
			"e_title": "개발프로젝트 연구노트",
			"indate": "작성일",
			"author": "기안자",
			"outworkplace": "제목",
			"al_content": "내용"
		};

		var tableHtml = '<table class="table table-bordered" style="width:80%;"><tbody>';

		for (var key in jsonContent) {
			if (jsonContent.hasOwnProperty(key) && keyMapping[key] && key !== 'al_content') {
				tableHtml += '<tr>';
				tableHtml += '<td class="text-center fw-bold" style="width:20%;">' + keyMapping[key] + '</td>';
				var value = jsonContent[key] === null ? '' : jsonContent[key].replace(/\n/g, "<br>");
				tableHtml += '<td class="text-start">' + value + '</td>';
				tableHtml += '</tr>';
			}
		}

		tableHtml += '</tbody></table>';

		// summernote 내용을 별도로 div에 출력
		var contentHtml = jsonContent["al_content"] || '';
		var summerHtml = `
			<div class="mt-3">
				<h6 class="fw-bold">내용</h6>
				<div class="border p-3" style="min-height:150px;text-align:left;">
					${contentHtml}
				</div>
			</div>
		`;

		// 첨부파일 영역 추가
		var fileTableHtml = '<div id="fileArea" class="mt-3"></div>';

		// 최종 렌더링
		htmlContainer.innerHTML = tableHtml + summerHtml + fileTableHtml;

		// 첨부파일 비동기 로드
		const tablename = "eworks";
		$.ajax({
			url: "/filedrive/load_filelist.php",
			type: "GET",
			data: { tablename: tablename, num: e_num, item: 'attached' },
			dataType: "json",
			success: function (data) {
				var fileArea = document.getElementById("fileArea");
				if (!fileArea) return;

				if (Array.isArray(data) && data.length > 0) {
					var list = "<h6 class='fw-bold'>첨부파일</h6><ul class='list-group'>";
					data.forEach(function (file) {
						const name = file.realname || '첨부파일';
						const link = file.link || '#';
						list += `<li class="list-group-item">
							<a href="#" onclick="popupCenter('${link}', 'filePopup', 800, 600); return false;">
								<i class="bi bi-paperclip"></i> ${name}
							</a>
						</li>`;
					});
					list += "</ul>";
					fileArea.innerHTML = list;
				} else {
					fileArea.innerHTML = '<div class="text-muted">첨부파일 없음</div>';
				}
			}
		});
	}		
			
// 2025 5 12 추가된 것
else if (eworks_item === '연구개발보고서') {

		var jsonContent = JSON.parse(contents); // JSON 문자열을 객체로 변환
		var htmlContainer = document.getElementById('htmlContainer');

		// 키 매핑
		var keyMapping = {
			"e_title": "연구개발보고서",
			"indate": "작성일",
			"author": "기안자",
			"outworkplace": "제목",
			"al_content": "내용"
		};

		var tableHtml = '<table class="table table-bordered" style="width:80%;"><tbody>';

		for (var key in jsonContent) {
			if (jsonContent.hasOwnProperty(key) && keyMapping[key] && key !== 'al_content') {
				tableHtml += '<tr>';
				tableHtml += '<td class="text-center fw-bold" style="width:20%;">' + keyMapping[key] + '</td>';
				var value = jsonContent[key] === null ? '' : jsonContent[key].replace(/\n/g, "<br>");
				tableHtml += '<td class="text-start">' + value + '</td>';
				tableHtml += '</tr>';
			}
		}

		tableHtml += '</tbody></table>';

		// summernote 내용을 별도로 div에 출력
		var contentHtml = jsonContent["al_content"] || '';
		var summerHtml = `
			<div class="mt-3">
				<h6 class="fw-bold">내용</h6>
				<div class="border p-3" style="min-height:150px;text-align:left;">
					${contentHtml}
				</div>
			</div>
		`;

		// 첨부파일 영역 추가
		var fileTableHtml = '<div id="fileArea" class="mt-3"></div>';

		// 최종 렌더링
		htmlContainer.innerHTML = tableHtml + summerHtml + fileTableHtml;

		// 첨부파일 비동기 로드
		const tablename = "eworks";
		$.ajax({
			url: "/filedrive/load_filelist.php",
			type: "GET",
			data: { tablename: tablename, num: e_num, item: 'attached' },
			dataType: "json",
			success: function (data) {
				var fileArea = document.getElementById("fileArea");
				if (!fileArea) return;

				if (Array.isArray(data) && data.length > 0) {
					var list = "<h6 class='fw-bold'>첨부파일</h6><ul class='list-group'>";
					data.forEach(function (file) {
						const name = file.realname || '첨부파일';
						const link = file.link || '#';
						list += `<li class="list-group-item">
							<a href="#" onclick="popupCenter('${link}', 'filePopup', 800, 600); return false;">
								<i class="bi bi-paperclip"></i> ${name}
							</a>
						</li>`;
					});
					list += "</ul>";
					fileArea.innerHTML = list;
				} else {
					fileArea.innerHTML = '<div class="text-muted">첨부파일 없음</div>';
				}
			}
		});
	}		
			
			if(isNull(eworks_item))
				eworks_item = '일반';
			
				console.log('=== 폼 필드에 값 설정 시작 ===');
				
				// myheader.php의 hidden 필드 (전송용)
				$('#eworks_board_form #eworks_item').val(eworks_item);
				$('#eworks_board_form #e_title').val(e_title);
				$('#eworks_board_form #contents').val(contents);
				$('#eworks_board_form #registdate').val(registdate);
				$('#eworks_board_form #author').val(author);
				$('#eworks_board_form #author_id').val(author_id);
				$('#eworks_board_form #e_line').val(e_line);
				$('#eworks_board_form #e_line_id').val(e_line_id);
				$('#eworks_board_form #r_line').val(r_line);
				$('#eworks_board_form #r_line_id').val(r_line_id);
				$('#eworks_board_form #e_confirm').val(e_confirm);
				$('#eworks_board_form #e_confirm_id').val(e_confirm_id);
				$('#eworks_board_form #done').val(done);
				
				// write_form.php 모달의 visible 필드 (화면 표시용)
				$('#eworks_viewmodal #eworks_item').val(eworks_item);
				$('#eworks_viewmodal #e_title').val(e_title);
				$('#eworks_viewmodal #contents').val(contents);
				$('#eworks_viewmodal #registdate').val(registdate);
				$('#eworks_viewmodal #author').val(author);
				$('#eworks_viewmodal #e_line').val(e_line);
				$('#eworks_viewmodal #r_line').val(r_line);
				$('#eworks_viewmodal #e_prograss').val(e_prograss);
				
				$('#numdisplay').text(e_num); // 자료번호 화면에 출력
				
				// 디버깅: 설정 후 필드 값 확인
				console.log('설정 후 모달 #author 값:', $('#eworks_viewmodal #author').val());
				console.log('설정 후 모달 #e_line 값:', $('#eworks_viewmodal #e_line').val());
				console.log('설정 후 모달 #registdate 값:', $('#eworks_viewmodal #registdate').val());
				console.log('설정 후 hidden #e_line_id 값:', $('#eworks_board_form #e_line_id').val());
				console.log('=== 폼 필드 설정 완료 ===');			  
				
				// console.log(" 조회창 버튼 readonly 속성 부여");
				// console.log("status", status);
								
				// 모달 내의 input, textarea, button 요소에 대한 속성 변경을 처리하는 함수
				function setModalElementsReadonly(readonly) {
					var modalInputs = document.querySelectorAll('#eworks_viewmodal input');					
					var modalTextareas = document.querySelectorAll('#eworks_viewmodal textarea');
					var modalButtons = document.querySelectorAll('#eworks_viewmodal button');

					// 예외로 처리할 버튼 ID 목록
					var excludeButtonIds = ['closeModaldetailBtn', 'closesecondModalBtn', 'eworks_saveBtn', 'eworks_approvalBtn', 'eworks_delBtn', 'eworks_sendBtn', 'eworks_recallBtn', 'eworks_rejectBtn', 'eworks_waitBtn'];

					// input과 textarea 요소 처리
					[...modalInputs, ...modalTextareas].forEach(function(element) {
						element.readOnly = readonly;
					});

					// button 요소 처리
					modalButtons.forEach(function(button) {
						if (!excludeButtonIds.includes(button.id)) {
							button.disabled = readonly;
						}
					});
				}


			if(id==='') 		 
				 {	 	
					// 신규 작성 시 현재 사용자 정보 설정 (모달과 hidden 필드 모두)
					var currentDateTime = getCurrentDateTime();
					var userId = $('#user_id').val();
					var userName = $('#user_name').val();
					
					$('#eworks_viewmodal #registdate, #eworks_board_form #registdate').val(currentDateTime); 	
					$('#eworks_viewmodal #e_title, #eworks_board_form #e_title').val(''); 						
					$('#eworks_viewmodal #contents, #eworks_board_form #contents').val(''); 	
					$('#eworks_viewmodal #author_id, #eworks_board_form #author_id').val(userId); 	
					$('#eworks_viewmodal #author, #eworks_board_form #author').val(userName); 						
					$('#eworks_viewmodal #e_line, #eworks_board_form #e_line').val(''); 	
					$('#eworks_viewmodal #e_line_id, #eworks_board_form #e_line_id').val(''); 	
					$('#eworks_viewmodal #r_line, #eworks_board_form #r_line').val(''); 	
					$('#eworks_viewmodal #r_line_id, #eworks_board_form #r_line_id').val(''); 						
					$('#eworks_board_form #status').val('draft'); 	
					status = 'draft';
					
					console.log('신규 작성 - author 설정:', userName);
					console.log('신규 작성 - registdate 설정:', currentDateTime);
				   }	
			
				// 상태에 따라 함수 호출
				if(status !== null && status !== 'draft' && status !== '' ) {
					setModalElementsReadonly(true);
				} else {
					setModalElementsReadonly(false);
				}

			 console.log('status');
			 console.log(status);			
								
			// 결재권자이면 버튼을 다르게 부여한다. 승인/거절/보류   
			// 결재권자에 해당되면 버튼을 다르게 띄워준다.
			let str = $("#user_id").val();
			 
			let url = "/eworks/eworksBtn.php";				 

			if (ajaxRequest2 !== null) {
					ajaxRequest2.abort();
				}

				 // ajax 요청 생성
			ajaxRequest2 =$.ajax({
				  url:  url ,
				  type: 'POST',
				  data: $("#eworks_board_form").serialize() ,
				  success: function(response) {					  	
				  
				  $('#eworksBtn').html(response);												
					// console.log(response);		


					// show로 보여주고	
					setTimeout(function(){ 
						$("#eworks_viewmodal").modal("show");							
					 }, 1000); //중복 방지를 위해 타임아웃 설정    

					$("#eworks_viewmodal").on("shown.bs.modal", function () {
						// 모달에 대해 shown.bs.modal 가동하고
						isModalOpen = true; // 모달이 열리면 true로 설정
						// PC에서만 숨김 (모바일은 토글로 제어)
						if (window.innerWidth > 768) {
							$('.sideEworksBanner').css('display', 'none'); // sideEworksBanner 숨김
						}
						
						 
						  // 모달창에서 
						  $('#eworks_delBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });	
						  
						  $('#eworks_viewExceptBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });						  
						  $('#eworks_approvalviewExceptBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });	
						  // 모달창에서 결재상신
						  $('#eworks_sendBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });		
						  // 모달창에서 회신
						  $('#eworks_recallBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });	
						  // 모달창에서 
						  $('#eworks_approvalBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });	
						  // 모달창에서 
						  $('#eworks_rejectBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });		
						  // 모달창에서 
						  $('#eworks_waitBtn').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });		
						  // 모달창에서 버튼 이벤트 막기 처리 행추가
						  $('.btnClear').on('click', function(e) {
								e.preventDefault(); // 기본 이벤트 동작 막기
							// 클릭 이벤트 처리 로직    
						  });				

					

					// eworks 저장버튼 실행	(임시저장 의미) 결재 상신전
				$("#eworks_saveBtn").click( function() {
					
						
						 if($("#e_line").val() == '')
						 {
							  Toastify({
									text: "결재라인은 반드시 있어야 합니다.",
									duration: 3000,
									close:true,
									gravity:"top",
									position: "center",
									style: {
										background: "linear-gradient(to right, #00b09b, #96c93d)"
									},
								}).showToast();	
							 
							 return false;
							 
						 }
						
						 if($("#e_title").val() == '')
						 {
							  Toastify({
									text: "제목은 반드시 있어야 합니다.",
									duration: 3000,
									close:true,
									gravity:"top",
									position: "center",
									style: {
										background: "linear-gradient(to right, #00b09b, #96c93d)"
									},
								}).showToast();	
							 
							 return false;
							 
						 }
									
					
						// 버튼 비활성화
						var btn = $(this);
						if(btn.prop('disabled')) return false;
						btn.prop('disabled', true);
					 
					 // 저장버튼 일시적으로 끝냄
					 // $('#orderSaveBtn').off('click');		
						   
						   if( $("#e_title").val() == '')
							 {
							   alert("제목은 필수항목입니다. 확인해주세요.");	
							   btn.prop('disabled', false);
							   return;
							 }	
								
							var e_num =  $("#e_num").val();
							
							 // console.log('e_num saveBtn 변화 관찰 : ' + $("#e_num").val()	);							   
							if(Number(e_num)!= 0 )  // 레코드 번호로 수정과 삽입을 구분함.
									  $("#SelectWork").val('update');  // 저장이 수정버튼의 역할을 함/ 파일 추가업로드시도 동작.
									else
										$("#SelectWork").val('insert');
									
							// 폼데이터 전송시 사용함 Get form         
							var form = $('#eworks_board_form')[0];  	    
							// Create an FormData object          
							var orderdata = new FormData(form); 	

							// console.log(orderdata);				

						if (ajaxRequest !== null) {
								ajaxRequest.abort();
							}

							 // ajax 요청 생성
						 ajaxRequest = $.ajax({					
								url: "/eworks/process.php",
								enctype: 'multipart/form-data',    // file을 서버에 전송하려면 이렇게 해야 함 주의										
								processData: false,    
								contentType: false,      
								cache: false,           
								timeout: 30000, 
								type: "post",		
								data: orderdata,		
								dataType: 'json',
								success : function(data){									
									//성공했을때 동작해야될 코드
									
										// console.log('data ' + data ) ;
																								 
										 // 신규로 하면 수정모드로 변경해 준다.
										 $("#e_num").val( data["e_num"]);  // 번호를 기억해 준다.
										 $("#SelectWork").val('update');  // 저장이 수정버튼의 역할을 함/ 파일 추가업로드시도 동작.
									 
										// console.log('저장된 e_num ' + data["e_num"] ) ;
										
										 Toastify({
												text: "파일 저장 완료!",
												duration: 2000,
												close:true,
												gravity:"top",
												position: "center",
												style: {
													background: "linear-gradient(to right, #00b09b, #96c93d)"
												},
											}).showToast();									

											  btn.prop('disabled', false); // 버튼 다시 활성화시킴						
									   

								}  ,
								error : function( jqxhr , status , error ){
									console.log( jqxhr , status , error );
											} 			      		
							   });		

					});		
				
				// 전자결재 삭제 실행	
				$("#eworks_delBtn").click( function() {

				// 버튼 비활성화
				var btn = $(this);
				if(btn.prop('disabled')) return false;
				btn.prop('disabled', true);
				
				// 삭제할 레코드 번호를 e_num에 넣어야 합니다.
				
					// 레코드 번호를 저장한다.
					const id = $("#e_num").val();
					const status = $("#status").val();
					
					if(id !==null || id!=='')
						  e_num = Number(id);
					 
				 // console.log('e_num');
				 // console.log(e_num);
				 
				 if( status === 'noend' || status === 'end' || status === 'ing' )
				 {
						
						Toastify({
									text: "삭제불가 결재중이거나 결재완료는 삭제가 안됩니다.",
									duration: 3000,
									close:true,
									gravity:"top",
									position: "center",
									style: {
										background: "linear-gradient(to right, #00b09b, #96c93d)"
									},
								}).showToast();						
							   

							setTimeout(function() {							
									}, 4000);				   
							   
				 }
					else
						{
						// DATA 삭제버튼 클릭시
							Swal.fire({ 
								   title: '전자결제 삭제', 
								   text: " 삭제는 신중! '\n 정말 삭제 하시겠습니까?", 
								   icon: 'warning', 
								   showCancelButton: true, 
								   confirmButtonColor: '#3085d6', 
								   cancelButtonColor: '#d33', 
								   confirmButtonText: '삭제', 
								   cancelButtonText: '취소' })
								   .then((result) => { if (result.isConfirmed) { 
								   
									$("#SelectWork").val('deldata');
									
								if (ajaxRequest !== null) {
										ajaxRequest.abort();
									}

									 // ajax 요청 생성
								 ajaxRequest = $.ajax({	
												url: "/eworks/process.php",
												type: "post",		
												data: $("#eworks_board_form").serialize(),
												dataType:"json",  // json형태로 보냄
												success : function( data ){															
														// console.log('저장된 e_num ' + $("#e_num").val()) ;													
														 Toastify({
																text: "파일 삭제 완료!",
																duration: 3000,
																close:true,
																gravity:"top",
																position: "center",
																style: {
																	background: "linear-gradient(to right, #00b09b, #96c93d)"
																},
															}).showToast();									
													
													btn.prop('disabled', false); // 버튼 다시 활성화시킴
																															
													  setTimeout(function() {
																  // 모달창 닫기
																 $("#eworks_viewmodal").modal("hide");
															   }, 1000);
															   
														setTimeout(function(){ 
																refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
														 }, 1000); //중복 방지를 위해 타임아웃 설정   
																	
													},
													error : function( jqxhr , status , error ){
														console.log( jqxhr , status , error );
												} 			      		
											   });												
								   } });	

							}

					btn.prop('disabled', false);  // 버튼 활성화
					
					}); // end of Del function			
												
				
										
				// 전자메일 결제회수	(작성자만 사용함)
				$("#eworks_recallBtn").click( function() {

				// 저장하고 전송하는것까지 실행
				
				 if($("#e_line").val() == '')
				 {
					  Toastify({
							text: "결재라인은 반드시 있어야 합니다.",
							duration: 3000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
					 
					 return false;
					 
				 }
				
				 if($("#e_title").val() == '')
				 {
					  Toastify({
							text: "제목은 반드시 있어야 합니다.",
							duration: 3000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
					 
					 return false;
					 
				 }
				
					// 레코드 번호를 저장한다.
					const id = $("#e_num").val();
					if(id !==null || id!=='')
						  e_num = Number(id);
					  
					Swal.fire({ 
						   title: '결재회신', 
						   text: " 결재회신! '\n 정말 회수 하시겠어요?", 
						   icon: 'info', 
						   showCancelButton: true, 
						   confirmButtonColor: '#3085d6', 
						   cancelButtonColor: '#d33', 
						   confirmButtonText: '결재회수', 
						   cancelButtonText: '취소' })
						   .then((result) => { if (result.isConfirmed) { 
						   
						   
						   // 저장하고 전송하기	
						
							e_num =  $("#e_num").val();					
							   
							$("#SelectWork").val('recall');
						   
									
							// 폼데이터 전송시 사용함 Get form         
							var form = $('#eworks_board_form')[0];  	    
							// Create an FormData object          
							var orderdata = new FormData(form); 	

					if($("#SelectWork").val() == 'recall')
					 {
						 
						if (ajaxRequest2 !== null) {
								ajaxRequest2.abort();
							}

					 // ajax 요청 생성
							 ajaxRequest2 = $.ajax({	
										url: "/eworks/process.php",
										type: "post",		
										data: $("#eworks_board_form").serialize(),
										dataType:"json",  // json형태로 보냄	
										success : function(data){									
						   
										$("#SelectWork").val('draft');
										
									if (ajaxRequest1 !== null) {
											ajaxRequest1.abort();
										}

										 // ajax 요청 생성
									 ajaxRequest1 = $.ajax({	
										url: "/eworks/process.php",
										type: "post",		
										data: $("#eworks_board_form").serialize(),
										dataType:"json",  // json형태로 보냄
										success : function( data ){															
												// console.log('결재올린 e_num ' + $("#e_num").val()) ;													
												 Toastify({
														text: "결재회수 완료!",
														duration: 3000,
														close:true,
														gravity:"top",
														position: "center",
														style: {
															background: "linear-gradient(to right, #00b09b, #96c93d)"
														},
													}).showToast();									
											
											 // $("#eworksel").val('send'); 	
											 
											  setTimeout(function() {
														  // 모달창 닫기
												 $("#eworks_viewmodal").modal("hide");
											   }, 1000);
											   
												setTimeout(function(){ 
														refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
												 }, 1000); //중복 방지를 위해 타임아웃 설정   
															
											},
											error : function( jqxhr , status , error ){
												console.log( jqxhr , status , error );
										} 			      		
									   });												
									   
								}  ,
								error : function( jqxhr , status , error ){
									console.log( jqxhr , status , error );
											} 			      		
							   });		
							}  // end of insert 일때
							
						   

							 } });		 // end of swal function
						
					}); // end of function			
				
															
				// 전자메일 결재상신	(작성자만 사용함)
				$("#eworks_sendBtn").click( function() {

				// 저장하고 전송하는것까지 실행
				
				 if($("#e_line").val() == '')
				 {
					  Toastify({
							text: "결재라인은 반드시 있어야 합니다.",
							duration: 3000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
					 
					 return false;
					 
				 }
				
				 if($("#e_title").val() == '')
				 {
					  Toastify({
							text: "제목은 반드시 있어야 합니다.",
							duration: 3000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
					 
					 return false;
					 
				 }
				
					// 레코드 번호를 저장한다.
					const id = $("#e_num").val();
					if(id !==null || id!=='')
						  e_num = Number(id);
					  
					Swal.fire({ 
						   title: '저장 후 결재상신', 
						   text: " 결재상신! '\n 정말 결재를 올리시겠어요?", 
						   icon: 'info', 
						   showCancelButton: true, 
						   confirmButtonColor: '#3085d6', 
						   cancelButtonColor: '#d33', 
						   confirmButtonText: '결재상신', 
						   cancelButtonText: '취소' })
						   .then((result) => { if (result.isConfirmed) { 
						   
						   
						   // 저장하고 전송하기	
						
							e_num =  $("#e_num").val();					
							   
							if(Number(e_num)!= 0 )  // 레코드 번호로 수정과 삽입을 구분함.
									  $("#SelectWork").val('update');  // 저장이 수정버튼의 역할을 함/ 파일 추가업로드시도 동작.
									else
										$("#SelectWork").val('insert');
						   
									
							// 폼데이터 전송시 사용함 Get form         
							var form = $('#eworks_board_form')[0];  	    
							// Create an FormData object          
							var orderdata = new FormData(form); 	

					if($("#SelectWork").val() == 'insert')
					 {
						 
						if (ajaxRequest2 !== null) {
								ajaxRequest2.abort();
							}

					 // ajax 요청 생성
							 ajaxRequest2 = $.ajax({	
										url: "/eworks/process.php",
										type: "post",		
										data: $("#eworks_board_form").serialize(),
										dataType:"json",  // json형태로 보냄	
										success : function(data){									
						   
										$("#SelectWork").val('send');
										
									if (ajaxRequest1 !== null) {
											ajaxRequest1.abort();
										}

										 // ajax 요청 생성
									 ajaxRequest1 = $.ajax({	
										url: "/eworks/process.php",
										type: "post",		
										data: $("#eworks_board_form").serialize(),
										dataType:"json",  // json형태로 보냄
										success : function( data ){															
												// console.log('결재올린 e_num ' + $("#e_num").val()) ;													
												 Toastify({
														text: "결재상신 완료!",
														duration: 3000,
														close:true,
														gravity:"top",
														position: "center",
														style: {
															background: "linear-gradient(to right, #00b09b, #96c93d)"
														},
													}).showToast();									
											
											 // $("#eworksel").val('send'); 	
											 
											  setTimeout(function() {
														  // 모달창 닫기
												 $("#eworks_viewmodal").modal("hide");
											   }, 1000);
											   
												setTimeout(function(){ 
														refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
												 }, 1000); //중복 방지를 위해 타임아웃 설정   
															
											},
											error : function( jqxhr , status , error ){
												console.log( jqxhr , status , error );
										} 			      		
									   });												
									   
								}  ,
								error : function( jqxhr , status , error ){
									console.log( jqxhr , status , error );
											} 			      		
							   });		
							}  // end of insert 일때
							
						else {  // insert가 아닐때

							// ajax 요청 생성
								$("#SelectWork").val('send');
										
									if (ajaxRequest1 !== null) {
											ajaxRequest1.abort();
										}

										 // ajax 요청 생성
									 ajaxRequest1 = $.ajax({	
										url: "/eworks/process.php",
										type: "post",		
										data: $("#eworks_board_form").serialize(),
										dataType:"json",  // json형태로 보냄
										success : function( data ){															
											// 	console.log('결재올린 e_num ' + $("#e_num").val()) ;													
												 Toastify({
														text: "결재상신 완료!",
														duration: 3000,
														close:true,
														gravity:"top",
														position: "center",
														style: {
															background: "linear-gradient(to right, #00b09b, #96c93d)"
														},
													}).showToast();									
											
											 // $("#eworksel").val('send'); 	
											 
											  setTimeout(function() {
														  // 모달창 닫기
												 $("#eworks_viewmodal").modal("hide");
											   }, 1000);
																		   
												setTimeout(function(){ 
														refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
												 }, 1000); //중복 방지를 위해 타임아웃 설정   
															
											},
											error : function( jqxhr , status , error ){
												console.log( jqxhr , status , error );
										} 			      		
									   });												
							
							 }										
						   
						   

							 } });		 // end of swal function
						
					}); // end of function			
				
					// 전자결재 승인	
				$("#eworks_approvalBtn").off('click').on('click', function () {
					const $btn = $(this);
					
					// 중복 클릭 방지: 버튼 비활성화
					if ($btn.prop('disabled')) return; // 이미 비활성화면 실행 안함
					$btn.prop('disabled', true); // 클릭되면 비활성화

					const id = $("#e_num").val();
					if (id !== null && id !== '') e_num = Number(id);

					// 승인 전 모든 hidden 필드 값 확인
					console.log('=== 승인 버튼 클릭 - 현재 폼 상태 ===');
					console.log('e_num:', $('#e_num').val());
					console.log('eworks_board_form #e_confirm:', $('#eworks_board_form #e_confirm').val());
					console.log('eworks_board_form #e_confirm_id:', $('#eworks_board_form #e_confirm_id').val());
					console.log('eworks_board_form #e_line:', $('#eworks_board_form #e_line').val());
					console.log('eworks_board_form #e_line_id:', $('#eworks_board_form #e_line_id').val());
					console.log('eworks_board_form #author:', $('#eworks_board_form #author').val());
					console.log('eworks_board_form #author_id:', $('#eworks_board_form #author_id').val());

					Swal.fire({
						title: '전자결재 처리',
						text: "결재승인!\n정말 결재를 진행하시겠어요?",
						icon: 'info',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: '결재승인',
						cancelButtonText: '취소'
					}).then((result) => {
						if (result.isConfirmed) {
							// 로딩 인디케이터 표시
							showLoadingIndicator();
							
							$("#SelectWork").val('approval');

							// 디버깅: 직렬화된 데이터 확인
							var formData = $("#eworks_board_form").serialize();
							console.log('=== 전송할 직렬화 데이터 ===');
							console.log(formData);
							
							// e_confirm_id가 포함되어 있는지 확인
							if (formData.indexOf('e_confirm_id') === -1) {
								console.error('경고: e_confirm_id가 전송 데이터에 포함되지 않았습니다!');
							}

							if (ajaxRequest !== null) {
								ajaxRequest.abort();
							}

							ajaxRequest = $.ajax({
								url: "/eworks/process.php",
								type: "post",
								data: $("#eworks_board_form").serialize(),
								dataType: "json",
								success: function (data) {
									// 로딩 인디케이터 숨김
									hideLoadingIndicator();
									
									Toastify({
										text: "결재처리 완료!",
										duration: 2000,
										close: true,
										gravity: "top",
										position: "center",
										style: {
											background: "linear-gradient(to right, #00b09b, #96c93d)"
										},
									}).showToast();

									setTimeout(function () {
										$("#eworks_viewmodal").modal("hide");
									}, 1000);

									setTimeout(function () {
										refresheworks();
										$btn.prop('disabled', false); // 재활성화
									}, 1000);
								},
								error: function (jqxhr, status, error) {
									// 로딩 인디케이터 숨김
									hideLoadingIndicator();
									
									console.log(jqxhr, status, error);
									$btn.prop('disabled', false); // 오류 발생 시 재활성화
								}
							});
						} else {
							$btn.prop('disabled', false); // 취소 시 재활성화
						}
					});
				});

					
				 // 전자결재 반려	
				$("#eworks_rejectBtn").click( function() {
					
				// 레코드 번호를 저장한다.
				const id = $("#e_num").val();
				if(id !==null || id!=='')
					  e_num = Number(id);
			 
				 // console.log('e_num');
				 // console.log(e_num);

				// DATA 삭제버튼 클릭시
					Swal.fire({ 
						   title: '전자결재 반려처리', 
						   text: " 결재반려! '\n 정말 결재를 반려하시겠어요?", 
						   icon: 'warning', 
						   showCancelButton: true, 
						   confirmButtonColor: '#3085d6', 
						   cancelButtonColor: '#d33', 
						   confirmButtonText: '결재반려', 
						   cancelButtonText: '취소' })
						   .then((result) => { if (result.isConfirmed) { 
						   
							$("#SelectWork").val('reject');
							
							
							
						if (ajaxRequest !== null) {
								ajaxRequest.abort();
							}

							 // ajax 요청 생성
						 ajaxRequest = $.ajax({	
										url: "/eworks/process.php",
										type: "post",		
										data: $("#eworks_board_form").serialize(),
										dataType:"json",  // json형태로 보냄
										success : function( data ){															
											// 	console.log('결재올린 e_num ' + $("#e_num").val()) ;													
												 Toastify({
														text: "결재반려 완료!",
														duration: 3000,
														close:true,
														gravity:"top",
														position: "center",
														style: {
																background: "linear-gradient(to right, #00b09b, #96c93d)"
															},
													}).showToast();									
											
											 // $("#eworksel").val('send'); 	
											 
											  setTimeout(function() {
														  // 모달창 닫기
												 $("#eworks_viewmodal").modal("hide");
											   }, 1000);
											   
												setTimeout(function(){ 
														refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
												 }, 1000); //중복 방지를 위해 타임아웃 설정   
															
											},
											error : function( jqxhr , status , error ){
												console.log( jqxhr , status , error );
										} 			      		
									   });												
						   } });	

					}); 		
				
						
				 // 전자메일  보류	
				$("#eworks_waitBtn").click( function() {

				
				// 삭제할 레코드 번호를 num에 넣어야 합니다.
				
				// 레코드 번호를 저장한다.
				const id = $("#e_num").val();
				if(id !==null || id!=='')
					  e_num = Number(id);

			// DATA 삭제버튼 클릭시
				Swal.fire({ 
					   title: '전자결재 보류처리', 
					   text: " 결재보류! '\n 정말 결재를 보류하시겠어요?", 
					   icon: 'warning', 
					   showCancelButton: true, 
					   confirmButtonColor: '#3085d6', 
					   cancelButtonColor: '#d33', 
					   confirmButtonText: '결재보류', 
					   cancelButtonText: '취소' })
					   .then((result) => { if (result.isConfirmed) { 
					   
						$("#SelectWork").val('wait');
						
					if (ajaxRequest !== null) {
							ajaxRequest.abort();
						}

						 // ajax 요청 생성
					 ajaxRequest = $.ajax({	
									url: "/eworks/process.php",
									type: "post",		
									data: $("#eworks_board_form").serialize(),
									dataType:"json",  // json형태로 보냄
									success : function( data ){															
			//							console.log('결재올린 e_num ' + $("#e_num").val()) ;													
										 Toastify({
												text: "결재보류 완료!",
												duration: 3000,
												close:true,
												gravity:"top",
												position: "center",
												style: {
													background: "linear-gradient(to right, #00b09b, #96c93d)"
												},
											}).showToast();									
									
									 // $("#eworksel").val('send'); 	
										 
										  setTimeout(function() {
													  // 모달창 닫기
											 $("#eworks_viewmodal").modal("hide");
										   }, 1000);
										   
											setTimeout(function(){ 
													refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
											 }, 1000); //중복 방지를 위해 타임아웃 설정   
														
										},
										error : function( jqxhr , status , error ){
											console.log( jqxhr , status , error );
									} 			      		
								   });												
					   } });	

				}); 			
			
			
	

			
			});  // end of shown modal


           // ajax 비동기 방식은 결과값을 주는 시간이 일정하지 않아서 그 시간을 그 시간을 보장하지 않고 코딩을 하면....
		   // 절대로 맞는 결과값을 받을 수 없다. 이번 프로젝트를 통해서 많은 시간을 허비하면서 실전으로 배웠다.
		   // ajax를 쓰는 경우는 반드시 리턴값을 받고 또 그 리턴값으로 수정하고 또 리턴을 받을때 중복으로 처리하지 않으면
		   // 엄청난 에러에 직면한다.
		   // ajax는 모달을 사용하거나 비동기 방식으로 UI를 꾸밀때 필수요소인데, 정말 많은 시간을 허비하면서 배웠다.
		   // 잊지말자 이 것이 바로 ajax의 면모인 것을....

				}  ,
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
							} 			      		
			   });	// 2번 ajax문장
		
			}  ,
	error : function( jqxhr , status , error ){
		console.log( jqxhr , status , error );
				} 			      		
   });	 // 첫번째 ajax문장		
		
}  // end of viewEworks_detail

// 의견 삭제	
eworks_delete_ripple = function (ripple_num)
{
	var btn = $(this); // 현재 버튼 참조 저장
	if (btn.prop('disabled')) return false;
			
	// 레코드 번호를 저장한다.
	$("#ripple_num").val(ripple_num);

	// DATA 삭제버튼 클릭시
		Swal.fire({ 
			   title: '의견 삭제', 
			   text: " 삭제! '\n 정말 삭제 하시겠습니까?", 
			   icon: 'warning', 
			   showCancelButton: true, 
			   confirmButtonColor: '#3085d6', 
			   cancelButtonColor: '#d33', 
			   confirmButtonText: '삭제', 
			   cancelButtonText: '취소' })
			   .then((result) => { if (result.isConfirmed) {

				// AJAX 요청 성공 또는 실패 후에 버튼을 다시 활성화
				ajaxRequest.always(function() {
					btn.prop('disabled', false);
				});			   
			   
				$("#SelectWork").val('delete_ripple');
					
				if (ajaxRequest !== null) {
						ajaxRequest.abort();
					}

					 // ajax 요청 생성
				 ajaxRequest = $.ajax({	
								url: "/eworks/process.php",
								type: "post",		
								data: $("#eworks_board_form").serialize(),
								dataType:"json",  // json형태로 보냄
								success : function( data ){															
										console.log('저장된 e_num ' + $("#e_num").val()) ;													
										 Toastify({
												text: "의견 삭제 완료!",
												duration: 3000,
												close:true,
												gravity:"top",
												position: "center",
												style: {
													background: "linear-gradient(to right, #00b09b, #96c93d)"
												},
											}).showToast();									
																					
									// console.log(data);
									
									 $("#ripple-" + ripple_num).remove(); // 댓글 요소 제거
																										   
									// refresheworks();
													
									},
									error : function( jqxhr , status , error ){
										console.log( jqxhr , status , error );
										// 사용자가 취소를 클릭한 경우, 버튼을 다시 활성화
										btn.prop('disabled', false);									
								} 			      		
							   });												
			   } 	})
		
}	

function createApprovalTable(e_line, e_confirm) {
    // e_line이 null이나 빈 문자열이 아닐 때만 실행
    if (e_line && e_line.trim() !== '') {
		// '!' 기준으로 문자열 분리
		var approvalNames = e_line.split('!');
		var approvalDates = e_confirm.split('!');

		// 테이블 요소 생성
		var table = $('<table>').addClass('table table-bordered');
		var thead = $('<thead>');
		var tbody = $('<tbody>');
		var nameRow = $('<tr>');
		var dateRow = $('<tr>');

		// 헤더 셀 추가
		thead.append($('<tr>').append($('<th>').attr('colspan', approvalNames.length).addClass('text-center fs-6').text('결재')));

		// 각 결재자 이름과 날짜를 행에 추가
		$.each(approvalNames, function(index, name) {
			nameRow.append($('<td>').addClass('text-center fs-6').css('height', '60px').text(name));
			dateRow.append($('<td>').addClass('text-center').text(approvalDates[index]));
		});

		// 테이블 구성
		tbody.append(nameRow).append(dateRow);
		table.append(thead).append(tbody);

		// 결과를 id="approvalTable"인 요소에 추가
		$('#approvalTable').empty().append(table);
	}
}
	

// 댓글 추가	
eworks_insert_ripple = function (e_num) {
    var btn = $(this);
    if (btn.prop('disabled')) return false;

    $("#ripple_num").val(ripple_num);

    ajaxRequest.always(function() {
        btn.prop('disabled', false);
    });

    $("#SelectWork").val('insert_ripple');

    if (ajaxRequest !== null) {
        ajaxRequest.abort();
    }

    ajaxRequest = $.ajax({    
        url: "/eworks/process.php",
        type: "post",        
        data: $("#eworks_board_form").serialize(),
        dataType: "json",
        success: function(data) {
            console.log('저장된 e_num ' + $("#e_num").val());
            Toastify({
                text: "의견 추가!",
                duration: 2000,
                close: true,
                gravity: "top",
                position: "center",
                style: {
                    background: "linear-gradient(to right, #00b09b, #96c93d)"
                },
            }).showToast();

			// Check if the user has delete permissions (set this variable appropriately in your PHP code)
			var canDelete = true;

			// Construct the new comment HTML
			var newCommentHtml = '<div class="card ripple-item" id="ripple-' + data.num + '" style="width:80%">' +
								 '<div class="row justify-content-center">' +
								 '<div class="card-body">' +
								 '<span class="mt-1 mb-2"> ▶&nbsp;&nbsp;' + data.content + ' ✔&nbsp;&nbsp;작성자: ' + data.author + ' | ' + data.regist_day;

			// Add delete button if the user can delete
			if (canDelete) {
				newCommentHtml += ' <a href="#" class="text-danger" onclick="eworks_delete_ripple(\'' + data.num + '\')"> <i class="bi bi-trash"></i> </a>';
			}

			// Close the span and div tags
			newCommentHtml += '</span></div></div></div>';

			// Append the new comment to the comments container
			$('#comments-container').append(newCommentHtml);


            // Optional: Clear the input field if needed
            // $('#your-input-field-id').val('');
        },
        error: function(jqxhr, status, error) {
            console.log(jqxhr, status, error);
            btn.prop('disabled', false);                                    
        }                   
    });
}
	
// 전자결재 restore	
restore = function (e_num, eworksPage)
{
	var btn = $(this); // 현재 버튼 참조 저장
	if (btn.prop('disabled')) return false;
			
	// 레코드 번호를 저장한다.
	$("#e_num").val(e_num);

	// DATA 삭제버튼 클릭시
		Swal.fire({ 
			   title: '전자결제 삭제 복구', 
			   text: " 복구! '\n 정말 복구 하시겠습니까?", 
			   icon: 'warning', 
			   showCancelButton: true, 
			   confirmButtonColor: '#3085d6', 
			   cancelButtonColor: '#d33', 
			   confirmButtonText: '복구', 
			   cancelButtonText: '취소' })
			   .then((result) => { if (result.isConfirmed) {

				// AJAX 요청 성공 또는 실패 후에 버튼을 다시 활성화
				ajaxRequest.always(function() {
					btn.prop('disabled', false);
				});			   
			   
				$("#SelectWork").val('restore');
					
				if (ajaxRequest !== null) {
						ajaxRequest.abort();
					}

					 // ajax 요청 생성
				 ajaxRequest = $.ajax({	
								url: "/eworks/process.php",
								type: "post",		
								data: $("#eworks_board_form").serialize(),
								dataType:"json",  // json형태로 보냄
								success : function( data ){															
										console.log('저장된 e_num ' + $("#e_num").val()) ;													
										 Toastify({
												text: "파일 복구 완료!",
												duration: 3000,
												close:true,
												gravity:"top",
												position: "center",
												style: {
													background: "linear-gradient(to right, #00b09b, #96c93d)"
												},
											}).showToast();									
																					
										setTimeout(function(){ 
												refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
										 }, 1000); //중복 방지를 위해 타임아웃 설정   
													
									},
									error : function( jqxhr , status , error ){
										console.log( jqxhr , status , error );
										// 사용자가 취소를 클릭한 경우, 버튼을 다시 활성화
										btn.prop('disabled', false);									
								} 			      		
							   });												
			   } 	})
		
}		
				
// 전자결재 처리 후 화면에서 제외	
approvalviewExcept = function (e_num, eworksPage)
{
	var btn = $(this); // 현재 버튼 참조 저장
	if (btn.prop('disabled')) return false;
	
    var selectedEworks = [];
	// 레코드 번호를 저장한다.	
	selectedEworks.push(e_num);  // date-id의 값을 가져와서 고유의 번호를 기억한다.

		Swal.fire({ 
			   title: '전자결재 처리', 
			   text: " 결재승인! '\n 정말 결재를 진행하시겠어요?", 
			   icon: 'info', 
			   showCancelButton: true, 
			   confirmButtonColor: '#3085d6', 
			   cancelButtonColor: '#d33', 
			   confirmButtonText: '결재승인', 
			   cancelButtonText: '취소' })
			   .then((result) => { if (result.isConfirmed) { 						   					
					
			if (ajaxRequest !== null) {
					ajaxRequest.abort();
				}
     
			 ajaxRequest = $.ajax({		
                url: "/eworks/approvalSelected.php",
                type: "post",		
                data: { selectedIds: selectedEworks },
                dataType: "json",
                success: function(data) {
					console.log('전달된 data');
					console.log(data);
					
                    Toastify({
                        text: "선택 결재 완료!",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        },
                    }).showToast();
					setTimeout(function(){ 
							refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
					 }, 1000); //중복 방지를 위해 타임아웃 설정    					

                },
                error: function(jqxhr, status, error) {
                    console.error(jqxhr, status, error);								
                }
            });
        }
    });
}		
					
// 전자결재 제외 실행	
viewExcept = function (e_num, eworksPage)
{
	var btn = $(this); // 현재 버튼 참조 저장
	if (btn.prop('disabled')) return false;
			
	// 레코드 번호를 저장한다.
	$("#e_num").val(e_num);

	// DATA 삭제버튼 클릭시
		Swal.fire({ 
			   title: '전자결제 삭제', 
			   text: " 삭제! '\n 정말 삭제 하시겠습니까?", 
			   icon: 'warning', 
			   showCancelButton: true, 
			   confirmButtonColor: '#3085d6', 
			   cancelButtonColor: '#d33', 
			   confirmButtonText: '삭제', 
			   cancelButtonText: '취소' })
			   .then((result) => { if (result.isConfirmed) {

				// AJAX 요청 성공 또는 실패 후에 버튼을 다시 활성화
				ajaxRequest.always(function() {
					btn.prop('disabled', false);
				});			   
			   
				$("#SelectWork").val('except');
					
				if (ajaxRequest !== null) {
						ajaxRequest.abort();
					}

					 // ajax 요청 생성
				 ajaxRequest = $.ajax({	
								url: "/eworks/process.php",
								type: "post",		
								data: $("#eworks_board_form").serialize(),
								dataType:"json",  // json형태로 보냄
								success : function( data ){															
									console.log('저장된 e_num ' + $("#e_num").val()) ;													
									 Toastify({
											text: "파일 삭제 완료!",
											duration: 3000,
											close:true,
											gravity:"top",
											position: "center",
											style: {
												background: "linear-gradient(to right, #00b09b, #96c93d)"
											},
										}).showToast();									
																					
									setTimeout(function(){ 
											refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
									 }, 1000); //중복 방지를 위해 타임아웃 설정   
													
									},
									error : function( jqxhr , status , error ){
										console.log( jqxhr , status , error );
										// 사용자가 취소를 클릭한 경우, 버튼을 다시 활성화
										btn.prop('disabled', false);									
								} 			      		
							   });												
			   } 	})
		
}		
				
// 선택된 항목들을 결재하는 함수
approvalSelectedEworks = function () 
{
    var selectedEworks = [];
    $('.checkItem:checked').each(function() {
        selectedEworks.push($(this).data('id'));  // date-id의 값을 가져와서 고유의 번호를 기억한다.
    });

    if (selectedEworks.length === 0) {
        alert('결재할 항목을 선택해주세요.');
        return;
    }

    console.log(selectedEworks);
    Swal.fire({ 
        title: '일괄결재', 
        text: "항목을 결재하시겠습니까?", 
        icon: 'info', 
        showCancelButton: true, 
        confirmButtonColor: '#3085d6', 
        cancelButtonColor: '#d33', 
        confirmButtonText: '승인', 
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {

	   
			if (ajaxRequest !== null) {
					ajaxRequest.abort();
				}						
            
			 ajaxRequest = $.ajax({		
                url: "/eworks/approvalSelected.php",
                type: "post",		
                data: { selectedIds: selectedEworks },
                dataType: "json",
                success: function(data) {
					console.log(data);
					
                    Toastify({
                        text: "선택 결재 완료!",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        },
                    }).showToast();
					setTimeout(function(){ 
							refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
					 }, 1000); //중복 방지를 위해 타임아웃 설정    					

                },
                error: function(jqxhr, status, error) {
                    console.error(jqxhr, status, error);								
                }
            });
        }
    });
}

// 선택된 항목들을 삭제하는 함수
deleteSelectedEworks = function () 
{
    var selectedEworks = [];
    $('.checkItem:checked').each(function() {
        selectedEworks.push($(this).data('id'));  // date-id의 값을 가져와서 고유의 번호를 기억한다.
    });

    if (selectedEworks.length === 0) {
        alert('삭제할 항목을 선택해주세요.');
        return;
    }


    console.log(selectedEworks);
    Swal.fire({ 
        title: '선택 삭제', 
        text: "선택된 항목을 삭제하시겠습니까?", 
        icon: 'warning', 
        showCancelButton: true, 
        confirmButtonColor: '#3085d6', 
        cancelButtonColor: '#d33', 
        confirmButtonText: '삭제', 
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {

	   
			if (ajaxRequest !== null) {
					ajaxRequest.abort();
				}		
							
            // 예시: selectedEworks 배열을 서버에 전송하여 삭제 처리
			 ajaxRequest = $.ajax({		
                url: "/eworks/deleteSelected.php",
                type: "post",		
                data: { selectedIds: selectedEworks },
                dataType: "json",
                success: function(data) {
					console.log(data);
					
                    Toastify({
                        text: "선택된 항목 삭제 완료!",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        },
                    }).showToast();
					setTimeout(function(){ 
							refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
					 }, 1000); //중복 방지를 위해 타임아웃 설정    					

                },
                error: function(jqxhr, status, error) {
                    console.error(jqxhr, status, error);								
                }
            });
        }
    });
}



	// 화면에 결재관련 숫자 표현
	// 페이지 로드 시 전자결재 카운트 표시
	console.log('=== 페이지 로드 완료, 전자결재 초기화 시작 ===');
	console.log('eworks_board_form 존재 여부:', $('#eworks_board_form').length);

	if ($('#eworks_board_form').length > 0) {
		console.log('전자결재 폼 발견, 데이터 로드 시작');
		// 약간의 지연을 주어 DOM이 완전히 로드되도록 함
		setTimeout(function() {
			load_eworkslist();
		}, 500);
	} else {
		console.warn('전자결재 폼(eworks_board_form)이 존재하지 않습니다');
	}

});
		
// 신규작성이며 삭제버튼 막기			
function eworks_movetoPage(eworksPage) 
{ 	  
			$("#eworksPage").val(eworksPage); 

			let url = '/eworks/list.php';

			if (ajaxRequest !== null) {
				ajaxRequest.abort();
			}

			 // ajax 요청 생성
			 ajaxRequest =$.ajax({
				  url:  url ,
				  type: 'POST',
				  data: $("#eworks_board_form").serialize() ,
				  success: function(response) {				  	
						$('#eworks_list').html(response);							
						
				    // css active 속성주기 (선택된 것 표현)
			        const val = Number($("#choice").val());
                  	$(".nav-link").eq(val - 1).addClass("active");
				//  console.log('mevetoPage :');
				// console.log(response);
				
					hideEworksLoadingIndicator(); // 로딩 완료
			  },
			  error: function(jqxhr, status, error) {
					console.log('eworks_movetoPage 오류:', jqxhr, status, error);
					hideEworksLoadingIndicator(); // 오류 시에도 로딩 완료
			  }
			});	  
}	 // end of movetoPage

// 전자결재를 클릭하면 최초 실행되는 것	
// 전자결재를 클릭하면 최초 실행되는 것	
function eworksList()
{	

	 var URL =	"/eworks/load_list.php";	
		   
		if (ajaxRequest !== null) {
			ajaxRequest.abort();
		}

	 // ajax 요청 생성
	 ajaxRequest = $.ajax({
		url: URL,
		type: "post",		
		data: $("#eworks_board_form").serialize(),
		dataType:"json",	
		success : function(data){	

			console.log('data result: ');
			console.log(data);
			// 거래처 납기일 등 기록하기		
				
			let num_arr= data["num_arr"];
			let e_title_arr= data["e_title_arr"];
			let contents_arr= data["contents_arr"];
			let registdate_arr= data["registdate_arr"];
			let status_arr= data["status_arr"];
			let e_line_arr= data["e_line_arr"];
			let e_line_id_arr= data["e_line_id_arr"];
			let e_confirm_arr= data["e_confirm_arr"];
			let r_line_arr= data["r_line_arr"];
			let r_line_id_arr= data["r_line_id_arr"];
			let recordtime_arr= data["recordtime_arr"];
			let author_arr= data["author_arr"];
			let author_id_arr= data["author_id_arr"];

			// input으로 레코드 번호 저장		
			
			// 화면 초기화
			$('#registdate').val(''); 	
			$('#e_title').val(''); 				
			$('#contents').val(''); 	
			$('#author').val(''); 	
			$('#e_line').val(''); 	
			$('#r_line').val(''); 		
			$('#author_id').val(''); 	
			$('#e_line_id').val(''); 	
			$('#r_line_id').val(''); 			
			
			$('#registdate').val(registdate_arr[e_num]); 				
			$('#e_title').val(e_title_arr[e_num]); 	
			$('#contents').val(contents_arr[e_num]); 	
			$('#author_id').val(author_id_arr[e_num]); 	
			$('#e_line_id').val(e_line_id_arr[e_num]); 	
			$('#r_line_id').val(r_line_id_arr[e_num]); 	
			$('#author').val(author_arr[e_num]); 	
			$('#e_line').val(e_line_arr[e_num]); 	
			$('#r_line').val(r_line_arr[e_num]); 	
				 
			 // console.log('선택된 배열속 진짜 e_num');
			 // console.log($('#e_num').val());
			 
			 hideEworksLoadingIndicator(); // 로딩 완료
			},
		error : function( jqxhr , status , error ){
			console.log( jqxhr , status , error );
			hideEworksLoadingIndicator(); // 로딩 완료 (오류 시에도)
					} 			      		
	   });	

	// option은 데이터 복사를 위해서 사용한다.			
	// 레코드 번호를 저장한다.

	// 뒷배경 누를때 안닫히게
	$('#eworks_form').modal( {
	backdrop: 'static', keyboard : false });	

		  // 모달창에서 버튼 이벤트 막기 처리 행추가
	  $('#E_searchBtn').on('click', function(e) {
			e.preventDefault(); // 기본 이벤트 동작 막기
		// 클릭 이벤트 처리 로직    
	  });			
	  					  
///////////////////// input 필드 값 옆에 X 마크 띄우기 
///////////////////// input 필드 값 옆에 X 마크 띄우기 

		var btnClear = document.querySelectorAll('.btnClear');
		btnClear.forEach(function(btn){
		btn.addEventListener('click', function(e){
		btn.parentNode.querySelector('input').value = "";
		e.preventDefault(); // 기본 이벤트 동작 막기
		})
		})		  
	  
				
	setTimeout(function(){ 
		$("#eworks_form").modal("show");
	 }, 800); //중복 방지를 위해 타임아웃 설정    

	$("#eworks_form").on("shown.bs.modal", function () {
		isModalOpen = true; // 모달이 열리면 true로 설정
		// PC에서만 숨김 (모바일은 토글로 제어)
		if (window.innerWidth > 768) {
			$('.sideEworksBanner').css('display', 'none'); // sideEworksBanner 숨김
		}
			  
	  // 뒷배경 누를때 안닫히게
		$('#eworks_form').modal( {
			backdrop: 'static', keyboard : false });		

			  // 모달창에서 버튼 이벤트 막기 처리 행추가
		  $('#E_searchBtn').on('click', function(e) {
				e.preventDefault(); // 기본 이벤트 동작 막기
			// 클릭 이벤트 처리 로직    
		  });	  
		  
		  // 모달창에서 버튼 이벤트 막기 처리 행추가
		  $('.btnClear').on('click', function(e) {
				e.preventDefault(); // 기본 이벤트 동작 막기
			// 클릭 이벤트 처리 로직    
		  });				  
			  
		 enterkey = function() {
			 
			 let url = '/eworks/list.php';
			 
			 showEworksLoadingIndicator(); // 로딩 시작
			 
				if (ajaxRequest1 !== null) {
					ajaxRequest1.abort();
				}

					 // ajax 요청 생성
				 ajaxRequest1 =$.ajax({
					  url:  url ,
					  type: 'POST',
					  data: $("#eworks_board_form").serialize() ,
					  success: function(response) {					  
								$('#eworks_list').html(response);
								hideEworksLoadingIndicator(); // 로딩 완료
					},
				error : function( jqxhr , status , error ){
					console.log( jqxhr , status , error );
					hideEworksLoadingIndicator(); // 오류 시에도 로딩 완료
							} 
				 });					
			}	

			// search all
			$("#E_searchAllBtn").click( function() {
					
				$('#search').val(''); 	
					enterkey();
					
				});		
	
	});	  // end of shown modal
	
	$("#eworks_form").on("hidden.bs.modal", function () {
		// 여기에 모달이 닫힐 때 실행할 함수를 작성하세요
		isModalOpen = false; // 모달이 닫히면 false로 설정
		load_eworkslist();
	});	
	
}  // end of eworksList
	
function update_eworks_nav() 
{
	console.log('=== update_eworks_nav 호출, activeTab:', activeTab);
	
	$.ajax({
		url: "/eworks/eworks_nav.php?selnum=" + activeTab, 
		type: "GET",
		success: function(response) {
			// 서버에서 받은 HTML을 웹 페이지의 특정 부분에 삽입
			$("#eworksNavContainer").html(response);
			console.log('eworks_nav 업데이트 완료');
		},
		error: function(xhr, status, error) {
			console.error("eworks_nav 오류:", error);
		}
	});
}

function initializeContents() 
{
	var defaultContent = '<div id="htmlContainer">' +
						 '    <textarea id="contents" class="form-control" name="contents" rows="10">' +                    
						 '    </textarea>' +
						 '</div>';

    var divElement = document.getElementById("htmlContainer");
    if (divElement) {
        divElement.innerHTML = defaultContent;
    }
}
	
function setLine()
{
	// 결재라인 지정
	val = $("#e_line_id").val();
	popupCenter("/eworks/setline.php?e_line_id=" + val , '결재라인 지정', 600, 500);	  
}
  
function setRef()
{
	// 참조라인 지정
	val = $("#r_line_id").val();	
	popupCenter("/eworks/setRef.php?r_line_id=" + val , '참조인 지정', 600, 900);			
}

// 전자결재 각 항목별 해당 숫자 읽기
function load_eworkslist()
{	
	if ($('#eworks_board_form').length) {		
		let url = '/eworks/load_eworks.php';
		if (ajaxRequest4 !== null) {
				ajaxRequest4.abort();
		}

		 // ajax 요청 생성
		 ajaxRequest4 = $.ajax({
				  url:  url ,
				  type: 'POST',
				  data: $("#eworks_board_form").serialize() ,
				  dataType:"json",	  
				  success : function(data){

				             console.log('=== 전자결재 데이터 로드 성공 ===');
				             console.log('받은 데이터:', data);
							 console.log('데이터 타입:', typeof data);

							 // 각 badge 요소 존재 여부 확인
							 console.log('badge1 존재:', $('#badge1').length);
							 console.log('badge2 존재:', $('#badge2').length);
							 console.log('badge3 존재:', $('#badge3').length);
							 console.log('badge4 존재:', $('#badge4').length);
							 console.log('badge5 존재:', $('#badge5').length);

							// badge1 = 작성(draft) = val0
							if ($('#badge1').length > 0) {
								var val = (data["val0"] && data["val0"] !== 0) ? data["val0"] : '';
								console.log('badge1에 설정할 값:', val);
								$('#badge1').text(val);
							}
							// badge2 = 상신(send) = val1
							if ($('#badge2').length > 0) {
								var val = (data["val1"] && data["val1"] !== 0) ? data["val1"] : '';
								console.log('badge2에 설정할 값:', val);
								$('#badge2').text(val);
							}
							// badge3 = 미결(noend) = val2
							if ($('#badge3').length > 0) {
								var val = (data["val2"] && data["val2"] !== 0) ? data["val2"] : '';
								console.log('badge3에 설정할 값:', val);
								$('#badge3').text(val);
							}
							// badge4 = 진행(ing) = val3
							if ($('#badge4').length > 0) {
								var val = (data["val3"] && data["val3"] !== 0) ? data["val3"] : '';
								console.log('badge4에 설정할 값:', val);
								$('#badge4').text(val);
							}
							// badge5 = 결재(end) = val4
							if ($('#badge5').length > 0) {
								var val = (data["val4"] && data["val4"] !== 0) ? data["val4"] : '';
								console.log('badge5에 설정할 값:', val);
								$('#badge5').text(val);
							}
							// badge6 = 반려(reject) = val5
							if ($('#badge6').length > 0) {
								var val = (data["val5"] && data["val5"] !== 0) ? data["val5"] : '';
								$('#badge6').text(val);
							}
							// badge7 = 보류(wait) = val6
							if ($('#badge7').length > 0) {
								var val = (data["val6"] && data["val6"] !== 0) ? data["val6"] : '';
								$('#badge7').text(val);
							}
							// badge8 = 참조(refer) = val7 (필요시)
							if ($('#badge8').length > 0) {
								var val = (data["val7"] && data["val7"] !== 0) ? data["val7"] : '';
								$('#badge8').text(val);
							}

							console.log('=== badge 업데이트 완료 ===');

														
						// 종 아이콘 및 "알림" 버튼 처리
						var bellIcon = document.getElementById('bellIcon');
						var alertEworks = document.getElementById('alert_eworks_bell');
						var badgeElement = document.getElementById('badge3');
						if (badgeElement && bellIcon && alertEworks) {								
							var badgeText = badgeElement.innerText || badgeElement.textContent || '0';
							var badgeCount = parseInt(badgeText.trim());
							const ework_approval = $("#ework_approval").val();
							
							// badgeCount가 NaN이면 0으로 설정
							if (isNaN(badgeCount)) {
								badgeCount = 0;
							}
							
							console.log('badgeCount :', badgeCount);								  
							console.log('ework_approval :', $("#ework_approval").val());
							
							if (parseInt(ework_approval) > 0 && badgeCount > 0)
								{
									bellIcon.style.display = 'inline';
									bellIcon.classList.add('blink');
									alertEworks.style.display = 'inline';
									alertEworks.classList.add('blink');
								} else {
									if(bellIcon && parseInt(ework_approval) > 0) {
										bellIcon.style.display = 'none';
										bellIcon.classList.remove('blink');
										alertEworks.style.display = 'none';
										alertEworks.classList.remove('blink');
									}
								}
								
								
							}

							
							// $('#displaytmp').text(data["sql"]); // sql
				 
					},
					error : function( jqxhr , status , error ){
						console.error('=== 전자결재 데이터 로드 실패 ===');
						console.error('Status:', status);
						console.error('Error:', error);
						console.error('Response:', jqxhr.responseText);
				}
			   });
	} else {
		console.warn('eworks_board_form이 페이지에 존재하지 않습니다');
	}
}

function seltab(e_num) 
{
	console.log('=== seltab 호출 ===', e_num);
	
    $("#search").val('');  	
    activeTab = e_num;
	
	showEworksLoadingIndicator(); // 전자결재 로딩 시작

    // 탭에 따라 form의 hidden input 값 설정
    switch (e_num) {
        case 1:
            $('#choice').val(e_num);   
            $('#eworksel').val('draft');   
            break;
        case 2:
            $('#choice').val(e_num);   
            $('#eworksel').val('send');   
            break;
        case 3:
            $('#choice').val(e_num);   
            $('#eworksel').val('noend');   
            break;
        case 4:
            $('#choice').val(e_num);   
            $('#eworksel').val('ing');   
            break;
        case 5:
            $('#choice').val(e_num);   
            $('#eworksel').val('end');   
            break;
        case 6:
            $('#choice').val(e_num);   
            $('#eworksel').val('reject');   
            break;
        case 7:
            $('#choice').val(e_num);   
            $('#eworksel').val('wait');   
            break;
        case 8:
            $('#choice').val(e_num);   
            $('#eworksel').val('refer');   
            break;
        case 9:
            $('#choice').val(e_num);   
            $('#eworksel').val('trash');   
            break;
    }
	
	// 즉시 실행 (타임아웃 제거하여 속도 개선)
	refresheworks(); // 페이지 새로고침 또는 데이터 테이블 재로드
	
	// 로딩 완료는 refresheworks 내부에서 처리
}

function refresheworks() {	
	console.log('=== refresheworks 시작 ===');
	
	// 탭 UI 업데이트
	update_eworks_nav();
	
	// 목록 HTML 로드 (list.php)
	var url = '/eworks/list.php';
	
	if (ajaxRequest !== null) {
		ajaxRequest.abort();
	}
	
	ajaxRequest = $.ajax({
		url: url,
		type: 'POST',
		data: $("#eworks_board_form").serialize(),
		success: function(response) {
			console.log('목록 로드 완료, 길이:', response.length);
			$('#eworks_list').html(response);
			
			// eworksList()로 JSON 데이터 로드
			eworksList();
			
			// 로딩 인디케이터는 eworksList() 완료 후 숨김
		},
		error: function(jqxhr, status, error) {
			console.log('목록 로드 오류:', jqxhr, status, error);
			hideEworksLoadingIndicator();
		}
	});
	
	console.log('=== refresheworks 완료, activeTab:', activeTab);
}

// 전자결재 각 항목별 해당 숫자 읽기
function alert_eworkslist() {
	if ($('#eworks_board_form').length) {
			
		let url = '/eworks/load_eworks.php';
		
		if (ajaxRequest !== null) {
			ajaxRequest.abort();
		}

    // ajax 요청 생성
    ajaxRequest = $.ajax({
        url: url,
        type: 'POST',
        data: $("#eworks_board_form").serialize(),
        dataType: "json",
        success: function(data) {
            var badge3 = data["val2"] === 0 ? '' : data["val2"];
            
            // 종 아이콘 및 "알림" 버튼 처리
            var alertEworks = document.getElementById('alert_eworks_bell');			
			const ework_approval = $("#ework_approval").val();			
			
            if (alertEworks) {
                if (Number(badge3) > 0 && parseInt(ework_approval) > 0 ) {
                    alertEworks.style.display = 'inline';
                    alertEworks.classList.add('blink');
					// PC에서만 자동으로 보여줌 (모바일은 토글로 제어)
					if(!isModalOpen && window.innerWidth > 768)
						$('.sideEworksBanner').css('display', 'block'); // '미결'이 존재하면 sideEworksBanner 보여줌
                } else {
                    alertEworks.style.display = 'none';
                    alertEworks.classList.remove('blink');
					// PC에서만 자동으로 숨김 (모바일은 토글로 제어)
					if (window.innerWidth > 768)
                    	$('.sideEworksBanner').css('display', 'none'); // '미결'이 없으면 sideEworksBanner 숨김
                }
            }
        },
        error: function(jqxhr, status, error) {
            console.log(jqxhr, status, error);
        }
      });
   }
}


function eworksItemChanged(selectElement) {
	var value = selectElement.value;
	var status = $("#status").val();

	if (value === "연차" && ( status=== "" || status=== null || status=== 'draft' ) ) {
			// '연차' 선택 시 수행할 작업
			openAnnualLeaveWindow();
		}
	
	if (value === "연장근무" && ( status=== "" || status=== null || status=== 'draft' ) ) {
			// '연장근무' 선택 시 수행할 작업
			openOvertimeWindow();
		}
}

function openAnnualLeaveWindow() {
	// 연차 관련 작업 수행
	 popupCenter('/annualleave/write_form_ask.php', '등록/수정/삭제', 420, 720);

}

function openOvertimeWindow() {
	// 연장근무 관련 작업 수행
	popupCenter('/request_overtime/write_form.php', '연장근무 신청', 500, 650);
}


$(document).ready(function () {	
	
	 $(document).on('change', '#checkAll', function(e) {
        var checkboxes = document.querySelectorAll('.checkItem');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = e.target.checked;
        }
    });

  	
});

$(document).ready(function () {			
	///////////////////// input 필드 값 옆에 X 마크 띄우기 
	///////////////////// input 필드 값 옆에 X 마크 띄우기 

	var btnClear = document.querySelectorAll('.btnClear');
	if(btnClear)
	{
		btnClear.forEach(function(btn){
			btn.addEventListener('click', function(e){
				btn.parentNode.querySelector('input').value = "";			
				e.preventDefault(); // 기본 이벤트 동작 막기
			  // 포커스 얻기
			  btn.parentNode.querySelector('input').focus();
			})
		})	
  	}
	
	$("#searchBtn").click(function(){  document.getElementById('board_form').submit();   });	

});
  
function SearchEnter(){
    if(event.keyCode == 13){
	 var page = document.getElementById('page');
	 if(page)
		$("#page").val('1');		
	
	document.getElementById('board_form').submit(); 
    }
}  	
	
	
// 특정 문자열이 포함되어 있는지 여부를 알려주는 함수	
function checkString(str, subStr) {
    if (str.includes(subStr)) {
        return true;
    } else {
        return false;
    }
}

// object가 null 여부를 판단해서 true, false 반환하는 함수
function isNull(obj) {
    if (obj === null || obj === '') {
        return true;
    } else {
        return false;
    }
}

// 결재창 계속 5초 간격 확인하기		  
var timer = setInterval(function() 
{
	alert_eworkslist();	
}, 1500); 	// 1.5초 간격	
	
$(document).ready(function() {
    // change_dateRange 클래스를 가진 버튼에 대한 클릭 이벤트 핸들러
    $('.change_dateRange').on('click', function() {
        setCookie('dateRange', '직접설정', 30);  
    });
});
	
function showLoadingIndicator() {
    var indicator = document.getElementById('loadingIndicator');
    console.log('showLoadingIndicator 호출, indicator:', indicator);
    
    if (indicator) {
        indicator.style.display = 'flex';
        console.log('로딩 인디케이터 표시됨');
        
        // 키 입력 차단
        document.body.style.pointerEvents = 'none';
        
        // 로딩 인디케이터는 클릭 가능하도록
        indicator.style.pointerEvents = 'auto';
    } else {
        console.error('loadingIndicator 요소를 찾을 수 없습니다!');
    }
}

function hideLoadingIndicator() {
    var indicator = document.getElementById('loadingIndicator');
    console.log('hideLoadingIndicator 호출, indicator:', indicator);
    
    if (indicator) {
        indicator.style.display = 'none';
        console.log('로딩 인디케이터 숨김');
        
        // 키 입력 복구
        document.body.style.pointerEvents = 'auto';
    }
}

// 전자결재 전용 로딩 인디케이터
function showEworksLoadingIndicator() {
    var indicator = document.getElementById('eworksLoadingIndicator');
    console.log('showEworksLoadingIndicator 호출, indicator:', indicator);
    
    if (indicator) {
        indicator.style.display = 'block';
        console.log('전자결재 로딩 인디케이터 표시됨');
    } else {
        console.error('eworksLoadingIndicator 요소를 찾을 수 없습니다!');
    }
}

function hideEworksLoadingIndicator() {
    var indicator = document.getElementById('eworksLoadingIndicator');
    console.log('hideEworksLoadingIndicator 호출, indicator:', indicator);
    
    if (indicator) {
        indicator.style.display = 'none';
        console.log('전자결재 로딩 인디케이터 숨김');
    }
}

// 전자결재에서 첨부파일 불러오기 함수
function loadAttachedFiles(tablename, num) {
    $.ajax({
        url: '/filedrive/load_filelist.php',
        type: 'GET',
        data: {
            tablename: tablename,
            num: num,
            item: 'attached'
        },
        dataType: 'json',
        success: function (data) {
            $('#displayFile').html('');
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function (fileData, index) {
                    const link = fileData.link || '#';
                    const name = fileData.realname || '파일';
                    const fileId = fileData.fileId || '';
                    $('#displayFile').append(`
                        <div class="mb-2">
                            <a href="#" onclick="popupCenter('${link}', 'filePopup', 800, 600); return false;">${name}</a>
                        </div>
                    `);
                });
            } else {
                $('#displayFile').html('<div class="text-muted">첨부된 파일이 없습니다.</div>');
            }
        }
    });
}
