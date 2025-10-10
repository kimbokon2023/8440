<?php
include getDocumentRoot() . '/load_header.php';

require_once("../lib/mydb.php");
$pdo = db_connect();

// 기간을 정하는 구간
$todate = date("Y-m-d");   // 현재일자 변수지정

// 변수 초기화
$num = isset($_REQUEST["num"]) ? $_REQUEST["num"] : "";
$laserdueday = "";

$common = " where num=$num ";

$sql = "select * from mirae8440.ceiling " . $common;

$nowday = date("Y-m-d");   // 현재일자 변수지정

$stmh = $pdo->query($sql);

$row = $stmh->fetch(PDO::FETCH_ASSOC);

if ($row) {
    include '_rowDB.php';
}

?>

<title>laser 예정일 변경</title>
</head>

<body>
    <div class="container" style="width:280px;">
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex p-1 m-1 mt-1 mb-4 justify-content-center align-items-center">
                    <span class="badge bg-secondary fs-6">&nbsp;&nbsp; laser 예정일 변경</span>
                </div>
                
                <div class="d-flex p-1 m-1 mt-1 mb-4 justify-content-center align-items-center">
                    <form id="board_form" name="board_form" method="post">
                        <input type="hidden" id="num" name="num" value="<?=$num?>">
                        &nbsp;&nbsp;
                        <input type="date" id="laserdueday" name="laserdueday" value="<?=$laserdueday?>">
                        <button type="button" class="btn btn-secondary btn-sm mb-2" id="saveBtn">저장</button>
                        &nbsp;
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>   

<script>
window.addEventListener('load', function() {
    var laserdueday = document.getElementById('laserdueday');
    laserdueday.focus();
});

$(document).ready(function() {
    $("#saveBtn").click(function() {
        $.ajax({
            url: "/ceiling/updatedayprocess.php",
            type: "post",
            data: $("#board_form").serialize(),
            dataType: "json",
            success: function(data) {
                console.log(data);
                opener.location.reload();
                self.close();
            },
            error: function(jqxhr, status, error) {
                console.log(jqxhr, status, error);
            }
        });
    });
});
</script>
