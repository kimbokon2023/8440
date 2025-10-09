<?php
require_once __DIR__ . '/../bootstrap.php';

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    header("Location:" . getBaseUrl() . "/login/login_form.php");
    exit;
}

include includePath('load_header.php');
?>

<title>출고일 기준 미청구리스트</title>
</head>

<?php

$search = $_REQUEST["search"] ?? "";
$fromdate = $_REQUEST["fromdate"] ?? substr(date("Y"), 0, 4) . "-01-01";
$todate = $_REQUEST["todate"] ?? substr(date("Y"), 0, 4) . "-12-31";
$Transtodate = date("Y-m-d", strtotime($todate . '+1 days'));
$includeDemanded = isset($_REQUEST["includeDemanded"]) ? true : false;  // 청구완료 포함 여부 확인

// bootstrap.php에서 이미 DB 연결됨

$orderby = "order by workday desc";

$sql = "SELECT * FROM mirae8440.work WHERE workday BETWEEN date('$fromdate') AND date('$Transtodate')";
if (!$includeDemanded) {
    $sql .= " AND (demand = '' or demand is NULL) ";
}
if ($search) {
    $sql .= " AND (workplacename LIKE '%$search%' OR firstordman LIKE '%$search%' OR secondordman LIKE '%$search%' OR chargedman LIKE '%$search%' OR delicompany LIKE '%$search%' OR hpi LIKE '%$search%' OR firstord LIKE '%$search%' OR secondord LIKE '%$search%' OR worker LIKE '%$search%' OR memo LIKE '%$search%')";
}
$sql .= " $orderby";

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();

    $data = [];
    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        foreach (['orderday', 'measureday', 'drawday', 'deadline', 'workday', 'endworkday', 'demand', 'startday', 'testday'] as $dateField) {
            if ($row[$dateField] == "0000-00-00" || $row[$dateField] == "1970-01-01" || !$row[$dateField]) {
                $row[$dateField] = "";
            } else {
                $row[$dateField] = date("Y-m-d", strtotime($row[$dateField]));
            }
        }

        $row['material'] = implode('', array_slice($row, array_search('material1', array_keys($row)), 6));

        include '_row.php';
        $customer_data = $row["customer"];
        $customer_object = json_decode($customer_data, true);
        if ($customer_object === null || empty($customer_object['image_url'])) {
            $confirm = '';
        } else {
            $confirm = '서명';
        }

        $data[] = [
            'num' => $row["num"],
            'workday' => $row["workday"],
            'firstord' => $row["firstord"],
            'secondord' => $row["secondord"],
            'workplacename' => $row["workplacename"],
            'address' => $row["address"],
            'material' => $row['material'],
            'chargedman' => $row["chargedman"],
            'chargedmantel' => $row["chargedmantel"],
            'firstordman' => $row["firstordman"],
            'firstordmantel' => $row["firstordmantel"],
            'hpi' => $row["hpi"],
            'startday' => $row["startday"],
            'testday' => $row["testday"],
            'worker' => $row["worker"],
            'measureday' => $row["measureday"],
            'widejamb' => $row["widejamb"],
            'normaljamb' => $row["normaljamb"],
            'smalljamb' => $row["smalljamb"],
            'confirm' => $confirm
        ];
    }
} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}
?>

