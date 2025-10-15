<?php
/**
 * 장비 점검 자동 생성 페이지
 * 매주 금요일에 자동으로 점검 데이터 생성
 * 로컬 및 서버 환경 모두 지원
 */

require_once __DIR__ . '/../bootstrap.php';

// 부트스트랩 단계에서 연결 실패 시 재시도
if (!isset($pdo) || !$pdo) {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();
}

// 동적 URL 생성
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = "{$protocol}://{$host}";

// 1) 장비 점검 리스트 읽기 (mymclist)
$checkdate_arr = [];
try {
    $sql = "SELECT checkdate FROM mirae8440.mymclist";
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $checkdate_arr[] = $row["checkdate"];
    }
} catch (PDOException $ex) {
    error_log("장비 점검 리스트 읽기 오류: " . $ex->getMessage());
    exit("오류(점검리스트 읽기): " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
}

// 2) 장비 마스터 정보 읽기 (mymc)
$mcno_arr = [];
$mcmain_arr = [];
$mcsub_arr = [];
try {
    $sql = "SELECT mcno, mcmain, mcsub FROM mirae8440.mymc ORDER BY num";
    $stmh = $pdo->query($sql);
    
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $mcno_arr[] = $row["mcno"];
        $mcmain_arr[] = $row["mcmain"];
        $mcsub_arr[] = $row["mcsub"];
    }
} catch (PDOException $ex) {
    error_log("장비 마스터 정보 읽기 오류: " . $ex->getMessage());
    exit("오류(마스터 읽기): " . htmlspecialchars($ex->getMessage(), ENT_QUOTES, 'UTF-8'));
}

$todayStr = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>장비 점검 자동 생성</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="Form1" name="Form1">
        <input type="hidden" id="table" name="table" value="mymclist">
        <input type="hidden" id="command" name="command">
        <input type="hidden" id="fieldarr" name="fieldarr[]">
        <input type="hidden" id="arr" name="arr[]">
    </form>

