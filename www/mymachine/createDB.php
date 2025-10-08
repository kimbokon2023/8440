<?php
require_once __DIR__ . '/../bootstrap.php';
// 보강: 부트스트랩 단계에서 연결 실패 시 재시도
if (!isset($pdo) || !$pdo) {
    require_once includePath('lib/mydb.php');
    $pdo = db_connect();
}

// 1) 장비 점검 리스트 읽기 (mymclist)
$checkdate_arr = [];
try {
    $stmh = $pdo->query("SELECT checkdate FROM mirae8440.mymclist");
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $checkdate_arr[] = $row["checkdate"];
    }
} catch (PDOException $e) {
    exit("오류(점검리스트 읽기): " . $e->getMessage());
}

// 2) 장비 마스터 정보 읽기 (mymc)
$mcno_arr   = [];
$mcmain_arr = [];
$mcsub_arr  = [];
try {
    $stmh = $pdo->query("SELECT mcno, mcmain, mcsub FROM mirae8440.mymc ORDER BY num");
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        $mcno_arr[]   = $row["mcno"];
        $mcmain_arr[] = $row["mcmain"];
        $mcsub_arr[]  = $row["mcsub"];
    }
} catch (PDOException $e) {
    exit("오류(마스터 읽기): " . $e->getMessage());
}

$todayStr = date("Y-m-d");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>장비 점검 자동 생성</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <form id="Form1" name="Form1">
    <input type="hidden" id="table"    name="table"    value="mymclist">
    <input type="hidden" id="command"  name="command">
    <input type="hidden" id="fieldarr" name="fieldarr[]">
    <input type="hidden" id="arr"      name="arr[]">
  </form>

<script>
  // 한 번만 실행 가드
  let hasCreated = false;

  // YYYY-MM-DD 포맷
  function dateFormat(date) {
    const y = date.getFullYear();
    const m = ('0'+(date.getMonth()+1)).slice(-2);
    const d = ('0'+date.getDate()).slice(-2);
    return `${y}-${m}-${d}`;
  }

  // 요일 한글
  function getDayOfWeek(date) {
    return ['일','월','화','수','목','금','토'][date.getDay()];
  }

  // 해당 연·월의 모든 금요일을 [{weekFriday: "YYYY-MM-DD"}, ...] 형태로 반환
  function searchFriday(year, month) {
    const fridays = [];
    const d = new Date(year, month-1, 1);
    while (d.getMonth() === month-1) {
      if (d.getDay() === 5) {
        fridays.push({ weekFriday: dateFormat(new Date(d)) });
      }
      d.setDate(d.getDate() + 1);
    }
    return fridays;
  }

  $(document).ready(function() {
    const checkdateArr = <?php echo json_encode($checkdate_arr); ?>;
    const mcnoArr      = <?php echo json_encode($mcno_arr);      ?>;
    const mcmainArr    = <?php echo json_encode($mcmain_arr);    ?>;
    const mcsubArr     = <?php echo json_encode($mcsub_arr);     ?>;
    const todayStr     = '<?php echo $todayStr; ?>';

    function createDB() {
      if (hasCreated) return;
      hasCreated = true;

      // 이미 오늘 데이터가 있으면 중단
      if (checkdateArr.includes(todayStr)) return;

      const today = new Date(todayStr);
      // 금요일이 아니면 중단
      if (getDayOfWeek(today) !== '금') return;

      // 이번 달 금요일들
      const fridays = searchFriday(today.getFullYear(), today.getMonth()+1);

      // ── 주간 체크 (매주 금요일)
      mcnoArr.forEach((mcno, i) => {
        writeDB(
          ['checkdate','item','term','writer','writer2'],
          [todayStr, mcno, '주간', mcmainArr[i], mcsubArr[i]]
        );
      });

      // ── 1개월 체크 (3번째 금요일)
      if (fridays[2] && fridays[2].weekFriday === todayStr) {
        mcnoArr.forEach((mcno, i) => {
          writeDB(
            ['checkdate','item','term','writer','writer2'],
            [todayStr, mcno, '1개월', mcmainArr[i], mcsubArr[i]]
          );
        });
      }

      // ── 2개월 체크 (짝수 월의 3번째 금요일)
      const curMonth = todayStr.substr(5,2);
      if (['02','04','06','08','10','12'].includes(curMonth)
          && fridays[2] && fridays[2].weekFriday === todayStr) {
        mcnoArr.forEach((mcno, i) => {
          writeDB(
            ['checkdate','item','term','writer','writer2'],
            [todayStr, mcno, '2개월', mcmainArr[i], mcsubArr[i]]
          );
        });
      }

      // ── 6개월 체크 (6·12월의 3번째 금요일)
      if (['06','12'].includes(curMonth)
          && fridays[2] && fridays[2].weekFriday === todayStr) {
        mcnoArr.forEach((mcno, i) => {
          writeDB(
            ['checkdate','item','term','writer','writer2'],
            [todayStr, mcno, '6개월', mcmainArr[i], mcsubArr[i]]
          );
        });
      }
    }

    // Ajax로 proDB_arr.php에 insert 요청
    function writeDB(fnArr, fvArr) {
      $('#command').val('insert');
      $('#table').val('mymclist');  // 올바른 테이블명으로 수정
      $('#fieldarr').val(fnArr.join(','));
      $('#arr').val(fvArr.join(','));
      $.ajax({
        url: "<?= url('proDB_arr.php') ?>",
        type: "post",
        data: $("#Form1").serialize(),
        dataType: "json"
      }).done(data => console.log(data))
        .fail((jqxhr, status, error) => console.error(status, error));
    }

    // 페이지 로드 후 10초 뒤 한 번만 실행
    setTimeout(createDB, 10000);
  });
</script>
</body>
</html>