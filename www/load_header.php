<?php\nrequire_once __DIR__ . '/common/functions.php';
require_once(includePath('session.php'));

// header('Cache-Control: no cache');
// 자바스크립트 자동 업데이트를 위한 version 설정

require_once(includePath('common.php'));

$now = date("Y-m-d",time()) ;  
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 필요한 데이터를 담을 배열 초기화
$firstStep = array();
$firstStepID = array(); // 추가: 결재권한 ID를 저장할 배열 초기화

$admin = 0;
$ework_approval = 0 ;

try {
    $sql = "SELECT * FROM mirae8440.member WHERE part IN ('제조파트', '지원파트')";
    $stmh = $pdo->prepare($sql);
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
<meta property="og:type" content="미래기업 통합정보시스템">
<meta property="og:title" content="미래기업 통합정보시스템">
<meta property="og:url" content="https://8440.co.kr">
<meta property="og:description" content="정확한 업무처리 포탈!">
<meta property="og:image" content="<?$root_dir?>/img/miraethumbnail.jpg"> 
 
<script src="<?$root_dir?>/js/jquery.min.js"></script>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.10/sweetalert2.min.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> 
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js" > </script>
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

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.5.3/signature_pad.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="<?$root_dir?>/css/style.css?version=<?=$version?>">
<link rel="stylesheet" href="<?$root_dir?>/css/eworks.css?version=<?=$version?>">
<script src="<?$root_dir?>/js/common.js?version=<?=$version?>"></script>
<script src="<?$root_dir?>/js/typingscript.js"></script>  <!-- typingscript.js 포함  글자 움직이면서 써지는 루틴 -->
<script src="<?$root_dir?>/js/calinseung.js?version=<?=$version?>"></script>
<script src="<?$root_dir?>/js/date.js?version=<?=$version?>"></script>
<script src="<?$root_dir?>/js/index.js?version=<?=$version?>"></script>
<script src="<?$root_dir?>/js/todolist.js?version=<?=$version?>"></script>
<script src="<?$root_dir?>/js/cookie.js?version=<?=$version?>"></script>
<script src="<?$root_dir?>/js/log.js?version=<?=$version?>"></script>  <!-- 각 메뉴 방문기록 남김 -->
