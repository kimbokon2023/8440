
<div class="container">

<?php
// 접수중인 VOC 목록 나타내기
$sqltemp = "SELECT * FROM mirae8440.voc WHERE content <> '' AND is_html = '1' ORDER BY num DESC";

// 전체 레코드수를 파악한다.
try {
    $stmhtmp = $pdo->query($sqltemp);
    $voc = $stmhtmp->rowCount();

    if ((int)$voc > 0) {
?>
<style>
    table.table-bordered.table-hover tbody tr:hover {
        background-color: lightgray;
    }
</style>
<div class="d-flex justify-content-center">
    <div class="card mt-3" style="width:70%;">
        <div class="card-header mt-1 mb-1 p-2 justify-content-center text-center">
            <span class="fs-6 text-center text-danger fw-bold">미래소장 VOC 등록 (긴급 처리요함)</span>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-center">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th class="col text-center">현장명</th>
                            <th class="col text-center">내용</th>
                            <th class="col text-center">미래소장</th>
                            <th class="col text-center">등록일</th>
                            <th class="col text-center">처리상황</th>
                        </tr>
                    </thead>
                    <tbody>		   

<?php  	 
        while ($row = $stmhtmp->fetch(PDO::FETCH_ASSOC)) {
            $item_num = $row["num"] ?? '';
            $item_id = $row["id"] ?? '';
            $item_name = $row["name"] ?? '';
            $is_html = $row["is_html"] ?? '';
            $item_date = $row["regist_day"] ?? '';
            $item_date = substr($item_date, 0, 10);
            $item_subject = iconv_substr($row["subject"] ?? '', 0, 60, "utf-8");
            $item_content = iconv_substr($row["content"] ?? '', 0, 60, "utf-8");
            $num_ripple = 0; // Initialize variable
 ?>
 
<tr onclick="toView('<?=$item_num?>')" >
                            <td class="p-2"><?= $item_subject ?>
                                <?php
                                if ($num_ripple) {
                                    echo "[<font color=red><b>$num_ripple</b></font>]";
                                }
                                ?>
                            </td>
                            <td class="p-2"><?= $item_content ?></td>
                            <td class="text-center p-2"><?= $item_name ?></td>
                            <td class="text-center p-2"><?= $item_date ?></td>
                            <td class="text-center p-2">
                                <?php
                                if ($is_html == '1') {
                                    echo '<span class="blinking" style="color:red">접수 중</span>';
                                } else {
                                    echo "확인완료";
                                }
                                ?>
                            </td>
                        </tr>

<?php
        }
    }
} catch (PDOException $Exception) {
    echo "오류: " . $Exception->getMessage();
}
?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toView(num) {
    popupCenter('../work_voc/view.php?num=' + num, '소장 VOC', 1400, 850);
}
</script>