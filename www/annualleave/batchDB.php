<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? '';
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://")
        . $_SERVER['HTTP_HOST']
        . "/login/login_form.php");
    exit;
}

$tablename = 'eworks';
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title> 연차 사용 리스트 </title>
<style>
    /* 모바일 최적화 */
    @media (max-width: 768px) {
        /* body와 html의 width 제한 */
        html, body {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            font-size: 16px !important;
        }

        /* 컨테이너 모바일 최적화 */
        .container,
        .container-fluid {
            max-width: 100vw !important;
            padding: 5px !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        /* 카드 모바일 최적화 */
        .card {
            margin: 0.25rem 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        .card-body {
            padding: 0.4rem 0.3rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        .card-header {
            padding: 0.4rem 0.3rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        /* 제목 영역 모바일 최적화 */
        .badge.bg-primary {
            font-size: 0.8rem !important;
            padding: 0.3rem 0.5rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 기간 설정 영역 모바일 최적화 */
        .d-flex.justify-content-center {
            flex-wrap: wrap !important;
            gap: 0.25rem !important;
            justify-content: flex-start !important;
            align-items: center !important;
            padding: 0.3rem 0.25rem !important;
            margin: 0.25rem 0 !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 버튼 모바일 최적화 */
        .btn {
            font-size: 0.75rem !important;
            padding: 0.25rem 0.4rem !important;
            white-space: nowrap !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            flex: 0 0 auto !important;
            margin: 0.1rem !important;
        }

        .btn-sm {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.35rem !important;
        }

        /* TUI Grid 모바일 최적화 - 카드 형식으로 변환 */
        #grid {
            display: none !important;
        }

        /* 모바일 카드 형식 컨테이너 */
        #mobile-grid-cards {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            padding: 0.3rem !important;
        }

        /* 모바일 카드 형식 개별 카드 */
        .mobile-grid-card {
            background: #fff !important;
            border: 1px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            box-shadow: 0 0.1rem 0.2rem rgba(0, 0, 0, 0.075) !important;
            padding: 0.4rem !important;
            margin-bottom: 0.4rem !important;
            box-sizing: border-box !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .mobile-grid-card-item {
            display: flex !important;
            width: 100% !important;
            padding: 0.3rem 0.4rem !important;
            border-bottom: 1px solid #f0f0f0 !important;
            box-sizing: border-box !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            flex-wrap: wrap !important;
        }

        .mobile-grid-card-item:last-child {
            border-bottom: none !important;
        }

        .mobile-grid-card-label {
            font-weight: bold !important;
            display: inline-block !important;
            min-width: 30% !important;
            margin-right: 0.5rem !important;
            color: #495057 !important;
            font-size: 0.75rem !important;
            flex-shrink: 0 !important;
        }

        .mobile-grid-card-value {
            flex: 1 1 auto !important;
            min-width: 0 !important;
            font-size: 0.75rem !important;
            color: #212529 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 모든 요소가 카드 내부에 머물도록 */
        .card *,
        .container *,
        .container-fluid * {
            box-sizing: border-box !important;
            max-width: 100% !important;
        }

        .card button,
        .card .btn,
        .card span,
        .card input,
        .container button,
        .container .btn,
        .container span,
        .container input,
        .card-body *,
        .card-header * {
            max-width: 100% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }

        /* 카드 내부 모든 요소가 넘치지 않도록 */
        .card {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }

        .card-body {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }

        .card-header {
            overflow-x: hidden !important;
            overflow-y: visible !important;
        }

        /* 폼 요소 모바일 최적화 */
        form {
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }

        form * {
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 행 레이아웃 모바일 최적화 */
        .row {
            margin-left: -5px !important;
            margin-right: -5px !important;
        }

        .row > [class*="col-"] {
            padding-left: 5px !important;
            padding-right: 5px !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
    }
</style>
</head>
<?php
// 요청 파라미터 초기화
$fromdate = $_REQUEST["fromdate"] ?? "";
$todate = $_REQUEST["todate"] ?? "";
$recordDate = $_REQUEST["recordDate"] ?? date("Y-m-d");
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0';
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';
$page = $_REQUEST["page"] ?? 1;

$cursort = $_REQUEST["cursort"] ?? 0;
$sortof = $_REQUEST["sortof"] ?? 0;
$stable = $_REQUEST["stable"] ?? 0;

if (isset($_REQUEST["sortof"])) {
    if ($sortof == 1 and $stable == 0) {
        if ($cursort != 1)
            $cursort = 1;
        else
            $cursort = 2;
    }
    if ($sortof == 2 and $stable == 0) {
        if ($cursort != 3)
            $cursort = 3;
        else
            $cursort = 4;
    }
    if ($sortof == 3 and $stable == 0) {
        if ($cursort != 5)
            $cursort = 5;
        else
            $cursort = 6;
    }
    if ($sortof == 4 and $stable == 0) {
        if ($cursort != 7)
            $cursort = 7;
        else
            $cursort = 8;
    }
    if ($sortof == 5 and $stable == 0) {
        if ($cursort != 9)
            $cursort = 9;
        else
            $cursort = 10;
    }
    if ($sortof == 6 and $stable == 0) {
        if ($cursort != 11)
            $cursort = 11;
        else
            $cursort = 12;
    }
} else {
    $sortof = 0;
    $cursort = 0;
}

$sum = array();
$mode = $_REQUEST["mode"] ?? "";
$find = $_REQUEST["find"] ?? '';
$search = $_REQUEST["search"] ?? '';
$year = $_REQUEST["year"] ?? date("Y");
$process = $_REQUEST["process"] ?? '';
$asprocess = $_REQUEST["asprocess"] ?? '';
$up_fromdate = $_REQUEST["up_fromdate"] ?? '';
$up_todate = $_REQUEST["up_todate"] ?? '';
$separate_date = $_REQUEST["separate_date"] ?? '';
$view_table = $_REQUEST["view_table"] ?? '';

if ($fromdate == "") {
    $fromdate = substr(date("Y-m-d", time()), 0, 7);
    $fromdate = $fromdate . "-01";
}
if ($todate == "") {
    $todate = date("Y-m-d");
    $Transtodate = strtotime($todate . '+1 days');
    $Transtodate = date("Y-m-d", $Transtodate);
} else {
    $Transtodate = strtotime($todate);
    $Transtodate = date("Y-m-d", $Transtodate);
}

$orderby = " order by al_askdateto desc ";
$now = date("Y-m-d");

if ($mode == "search") {
    if ($search == "") {
        $sql = "select * from " . $DB . "." . $tablename . " where al_askdateto between date('$fromdate') and date('$Transtodate') AND is_deleted IS NULL " . $orderby;
    } elseif ($search != "") {
        $sql = "select * from " . $DB . "." . $tablename . " where ((author like '%$search%') or (al_askdateto like '%$search%') or (al_askdatefrom like '%$search%'))";
        $sql .= " and (al_askdateto between date('$fromdate') and date('$Transtodate')) AND is_deleted IS NULL " . $orderby;
    }
} else {
    $sql = "select * from " . $DB . "." . $tablename . " where al_askdateto between date('$fromdate') and date('$Transtodate') AND is_deleted IS NULL " . $orderby;
}

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

$counter = 0;
$num_arr = array();
$id_arr = array();
$name_arr = array();
$part_arr = array();
$registdate_arr = array();
$item_arr = array();
$al_askdatefrom_arr = array();
$al_askdateto_arr = array();
$usedday_arr = array();
$content_arr = array();
$state_arr = array();
$sum1 = 0;
$sum2 = 0;
$sum3 = 0;

// rowDBask.php에서 사용될 변수 초기화
$num = '';
$author_id = '';
$author = '';
$al_part = '';
$registdate = '';
$al_item = '';
$al_askdatefrom = '';
$al_askdateto = '';
$al_usedday = 0;
$al_content = '';
$status = '';

try {
    $stmh = $pdo->query($sql);
    $rowNum = $stmh->rowCount();

    while ($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
        include 'rowDBask.php';

        array_push($num_arr, $num);
        array_push($id_arr, $author_id);
        array_push($name_arr, $author);
        array_push($part_arr, $al_part);
        array_push($registdate_arr, $registdate);
        array_push($item_arr, $al_item);
        array_push($al_askdatefrom_arr, $al_askdatefrom);
        array_push($al_askdateto_arr, $al_askdateto);
        array_push($usedday_arr, $al_usedday);
        array_push($content_arr, $al_content);

        switch ($status) {
            case 'send':
                $statusstr = '결재상신';
                break;
            case 'ing':
                $statusstr = '결재중';
                break;
            case 'end':
                $statusstr = '결재완료';
                break;
            default:
                $statusstr = '';
                break;
        }

        array_push($state_arr, $statusstr);
    }
} catch (PDOException $ex) {
    error_log("eworks 연차 조회 오류: " . $ex->getMessage());
}
$all_sum = $sum1 + $sum2 + $sum3;
?>

<form name="board_form" id="board_form" method="post" action="batchDB.php?mode=search&year=<?= $year ?>&search=<?= $search ?>&process=<?= $process ?>&asprocess=<?= $asprocess ?>&fromdate=<?= $fromdate ?>&todate=<?= $todate ?>&up_fromdate=<?= $up_fromdate ?>&up_todate=<?= $up_todate ?>&separate_date=<?= $separate_date ?>&view_table=<?= $view_table ?>">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex mb-1 mt-2 justify-content-between align-items-center">
                    <span class="badge bg-primary fs-6">	직원 연차 list  </span>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="window.close();" title="닫기">
                        <i class="bi bi-x-lg"></i> 닫기
                    </button>
                </div>

                <div class="row">
                    <div class="d-flex mt-1 mb-2 justify-content-center align-items-center">
                        <!-- 기간설정 칸 -->
                        <?php include getDocumentRoot() . '/setdate.php' ?>
                    </div>
                </div>

            </div>
            <!-- end of card-header -->

            <div class="card-body">
                <div id="grid" style="width:1250px;"></div>
                <div id="mobile-grid-cards" style="display: none;"></div>
            </div>
        </div>
    </div>
    <!-- end of container -->
</form>
<!-- end of board_form -->


<form id=Form1 name="Form1">
    <input type=hidden id="num_arr" name="num_arr[]">
    <input type=hidden id="recordDate_arr" name="recordDate_arr[]">
</form>

</body>

</html>  



    <script>
        $(document).ready(function() {
            $("#searchBtn").click(function() {
                document.getElementById('board_form').submit();
            });

            var arr1 = <?php echo json_encode($num_arr); ?>;
            var numcopy = new Array();
            var arr2 = <?php echo json_encode($registdate_arr); ?>;
            var arr3 = <?php echo json_encode($al_askdatefrom_arr); ?>;
            var arr4 = <?php echo json_encode($al_askdateto_arr); ?>;
            var arr5 = <?php echo json_encode($usedday_arr); ?>;
            var arr6 = <?php echo json_encode($name_arr); ?>;
            var arr7 = <?php echo json_encode($state_arr); ?>;

            var total_sum = 0;
            var rowNum = arr1.length;
            var data = [];
            var columns = [];
            var count = 0;

            for (i = 0; i < rowNum; i++) {
                total_sum = total_sum + Number(uncomma(arr6[i]));
                row = {
                    name: i
                };
                row['col1'] = arr1[i];
                row['col2'] = arr2[i];
                row['col3'] = arr3[i];
                row['col4'] = arr4[i];
                row['col5'] = arr5[i];
                row['col6'] = arr6[i];
                row['col7'] = arr7[i];
                data.push(row);
                count++;
            }


            class CustomTextEditor {
                constructor(props) {
                    var el = document.createElement('input');
                    var maxLength = props.columnInfo.editor.options.maxLength;

                    el.type = 'text';
                    el.maxLength = maxLength;
                    el.value = String(props.value);

                    this.el = el;
                }

                getElement() {
                    return this.el;
                }

                getValue() {
                    return this.el.value;
                }

                mounted() {
                    this.el.select();
                }
            }

            // 모바일 감지
            var isMobile = window.innerWidth <= 768;
            
            // 모바일에서 카드 형식으로 표시하는 함수
            function renderMobileGridCards() {
                var cardsContainer = document.getElementById('mobile-grid-cards');
                var gridElement = document.getElementById('grid');
                
                if (isMobile) {
                    // 모바일: 그리드 숨기고 카드 표시
                    if (gridElement) {
                        gridElement.style.display = 'none';
                    }
                    
                    if (cardsContainer) {
                        cardsContainer.innerHTML = '';
                        cardsContainer.style.display = 'block';
                        
                        // 컬럼 헤더 정의
                        var columnLabels = {
                            'col1': '번호',
                            'col2': '접수일',
                            'col3': '시작일',
                            'col4': '종료일',
                            'col5': '사용일수',
                            'col6': '성명',
                            'col7': '결재상태'
                        };
                        
                        // 각 행을 카드로 변환
                        data.forEach(function(row, index) {
                            var card = document.createElement('div');
                            card.className = 'mobile-grid-card';
                            
                            var cardContent = '';
                            
                            // 각 컬럼을 카드 아이템으로 추가
                            Object.keys(columnLabels).forEach(function(colName) {
                                var value = row[colName] || '';
                                cardContent += '<div class="mobile-grid-card-item">';
                                cardContent += '<span class="mobile-grid-card-label">' + columnLabels[colName] + '</span>';
                                cardContent += '<span class="mobile-grid-card-value">' + (value || '-') + '</span>';
                                cardContent += '</div>';
                            });
                            
                            card.innerHTML = cardContent;
                            cardsContainer.appendChild(card);
                        });
                    }
                } else {
                    // PC: 카드 숨기고 그리드 표시
                    if (cardsContainer) {
                        cardsContainer.style.display = 'none';
                    }
                    if (gridElement) {
                        gridElement.style.display = 'block';
                    }
                }
            }
            
            var grid = new tui.Grid({
                el: document.getElementById('grid'),
                data: data,
                bodyHeight: isMobile ? 400 : 650,
                columns: [{
                        header: '번호',
                        name: 'col1',
                        sortingType: 'desc',
                        sortable: true,
                        width: 100,
                        align: 'center'
                    },
                    {
                        header: '접수일',
                        name: 'col2',
                        color: 'red',
                        sortingType: 'desc',
                        sortable: true,
                        width: 150,
                        align: 'center'
                    },
                    {
                        header: '시작일',
                        name: 'col3',
                        color: 'red',
                        sortingType: 'desc',
                        sortable: true,
                        width: 150,
                        align: 'center'
                    },
                    {
                        header: '종료일',
                        name: 'col4',
                        sortingType: 'desc',
                        sortable: true,
                        width: 150,
                        align: 'center'
                    },
                    {
                        header: '사용일수',
                        name: 'col5',
                        width: 150,
                        align: 'center'
                    },
                    {
                        header: '성명',
                        name: 'col6',
                        width: 150,
                        align: 'center'
                    },
                    {
                        header: '결재상태',
                        name: 'col7',
                        width: 150,
                        align: 'center'
                    }
                ],
                columnOptions: {
                    resizable: true
                },
                rowHeaders: ['rowNum', 'checkbox'],
                pageOptions: {
                    useClient: false,
                    perPage: 20
                }
            });

            var Grid = tui.Grid;
            Grid.applyTheme('default', {
                cell: {
                    normal: {
                        background: '#fbfbfb',
                        border: '#e0e0e0',
                        showVerticalBorder: true
                    },
                    header: {
                        background: '#eee',
                        border: '#ccc',
                        showVerticalBorder: true
                    },
                    rowHeader: {
                        border: '#ccc',
                        showVerticalBorder: true
                    },
                    editable: {
                        background: '#fbfbfb'
                    },
                    selectedHeader: {
                        background: '#d8d8d8'
                    },
                    focused: {
                        border: '#418ed4'
                    },
                    disabled: {
                        text: '#b0b0b0'
                    }
                }
            });
            
            // 초기 로드 시 모바일 카드 형식 적용
            renderMobileGridCards();
            
            // 윈도우 리사이즈 이벤트 처리
            var resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    isMobile = window.innerWidth <= 768;
                    renderMobileGridCards();
                }, 250);
            });
        });
    </script>