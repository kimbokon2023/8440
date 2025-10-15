<?php
/**
 * 회사 조직도 페이지 (Google Charts 사용)
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));

// 세션 변수 초기화 (?? '' 형태)
$level = $_SESSION["level"] ?? 999;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? 'mirae8440';

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";
$WebSite = $base_url . '/';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(2);
    header("Location: {$base_url}/login/login_form.php");
    exit;
}

// 캐시 방지 헤더
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

include getDocumentRoot() . "/common.php";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <title>회사 조직도</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        #org_chart_div {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div id="org_chart_div"></div>

    <script type="text/javascript">
    (function() {
        'use strict';
        
        // Google Charts 로드
        google.charts.load('current', {packages: ["orgchart"]});
        google.charts.setOnLoadCallback(drawChart);
        
        /**
         * 조직도 그리기 함수
         */
        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');
            
            // 조직도 데이터 추가
            data.addRows([
                ['소현철 대표', '', 'CEO/President'],
                ['관리/영업지원', '소현철 대표', '지원파트'],
                ['설계', '소현철 대표', '지원파트'],
                ['기업전담부서', '소현철 대표', '지원파트'],
                ['이경묵 공장장', '소현철 대표', '제조/생산'],
                
                ['총괄 최장중 이사', '관리/영업지원', ''],
                ['영업관리 조경임 부장', '관리/영업지원', ''],
                ['총무/경리 소민지 사원', '관리/영업지원', ''],
                
                ['설계 이미래 과장', '설계', ''],
                ['설계 이소정 사원', '설계', ''],
                
                ['연구 김보곤 실장', '기업전담부서', ''],
                ['연구 안현섭 차장', '기업전담부서', ''],
                
                ['절곡 조성원 부장', '이경묵 공장장', ''],
                ['절곡 김영무 과장', '이경묵 공장장', ''],
                ['가공 라나 과장', '이경묵 공장장', ''],
                ['가공 까심 사원', '이경묵 공장장', ''],
                ['가공 샤질 사원', '이경묵 공장장', ''],
                ['가공 팀 사원', '이경묵 공장장', ''],
                ['조립 권영철 부장', '이경묵 공장장', ''],
                ['조립 안병길 실장', '이경묵 공장장', ''],
                ['조립 김수로 대리', '이경묵 공장장', ''],
                ['조립 불한 사원', '이경묵 공장장', '']
            ]);
            
            var chart = new google.visualization.OrgChart(document.getElementById('org_chart_div'));
            chart.draw(data, {allowHtml: true});
        }
        
    })();
    </script>
</body>
</html>