<body>
    <form name="board_form" id="board_form" method="post" action="No_demandlist.php?mode=search">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex mb-3 mt-3 justify-content-center align-items-center">
                        <h5>시공완료 미청구 리스트</h5>
                    </div>

                    <div class="d-flex mb-1 mt-1 justify-content-center align-items-center">
					<input type="checkbox" id="includeDemanded" name="includeDemanded" <?= $includeDemanded ? 'checked' : '' ?>> 청구완료 포함 &nbsp;
                        <span id="showdate" class="btn btn-dark btn-sm">기간</span>&nbsp;
                        <div id="showframe" class="card">
                            <div class="card-header" style="padding:2px;">
                                기간 검색
                            </div>
                            <div class="card-body">
                                <button type="button" id="preyear" class="btn btn-primary btn-sm" onclick='pre_year()'>전년도</button>
                                <button type="button" id="three_month" class="btn btn-secondary btn-sm" onclick='three_month_ago()'>M-3월</button>
                                <button type="button" id="prepremonth" class="btn btn-secondary btn-sm" onclick='prepre_month()'>전전월</button>
                                <button type="button" id="premonth" class="btn btn-secondary btn-sm" onclick='pre_month()'>전월</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick='this_today()'>오늘</button>
                                <button type="button" id="thismonth" class="btn btn-dark btn-sm" onclick='this_month()'>당월</button>
                                <button type="button" id="thisyear" class="btn btn-dark btn-sm" onclick='this_year()'>당해년도</button>
                            </div>
                        </div>

                        <input type="date" id="fromdate" name="fromdate" class="form-control w120px" value="<?= $fromdate ?>" placeholder="기간 시작일"> &nbsp; ~ &nbsp;
                        <input type="date" id="todate" name="todate" class="form-control w120px" value="<?= $todate ?>" placeholder="기간 끝"> &nbsp;                        

                        <button type="button" id="searchBtn" class="btn btn-dark btn-sm me-2"><i class="bi bi-search"></i></button>
                        <button type="button" class="btn btn-dark btn-sm me-2" id="savePDFBtn">시공확인서 PDF 저장</button>
                        <button type="button" class="btn btn-dark btn-sm me-2" id="downloadcsvBtn">CSV 엑셀 다운로드</button>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-hover" id="myTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center"><input type="checkbox" id="select-all"></th>
                                <th class="text-center">출고일</th>
                                <th class="text-center">원청</th>
                                <th class="text-center">발주처</th>
                                <th class="text-center">시공<br>소장</th>
                                <th class="text-center">현장명</th>
                                <th class="text-center">확인서명</th>
                                <th class="text-center">막</th>
                                <th class="text-center">멍</th>
                                <th class="text-center">쪽</th>
                                <th class="text-center">재질</th>
                                <th class="text-center">담당자PM</th>
                                <th class="text-center">담당전번</th>
                                <th class="text-center">현장소장</th>
                                <th class="text-center">소장전번</th>
                                <th class="text-center">착공일</th>
                                <th class="text-center"  style="display:none;"  >rec No.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $row): ?>
                                <tr >
                                    <td class="text-center"><input type="checkbox" class="record-checkbox" value="<?= $row['num'] ?>"></td>
                                    <td class="text-center"><?= $row['workday'] ?></td>
                                    <td class="text-center"><?= $row['firstord'] ?></td>
                                    <td class="text-center"><?= $row['secondord'] ?></td>
                                    <td class="text-center"><?= $row['worker'] ?></td>
                                    <td class="text-start" onclick="openPopup('view.php?num=<?= $row['num'] ?>')"><?= $row['workplacename'] ?></td>
                                    <td class="text-center"><?= $row['confirm'] ?></td>
                                    <td class="text-center"><?= $row['widejamb'] ?></td>
                                    <td class="text-center"><?= $row['normaljamb'] ?></td>
                                    <td class="text-center"><?= $row['smalljamb'] ?></td>
                                    <td class="text-start"><?= $row['material'] ?></td>
                                    <td class="text-center"><?= $row['firstordman'] ?></td>
                                    <td class="text-center"><?= $row['firstordmantel'] ?></td>
                                    <td class="text-center"><?= $row['chargedman'] ?></td>
                                    <td class="text-center"><?= $row['chargedmantel'] ?></td>
                                    <td class="text-center"><?= $row['startday'] ?></td>
                                    <td class="text-center" style="display:none;" ><?= $row['num'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>

<script>
var data = <?php echo json_encode($data); ?>;
var dataTable; // DataTables 인스턴스 전역 변수
var nodemandpageNumber; // 현재 페이지 번호 저장을 위한 전역 변수

$(document).ready(function() {			
    // DataTables 초기 설정
    dataTable = $('#myTable').DataTable({
        "paging": true,
        "ordering": true,
        "searching": true,
        "pageLength": 200,
        "lengthMenu": [50, 100, 200, 500, 1000],
        "language": {
            "lengthMenu": "Show _MENU_ entries",
            "search": "Live Search:"
        },
        "order": [[1, 'desc']] // 이것이 첫화면의 정렬을 정하는 부분이다.
    });

    // 페이지 번호 복원 (초기 로드 시)
    var savedPageNumber = getCookie('nodemandpageNumber');
    if (savedPageNumber) {
        dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
    }

    // 페이지 변경 이벤트 리스너
    dataTable.on('page.dt', function() {
        var nodemandpageNumber = dataTable.page.info().page + 1;
        setCookie('nodemandpageNumber', nodemandpageNumber, 10); // 쿠키에 페이지 번호 저장
    });

    // 페이지 길이 셀렉트 박스 변경 이벤트 처리
    $('#myTable_length select').on('change', function() {
        var selectedValue = $(this).val();
        dataTable.page.len(selectedValue).draw(); // 페이지 길이 변경 (DataTable 파괴 및 재초기화 없이)

        // 변경 후 현재 페이지 번호 복원
        savedPageNumber = getCookie('nodemandpageNumber');
        if (savedPageNumber) {
            dataTable.page(parseInt(savedPageNumber) - 1).draw(false);
        }
    });

    // 검색 버튼 클릭 이벤트
    $("#searchBtn").click(function() {
        document.getElementById('board_form').submit();
    });

    // 전체 선택 체크박스 클릭 이벤트
    $("#select-all").click(function() {
        var isChecked = $(this).prop("checked");
        $(".record-checkbox").prop("checked", isChecked);
    });

    // CSV 다운로드 버튼 클릭 이벤트
    $("#downloadcsvBtn").click(function() {
        let csvContent = "data:text/csv;charset=utf-8,\uFEFF";
        csvContent += "선택,번호,출고일,원청,발주처,시공소장,현장명,막,멍,쪽,재질,담당자PM,담당전번,현장소장,소장전번,착공일,서명 상태\n";

        $(".record-checkbox:checked").each(function() {
            var rowIndex = $(this).closest('tr').index();
            var row = data[rowIndex];
            let rowStr = (rowIndex + 1) + ',';
            Object.keys(row).forEach((key, i) => {
                let cell = String(row[key]).replace(/undefined/gi, "").replace(/#/gi, " ").replace(/,/gi, "-");
                rowStr += cell + (i < Object.keys(row).length - 1 ? ',' : '');
            });
            csvContent += rowStr + "\n";
        });

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "miraeCSV_Nodemand.csv");
        document.body.appendChild(link);
        link.click();
    });

    // PDF 저장 버튼 클릭 이벤트
    $("#savePDFBtn").click(function() {
        var selectedRecords = [];
        $(".record-checkbox:checked").each(function() {
            selectedRecords.push($(this).val());
        });

        if (selectedRecords.length > 0) {
            generatePDFs(selectedRecords);
        } else {
            alert('하나 이상의 레코드를 선택하세요.');
        }
    });

    function generatePDFs(recordIds) {
        var popup = window.open("", "popupForm", "width=1000,height=800");
        var form = popup.document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", "invoice_content.php");

        recordIds.forEach(function(recordId) {
            var hiddenField = popup.document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "recordIds[]");
            hiddenField.setAttribute("value", recordId);
            form.appendChild(hiddenField);
        });

        popup.document.body.appendChild(form);
        form.submit();
    }

    function openPopup(url) {
        window.open(url, 'write_form', 'width=1900,height=900');
    }

    window.openPopup = openPopup; // Ensure openPopup is globally accessible
});

</script>
</body>
</html>
