<?php require_once __DIR__ . '/bootstrap.php';

// header('Cache-Control: no cache');
// 자바스크립트 자동 업데이트를 위한 version 설정

$now = date("Y-m-d",time()) ;  
$version = time(); // 캐시 방지를 위한 버전 번호

// 사용자 정보 초기화
$user_id = $_SESSION["userid"] ?? '';
$user_name = $_SESSION["name"] ?? '';

// 필요한 데이터를 담을 배열 초기화
$firstStep = array();
$firstStepID = array(); // 추가: 결재권한 ID를 저장할 배열 초기화

$admin = 0;
$ework_approval = 0 ;

try {
    $loadSQL = "SELECT * FROM mirae8440.member WHERE part IN ('제조파트', '지원파트')";
    $stmh = $pdo->prepare($loadSQL);
    $stmh->execute();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        // 필요한 데이터만 추출하여 배열에 저장
        $eworks_level = (int)$row["eworks_level"];
        if ($eworks_level === 1 || $eworks_level === 2) {
            $firstStep[] = $row["name"] . " " . $row["position"];
            $firstStepID[] = $row["id"]; // 결재권한 ID를 배열에 추가
        }
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

// 현재 사용자가 결재권자인지 확인
$eworks_level = in_array($user_id, $firstStepID);


$_SESSION["eworks_level"] = $eworks_level ;

if($user_name=='소현철' || $user_name=='김보곤' || $user_name=='이경묵' || $user_name=='최장중' || $user_name=='조경임' || $user_name=='소민지' )
{
	 $admin = 1;	
   if($user_name=='소현철' || $user_name=='최장중')
        $ework_approval = 1;   
}
  else
{
	for($i = 0; $i < count($firstStepID); $i++) {
		if($user_id === $firstStepID[$i]) {
			$admin = 1;
			$ework_approval = 1;
			break; // 일치하는 경우가 발견되면 루프를 종료
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<?php
// PC 버전 강제 모드 체크
$force_pc_view = isset($_COOKIE['force_pc_view']) && $_COOKIE['force_pc_view'] === '1';

if ($force_pc_view) {
    // PC 뷰 강제 시 고정 너비 설정 (예: 1280px)
    echo '<meta name="viewport" content="width=1280, user-scalable=yes">';
} else {
    // 기본 모바일 반응형 뷰포트
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">';
}
?>
<meta property="og:type" content="미래기업 통합정보시스템">
<meta property="og:title" content="미래기업 통합정보시스템">
<meta property="og:url" content="<?= getBaseUrl() ?>">
<meta property="og:description" content="정확한 업무처리 포탈!">
<meta property="og:image" content="<?= asset('img/miraethumbnail.jpg') ?>"> 
 
<script src="<?= asset('js/jquery.min.js') ?>"></script>

<!-- 동적 Base URL 설정 -->
<script>
    // PHP에서 생성된 base URL을 JavaScript 전역 변수로 설정
    window.baseUrl = <?= json_encode(getBaseUrl()) ?>;
</script>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.10/sweetalert2.min.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.4.0/highcharts.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/11.4.0/modules/accessibility.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">   <!--날짜 선택 창 UI 필요 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.css" rel="stylesheet">
<script src="https://unpkg.com/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.10/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.12.0/toastify.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js" integrity="sha512-SIMGYRUjwY8+gKg7nn9EItdD8LCADSDfJNutF9TPrvEo86sQmFMh6MyralfIyhADlajSxqc7G0gs7+MwWF/ogQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-annotation/3.0.1/chartjs-plugin-annotation.min.js" integrity="sha512-Hn1w6YiiFw6p6S2lXv6yKeqTk0PLVzeCwWY9n32beuPjQ5HLcvz5l2QsP+KilEr1ws37rCTw3bZpvfvVIeTh0Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.css" />
<script src="https://uicdn.toast.com/tui.pagination/latest/tui-pagination.js"></script>
<link rel="stylesheet" href="https://uicdn.toast.com/tui-grid/latest/tui-grid.css"/>
 <script src="https://uicdn.toast.com/tui-grid/latest/tui-grid.js"></script>	
 
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= asset('css/style.css?version=' . $version) ?>">
<link rel="stylesheet" href="<?= asset('css/eworks.css?version=' . $version) ?>">
<script>
    // 로컬/서버 환경 자동 감지 및 baseUrl 설정
    <?php
    $host = $_SERVER['HTTP_HOST'];
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    
    // 로컬 환경 감지 (8440.local 또는 localhost)
    if (strpos($host, '8440.local') !== false || strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        $baseUrl = $protocol . '://' . $host;
    } else {
        // 서버 환경
        $baseUrl = 'https://8440.co.kr';
    }
    ?>
    window.baseUrl = '<?php echo $baseUrl; ?>';
    // console.log('Base URL set to:', window.baseUrl);
</script>
<?php
// 각 js 파일의 절대 경로 & 웹 경로
$js_files = [
    'js/common.js',
    'js/typingscript.js',
    'js/calinseung.js',
    'js/date.js',
    'js/index.js',
    'js/todolist.js',
    'js/cookie.js',
    'js/log.js',
];

// 파일별 수정 시각을 쿼리 파라미터로 추가 (캐시 강제 갱신)
foreach ($js_files as $file) {
    // 서버 상의 실제 위치
    $file_path = realpath(__DIR__ . '/' . $file);
    if ($file_path && file_exists($file_path)) {
        $ver = filemtime($file_path);
    } else {
        // 파일이 없으면 fallback to 전체 $version이나 1
        $ver = isset($version) ? $version : 1;
    }
    $src = asset($file . '?version=' . $ver);
    // typingscript.js는 별도 주석 삽입
    if (strpos($file, 'typingscript.js') !== false) {
        echo "<script src=\"{$src}\"></script>  <!-- typingscript.js 포함  글자 움직이면서 써지는 루틴 -->\n";
    } else if (strpos($file, 'log.js') !== false) {
        echo "<script src=\"{$src}\"></script>  <!-- 각 메뉴 방문기록 남김 -->\n";
    } else {
        echo "<script src=\"{$src}\"></script>\n";
    }
}
?>