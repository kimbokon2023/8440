<?php
require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 5;
$WebSite = $_SESSION["WebSite"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$header = $_REQUEST['header'] ?? '';
$search = $_REQUEST['search'] ?? '';
$mode = $_REQUEST["mode"] ?? '';
$fromdate = $_REQUEST["fromdate"] ?? '';
$todate = $_REQUEST["todate"] ?? '';

$tablename = 'delivery';
$title_message = '경동화물/택배';

// 현재 날짜
$currentDate = date("Y-m-d");
$today = $currentDate;

// 기간이 비어있으면 현재 날짜로 설정
if ($fromdate === "" || $fromdate === null || $todate === "" || $todate === null) {
    $fromdate = $currentDate;
    $todate = $currentDate;
}

// 데이터베이스 연결
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// SQL 쿼리 생성 (날짜 범위 필터링)
$sql = "SELECT * FROM {$DB}.{$tablename} 
        WHERE is_deleted IS NULL 
          AND registedate BETWEEN :fromdate AND :todate";

// 검색어가 있을 경우 추가
if (!empty($search)) {
    $sql .= " AND searchtag LIKE :search";
}

$sql .= " ORDER BY registedate DESC";

try {
    $stmh = $pdo->prepare($sql);
    
    // 바인딩
    $stmh->bindValue(":fromdate", $fromdate, PDO::PARAM_STR);
    $stmh->bindValue(":todate", $todate, PDO::PARAM_STR);
    
    if (!empty($search)) {
        $stmh->bindValue(":search", "%{$search}%", PDO::PARAM_STR);
    }
    
    $stmh->execute();
    $total_row = $stmh->rowCount();
?>

<?php include getDocumentRoot() . '/load_header.php'; ?>

<link href="css/style.css" rel="stylesheet">
<title><?= htmlspecialchars($title_message) ?></title>
</head>

<body>
<?php require_once(includePath('myheader.php')); ?>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
    <input type="hidden" id="mode" name="mode" value="<?= htmlspecialchars($mode) ?>">
    <input type="hidden" id="num" name="num">
    <input type="hidden" id="tablename" name="tablename" value="<?= htmlspecialchars($tablename) ?>">
    <input type="hidden" id="header" name="header" value="<?= htmlspecialchars($header) ?>">
    
    <div class="container-fluid">
        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title">경동화물/택배 등록/수정</span>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="custom-card"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="d-flex mt-4 mb-2 align-items-center justify-content-center">
            <span class="text-center fs-5"><?= htmlspecialchars($title_message) ?></span>
            <button type="button" class="btn btn-dark btn-sm mx-2" onclick="location.reload();">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
        
        <div class="d-flex mt-2 mb-3 align-items-center justify-content-center">
            ▷ <?= $total_row ?> 건
            
            <!-- 기간 설정 -->
            <span id="showdate" class="btn btn-dark btn-sm mx-1">기간</span>&nbsp;
            
            <div id="showframe" class="card">
                <div class="card-header" style="padding:2px;">
                    <div class="d-flex justify-content-center align-items-center">
                        기간 설정
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="button" class="btn btn-outline-success btn-sm me-1 change_dateRange" onclick="alldatesearch()">전체</button>
                        <button type="button" id="preyear" class="btn btn-outline-primary btn-sm me-1 change_dateRange" onclick="pre_year()">전년도</button>
                        <button type="button" id="three_month" class="btn btn-dark btn-sm me-1 change_dateRange" onclick="three_month_ago()">M-3월</button>
                        <button type="button" id="prepremonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick="prepre_month()">전전월</button>
                        <button type="button" id="premonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick="pre_month()">전월</button>
                        <button type="button" class="btn btn-outline-danger btn-sm me-1 change_dateRange" onclick="this_today()">오늘</button>
                        <button type="button" id="thismonth" class="btn btn-dark btn-sm me-1 change_dateRange" onclick="this_month()">당월</button>
                        <button type="button" id="thisyear" class="btn btn-dark btn-sm me-1 change_dateRange" onclick="this_year()">당해년도</button>
                    </div>
                </div>
            </div>
            
            <input type="date" id="fromdate" name="fromdate" size="12" class="form-control" 
                   style="width:100px;" value="<?= htmlspecialchars($fromdate) ?>">&nbsp;~&nbsp;
            <input type="date" id="todate" name="todate" size="12" class="form-control" 
                   style="width:100px;" value="<?= htmlspecialchars($todate) ?>">&nbsp;&nbsp;
            
            <div class="inputWrap30">
                <input type="text" id="search" class="form-control" style="width:150px;" 
                       name="search" autocomplete="off" value="<?= htmlspecialchars($search) ?>" 
                       onKeyPress="if (event.keyCode==13){ enter(); }">
                <button class="btnClear"></button>
            </div>
            <button class="btn btn-dark btn-sm mx-1" type="button" id="searchBtn">
                <i class="bi bi-search"></i>
            </button>
            <button id="newBtn" type="button" class="btn btn-dark btn-sm mx-1">
                <i class="bi bi-pencil-square"></i> 신규
            </button>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="d-flex mt-1 mb-1 align-items-center justify-content-center">
            <button type="button" class="btn btn-success btn-sm downloadExcel mx-2">
                📥 선택행 엑셀 다운로드
            </button>
            <button type="button" class="btn btn-secondary btn-sm downloadPDF mx-2">
                📥 선택행 PDF 다운로드
            </button>
        </div>
        
        <div class="d-flex mt-1 mb-1 align-items-center justify-content-center">
            <table class="table table-hover" id="myTable">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center" style="width:40px;">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th class="text-center" style="width:60px;">번호</th>
                        <th class="text-center" style="width:100px;">등록일자</th>
                        <th class="text-center" style="width:150px;">받을 분</th>
                        <th class="text-center" style="width:200px;">연락처</th>
                        <th class="text-center" style="width:300px;">도착지 주소</th>
                        <th class="text-center" style="width:150px;">보내는 사람</th>
                        <th class="text-center" style="width:200px;">품명/현장명</th>
                        <th class="text-center" style="width:100px;">포장</th>
                        <th class="text-center" style="width:80px;">수량</th>
                        <th class="text-center" style="width:80px;">운임</th>
                        <th class="text-center" style="width:100px;">운임구분</th>
                        <th class="text-center" style="width:150px;">물품가액</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $start_num = $total_row;
                    
                    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                        include '_row.php';
                    ?>
                        <tr onclick="loadForm('update', <?= $num ?>)" style="cursor: pointer;">
                            <td class="text-center" onclick="event.stopPropagation();">
                                <input type="checkbox" class="rowCheckbox">
                            </td>
                            <td class="text-center"><?= $start_num ?></td>
                            <td class="text-center"><?= htmlspecialchars($registedate) ?></td>
                            <td class="text-start fw-bold text-primary"><?= htmlspecialchars($receiver) ?></td>
                            <td class="text-center"><?= htmlspecialchars($receiver_tel) ?></td>
                            <td class="text-start"><?= htmlspecialchars($address) ?></td>
                            <td class="text-start"><?= htmlspecialchars($sender) ?></td>
                            <td class="text-start"><?= htmlspecialchars($item_name) ?></td>
                            <td class="text-center"><?= htmlspecialchars($unit) ?></td>
                            <td class="text-end"><?= is_numeric($surang) ? number_format($surang) : htmlspecialchars($surang) ?></td>
                            <td class="text-end"><?= is_numeric($fee) ? number_format($fee) : htmlspecialchars($fee) ?></td>
                            <td class="text-center"><?= htmlspecialchars($fee_type) ?></td>
                            <td class="text-end"><?= is_numeric($goods_price) ? number_format($goods_price) : htmlspecialchars($goods_price) ?></td>
                        </tr>
                    <?php
                        $start_num--;
                    }
                    } catch (PDOException $ex) {
                        error_log("배송 리스트 조회 오류: " . $ex->getMessage());
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
     
</form>

<script>
var ajaxRequest = null;
var ajaxRequest_write = null;
var dataTable; // DataTables 인스턴스 전역 변수
var material_regpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

function loadForm(mode, num) {
    num = num || null;
    
    if (mode === 'copy' && num) {
        $("#mode").val('copy');
        $("#num").val(num); // 기존 데이터 복사할 num 유지
    } else if (num == null) {
        $("#mode").val('insert');
    } else {
        $("#mode").val('update');
        $("#num").val(num);
    }
    
    $.ajax({
        type: "POST",
        url: "fetch_modal.php",
        data: { mode: mode, num: num },
        dataType: "html",
        success: function(response) {
            document.querySelector(".modal-body .custom-card").innerHTML = response;
            $("#myModal").show();
            
            $("#closeBtn").off("click").on("click", function() {
                $("#myModal").hide();
            });
            
            // 기존 이벤트 제거 후 재등록
            $(document).off('click', '.specialbtnClear').on('click', '.specialbtnClear', function(e) {
                e.preventDefault();
                $('#item_name').val('');
            });
            
            // Log 파일보기
            $("#showlogBtn").click(function() {
                var num = $(this).data("num");
                var tablename = $("#tablename").val();
                var btn = $(this);
                
                popupCenter("../Showlog.php?num=" + num + "&workitem=" + tablename, '로그기록 보기', 500, 500);
                btn.prop('disabled', false);
            });
            
            var isSaving = false;

            // 저장 버튼 (기존 이벤트 제거 후 재등록)
            $("#saveBtn").off("click").on("click", function() {
                if (isSaving) return;
                isSaving = true;

                var header = $("#header").val();
                var formData = $("#board_form").serialize();                

                $.ajax({
                    url: "process.php",
                    type: "post",
                    data: formData,
                    success: function(response) {
                        Toastify({
                            text: "저장완료",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87",
                        }).showToast();

                        setTimeout(function() {
                            $("#myModal").hide();
                            location.reload();
                        }, 2000);
                    },
                    error: function(jqxhr, status, error) {
                        console.log(jqxhr, status, error);
                        isSaving = false;
                    }
                });
            });		

            // 복사 버튼 (기존 이벤트 제거 후 재등록)
            $("#copyBtn").off("click").on("click", function() {
                var num = $('#num').val(); // num을 비워 새로 삽입할 수 있도록 초기화
				$("#myModal").hide();
				setTimeout(function() {
					copyForm('copy', num); // 복사 모드로 폼 다시 로드						
				}, 500);				
                
            });
			
            // 삭제 버튼
            $("#deleteBtn").off("click").on("click", function() {			            
                // var level = '<?= $_SESSION["level"] ?>';

                // if (level !== '1') {
                    // Swal.fire({
                        // title: '삭제불가',
                        // text: "관리자만 삭제 가능합니다.",
                        // icon: 'error',
                        // confirmButtonText: '확인'
                    // });
                    // return;
                // }

                Swal.fire({
                    title: '자료 삭제',
                    text: "삭제는 신중! 정말 삭제하시겠습니까?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '삭제',
                    cancelButtonText: '취소'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $("#mode").val('delete');
                        var formData = $("#board_form").serialize();

                        $.ajax({
                            url: "process.php",
                            type: "post",
                            data: formData,
                            success: function(response) {
                                Toastify({
                                    text: "파일 삭제완료",
                                    duration: 2000,
                                    close: true,
                                    gravity: "top",
                                    position: "center",
                                    style: {
                                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                                    },
                                }).showToast();

                                $("#myModal").hide();
                                location.reload();
                            },
                            error: function(jqxhr, status, error) {
                                console.log(jqxhr, status, error);
                            }
                        });
                    }
                });
            });
        },
        error: function(jqxhr, status, error) {
            console.log("AJAX Error: ", status, error);
        }
    });
}

