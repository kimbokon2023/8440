<?php
require_once __DIR__ . '/../bootstrap.php';
include getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$user_name = $_SESSION["name"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$DB = $_SESSION["DB"] ?? '';
?>
<?php include getDocumentRoot() . '/load_header.php' ?>

<style>
    .table-bordered,
    .table-bordered td,
    .table-bordered th {
        border-color: #000000 !important;
    }
</style>

<body>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg modal-center">
            <!-- Modal content-->
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h4 class="modal-title">알림</h4>
                </div>
                <div class="modal-body">
                    <div id=alertmsg class="fs-1 mb-5 justify-content-center">
                        결재가 진행중입니다. <br>
                        <br>
                        수정사항이 있으면 결재권자에게 말씀해 주세요.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalBtn" class="btn btn-default" data-dismiss="modal">닫기</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $tablename = "eworks";

    // 요청 파라미터 초기화
    $num = $_REQUEST["num"] ?? '';

    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // rowDBask.php에서 사용될 변수 초기화
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
    $e_confirm = '';
    $e_confirm_id = '';

    try {
        $sql = "select * from " . $DB . "." . $tablename . " where num = ?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();
        $count = $stmh->rowCount();
        $row = $stmh->fetch(PDO::FETCH_ASSOC);

        include 'rowDBask.php';
    } catch (PDOException $ex) {
        error_log("eworks 연차 조회 오류: " . $ex->getMessage());
    }

    // 배열로 기본정보 불러옴
    include "load_DB.php";

    // load_DB.php에서 정의될 변수들 초기화 (정의되지 않은 경우 대비)
    $basic_name_arr = $basic_name_arr ?? array();
    $basic_part_arr = $basic_part_arr ?? array();
    $totalname_arr = $totalname_arr ?? array();
    $totalused_arr = $totalused_arr ?? array();
    $availableday_arr = $availableday_arr ?? array();
    $employee_name_arr = $employee_name_arr ?? array();
    $employee_id_arr = $employee_id_arr ?? array();
    $employee_part_arr = $employee_part_arr ?? array();

    if ($num == '') {
        // 신규데이터인 경우
        $registdate = date("Y-m-d H:i:s");
        $al_askdatefrom = date("Y-m-d");
        $al_askdateto = date("Y-m-d");
        $al_usedday = abs(strtotime($al_askdateto) - strtotime($al_askdatefrom)) + 1;
        $al_item = '연차';
        $status = 'send';
        $statusstr = '결재상신';
        $author = $_SESSION["name"];
        $author_id = $user_id;
        $al_part = '';

        // DB에서 al_part 찾아서 넣어주기
        for ($i = 0; $i < count($basic_name_arr); $i++) {
            if (trim($basic_name_arr[$i]) == trim($author)) {
                $al_part = $basic_part_arr[$i];
                break;
            }
        }
    }

    // 잔여일수 개인별 산출 루틴
    try {
        // 연차 잔여일수 산출
        $totalusedday = 0;
        $totalremainday = 0;
        $availableday = 0;

        for ($i = 0; $i < count($totalname_arr); $i++) {
            if ($author == $totalname_arr[$i]) {
                $availableday = $availableday_arr[$i];
            }
        }

        // 연차 사용일수 계산
        for ($i = 0; $i < count($totalname_arr); $i++) {
            if ($author == $totalname_arr[$i]) {
                $totalusedday = $totalused_arr[$i];
                $totalremainday = $availableday - $totalusedday;
            }
        }
    } catch (PDOException $ex) {
        error_log("잔여일수 산출 오류: " . $ex->getMessage());
    }
    ?>

    <form id="board_form" name="board_form" class="form-signin" method="post">
        <div class="container-fluid" style="width:380px;">
            <div class="row d-flex justify-content-center align-items-center h-50">
                <div class="col-12 text-center">
                    <div class="card align-middle" style="border-radius:20px;">
                        <div class="card" style="padding:10px;margin:10px;">
                            <h3 class="card-title text-center" style="color:#113366;"> 대량등록 (연차)신청 </h3>
                        </div>
                        <div class="card-body text-center">
                            <input type="hidden" id="mode" name="mode">
                            <input type="hidden" id="num" name="num" value="<?=$num?>">
                            <input type="hidden" id="registdate" name="registdate" value="<?=$registdate?>">
                            <input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>">
                            <input type="hidden" id="author_id" name="author_id" value="<?=$author_id?>">
                            <input type="hidden" id="htmltext" name="htmltext">
				<?php
                // 결재 승인자 초기화
                $approvals = array();
                $statusstr = $statusstr ?? '결재상신';

                if ($e_confirm === '') {
                    $formattedDate = date("m/d", strtotime($registdate));

                    if ($al_part == '제조파트') {
                        $approvals = array(
                            array("name" => "공장장 이경묵", "date" => $formattedDate),
                            array("name" => "대표 소현철", "date" => $formattedDate),
                        );
                    }
                    if ($al_part == '지원파트') {
                        $approvals = array(
                            array("name" => "이사 최장중", "date" => $formattedDate),
                            array("name" => "대표 소현철", "date" => $formattedDate),
                        );
                    }
                } else {
                    $approver_ids = explode('!', $e_confirm_id);
                    $approver_details = explode('!', $e_confirm);

                    foreach ($approver_ids as $index => $id) {
                        if (isset($approver_details[$index])) {
                            preg_match("/^(.+ \d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})$/", $approver_details[$index], $matches);

                            if (count($matches) === 3) {
                                $nameWithTitle = $matches[1];
                                $time = $matches[2];
                                $date = substr($nameWithTitle, -10);
                                $nameWithTitle = trim(str_replace($date, '', $nameWithTitle));
                                $formattedDate = date("m/d H:i:s", strtotime("$date $time"));

                                $approvals[] = array("name" => $nameWithTitle, "date" => $formattedDate);
                            }
                        }
                    }
                }
                ?>
                            <div id="savetext">
                                <div class="form-control">
                                    <label>
                                        <input type="checkbox" id="select-all" class="me-2">
                                        전체 선택
                                    </label>
                                    <div id="author-list" class="d-flex flex-wrap mt-2">
                                        <?php
                                        for ($i = 0; $i < count($employee_name_arr); $i++) {
                                            $checked = ($author == $employee_name_arr[$i]) ? "checked" : "";
                                            echo "<div class='d-flex align-items-start text-start' style='width: 33.33%;'>";
                                            echo "<label class='w-100'>";
                                            echo "<input type='checkbox' class='author-checkbox ms-3' name='author[]' value='" . $employee_name_arr[$i] . "' data-author-id='" . $employee_id_arr[$i] . "' $checked>";
                                            echo "<span class='ms-2'>" . $employee_name_arr[$i] . "</span>";
                                            echo "</label>";
                                            echo "</div>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <script>
                                    // "전체 선택" 체크박스 동작
                                    $('#select-all').on('change', function() {
                                        var isChecked = $(this).is(':checked');
                                        $('.author-checkbox').prop('checked', isChecked);
                                    });

                                    // 개별 체크박스 동작 시 "전체 선택" 체크박스 상태 업데이트
                                    $('.author-checkbox').on('change', function() {
                                        var allChecked = $('.author-checkbox:checked').length === $('.author-checkbox').length;
                                        $('#select-all').prop('checked', allChecked);
                                    });
                                </script>

                                <div class="d-flex justify-content-center align-items-center mt-2 mb-2">
                                    <?php
                                    // 연차종류 6종류로 만듬
                                    $item_arr = array('연차', '오전반차', '오전반반차', '오후반차', '오후반반차', '경조사');
                                    for ($i = 0; $i < count($item_arr); $i++) {
                                        if ($al_item == $item_arr[$i])
                                            print "<input type='radio' name='al_item' checked='checked' value='" . $item_arr[$i] . "'> " . $item_arr[$i] . " &nbsp ";
                                        else
                                            print "<input type='radio' name='al_item' value='" . $item_arr[$i] . "'> " . $item_arr[$i] . " &nbsp ";

                                        if ($i % 2 == 0)
                                            print '</div> <div class="d-flex justify-content-center align-items-center mt-3 mb-1">';
                                    }
                                    ?>
                                </div>
                                <div class="d-flex justify-content-center align-items-center mt-4 mb-2">
                                    신청시작일
                                    <input type="date" id="al_askdatefrom" name="al_askdatefrom" class="form-control text-center w100px ms-2 me-1" required autofocus value="<?=$al_askdatefrom?>">
                                </div>
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    신청종료일
                                    <input type="date" id="al_askdateto" name="al_askdateto" class="form-control text-center w100px ms-2 me-1" required value="<?=$al_askdateto?>">
                                </div>
                                <div class="d-flex justify-content-center align-items-center">
                                    <span class="text-danger fw-bold">신청 기간 산출</span>
                                    <input type="text" id="al_usedday" name="al_usedday" class="form-control text-center w50px ms-2 me-1" readonly value="<?=$al_usedday?>">
                                </div>
                                <br>
                                <h6 class="form-signin-heading mt-2 mb-2">신청 사유</h6>
                                <div class="d-flex justify-content-center align-items-center">
                                    <select name="al_content" id="al_content" class="form-select d-block w-auto mx-1" style="font-size: 0.8rem; height: 32px;">
                                        <?php
                                        $al_content_arr = array();
                                        array_push($al_content_arr, "개인사정", "휴가", "여행", "병원진료등", "전직원연차", "경조사", "기타");
                                        for ($i = 0; $i < count($al_content_arr); $i++) {
                                            if ($al_content == $al_content_arr[$i])
                                                print "<option selected value='" . $al_content_arr[$i] . "'> " . $al_content_arr[$i] . "</option>";
                                            else
                                                print "<option value='" . $al_content_arr[$i] . "'> " . $al_content_arr[$i] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- end of savetext -->

                            <?php
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
                            ?>

                            <h6 class="form-signin-heading mt-2 mb-2">결재 상태</h6>
                            <div class="d-flex justify-content-center align-items-center">
                                <input type="hidden" id="status" name="status" value="<?=$status?>">
                                <input type="text" id="statusstr" name="statusstr" class="form-control text-center w120px ms-2 me-1" readonly value="<?=$statusstr?>">
                            </div>
                            <br>

                            <button id="saveBtn" class="btn btn-sm btn-dark me-1" type="button"> (주의) 대량등록 실행 </button>

                            <button class="btn btn-dark btn-sm mx-4" id="closeBtn"> &times; 닫기 </button>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </form>

    <script>
        var ajaxRequest = null;

        $(document).ready(function() {

            var status = $('#status').val();
            // 처리완료인 경우는 수정하기 못하게 한다.

            $("#closeModalBtn").click(function() {
                $('#myModal').modal('hide');
            });

            // 신청일 변경시 종료일도 변경함
            $("#al_askdatefrom").change(function() {
                var radioVal = $('input[name="al_item"]:checked').val();
                console.log(radioVal);
                $('#al_askdateto').val($("#al_askdatefrom").val());
                var result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
                switch (radioVal) {
                    case '연차':
                        $('#al_usedday').val(result);
                        break;
                    case '오전반차':
                    case '오후반차':
                        $('#al_usedday').val(result / 2);
                        break;
                    case '오전반반차':
                    case '오후반반차':
                        $('#al_usedday').val(result / 4);
                        break;
                    case '경조사':
                        $('#al_usedday').val(0);
                        break;
                }
            });

            $('input[name="al_item"]').change(function() {
                var radioVal = $('input[name="al_item"]:checked').val();
                console.log(radioVal);
                var result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;
                switch (radioVal) {
                    case '연차':
                        $('#al_usedday').val(result);
                        break;
                    case '오전반차':
                    case '오후반차':
                        $('#al_usedday').val(result / 2);
                        break;
                    case '오전반반차':
                    case '오후반반차':
                        $('#al_usedday').val(result / 4);
                        break;
                    case '경조사':
                        $('#al_usedday').val(0);
                        break;
                }
            });

            // 종료일을 변경해도 자동계산해 주기
            $("#al_askdateto").change(function() {
                var radioVal = $('input[name="al_item"]:checked').val();
                console.log(radioVal);
                var result = getDateDiff($("#al_askdatefrom").val(), $("#al_askdateto").val()) + 1;

                switch (radioVal) {
                    case '연차':
                        $('#al_usedday').val(result);
                        break;
                    case '오전반차':
                    case '오후반차':
                        $('#al_usedday').val(result / 2);
                        break;
                    case '오전반반차':
                    case '오후반반차':
                        $('#al_usedday').val(result / 4);
                        break;
                    case '경조사':
                        $('#al_usedday').val(0);
                        break;
                }
            });

            $("#closeBtn").click(function() {
                window.close(); // 현재 창 닫기
                setTimeout(function() {
                    if (window.opener && !window.opener.closed) {
                        window.opener.location.reload(); // 부모 창 새로고침
                    }
                }, 1000);
            });

            $("#saveBtn").click(function() {
                var authorList = [];
                var basicNameArr = <?= json_encode($employee_name_arr); ?>;
                var basicAlPartArr = <?= json_encode($employee_part_arr); ?>;

                // 선택된 직원 정보를 배열에 추가
                $(".author-checkbox:checked").each(function() {
                    var authorName = $(this).val();
                    var authorPart = "";

                    // PHP 배열을 이용하여 al_part 값 찾기
                    for (var i = 0; i < basicNameArr.length; i++) {
                        if (basicNameArr[i].trim() === authorName.trim()) {
                            authorPart = basicAlPartArr[i];
                            break;
                        }
                    }

                    authorList.push({
                        name: authorName,
                        al_part: authorPart,
                        author_id: $(this).data("author-id")
                    });
                });

                if (authorList.length === 0) {
                    alert("직원을 선택하세요.");
                    return;
                }

                // 필요한 데이터 수집
                var requestData = {
                    author_list: JSON.stringify(authorList),
                    registdate: $("#registdate").val(),
                    al_item: $("input[name='al_item']:checked").val(),
                    al_askdatefrom: $("#al_askdatefrom").val(),
                    al_askdateto: $("#al_askdateto").val(),
                    al_usedday: $("#al_usedday").val(),
                    al_content: $("#al_content").val()
                };

                console.log('requestData : ', requestData);

                if ((typeof ajaxRequest !== 'undefined' && ajaxRequest) || ajaxRequest !== null) {
                    ajaxRequest.abort();
                }

                ajaxRequest = $.ajax({
                    url: "insert_mass.php",
                    type: "POST",
                    data: requestData,
                    dataType: "json",
                    success: function(response) {
                        console.log(response);

                        ajaxRequest = null;

                        if (response.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "등록 완료",
                                text: "대량 등록이 성공적으로 완료되었습니다.",
                                confirmButtonText: "확인"
                            }).then(function() {
                                window.opener.location.reload(); // 부모 창 리로드
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "오류 발생",
                                text: response.message,
                                confirmButtonText: "확인"
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX 에러:", textStatus, errorThrown);
                        Swal.fire({
                            icon: "error",
                            title: "저장 실패",
                            text: "저장 중 오류가 발생했습니다. 다시 시도해주세요.",
                            confirmButtonText: "확인"
                        });
                    }
                });
            });

        }); // end of ready document

        // 두날짜 사이 일자 구하기
        function getDateDiff(d1, d2) {
            var date1 = new Date(d1);
            var date2 = new Date(d2);

            var count = 0;
            var oneDay = 24 * 60 * 60 * 1000; // 하루의 밀리세컨드 수

            while (date1 < date2) {
                var dayOfWeek = date1.getDay(); // 요일 (0:일, 1:월, ..., 6:토)
                // 토요일(6)이나 일요일(0)이 아닌 경우에만 count 증가
                if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                    count++;
                }
                date1.setTime(date1.getTime() + oneDay); // 다음 날짜로 이동
            }
            return count;
        }

    </script>
</body>
</html>