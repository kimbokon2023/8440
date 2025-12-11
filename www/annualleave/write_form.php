<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/session.php';

// 세션 변수 초기화
$level = $_SESSION["level"] ?? 0;
$user_name = $_SESSION["name"] ?? '';
$DB = $_SESSION["DB"] ?? '';

// 요청 파라미터 초기화
$num = $_REQUEST["num"] ?? '';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// rowDB.php에서 사용될 변수 초기화
$name = '';
$part = '';
$dateofentry = '';
$referencedate = '';
$availableday = 0;
$comment = '';

try {
    $sql = "select * from mirae8440.almember where num = ?";
    $stmh = $pdo->prepare($sql);
    $stmh->bindValue(1, $num, PDO::PARAM_STR);
    $stmh->execute();
    $count = $stmh->rowCount();
    $row = $stmh->fetch(PDO::FETCH_ASSOC);

    include 'rowDB.php';
} catch (PDOException $ex) {
    error_log("almember 조회 오류: " . $ex->getMessage());
}

// 배열로 기본정보 불러옴
include "load_DB.php";
?>

<?php include getDocumentRoot() . '/load_header.php' ?>

<title> 직원 연차 </title>
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
        .container {
            max-width: 100vw !important;
            padding: 5px !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
            height: auto !important;
        }

        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .row > [class*="col-"] {
            padding-left: 5px !important;
            padding-right: 5px !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        /* 카드 모바일 최적화 */
        .card {
            margin: 0.25rem 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
            border-radius: 0.5rem !important;
        }

        .card.align-middle {
            width: 100% !important;
            max-width: 100% !important;
        }

        .card-body {
            padding: 0.4rem 0.3rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }

        /* 제목 영역 모바일 최적화 */
        h3.card-title {
            font-size: 0.9rem !important;
            margin: 0.25rem 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        h5.form-signin-heading {
            font-size: 0.8rem !important;
            margin: 0.5rem 0 0.25rem 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* 입력 필드 모바일 최적화 */
        .form-control {
            font-size: 0.8rem !important;
            padding: 0.3rem 0.4rem !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            width: 100% !important;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            font-size: 0.8rem !important;
            padding: 0.3rem 0.4rem !important;
        }

        /* 버튼 모바일 최적화 */
        .btn {
            font-size: 0.75rem !important;
            padding: 0.25rem 0.4rem !important;
            white-space: nowrap !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            margin: 0.2rem 0.1rem !important;
        }

        .btn-lg {
            font-size: 0.8rem !important;
            padding: 0.3rem 0.5rem !important;
        }

        .btn-sm {
            font-size: 0.7rem !important;
            padding: 0.25rem 0.35rem !important;
        }

        /* 버튼 그룹 모바일 최적화 */
        .card-body form {
            display: flex !important;
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.3rem !important;
        }

        .card-body form .btn {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.2rem 0 !important;
        }

        /* 모든 요소가 카드 내부에 머물도록 */
        .card *,
        .container * {
            box-sizing: border-box !important;
            max-width: 100% !important;
        }

        .card button,
        .card .btn,
        .card span,
        .card input,
        .card select,
        .container button,
        .container .btn,
        .container span,
        .container input,
        .card-body * {
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

        /* 간격 최적화 */
        .mb-2 {
            margin-bottom: 0.3rem !important;
        }

        .mt-2 {
            margin-top: 0.3rem !important;
        }

        .mx-4 {
            margin-left: 0.5rem !important;
            margin-right: 0.5rem !important;
        }
    }

    /* PC 화면 텍스트 크기 1.5배 */
    @media (min-width: 769px) {
        .card-title,
        h3.card-title {
            font-size: 1.5em !important;
        }

        .form-signin-heading,
        h5.form-signin-heading {
            font-size: 1.5em !important;
        }

        .form-control,
        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            font-size: 1.5em !important;
        }

        .btn {
            font-size: 1.5em !important;
        }

        .btn-sm {
            font-size: 1.35em !important;
        }

        .btn-lg {
            font-size: 1.65em !important;
        }
    }
</style>

<body>

    <div class="container h-50">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-1"></div>
            <div class="col-12 text-center">
                <div class="card align-middle" style="width:30rem; border-radius:20px;">
                    <div class="card" style="padding:10px;margin:10px;">
                        <h3 class="card-title text-center" style="color:#113366;"> 연차일수 정보 DATA </h3>
                    </div>
                    <div class="card-body text-center">
                        <form id="board_form" name="board_form" class="form-signin" method="post" action="insert.php">
                            <input type="hidden" id="mode" name="mode">
                            <input type="hidden" id="num" name="num" value="<?=$num?>">
                            <input type="hidden" id="user_name" name="user_name" value="<?=$user_name?>" size="5">

                            <h5 class="form-signin-heading mb-2">성명</h5>
                            <input type="text" id="name" name="name" class="form-control text-center" placeholder="성명" required value="<?=$name?>">

                            <h5 class="form-signin-heading mb-2">구분</h5>
                            <input type="text" id="comment" name="comment" class="form-control text-center" placeholder="재직/퇴사" required value="<?=$comment?>">

                            <h5 class="form-signin-heading mb-2">부서</h5>
                            <select name="part" id="part" class="form-control text-center">
                                <?php
                                $part_arr = array();
                                array_push($part_arr, "지원파트", "제조파트");
                                for ($i = 0; $i < count($part_arr); $i++) {
                                    if ($part == $part_arr[$i])
                                        print "<option selected value='" . $part_arr[$i] . "'> " . $part_arr[$i] . "</option>";
                                    else
                                        print "<option value='" . $part_arr[$i] . "'> " . $part_arr[$i] . "</option>";
                                }
                                ?>
                            </select>

                            <h5 class="form-signin-heading mb-2">입사일</h5>
                            <input type="date" name="dateofentry" class="form-control text-center" placeholder="입사일" required value="<?=$dateofentry?>">

                            <h5 class="form-signin-heading mb-2">해당연도</h5>
                            <input type="number" name="referencedate" class="form-control text-center" placeholder="해당연도" required value="<?=$referencedate?>">

                            <h5 class="form-signin-heading mb-2">연차 발생일수</h5>
                            <input type="number" name="availableday" class="form-control text-center" placeholder="발생일수" required autofocus value="<?=$availableday?>"><br>

                            <button id="saveBtn" class="btn btn-lg btn-dark btn-sm" type="button">
                                <i class="bi bi-floppy-fill"></i>
                                <?php if ((int)$num > 0) print '저장'; else print '저장'; ?>
                            </button>
                            <?php if ((int)$num > 0) { ?>
                                <button id="copyBtn" class="btn btn-primary btn-sm" type="button">데이터복사</button>
                                <button id="delBtn" class="btn btn-danger btn-sm" type="button">삭제</button>
                            <?php } ?>
                            <button class="btn btn-secondary btn-sm mx-4" type="button" onclick="window.close();"> &times; 닫기</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-1"></div>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            $("#closeBtn").click(function() {
                self.close();
            });

            $("#copyBtn").click(function() {
                var user_name = $("#user_name").val();
                if (user_name == '소현철' || user_name == '김보곤') {
                    var num = $("#num").val();
                    location.href = 'copy_data.php?num=' + num;
                }
            });

            $("#saveBtn").click(function() {
                var user_name = $("#user_name").val();
                if (user_name == '소현철' || user_name == '김보곤' || user_name == '소민지') {
                    var num = $("#num").val();
                    if (Number(num) > 0)
                        $("#mode").val('modify');
                    else
                        $("#mode").val('insert');

                    $.ajax({
                        url: "insert.php",
                        type: "post",
                        data: $("#board_form").serialize(),
                        dataType: "json",
                        success: function(data) {
                            console.log(data);
                            opener.location.reload();
                            window.close();
                        },
                        error: function(jqxhr, status, error) {
                            console.log(jqxhr, status, error);
                        }
                    });
                }
            });

            $("#delBtn").click(function() {
                var user_name = $("#user_name").val();
                if (user_name == '소현철' || user_name == '김보곤') {
                    var num = $("#num").val();

                    // DATA 삭제버튼 클릭시
                    Swal.fire({
                        title: '해당 DATA 삭제',
                        text: " DATA 삭제는 신중하셔야 합니다. '\n 정말 삭제 하시겠습니까?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '삭제',
                        cancelButtonText: '취소'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            $("#mode").val('delete');

                            $.ajax({
                                url: "insert.php",
                                type: "post",
                                data: $("#board_form").serialize(),
                                dataType: "json",
                                success: function(data) {
                                    console.log(data);
                                    opener.location.reload();
                                    window.close();
                                },
                                error: function(jqxhr, status, error) {
                                    console.log(jqxhr, status, error);
                                }
                            });
                        }
                    });
                }
            });

        }); // end of ready document

    </script>
</body>
</html>