function copyForm(mode, num) {
    num = num || null;
    
    if (mode === 'copy' && num) {
        $("#mode").val('copy');
        $("#num").val(num); // 기존 데이터 복사할 num 유지
    } else if (num == null) {
        $("#mode").val('insert');
    } else {
        $("#mode").val('update');
        $("#num").val(num);
    }
    
    $.ajax({
        type: "POST",
        url: "fetch_modal.php",
        data: { mode: mode, num: num },
        dataType: "html",
        success: function(response) {
            document.querySelector(".modal-body .custom-card").innerHTML = response;
            $("#myModal").show();
            
            $("#closeBtn").off("click").on("click", function() {
                $("#myModal").hide();
            });
            
            // 기존 이벤트 제거 후 재등록
            $(document).off('click', '.specialbtnClear').on('click', '.specialbtnClear', function(e) {
                e.preventDefault();
                $('#item_name').val('');
            });
            
            var isSaving = false;

            // 저장 버튼 (기존 이벤트 제거 후 재등록)
            $("#saveBtn").off("click").on("click", function() {
                if (isSaving) return;
                isSaving = true;

                var header = $("#header").val();
                var formData = $("#board_form").serialize();                

                $.ajax({
                    url: "process.php",
                    type: "post",
                    data: formData,
                    success: function(response) {
						
						console.log('response',response);
                        Toastify({
                            text: "저장완료",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87",
                        }).showToast();

                        setTimeout(function() {
                            $("#myModal").hide();
                            location.reload();
                        }, 2000);
                    },
                    error: function(jqxhr, status, error) {
                        console.log(jqxhr, status, error);
                        isSaving = false;
                    }
                });
            });		

	  },
        error: function(jqxhr, status, error) {
            console.log("AJAX Error: ", status, error);
        }
    });
}

