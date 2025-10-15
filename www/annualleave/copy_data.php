<?php
require_once __DIR__ . '/../common/functions.php';
require_once getDocumentRoot() . '/session.php';

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
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <style>
        html,
        body {
            height: 100%;
        }
    </style>
</head>


<body>
    <div class="container h-50">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-1"></div>
            <div class="col-12 text-center">
                <div class="card align-middle" style="width:30rem; border-radius:20px;">
                    <div class="card" style="padding:10px;margin:10px;">
                        <h3 class="card-title text-center" style="color:#113366;"> 연차일수 정보 등록/조회/수정 </h3>
                    </div>
                    <div class="card-body text-center">
                        <form id="board_form" class="form-signin" method="post" action="insert.php">
                            <input type="hidden" id="mode" name="mode">
                            <input type="hidden" id="num" name="num" value="<?= $num ?>">

                            <h5 class="form-signin-heading">성명</h5>
                            <input type="text" name="name" class="form-control" placeholder="성명" required value="<?= $name ?>">

                            <h5 class="form-signin-heading">부서</h5>
                            <select name="part" id="part" class="form-control">
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

                            <h5 class="form-signin-heading">입사일</h5>
                            <input type="date" name="dateofentry" class="form-control" placeholder="입사일" required value="<?= $dateofentry ?>">

                            <h5 class="form-signin-heading">해당연도</h5>
                            <input type="number" name="referencedate" class="form-control" placeholder="해당연도" required value="<?= $referencedate ?>">

                            <h5 class="form-signin-heading">연차 발생일수</h5>
                            <input type="number" name="availableday" class="form-control" placeholder="발생일수" required autofocus value="<?= $availableday ?>"><br>

                            <button id="saveBtn" class="btn btn-lg btn-secondary btn-block" type="button">등록</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </div>

    <form id=Form1 name="Form1">
        <input type=hidden id="steelcompany" name="steelcompany[]">
    </form>

    <script>
        $(document).ready(function() {
            $("#closeBtn").click(function() {
                // 저장하고 창닫기
            });

            $("#saveBtn").click(function() {
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
            });

            $("#deldataBtn").click(function() {
                deldataDo();
            });

            $("#SelInsertDataBtn").click(function() {
                SelInsertData();
            });
        });
    </script>
</body>

</html>


