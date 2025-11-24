<?php
/**
 * Jamb 그리기 페이지
 * Jamb 설계도를 그리고 관리하는 페이지입니다.
 */

// 로컬과 서버 호환성을 위한 설정
if (file_exists(__DIR__ . '/../common/functions.php')) {
    require_once __DIR__ . '/../common/functions.php';
}

// 세션 시작
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 세션 변수 초기화
$DB = $_SESSION["DB"] ?? 'mirae8440';
$level = $_SESSION["level"] ?? '';
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$WebSite = $_SESSION["WebSite"] ?? '';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    $baseUrl = getBaseUrl();
    header("Location: " . $baseUrl . "/login/login_form.php");
    exit;
}

// 요청 파라미터 초기화
$menu = $_REQUEST["menu"] ?? '';

// 변수 초기화
$title_message = 'jamb 그리기';

// 배열 초기화
$item = array();
$item[] = '선택';
$item[] = '와이드쟘';
$item[] = '와이드쟘-사이드(좌큼)';
$item[] = '와이드쟘-사이드(우큼)';
$item[] = '멍텅구리';
$item[] = '멍텅구리-사이드(좌큼)';
$item[] = '멍텅구리-사이드(우큼)';
$item[] = '쪽쟘';

$item_counter = count($item);

$whois = array();
$whois[] = '추영덕소장';
$whois[] = '이만희소장';
$whois[] = '손상민소장';
$whois[] = '백석묵소장';

$whois_counter = count($whois);

$bendrate = array();
$bendrate[] = '0.75';
$bendrate[] = '0.6';

$bendrate_counter = count($bendrate);

include getDocumentRoot() . '/load_header.php';
?>

<script src="https://bossanova.uk/jexcel/v3/jexcel.js"></script>
<script src="https://bossanova.uk/jsuites/v2/jsuites.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jexcel/v3/jexcel.css" type="text/css" />
<link rel="stylesheet" href="https://bossanova.uk/jsuites/v2/jsuites.css" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="<?php echo getBaseUrl(); ?>/jamb/jamb.js"></script>

<title><?php echo htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8'); ?></title>

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 선택 영역 최적화 */
        .d-flex.mt-3.mb-3.justify-content-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.mt-3.mb-3.justify-content-center select,
        .d-flex.mt-3.mb-3.justify-content-center input[type="checkbox"] {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        .d-flex.mt-3.mb-3.justify-content-center span {
            width: 100% !important;
            max-width: 100% !important;
            text-align: center !important;
            margin: 0.25rem 0 !important;
        }
        
        /* Jexcel 스프레드시트 최적화 */
        #spreadsheet,
        #spreadsheet2 {
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: auto !important;
        }
        
        /* Canvas 영역 최적화 */
        #canvas_outline,
        #canvas_sideoutline {
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            overflow-x: auto !important;
            overflow-y: auto !important;
            border: 2px solid black !important;
        }
        
        #canvas,
        #canvas_side {
            max-width: 100% !important;
            height: auto !important;
        }
        
        #title,
        #title2 {
            width: 100% !important;
            max-width: 100% !important;
            float: none !important;
            margin: 0.5rem 0 !important;
            text-align: center !important;
            font-size: 1.25rem !important;
        }
        
        /* 제어 영역 최적화 */
        #display_control {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0.5rem !important;
        }
        
        #goods_list table {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        #goods_list table td {
            padding: 0.5rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        #goods_list input,
        #goods_list select,
        #goods_list button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
        }
        
        #goods_list img {
            width: 30px !important;
            height: 30px !important;
            margin: 0.25rem !important;
        }
        
        /* 제목 영역 최적화 */
        h1 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            padding: 0.5rem !important;
        }
        
        h1 table {
            width: 100% !important;
            max-width: 100% !important;
        }
        
        h1 table td {
            padding: 0.5rem !important;
        }
        
        h1 input,
        h1 img {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
        }
        
        h4 {
            font-size: 1.1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            padding: 0.5rem !important;
        }
        
        /* 버튼 최적화 */
        #maketable,
        #drawline,
        #alertangle {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
</style>

</head>

