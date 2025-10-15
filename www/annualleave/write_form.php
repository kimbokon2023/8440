<?php
require_once __DIR__ . '/../common/functions.php';
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