<script type="text/javascript">
(function() {
    'use strict';
    
    // 한 번만 실행 가드
    var hasCreated = false;
    
    /**
     * 날짜를 YYYY-MM-DD 포맷으로 변환
     */
    function dateFormat(date) {
        var y = date.getFullYear();
        var m = ('0' + (date.getMonth() + 1)).slice(-2);
        var d = ('0' + date.getDate()).slice(-2);
        return y + '-' + m + '-' + d;
    }
    
    /**
     * 요일을 한글로 반환
     */
    function getDayOfWeek(date) {
        var days = ['일', '월', '화', '수', '목', '금', '토'];
        return days[date.getDay()];
    }
    
    /**
     * 해당 연·월의 모든 금요일을 배열로 반환
     * @param {number} year - 연도
     * @param {number} month - 월 (1-12)
     * @returns {Array} [{weekFriday: "YYYY-MM-DD"}, ...]
     */
    function searchFriday(year, month) {
        var fridays = [];
        var d = new Date(year, month - 1, 1);
        
        while (d.getMonth() === month - 1) {
            if (d.getDay() === 5) { // 금요일
                fridays.push({ weekFriday: dateFormat(new Date(d)) });
            }
            d.setDate(d.getDate() + 1);
        }
        
        return fridays;
    }
    
    $(document).ready(function() {
        // PHP 데이터를 JavaScript 변수로 전달
        var checkdateArr = <?php echo json_encode($checkdate_arr, JSON_UNESCAPED_UNICODE); ?>;
        var mcnoArr = <?php echo json_encode($mcno_arr, JSON_UNESCAPED_UNICODE); ?>;
        var mcmainArr = <?php echo json_encode($mcmain_arr, JSON_UNESCAPED_UNICODE); ?>;
        var mcsubArr = <?php echo json_encode($mcsub_arr, JSON_UNESCAPED_UNICODE); ?>;
        var todayStr = <?php echo json_encode($todayStr, JSON_UNESCAPED_UNICODE); ?>;
        var baseUrl = <?php echo json_encode($base_url, JSON_UNESCAPED_UNICODE); ?>;
        
        /**
         * 장비 점검 데이터 자동 생성
         */
        function createDB() {
            if (hasCreated) {
                console.log('이미 생성됨');
                return;
            }
            hasCreated = true;
            
            // 이미 오늘 데이터가 있으면 중단
            if (checkdateArr.indexOf(todayStr) !== -1) {
                console.log('오늘 데이터가 이미 존재합니다.');
                return;
            }
            
            var today = new Date(todayStr);
            
            // 금요일이 아니면 중단
            if (getDayOfWeek(today) !== '금') {
                console.log('금요일이 아닙니다.');
                return;
            }
            
            console.log('장비 점검 데이터 생성 시작...');
            
            // 이번 달 금요일들
            var fridays = searchFriday(today.getFullYear(), today.getMonth() + 1);
            
            // ── 주간 체크 (매주 금요일)
            for (var i = 0; i < mcnoArr.length; i++) {
                writeDB(
                    ['checkdate', 'item', 'term', 'writer', 'writer2'],
                    [todayStr, mcnoArr[i], '주간', mcmainArr[i], mcsubArr[i]]
                );
            }
            
            // ── 1개월 체크 (3번째 금요일)
            if (fridays[2] && fridays[2].weekFriday === todayStr) {
                console.log('1개월 점검 데이터 생성 중...');
                for (var i = 0; i < mcnoArr.length; i++) {
                    writeDB(
                        ['checkdate', 'item', 'term', 'writer', 'writer2'],
                        [todayStr, mcnoArr[i], '1개월', mcmainArr[i], mcsubArr[i]]
                    );
                }
            }
            
            // ── 2개월 체크 (짝수 월의 3번째 금요일)
            var curMonth = todayStr.substr(5, 2);
            if (['02', '04', '06', '08', '10', '12'].indexOf(curMonth) !== -1
                && fridays[2] && fridays[2].weekFriday === todayStr) {
                console.log('2개월 점검 데이터 생성 중...');
                for (var i = 0; i < mcnoArr.length; i++) {
                    writeDB(
                        ['checkdate', 'item', 'term', 'writer', 'writer2'],
                        [todayStr, mcnoArr[i], '2개월', mcmainArr[i], mcsubArr[i]]
                    );
                }
            }
            
            // ── 6개월 체크 (6·12월의 3번째 금요일)
            if (['06', '12'].indexOf(curMonth) !== -1
                && fridays[2] && fridays[2].weekFriday === todayStr) {
                console.log('6개월 점검 데이터 생성 중...');
                for (var i = 0; i < mcnoArr.length; i++) {
                    writeDB(
                        ['checkdate', 'item', 'term', 'writer', 'writer2'],
                        [todayStr, mcnoArr[i], '6개월', mcmainArr[i], mcsubArr[i]]
                    );
                }
            }
        }
        
        /**
         * Ajax로 proDB_arr.php에 insert 요청
         * @param {Array} fnArr - 필드명 배열
         * @param {Array} fvArr - 필드값 배열
         */
        function writeDB(fnArr, fvArr) {
            $('#command').val('insert');
            $('#table').val('mymclist');
            $('#fieldarr').val(fnArr.join(','));
            $('#arr').val(fvArr.join(','));
            
            $.ajax({
                url: baseUrl + "/proDB_arr.php",
                type: "post",
                data: $("#Form1").serialize(),
                dataType: "json"
            }).done(function(data) {
                console.log('저장 성공:', data);
            }).fail(function(jqxhr, status, error) {
                console.error('저장 실패:', status, error);
            });
        }
        
        // 페이지 로드 후 10초 뒤 한 번만 실행
        setTimeout(function() {
            createDB();
        }, 10000);
    });
    
})();
</script>

</body>
</html>
