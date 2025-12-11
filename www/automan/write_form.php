<?php
require_once __DIR__ . '/../bootstrap.php';
require_once(includePath('session.php'));

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 10;
$WebSite = $_SESSION["WebSite"] ?? '';
$user_name = $_SESSION["name"] ?? '';

// 권한 체크
if (!isset($_SESSION["level"]) || $level > 5) {
    sleep(1);
    
    // 로컬/서버 환경에 따른 동적 리다이렉션
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
        header("Location: http://" . $host . "/login/login_form.php");
    } else {
        header("Location: " . $WebSite . "login/login_form.php");
    }
    exit;
}
?>
<?php include getDocumentRoot() . '/load_header.php' ?>
<title> 전산실장 정산 </title>
</head>

<body>
    <?php require_once(includePath('myheader.php')); ?>

    <?php
    // 요청 파라미터 초기화
    $callback = $_REQUEST["callback"] ?? '';
    $mode = $_REQUEST["mode"] ?? '';
    $which = $_REQUEST["which"] ?? '2';
    $num = $_REQUEST["num"] ?? '';
    $page = $_REQUEST["page"] ?? 1;
    $search = $_REQUEST["search"] ?? '';
    $find = $_REQUEST["find"] ?? '';
    $process = $_REQUEST["process"] ?? '전체';
    $fromdate = $_REQUEST["fromdate"] ?? '';
    $todate = $_REQUEST["todate"] ?? '';
    $regist_state = $_REQUEST["regist_state"] ?? '1';
    $year = $_REQUEST["year"] ?? '';
       
    // 데이터베이스 변수 초기화
    $proDate = '';
    $writer = '';
    $amount = '';
    $memo = '';
    $first_writer = '';
    $update_log = '';

    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    if ($mode == "modify") {
        try {
            $sql = "select * from mirae8440.automan where num = ? ";
            $stmh = $pdo->prepare($sql);

            $stmh->bindValue(1, $num, PDO::PARAM_STR);
            $stmh->execute();
            $count = $stmh->rowCount();
            $row = $stmh->fetch(PDO::FETCH_ASSOC);

            if ($count < 1) {
                error_log("Automan 데이터 없음: num=" . $num);
            } else {
                $num = $row["num"];
                $proDate = $row["proDate"];
                $writer = $row["writer"];
                $amount = $row["amount"];
                $memo = $row["memo"];
                $which = $row["which"];
            }
        } catch (PDOException $ex) {
            error_log("Automan 조회 오류: " . $ex->getMessage());
        }
    }

    if ($mode != "modify") {
        // 신규 자료일때는 변수 초기화
        $proDate = date("Y-m-d");
        $writer = $user_name;
        $amount = "";
        $memo = "";
        $which = "2";
    }
    ?>

    <div class="container">
        <div class="card mt-2 mb-1">
            <div class="card-header">
                <div class="d-flex mb-4 mt-4 fs-4 justify-content-center">
                    <?php
                    if ($mode == "modify") {
                    ?>
                        <form id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>">
                        <?php } else { ?>
                            <form id="board_form" name="board_form" method="post" onkeydown="return captureReturnKey(event)" action="insert.php?mode=not&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>">
                            <?php
                        }
                            ?>

                            <input type="hidden" id="first_writer" name="first_writer" value="<?=$first_writer?>">
                            <input type="hidden" id="update_log" name="update_log" value="<?=$update_log?>">
                            <input type="hidden" id="page" name="page" value="<?=$page?>">
                            <input type="hidden" id="num" name="num" value="<?=$num?>">
                            <input type="hidden" id="mode" name="mode" value="<?=$mode?>">

                            <h4> 전산실장 정산내용 등록/수정 &nbsp;&nbsp;&nbsp;&nbsp;
                                <button type="button" id="saveBtn" class="btn btn-primary"> DATA 저장 </button>&nbsp;
                                <button type="button" id="gotoList" class="btn btn-secondary" onclick="location.href='list.php?mode=search&search=<?=$search?>&find=<?=$find?>&page=<?=$page?>&year=<?=$year?>&search=<?=$search?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>'"> 목록(List) </button>&nbsp;
                            </h4>
                </div>
            </div>

            <div class="card-body justify-content-center align-items-center">
                <div class="d-flex mb-1 mt-2 justify-content-center">

                    <?php
                    $aryreg = array();
                    $aryitem = array();
                    if ($which == '') $which = '2';
                    switch ($which) {
                        case "1":
                            $aryreg[0] = "checked";
                            break;
                        case "2":
                            $aryreg[1] = "checked";
                            break;
                        default:
                            break;
                    }
                    ?>
                    구분 : &nbsp; &nbsp; <span class="text-primary"> 수입 </span> &nbsp;
                    <input type="radio" <?=$aryreg[0]?> name="which" value="1"> &nbsp; &nbsp;
                    &nbsp; <span class="text-danger"> 지출 </span> &nbsp;
                    <input type="radio" <?=$aryreg[1]?> name="which" value="2">
                </div>
                <div class="d-flex p-3 m-4 mb-2 mt-2 justify-content-center">
                    기록일 : &nbsp;
                    <input type="date" id="proDate" name="proDate" value="<?=$proDate?>" size="14"> &nbsp;
                    작성자 : &nbsp;
                    <input type="text" id="writer" name="writer" value="<?=$writer?>" size="14"> &nbsp;
                </div>
                <div class="d-flex p-3 m-4 mb-2 mt-2 justify-content-center">
                    &nbsp; 내 역 : &nbsp;
                    <input type="text" id="memo" name="memo" value="<?=$memo?>" size="45" placeholder="내역">
                </div>
                <div class="d-flex p-3 m-4 mb-2 mt-2 justify-content-center">
                    금 액 : &nbsp;
                    <input type="text" name="amount" id="amount" value="<?=$amount?>" onkeyup="inputNumberFormat(this)" size="10" style="text-align:right;" placeholder="비용" />
                </div>

            </div>
            </form>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#saveBtn").click(function() {
                // grid 배열 form에 전달하기
                $("#board_form").submit();
            });

        });

        function inputNumberFormat(obj) {
            obj.value = comma(uncomma(obj.value));
        }

        function comma(str) {
            str = String(str);
            return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
        }

        function uncomma(str) {
            str = String(str);
            return str.replace(/[^\d]+/g, '');
        }

        function date_mask(formd, textid) {
            /*
            input onkeyup에서
            formd == this.form.name
            textid == this.name
            */
            var form = eval("document." + formd);
            var text = eval("form." + textid);

            var textlength = text.value.length;

            if (textlength == 4) {
                text.value = text.value + "-";
            } else if (textlength == 7) {
                text.value = text.value + "-";
            } else if (textlength > 9) {
                // 날짜 수동 입력 Validation 체크
                var chk_date = checkdate(text);

                if (chk_date == false) {
                    return;
                }
            }
        }

        function checkdate(input) {
            var validformat = /^\d{4}\-\d{2}\-\d{2}$/; // Basic check for format validity
            var returnval = false;

            if (!validformat.test(input.value)) {
                alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            } else {
                // Detailed check for valid date ranges
                var yearfield = input.value.split("-")[0];
                var monthfield = input.value.split("-")[1];
                var dayfield = input.value.split("-")[2];
                var dayobj = new Date(yearfield, monthfield - 1, dayfield);
            }

            if ((dayobj.getMonth() + 1 != monthfield) ||
                (dayobj.getDate() != dayfield) ||
                (dayobj.getFullYear() != yearfield)) {
                alert("날짜 형식이 올바르지 않습니다. YYYY-MM-DD");
            } else {
                returnval = true;
            }
            if (returnval == false) {
                input.select();
            }
            return returnval;
        }

        function input_Text() {
            document.getElementById("test").value = comma(Math.floor(uncomma(document.getElementById("test").value) * 1.1));
            var copyText = document.getElementById("test");
            copyText.select();
            document.execCommand("Copy");
        }

        function captureReturnKey(e) {
            if (e.keyCode == 13 && e.srcElement.type != 'textarea')
                return false;
        }

        function recaptureReturnKey(e) {
            if (e.keyCode == 13)
                exe_search();
        }

        function Enter_Check() {
            var tmp = $('input[name=search_opt]:checked').val();

            // 엔터키의 코드는 13입니다.
            if (event.keyCode == 13 && tmp == 1)
                search_jamb(); // 잠 현장검색

            if (event.keyCode == 13 && tmp == 2)
                search_ceiling(); // 천장 현장 검색
        }

        function Choice_search() {
            var tmp = $('input[name=search_opt]:checked').val();
            if (tmp == '1')
                search_jamb(); // 잠 현장검색
            if (tmp == '2')
                search_ceiling(); // 천장 현장 검색
        }

        function Enter_CheckTel() {
            // 엔터키의 코드는 13입니다.
            if (event.keyCode == 13) {
                exe_searchTel(); // 실행할 이벤트
            }
        }

        function deldate() {
            document.getElementById("indate").value = "";
            var _today = new Date();

            printday = _today.format('yyyy-MM-dd');
            document.getElementById("proDate").value = printday;
            $("input[name='which']:radio[value='1']").attr("checked", true);
        }

        // _today.format 사용하려면 아래 내용이 함께 포함되어야 합니다.
        Date.prototype.format = function(f) {

            if (!this.valueOf()) return " ";

            var weekKorName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
            var weekKorShortName = ["일", "월", "화", "수", "목", "금", "토"];
            var weekEngName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var weekEngShortName = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

            var d = this;

            return f.replace(/(yyyy|yy|MM|dd|KS|KL|ES|EL|HH|hh|mm|ss|a\/p)/gi, function($1) {

                switch ($1) {
                    case "yyyy":
                        return d.getFullYear(); // 년 (4자리)
                    case "yy":
                        return (d.getFullYear() % 1000).zf(2); // 년 (2자리)
                    case "MM":
                        return (d.getMonth() + 1).zf(2); // 월 (2자리)
                    case "dd":
                        return d.getDate().zf(2); // 일 (2자리)
                    case "KS":
                        return weekKorShortName[d.getDay()]; // 요일 (짧은 한글)
                    case "KL":
                        return weekKorName[d.getDay()]; // 요일 (긴 한글)
                    case "ES":
                        return weekEngShortName[d.getDay()]; // 요일 (짧은 영어)
                    case "EL":
                        return weekEngName[d.getDay()]; // 요일 (긴 영어)
                    case "HH":
                        return d.getHours().zf(2); // 시간 (24시간 기준, 2자리)
                    case "hh":
                        return ((h = d.getHours() % 12) ? h : 12).zf(2); // 시간 (12시간 기준, 2자리)
                    case "mm":
                        return d.getMinutes().zf(2); // 분 (2자리)
                    case "ss":
                        return d.getSeconds().zf(2); // 초 (2자리)
                    case "a/p":
                        return d.getHours() < 12 ? "오전" : "오후"; // 오전/오후 구분
                    default:
                        return $1;
                }

            });

        };

        String.prototype.string = function(len) {
            var s = '',
                i = 0;
            while (i++ < len) {
                s += this;
            }
            return s;
        };

        String.prototype.zf = function(len) {
            return "0".string(len - this.length) + this;
        };

        Number.prototype.zf = function(len) {
            return this.toString().zf(len);
        };
    </script>
</body>

</html>
