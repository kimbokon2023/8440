<?php
/**
 * QC 사무실 정비 항목 등록/수정 폼
 */

require_once __DIR__ . '/../bootstrap.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? 0;

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 8) {
    echo "<script>alert('권한이 없습니다.'); history.back();</script>";
    exit;
}

$title_message = '정비 항목 등록/수정';
$tablename = 'myarea';

include includePath('load_header.php');

$mode = "insert";
$num = $_GET["num"] ?? "";

$mcno = "";
$mcname = "";
$mcspec = "";
$mcmaker = "";
$mcmain = "";
$mcsub = "";
$qrcode = "";

if ($num) {
    $mode = "update";
    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();
    
    $sql = "SELECT * FROM {$DB}.{$tablename} WHERE num = :num";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':num', $num, PDO::PARAM_INT);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $mcno = $row["mcno"];
        $mcname = $row["mcname"];
        $mcspec = $row["mcspec"];
        $mcmaker = $row["mcmaker"];
        $mcmain = $row["mcmain"];
        $mcsub = $row["mcsub"];
        $qrcode = $row["qrcode"];
    } else {
        echo "<script>alert('해당 항목 정보가 없습니다.'); history.back();</script>";
        exit;
    }
}
?>
<title><?= $title_message ?></title>
</head>
<body>

<?php require_once(includePath('myheader.php')); ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="card-title fw-bold m-0"><?= $title_message ?></h5>
        </div>
        <div class="card-body">
            <form name="mc_form" method="post" action="mc_process.php">
                <input type="hidden" name="mode" value="<?= $mode ?>">
                <input type="hidden" name="num" value="<?= $num ?>">

                <div class="mb-3 row">
                    <label for="mcname" class="col-sm-2 col-form-label fw-bold">항목명 <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="mcname" name="mcname" value="<?= htmlspecialchars($mcname) ?>" required placeholder="예: 회의실 A">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="mcspec" class="col-sm-2 col-form-label fw-bold">규격/위치</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="mcspec" name="mcspec" value="<?= htmlspecialchars($mcspec) ?>" placeholder="예: 2층 동측">
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="mcno" class="col-sm-2 col-form-label fw-bold">관리번호 (ID)</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="mcno" name="mcno" value="<?= htmlspecialchars($mcno) ?>" placeholder="시스템 내부 관리용 ID (예: room01)">
                        <div class="form-text">QR코드 이미지 파일명과 연동될 수 있습니다.</div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="mcmaker" class="col-sm-2 col-form-label fw-bold">제조사/브랜드</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="mcmaker" name="mcmaker" value="<?= htmlspecialchars($mcmaker) ?>">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="mcmain" class="col-sm-2 col-form-label fw-bold">담당자(정)</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="mcmain" name="mcmain" value="<?= htmlspecialchars($mcmain) ?>" placeholder="예: 홍길동">
                    </div>
                    <label for="mcsub" class="col-sm-2 col-form-label fw-bold">담당자(부)</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="mcsub" name="mcsub" value="<?= htmlspecialchars($mcsub) ?>" placeholder="예: 김철수">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="qrcode" class="col-sm-2 col-form-label fw-bold">QR코드 경로/URL</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="qrcode" name="qrcode" value="<?= htmlspecialchars($qrcode) ?>" placeholder="예: http://... 또는 파일명.png">
                    </div>
                </div>

                <div class="text-center pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg"></i> 저장</button>
                    <button type="button" class="btn btn-secondary px-4" onclick="location.href='mc_list.php'">목록으로</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include includePath('shop/footer.php'); ?>

</body>
</html>
