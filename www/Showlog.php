<?php
require_once __DIR__ . '/bootstrap.php';

// 세션 변수 안전하게 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? 'Unknown';
$DB = $_SESSION["DB"] ?? 'mirae8440';

ini_set('display_errors', isLocal() ? '1' : '0');

// 요청 변수 안전하게 초기화
$num = $_REQUEST["num"] ?? '';
$workitem = $_REQUEST["workitem"] ?? '';

// 입력 검증
if (empty($num) || empty($workitem)) {
    die('필수 매개변수가 누락되었습니다.');
}

// 로그 데이터 조회
$update_log = "";
$update_log_arr = "";

try {
    $sql = "SELECT * FROM {$DB}.{$workitem} WHERE num = ?";
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $num, PDO::PARAM_STR); 
    $stmh->execute();
    $count = $stmh->rowCount();              
    
    if ($count < 1) {  
        // 데이터가 없을 경우 빈 문자열로 초기화
        $update_log = "";
        $update_log_arr = "";
    } else {      
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $update_log = $row["update_log"] ?? "";
        
        // 로그 포맷팅: 연도 앞에 줄바꿈 추가
        $update_log_arr = preg_replace('/(\d{4})/', "<br>$1", $update_log);		
    }      									  		      										
} catch (PDOException $Exception) {
    if (isLocal()) {
        error_log("Database error in Showlog.php: " . $Exception->getMessage());
        die("데이터베이스 오류: " . $Exception->getMessage());
    } else {
        error_log("Database error in Showlog.php: " . $Exception->getMessage());
        die("데이터베이스 오류가 발생했습니다. 관리자에게 문의하세요.");
    }
}
 


?>

<?php include includePath('load_header.php'); ?>

<title>로그 기록</title>
</head>

<body>

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">

<div class="container" style="width:480px;">

<!-- Extra Full Modal -->	
<div class="card justify-content-center mt-3 mb-3 p-2 m-2">
    <div class="card-header justify-content-center">
        <h5 class="modal-title text-center fs-5" id="myModalLabel16">로그 기록</h5>								
    </div>
    <div class="card-body">								
        <div class="card">
            <table class="table table-striped">
                <thead class="modal-title justify-content-center"> 
                    <tr>
                        <th class="justify-content-center text-center fs-6">기록 시간 / 사용자명</th>
                    </tr>
                </thead>
                <tbody> 				   				   
                    <?php
                    // 로그 데이터 포맷팅 및 출력
                    $update_log_arr = str_replace("&#10;", "<br>", $update_log_arr);
                    
                    if (!empty($update_log_arr)) {
                        echo "<tr><td class='text-center fs-6'>";
                        echo htmlspecialchars($update_log_arr, ENT_QUOTES, 'UTF-8');
                        echo "</td></tr>";
                    } else {
                        echo "<tr><td class='text-center fs-6 text-muted'>로그 기록이 없습니다.</td></tr>";
                    }
                    ?>					  
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer justify-content-start mt-2 mb-2">
        <button type="button" id="closeBtn" class="btn btn-secondary btn-sm">		
            <i class="bi bi-x-lg"></i> 창닫기
        </button>
    </div>
</div>

</div>
</form>
</body>
</html>
	 
<script>
// 전역 변수 초기화
var ajRequest = null;

$(document).ready(function(){	  
    // 창닫기 버튼 이벤트
    $("#closeBtn").on("click", function() {
        self.close();
    });	
});	  // end of ready

/* ESC 키 누를시 팝업 닫기 */
$(document).keydown(function(e){
    // keyCode 구 브라우저, which 현재 브라우저
    var code = e.keyCode || e.which;
    
    if (code == 27) { // 27은 ESC 키번호
        self.close();
    }
});

</script>