</script>

<!-- 데이터 테이블 및 기타 기능을 위한 스크립트 -->
<script>
$(document).ready(function() {            
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 200,
        "lengthMenu": [ 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[1, 'desc']],
        "dom": 't<"bottom"ip>' // search 창과 lengthMenu 숨기기		
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('material_regpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var material_regpageNumber = dataTable.page.info().page + 1;
        setCookie('material_regpageNumber', material_regpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('material_regpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });

    $(document).on('click', '.specialbtnClear', function(e) {
        e.preventDefault(); // 기본 동작을 방지합니다.
        $(this).siblings('input').val('').focus();
    });
	
    $(document).on('click', '.btnClear_lot', function(e) {
        e.preventDefault(); // 기본 동작을 방지합니다.
        $(this).siblings('input').val('').focus();
    });
});

</script>

<!-- 페이지로딩 및 Modal Script -->
<script>
$(document).ready(function(){    
    var loader = document.getElementById('loadingOverlay');
    if(loader)
		loader.style.display = 'none';

    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    $(".close").on("click", function() {		
        $("#myModal").hide();
    });
	
    $("#newBtn").on("click", function() {		
        loadForm('insert');
    });

    $("#searchBtn").on("click", function() {
        $("#board_form").submit();
    });
	
    $("#closeBtn").on("click", function() {
         var modal = document.getElementById("myModal");
		 modal.style.display = "none";
    });
});

function enter() {
    $("#board_form").submit();
}

function inputNumberFormat(obj) {
    // 숫자 이외의 문자는 제거
    obj.value = obj.value.replace(/[^0-9]/g, '');
    
    // 콤마를 제거하고 숫자를 포맷팅
    var value = obj.value.replace(/,/g, '');
    obj.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

// Prevent form submission on Enter key
$(document).on("keypress", "input", function(event) {
	return event.keyCode != 13;
});		

$(document).ready(function(){    
   // 방문기록 남김
   var title_message = '<?php echo $title_message ; ?>';
   saveMenuLog(title_message);
});

$(document).ready(function() {
    // 전체 선택 체크박스 클릭 이벤트
    $('#selectAll').on('click', function() {
        $('.rowCheckbox').prop('checked', this.checked);
    });
    
    // 개별 체크박스 클릭 시 전체 선택 체크박스 상태 업데이트
    $('.rowCheckbox').on('click', function() {
        $('#selectAll').prop('checked', $('.rowCheckbox:checked').length === $('.rowCheckbox').length);
    });
    
    // 엑셀 다운로드 버튼 클릭 이벤트
    $('.downloadExcel').on('click', function() {
        var data = [];
        var rows = $('#myTable tbody tr');
        
        // 체크된 행만 수집
        rows.each(function() {
            if ($(this).find('.rowCheckbox').is(':checked')) {
                var rowData = {};
                rowData['번호'] = $(this).find('td').eq(1).text().trim();
                rowData['등록일자'] = $(this).find('td').eq(2).text().trim();
                rowData['받을 분'] = $(this).find('td').eq(3).text().trim();
                rowData['연락처'] = $(this).find('td').eq(4).text().trim();
                rowData['도착지 주소'] = $(this).find('td').eq(5).text().trim();
                rowData['보내는 사람'] = $(this).find('td').eq(6).text().trim();
                rowData['품명/현장명'] = $(this).find('td').eq(7).text().trim();
                rowData['포장'] = $(this).find('td').eq(8).text().trim();
                rowData['수량'] = $(this).find('td').eq(9).text().trim();
                rowData['운임'] = $(this).find('td').eq(10).text().trim();
                rowData['운임구분'] = $(this).find('td').eq(11).text().trim();
                rowData['물품가액'] = $(this).find('td').eq(12).text().trim();
                data.push(rowData);
            }
        });
        
        if (data.length === 0) {
            alert("선택된 데이터가 없습니다.");
            return;
        }
        
        // 엑셀 파일 생성 요청
        $.ajax({
            url: '/delivery/dl_ex_deliveryfee.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(response) {
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        alert('서버에서 유효하지 않은 응답을 받았습니다.');
                        console.error("응답 파싱 오류:", e);
                        return;
                    }
                }
                
                if (response.success) {
                    var filename = response.filename.split('/').pop();
                    var downloadUrl = '/excelsave/' + encodeURIComponent(filename);
                    window.location.href = downloadUrl;
                } else {
                    alert('엑셀 파일 생성에 실패했습니다: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX 오류:", textStatus, errorThrown);
                alert('엑셀 파일 생성 중 오류가 발생했습니다.');
            }
        });
    });
});

$(document).off('click', '.downloadPDF').on('click', '.downloadPDF', function(event) {
    event.preventDefault();
    console.log("📌 PDF 다운로드 버튼 클릭됨");
    
    var deadline = '<?php echo $today; ?>';
    var deadlineDate = new Date(deadline);
    var formattedDate = "(" + String(deadlineDate.getFullYear()).slice(-2) + "." +
                        ("0" + (deadlineDate.getMonth() + 1)).slice(-2) + "." +
                        ("0" + deadlineDate.getDate()).slice(-2) + ")";
    
    console.log("✅ PDF 파일명:", formattedDate);
    
    // 선택된 행만 가져오기
    var selectedRows = $('#myTable tbody tr').has('.rowCheckbox:checked').clone();
    console.log("✅ 선택된 행 개수:", selectedRows.length);
    
    if (selectedRows.length === 0) {
        alert('선택된 행이 없습니다.');
        return;
    }
    
    // 첫 번째 선택된 행에서 등록일자 가져오기
    var firstDate = selectedRows.first().find('td:eq(2)').text().trim();
    console.log("✅ 등록일자:", firstDate);
    
    // 등록일자 컬럼 및 체크박스 컬럼 제거
    var tableHeader = $('#myTable thead').clone();
    tableHeader.find('th:eq(0), th:eq(2)').remove();
    
    // 선택된 행에서 체크박스 및 등록일자 컬럼 제거
    selectedRows.each(function() {
        $(this).find('td:eq(0), td:eq(2)').remove();
    });
    
    // 제목을 등록일자 포함하여 설정
    var titleText = '<h2 class="text-center" style="font-size: 25px; font-weight: bold;">' + 
                    firstDate + ' 📦 경동화물/택배</h2>';

    // 임시 컨테이너 생성 후 선택된 데이터 추가
    var tempContainer = $('<div>').attr('id', 'temp-pdf-container').css({ 
        'display': 'block', 
        'padding': '5px',
        'background': '#fff',
        'width': '100%',
        'height': 'auto'
    });
    $('body').append(tempContainer);

    tempContainer.append(titleText);

    // 새로운 테이블 생성
    var tempTable = $('<table>').addClass('table').css({ 
        'width': '95%', // 전체 너비 줄임
        'border-collapse': 'collapse',
        'font-size': '11px',  // 폰트 크기 더 줄임
        'table-layout': 'fixed', // 고정 테이블 레이아웃
        'page-break-inside': 'avoid', // 테이블 내부 페이지 브레이크 방지
        'break-inside': 'avoid', // CSS3 표준
        'margin': '0 auto' // 중앙 정렬
    });

    // 헤더 추가 (등록일자 & 체크박스 제거된 상태) 및 컬럼 너비 설정
    tableHeader.find('th').each(function(index) {
        $(this).css({
            'border': '0.5px solid black',
            'text-align': 'left',
            'padding': '3px 1px', // 패딩 더 줄임
            'white-space': 'pre-line', // 자동 줄바꿈 허용 + 줄바꿈 문자 인식
            'font-family': 'Malgun Gothic, Dotum, Arial, sans-serif',
            'font-size': '11px', // 폰트 크기 더 줄임
            'word-break': 'break-all',
            'vertical-align': 'middle',
            'page-break-inside': 'avoid', // 헤더 페이지 브레이크 방지
            'break-inside': 'avoid'
        });
        
        // 컬럼별 너비 설정 (0-based index)
        switch(index) {
            case 0: $(this).css('width', '40px'); break;  // 번호
            case 1: $(this).css('width', '80px'); break;  // 받을분
            case 2: $(this).css('width', '100px'); break; // 연락처
            case 3: $(this).css('width', '250px'); break; // 도착지주소
            case 4: $(this).css('width', '80px'); break;  // 보내는사람
            case 5: $(this).css('width', '120px'); break; // 품명/현장명
            case 6: $(this).css('width', '60px'); break;  // 포장
            case 7: $(this).css('width', '40px'); break;  // 수량
            case 8: $(this).css('width', '80px'); break;  // 운임
        }
    });
    tempTable.append(tableHeader);

    // 데이터 행 추가 (등록일자 & 체크박스 제거된 상태)
    selectedRows.each(function() {
        // 모든 셀에 기본 스타일 적용
        var $tds = $(this).find('td');
        $tds.each(function(index) {
            $(this).css({
                'border': '0.5px solid black',
                'text-align': 'left',
                'padding': '3px 1px', // 패딩 더 줄임
                'white-space': 'pre-line', // 줄바꿈 문자 인식
                'font-family': 'Malgun Gothic, Dotum, Arial, sans-serif',
                'font-size': '11px', // 폰트 크기 더 줄임
                'word-break': 'break-all',
                'vertical-align': 'middle',
                'page-break-inside': 'avoid', // 셀 페이지 브레이크 방지
                'break-inside': 'avoid'
            });
            
            // 컬럼별 너비 설정 (0-based index)
            switch(index) {
                case 0: $(this).css('width', '40px'); break;  // 번호
                case 1: $(this).css('width', '80px'); break;  // 받을분
                case 2: $(this).css('width', '100px'); break; // 연락처
                case 3: $(this).css('width', '250px'); break; // 도착지주소
                case 4: $(this).css('width', '80px'); break;  // 보내는사람
                case 5: $(this).css('width', '120px'); break; // 품명/현장명
                case 6: $(this).css('width', '60px'); break;  // 포장
                case 7: $(this).css('width', '40px'); break;  // 수량
                case 8: $(this).css('width', '80px'); break;  // 운임
            }
        });

        // 도착지 주소 컬럼(3번째, 0-based index)만 별도 스타일 적용
        var $addressTd = $tds.eq(3); // 도착지주소는 3번째 컬럼
        $addressTd.css({
            'min-width': '200px', // 최소 너비 더 줄임
            'max-width': '250px', // 최대 너비 더 줄임
            'width': '250px', // 고정 너비
            'white-space': 'pre-line',
            'word-break': 'break-all',
            'line-height': '1.3', // 줄간격 더 줄임
            'font-size': '10px', // 폰트 크기 더 줄임
            'font-weight': 'bold',
            'page-break-inside': 'avoid', // 주소 셀 페이지 브레이크 방지
            'break-inside': 'avoid'
        });

        // 도착지 주소가 너무 길면 줄바꿈 문자 추가 (한글 겹침 방지)
        var addressText = $addressTd.text();
        if(addressText.length > 25) {
            // 25자마다 줄바꿈 삽입 (단어 단위가 아니더라도 강제 줄바꿈)
            addressText = addressText.replace(/(.{25})/g, "$1\n");
            $addressTd.text(addressText);
        }

        tempTable.append($(this));
    });

    tempContainer.append(tempTable);

    console.log("✅ 임시 컨테이너 생성 완료:", tempContainer.html());

    // 300ms 지연 후 실행하여 렌더링 문제 해결
    setTimeout(function() {
        console.log("📌 PDF 생성 시작");
        
        var opt = {
            margin: [5, 3, 5, 3], // 여백 (상, 우, 하, 좌)
            filename: '경동화물_택배_' + formattedDate + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: {
                scale: 2.2,
                useCORS: true,
                logging: false,
                windowWidth: document.body.scrollWidth,
                windowHeight: document.body.scrollHeight,
                allowTaint: true,
                backgroundColor: '#ffffff',
                letterRendering: true
            },
            jsPDF: {
                unit: 'mm',
                format: 'a4',
                orientation: 'landscape',
                compress: true,
                precision: 2
            },
            pagebreak: {
                mode: ['avoid-all', 'css', 'legacy'],
                before: '.page-break-before',
                after: '.page-break-after',
                avoid: ['tr', 'td', 'th', 'table', 'tbody', 'thead']
            }
        };
        
        html2pdf().from(tempContainer[0]).set(opt).save().then(function() {
            console.log("✅ PDF 생성 완료");
            tempContainer.remove();
        }).catch(function(err) {
            console.error("❌ PDF 생성 오류:", err);
        });
    }, 300);
});


</script>

</body>
</html>
 