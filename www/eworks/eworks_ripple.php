<?php

if (!isset($_SESSION)) {
    session_start();
}

$level = $_SESSION["level"];
$user_name = $_SESSION["name"];
$user_id = $_SESSION["userid"];

isset($_REQUEST["e_num"]) ? $e_num = $_REQUEST["e_num"] : $e_num = "";
isset($_REQUEST["page"]) ? $page = $_REQUEST["page"] : $page = 1;

// 데이터베이스 연결

require_once("eworksmydb.php");

$tablename = 'eworks_ripple';

// MySQL 연결 오류 발생 시 스크립트 종료
if (mysqli_connect_errno()) {
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}

?>

<div class="row p-1 m-1 mt-1 mb-1 justify-content-center">
    <?php
    $sql_ripple = "SELECT * FROM mirae8440.eworks_ripple WHERE parent=?";
    if ($stmt = mysqli_prepare($conn, $sql_ripple)) {
        mysqli_stmt_bind_param($stmt, "s", $e_num);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        while ($row_ripple = mysqli_fetch_assoc($result)) {
            $ripple_num = $row_ripple["num"];
            $ripple_id = $row_ripple["id"];
            $ripple_nick = $row_ripple["nick"];
            $ripple_content = str_replace("\n", "", $row_ripple["content"]);
            $ripple_content = str_replace(" ", "&nbsp;", $ripple_content);
            $ripple_date = $row_ripple["regist_day"];
            ?>
            <div class="card" style="width:80%">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <span class="mt-1 mb-2">▶&nbsp;&nbsp;<?=$ripple_content?> ✔&nbsp;&nbsp;작성자: <?=$ripple_nick?> | <?=$ripple_date?>
                        <?php
                        if (isset($_SESSION["userid"])) {
                            if ($_SESSION["userid"] == "admin" || $_SESSION["userid"] == $ripple_id || $_SESSION["level"] === 1) {
                                echo "<a href='#' onclick='rippledelete(\"$tablename\", \"$e_num\", \"$ripple_num\", \"$page\")'>[삭제]</a>";
                            }
                        }
                        ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php
        }
        mysqli_stmt_close($stmt);
    }
    ?>
</div>

<script>
function rippledelete(tablename, e_num, ripple_num, page) {
    Swal.fire({
        title: '댓글 삭제',
        text: "정말 삭제하시겠습니까?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '삭제',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `delete_ripple.php?tablename=${tablename}&e_num=${e_num}&ripple_num=${ripple_num}&page=${page}`;
        }
    });
}
</script>