<body>
    <?php require_once(includePath('common/modal.php')); ?>
    
    <?php
    if ($menu !== 'no') {
        require_once(includePath('myheader.php'));
    }
    ?>
    
    <div class="container-fluid">
        <div class="d-flex mt-3 mb-3 justify-content-center">
            쟘 형태 &nbsp;
            
            <select name="item" id="sel1">
                <?php
                for ($i = 0; $i < $item_counter; $i++) {
                    $selected = '';
                    if (isset($_REQUEST['item']) && $_REQUEST['item'] === $item[$i]) {
                        $selected = 'selected';
                    }
                    echo '<option ' . $selected . ' value="' . htmlspecialchars($item[$i], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($item[$i], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select> &nbsp;
            
            &nbsp; 옵션 &nbsp; &nbsp;
            <input id="measuredraw" name="measuredraw" type="checkbox" checked value="실측치draw" /> &nbsp; 실측치draw &nbsp;
            <input id="makedraw" name="makedraw" type="checkbox" value="제작치수draw" /> &nbsp; 제작치수draw &nbsp;
            
            &nbsp; 연신율 &nbsp; &nbsp;
            <select name="bendrate" id="sel3">
                <?php
                for ($i = 0; $i < $bendrate_counter; $i++) {
                    $selected = '';
                    if (isset($_REQUEST['bendrate']) && $_REQUEST['bendrate'] === $bendrate[$i]) {
                        $selected = 'selected';
                    }
                    echo '<option ' . $selected . ' value="' . htmlspecialchars($bendrate[$i], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($bendrate[$i], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select> &nbsp;
            
            &nbsp; 스타일 &nbsp;
            <select name="whois" id="sel2">
                <?php
                for ($i = 0; $i < $whois_counter; $i++) {
                    $selected = '';
                    if (isset($_REQUEST['whois']) && $_REQUEST['whois'] === $whois[$i]) {
                        $selected = 'selected';
                    }
                    echo '<option ' . $selected . ' value="' . htmlspecialchars($whois[$i], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($whois[$i], ENT_QUOTES, 'UTF-8') . '</option>';
                }
                ?>
            </select> &nbsp;
        </div>
        
        <div class="clear"></div>
        
        <div id="jambmore">
            <span class="more">
                <span class="blind"></span>
            </span>
        </div>
        
        <div class="clear"></div>
        
        <div id="display_jambtype" style="display:none" class="board">
            <div id="jambtype_col1" class="board" style="display:none"></div>
            
            <div id="jambtype_col2" class="board" style="display:none">
                <div id="j_row1">
                    <script>
                        if (typeof load_format === 'function') {
                            load_format();
                        }
                    </script>
                </div>
                
                <div id="j_row2">
                    <div id="title1">
                        제작치수 반영치 설정
                    </div>
                    
                    <div id="setdata">
                        <script>
                            if (typeof load_setdata === 'function') {
                                load_setdata();
                            }
                        </script>
                    </div>
                    
                    <div id="makeit">
                        <input id="maketable" type="button" value="제작치수 반영 후 DATA 생성" onclick="javascript:maketable();">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="clear"></div>
        
        <div id="all_spread">
            <div class="clear"></div>
            <h4 style="color:red">제작치수 반영 DATA</h4>
            <div id="spreadsheet" style="width:100%;"></div>
        </div>
        
        <div class="clear"></div>
        
        <div id="display_control">
            <input type="hidden" id="old_draw" name="old_draw">
            <input type="hidden" id="new_draw" name="new_draw">
            
            <div id="goods_list">
                <form>
                    <table align="" border="1" cellspacing="0" cellpadding="0">
                        <tr>
                            <td><h4>화면에 그려낼 DATA 선택</h4></td>
                        </tr>
                        <tr>
                            <td>
                                <table>
                                    <tr>
                                        <td>
                                            <input type="text" name="num" value="1" id="col1" class="num" style="width:100px; height:25px; font-size:16px; text-align:center;" />
                                        </td>
                                        <td>
                                            <div>
                                                <img src="../img/up_btn.png" alt="up" width="20" height="20" class="bt_up"/>
                                            </div>
                                            <div>
                                                <img src="../img/down_btn.png" alt="down" width="20" height="20" class="bt_down" />
                                            </div>
                                        </td>
                                        <td>
                                            <input id="drawline" type="button" value="그리기" onclick="javascript:select_jamb();" style="width:100px; height:30px; float:left;">
                                            <input id="alertangle" type="button" value="각도구하기" onclick="javascript:getAngle(200,200,800,400);" style="width:100px; height:30px; float:left;">
                                        </td>
                                        <td>
                                            <select name="Xscale" id="Xscale">
                                                <option value="none">X스케일 설정</option>
                                                <option value="2x1">1/2</option>
                                                <option value="3x1">1/3</option>
                                                <option value="5x1">1/5</option>
                                            </select>
                                            
                                            <input type="hidden" id="dis_xscale" name="dis_xscale">
                                            <input type="text" id="left_angle" name="left_angle" size="4">
                                            <input type="text" id="right_angle" name="right_angle" size="4">
                                            <input type="text" id="sin" name="sin" size="4">
                                            <input type="text" id="cos" name="cos" size="4">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        
        <div class="clear"></div>
        
        <script>
        (function() {
            'use strict';
            
            $(document).ready(function() {
                // 더보기/닫기 토글
                $('.more').on('click', function() {
                    if ($('.more').hasClass('more')) {
                        $('.more').addClass('close').removeClass('more');
                        $('.board').css('visibility', 'visible');
                        $('.board').show();
                    } else if ($('.close').hasClass('close')) {
                        $('.close').addClass('more').removeClass('close');
                        $('.board').css('visibility', 'hidden');
                        $('.board').hide();
                    }
                });
            });
            
            // Jexcel 테이블 초기화
            var data = [['']];
            
            var table1 = jexcel(document.getElementById('spreadsheet'), {
                data: data,
                csvHeaders: false,
                tableOverflow: true,
                tableHeight: '230px',
                rowResize: true,
                columnDrag: true,
                columns: [
                    { title: '호기', type: 'text', width: '30' },
                    { title: '층', type: 'text', width: '30' },
                    { title: 'U', type: 'text', width: '30' },
                    { title: 'G', type: 'text', width: '30' },
                    { title: 'MH1', type: 'text', width: '40' },
                    { title: 'MH2', type: 'text', width: '40' },
                    { title: 'JD1', type: 'text', width: '30' },
                    { title: 'JD2', type: 'text', width: '30' },
                    { title: 'OP1', type: 'text', width: '40' },
                    { title: 'OP2', type: 'text', width: '40' },
                    { title: 'R', type: 'text', width: '40' },
                    { title: 'K1', type: 'text', width: '30' },
                    { title: 'K2', type: 'text', width: '30' },
                    { title: '상판W', type: 'text', width: '40' },
                    { title: 'JB1', type: 'text', width: '30' },
                    { title: 'JB2', type: 'text', width: '30' },
                    { title: 'C1', type: 'text', width: '30' },
                    { title: 'C2', type: 'text', width: '30' },
                    { title: 'A1', type: 'text', width: '30' },
                    { title: 'A2', type: 'text', width: '30' },
                    { title: 'B1', type: 'text', width: '30' },
                    { title: 'B2', type: 'text', width: '30' },
                    { title: 'SIDE좌W', type: 'text', width: '55' },
                    { title: 'SIDE우W', type: 'text', width: '55' },
                    { title: 'LH1', type: 'text', width: '40' },
                    { title: 'LH2', type: 'text', width: '40' },
                    { title: 'RH1', type: 'text', width: '40' },
                    { title: 'RH2', type: 'text', width: '40' }
                ]
            });
            
            // 버튼 클릭 이벤트
            $(function() {
                $('.bt_up').on('click', function() {
                    var n = $('.bt_up').index(this);
                    var num = $('.num:eq(' + n + ')').val();
                    var trlength = $('#spreadsheet tbody tr').length;
                    
                    if (num < trlength) {
                        num = $('.num:eq(' + n + ')').val(num * 1 + 1);
                    }
                    
                    $('#x-coo').text(trlength);
                    
                    if (typeof select_jamb === 'function') {
                        select_jamb();
                    }
                });
                
                $('.bt_down').on('click', function() {
                    var n = $('.bt_down').index(this);
                    var num = $('.num:eq(' + n + ')').val();
                    
                    if (num > 1) {
                        num = $('.num:eq(' + n + ')').val(num * 1 - 1);
                    }
                    
                    if (typeof select_jamb === 'function') {
                        select_jamb();
                    }
                });
                
                $('.second_bt_up').on('click', function() {
                    var n = $('.second_bt_up').index(this);
                    var num = $('.num2:eq(' + n + ')').val();
                    var trlength = $('#spreadsheet tbody tr').length;
                    
                    if (num < trlength) {
                        num = $('.num2:eq(' + n + ')').val(num * 1 + 1);
                    }
                    
                    $('#x-coo').text(trlength);
                    
                    if (typeof second_select_jamb === 'function') {
                        second_select_jamb();
                    }
                });
                
                $('.second_bt_down').on('click', function() {
                    var n = $('.second_bt_down').index(this);
                    var num = $('.num2:eq(' + n + ')').val();
                    
                    if (num > 1) {
                        num = $('.num2:eq(' + n + ')').val(num * 1 - 1);
                    }
                    
                    if (typeof second_select_jamb === 'function') {
                        second_select_jamb();
                    }
                });
            });
        })();
        </script>
        
        <div id="canvas_outline" style="width:4000px; height:1200px; border: 2px solid black;">
            <div id="title" style="width:1100px; height:35px; float:left; margin-left:10px; margin-top:10px; text-align:center; font-size:28px;"></div>
            <canvas id="canvas" width="4000" height="1100" style="margin-left:20px; margin-top:20px;"></canvas>
        </div>
        
        <script>
        /**
         * 각도 계산 함수
         * @param {number} x1 - 시작점 X 좌표
         * @param {number} y1 - 시작점 Y 좌표
         * @param {number} x2 - 끝점 X 좌표
         * @param {number} y2 - 끝점 Y 좌표
         * @returns {number} - 계산된 각도 (절대값)
         */
        function getAngle(x1, y1, x2, y2) {
            var rad = Math.atan2(y2 - y1, x2 - x1);
            var d = (rad * 180) / Math.PI;
            
            var ch = d;
            if (ch > 90) {
                ch = 90 - ch;
            }
            
            return Math.abs(ch.toFixed(1));
        }
        </script>
        
        <h1>
            Jamb side(좌, 우) 설계도
            
            <table>
                <tr>
                    <td>
                        <input type="text" name="num2" value="1" id="col2" class="num2" style="width:100px; height:25px; font-size:16px; text-align:center;" />
                    </td>
                    <td>
                        <div>
                            <img src="../img/up_btn.png" alt="up" width="20" height="20" class="second_bt_up"/>
                        </div>
                        <div>
                            <img src="../img/down_btn.png" alt="down" width="20" height="20" class="second_bt_down" />
                        </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </h1>
        
        <script>
        (function() {
            'use strict';
            
            var data = [
                [''], [''], [''], [''], [''],
                [''], [''], [''], [''], [''],
                [''], [''], [''], [''], [''],
                [''], [''], [''], [''], [''],
                [''], [''], [''], [''], [''],
                [''], [''], [''], [''], ['']
            ];
            
            jexcel(document.getElementById('spreadsheet2'), {
                csv: '<?php echo getBaseUrl(); ?>/jamb/jamb_test_left.csv',
                csvHeaders: true,
                rowResize: true,
                columnDrag: true,
                columns: [
                    { title: '동', type: 'text', width: '40' },
                    { title: '층', type: 'text', width: '40' },
                    { title: 'JB', type: 'text', width: '40' },
                    { title: 'H1', type: 'text', width: '40' },
                    { title: 'H2', type: 'text', width: '40' },
                    { title: 'G1', type: 'text', width: '40' },
                    { title: 'G2', type: 'text', width: '40' },
                    { title: 'K1', type: 'text', width: '40' },
                    { title: 'K2', type: 'text', width: '40' },
                    { title: '좌(높이)', type: 'text', width: '40' },
                    { title: '', type: 'text', width: '40' },
                    { title: '1', type: 'text', width: '40' },
                    { title: '2', type: 'text', width: '40' },
                    { title: '우(높이)', type: 'text', width: '40' },
                    { title: '', type: 'text', width: '40' },
                    { title: '1', type: 'text', width: '40' },
                    { title: '2', type: 'text', width: '40' }
                ]
            });
        })();
        </script>
        
        <div id="canvas_sideoutline" style="width:4000px; height:1600px; border: 2px solid black;">
            <div id="title2" style="width:1100px; height:35px; float:left; margin-left:10px; margin-top:10px; text-align:center; font-size:28px;"></div>
            <canvas id="canvas_side" width="4000px" height="1600px" style="margin-left:20px; margin-top:20px;"></canvas>
        </div>
    </div>
</body>

<script>
(function() {
    'use strict';
    
    setTimeout(function() {
        $('#sel1').val('와이드쟘').prop('selected', true);
        
        if (typeof load_widejamb === 'function') {
            load_widejamb();
        }
        
        setTimeout(function() {
            if (typeof maketable === 'function') {
                maketable();
            }
        }, 100);
        
        setTimeout(function() {
            if (typeof redraw === 'function') {
                redraw();
            }
        }, 100);
    }, 1500);
    
    $('#Xscale').on('change', function() {
        if (typeof select_jamb === 'function') {
            select_jamb();
        }
    });
})();
</script>
</html>
