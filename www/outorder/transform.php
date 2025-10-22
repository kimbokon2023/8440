<?php
require_once __DIR__ . '/../bootstrap.php';

if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {
    sleep(1);
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

// 요청 변수 초기화
$mode = $_REQUEST["mode"] ?? '';
$num = $_REQUEST["num"] ?? '';
$page = $_REQUEST["page"] ?? 1;
$search = $_REQUEST["search"] ?? '';
$find = $_REQUEST["find"] ?? '';
$process = $_REQUEST["process"] ?? '전체';

if(isset($_REQUEST["check"]))
    $check=$_REQUEST["check"];
elseif(isset($_POST["check"]))
    $check=$_POST["check"];
else
    $check='0';

$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$yearcheckbox = $_REQUEST["yearcheckbox"] ?? '0';
$year = $_REQUEST["year"] ?? date('Y');
$cursort = $_REQUEST["cursort"] ?? $_POST["cursort"] ?? '0';
$sortof = $_REQUEST["sortof"] ?? $_POST["sortof"] ?? '0';
$stable = $_REQUEST["stable"] ?? $_POST["stable"] ?? '0';

$outputdate = date("Y-m-d", time());
if($outputdate!="") {
    $week = array("(일)", "(월)", "(화)", "(수)", "(목)", "(금)", "(토)");
    $outputdate = $outputdate . $week[date('w', strtotime($outputdate))];
}

try{
    $sql = "select * from mirae8440.outorder where num = ? ";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1,$num,PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();

    if($count<1){
        print "검색결과가 없습니다.<br>";
    }else{
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    }

    // 기본 정보
    $num=$row["num"];
    $checkstep=$row["checkstep"];
    $workplacename=$row["workplacename"];
    $address=$row["address"];
    $worker=$row["worker"];
    $secondord=$row["secondord"];
    $firstord=$row["firstord"];
    $startday=$row["startday"];
    $chargedman=$row["chargedman"];
    $chargedmantel=$row["chargedmantel"];
    $memo=$row["memo"];
    $submemo=$row["submemo"];
    $deadline=$row["deadline"];
    $delivery=$row["delivery"];
    $delipay=$row["delipay"];
    $delitext = $delivery . ' ' . $delipay;

    // 타입 및 수량
    $type1=$row["type1"];
    $inseung1=$row["inseung1"];
    $car_inside1=$row["car_inside1"];
    $su=(int)$row["su"];
    $lc_su=(int)$row["lc_su"];
    $etc_su=(int)$row["etc_su"];
    $air_su=(int)$row["air_su"];

    // 아이템 정보
    for($i=0; $i<10; $i++) {
        $items = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];
        $itemName = $items[$i];
        ${$itemName.'_item1'} = $row["{$itemName}_item1"];
        ${$itemName.'_item2'} = $row["{$itemName}_item2"];
        ${$itemName.'_item3'} = $row["{$itemName}_item3"];
        ${$itemName.'_item4'} = $row["{$itemName}_item4"];
    }

    // 타입 2-10
    for($i=2; $i<=10; $i++) {
        ${'type'.$i} = $row["type$i"];
        ${'inseung'.$i} = $row["inseung$i"];
        ${'car_inside'.$i} = $row["car_inside$i"];
        ${'comment'.$i} = $row["comment$i"];
    }
    $comment1 = $row["comment1"];

    // 날짜 변수들을 $row에서 추출
    $workday = $row["workday"] ?? '';
    $demand = $row["demand"] ?? '';
    $orderday = $row["orderday"] ?? '';
    $testday = $row["testday"] ?? '';
    $lc_draw = $row["lc_draw"] ?? '';
    $lclaser_date = $row["lclaser_date"] ?? '';
    $lcbending_date = $row["lcbending_date"] ?? '';
    $lcwelding_date = $row["lcwelding_date"] ?? '';
    $lcpainting_date = $row["lcpainting_date"] ?? '';
    $lcassembly_date = $row["lcassembly_date"] ?? '';
    $main_draw = $row["main_draw"] ?? '';
    $eunsung_make_date = $row["eunsung_make_date"] ?? '';
    $eunsung_laser_date = $row["eunsung_laser_date"] ?? '';
    $mainbending_date = $row["mainbending_date"] ?? '';
    $mainwelding_date = $row["mainwelding_date"] ?? '';
    $mainpainting_date = $row["mainpainting_date"] ?? '';
    $mainassembly_date = $row["mainassembly_date"] ?? '';
    $order_date1 = $row["order_date1"] ?? '';
    $order_date2 = $row["order_date2"] ?? '';
    $order_date3 = $row["order_date3"] ?? '';
    $order_date4 = $row["order_date4"] ?? '';
    $order_input_date1 = $row["order_input_date1"] ?? '';
    $order_input_date2 = $row["order_input_date2"] ?? '';
    $order_input_date3 = $row["order_input_date3"] ?? '';
    $order_input_date4 = $row["order_input_date4"] ?? '';

    // 날짜 변환
    $workday=trans_date($workday);
    $startday=trans_date($startday);
    $demand=trans_date($demand);
    $orderday=trans_date($orderday);
    $deadline=trans_date($deadline);
    $testday=trans_date($testday);
    $lc_draw=trans_date($lc_draw);
    $lclaser_date=trans_date($lclaser_date);
    $lcbending_date=trans_date($lcbending_date);
    $lcwelding_date=trans_date($lcwelding_date);
    $lcpainting_date=trans_date($lcpainting_date);
    $lcassembly_date=trans_date($lcassembly_date);
    $main_draw=trans_date($main_draw);
    $eunsung_make_date=trans_date($eunsung_make_date);
    $eunsung_laser_date=trans_date($eunsung_laser_date);
    $mainbending_date=trans_date($mainbending_date);
    $mainwelding_date=trans_date($mainwelding_date);
    $mainpainting_date=trans_date($mainpainting_date);
    $mainassembly_date=trans_date($mainassembly_date);
    $order_date1=trans_date($order_date1);
    $order_date2=trans_date($order_date2);
    $order_date3=trans_date($order_date3);
    $order_date4=trans_date($order_date4);
    $order_input_date1=trans_date($order_input_date1);
    $order_input_date2=trans_date($order_input_date2);
    $order_input_date3=trans_date($order_input_date3);
    $order_input_date4=trans_date($order_input_date4);

    // 시공자 전화번호
    $workertel = '';
    switch ($worker) {
        case "김운호": $workertel = "010-9322-7626"; break;
        case "김상훈": $workertel = "010-6622-2200"; break;
        case "이만희": $workertel = "010-6866-5030"; break;
        case "유영": $workertel = "010-5838-5948"; break;
        case "추영덕": $workertel = "010-6325-4280"; break;
        case "김지암": $workertel = "010-3235-5850"; break;
        case "손상민": $workertel = "010-4052-8930"; break;
        case "지영복": $workertel = "010-6338-9718"; break;
        case "김한준": $workertel = "010-4445-7515"; break;
        case "민경채": $workertel = "010-2078-7238"; break;
        case "이용휘": $workertel = "010-9453-8612"; break;
        case "박경호": $workertel = "010-3405-6669"; break;
        case "조형영": $workertel = "010-2419-2574"; break;
        case "김진섭": $workertel = "010-6524-3325"; break;
        case "최양원": $workertel = "010-5426-3475"; break;
    }

    // 배열 준비
    $text=array();
    $item=array();
    $spec=array();
    $carsize=array();
    $item_memo=array();
    $textnum=array();
    $textset=array();

    if($firstord=='덴크리')
        $firstord='덴크리무에이링';

    // 배열 채우기
    for($i=0; $i<10; $i++) {
        $text[$i] = ${'type'.($i+1)};
        $carsize[$i] = ${'car_inside'.($i+1)};
        $item_memo[$i] = ${'comment'.($i+1)};

        $items = ['first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth'];
        $item[$i] = ${$items[$i].'_item1'};
        $spec[$i] = ${$items[$i].'_item2'};
        $textnum[$i] = ${$items[$i].'_item3'};
        $textset[$i] = ${$items[$i].'_item4'};
    }

} catch (PDOException $Exception) {
    print "오류: ".$Exception->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>외주발주 출고증 정보 입력</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

    <!-- jQuery & jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: transparent !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            background: rgba(255, 255, 255, 0.98);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border: none;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-header h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .required-field {
            color: #dc3545;
            margin-left: 3px;
        }

        .btn {
            border-radius: 8px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #654a8e 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .info-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .info-box strong {
            color: #667eea;
        }

        .item-row {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .item-row:hover {
            border-color: #667eea;
            background: #fff;
        }

        .item-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 15px;
        }

        .special-notice {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(255, 193, 7, 0.1) 100%);
            border-left: 4px solid #dc3545;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .special-notice .form-control {
            font-size: 1.2rem;
            color: #dc3545;
            font-weight: 600;
            border: 2px solid #dc3545;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        @media print {
            .action-buttons, .card-header {
                display: none;
            }
            .card {
                box-shadow: none;
            }
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 15px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <form name="board_form" id="board_form" method="post" action="invoice.php?num=<?=$num?>" enctype="multipart/form-data" onkeydown="return captureReturnKey(event)">

            <input type="hidden" name="memo" value="<?=$memo?>">
            <input type="hidden" name="delitext" value="<?=$delitext?>">
            <input type="hidden" name="deadline" value="<?=$deadline?>">
            <input type="hidden" name="startday" value="<?=$startday?>">

            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-printer"></i> 인쇄하기
                </button>
                <button type="button" class="btn btn-secondary" onclick="window.close();">
                    <i class="bi bi-x-circle"></i> 창 닫기
                </button>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="bi bi-truck"></i>
                        외주발주 출고증 정보 입력
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">(귀중)<span class="required-field">*</span></label>
                            <input type="text" name="firstord" value="<?=$firstord?>" class="form-control" placeholder="회사명을 입력하세요" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">하차일시<span class="required-field">*</span></label>
                            <input type="text" name="outputdate" value="<?=$outputdate?>" class="form-control text-danger fw-bold" placeholder="하차일시" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">발주(업체)<span class="required-field">*</span></label>
                            <input type="text" name="secondord" value="<?=$secondord?>" class="form-control" placeholder="발주처(업체명)" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">현장명<span class="required-field">*</span></label>
                            <input type="text" name="workplacename" value="<?=$workplacename?>" class="form-control" placeholder="현장명" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">현장주소</label>
                            <input type="text" name="address" value="<?=$address?>" class="form-control" placeholder="현장주소">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">받으실 분</label>
                            <input type="text" name="chargedman" value="<?=$chargedman?>" class="form-control" placeholder="담당자명">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">받으실 분 연락처</label>
                            <input type="text" name="chargedmantel" id="chargedmantel" value="<?=$chargedmantel?>" class="form-control" placeholder="연락처">
                        </div>
                    </div>

                    <div class="info-box mt-4">
                        <strong><i class="bi bi-info-circle"></i> 자동 계산 확인</strong> - 아래 항목들이 자동으로 계산됩니다.
                    </div>

                    <?php for($i=0; $i<10; $i++): ?>
                    <div class="item-row">
                        <div class="d-flex align-items-start">
                            <div class="item-number"><?= $i+1 ?></div>
                            <div class="row g-2 flex-fill">
                                <div class="col-md-2">
                                    <label class="form-label small">타입</label>
                                    <input type="text" name="text[]" value="<?= $text[$i] ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">품목</label>
                                    <input type="text" name="item[]" value="<?= $item[$i] ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">규격</label>
                                    <input type="text" name="spec[]" value="<?= $spec[$i] ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label small">수량</label>
                                    <input type="text" name="textnum[]" value="<?= $textnum[$i] ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label small">단위</label>
                                    <input type="text" name="textset[]" value="<?= $textset[$i] ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small">차량크기</label>
                                    <input type="text" name="carsize[]" value="<?= $carsize[$i] ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small">비고</label>
                                    <input type="text" name="item_memo[]" value="<?= $item_memo[$i] ?>" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>

                    <div class="special-notice">
                        <label class="form-label text-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i> 특이사항 메모
                        </label>
                        <input type="text" name="submemo" id="submemo" value="<?=$submemo?>" class="form-control" placeholder="특이사항을 입력하세요">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
    $(function() {
        $("#id_of_the_component").datepicker({ dateFormat: 'yy-mm-dd' });
    });

    function captureReturnKey(e) {
        if(e.keyCode==13 && e.srcElement.type != 'textarea')
            return false;
    }
    </script>
</body>
</html>
