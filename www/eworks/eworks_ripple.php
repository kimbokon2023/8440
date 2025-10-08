<?php
require_once __DIR__ . '/../bootstrap.php';

isset($_REQUEST["e_num"]) ? $e_num = $_REQUEST["e_num"] : $e_num = "";
isset($_REQUEST["page"]) ? $page = $_REQUEST["page"] : $page = 1;
?>

<div class="row p-1 m-1 mt-1 mb-1 justify-content-center">
    <?php
    try {
        $sql_ripple = "SELECT * FROM mirae8440.eworks_ripple WHERE parent=? AND is_deleted IS NULL";
        $stmh = $pdo->prepare($sql_ripple);
        $stmh->bindValue(1, $e_num, PDO::PARAM_STR);
        $stmh->execute();

        while ($row_ripple = $stmh->fetch(PDO::FETCH_ASSOC)) {
            $ripple_num = $row_ripple["num"];
            $ripple_id = $row_ripple["author_id"];
            $ripple_nick = $row_ripple["author"];
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
                            if ($_SESSION["userid"] == "admin" || $_SESSION["userid"] == $ripple_id || $_SESSION["level"] == 1) {
                                echo "<a href='#' class='text-danger' onclick='eworks_delete_ripple(\"$ripple_num\")'> <i class='bi bi-trash'></i> </a>";
                            }
                        }
                        ?>
                        </span>
                    </div>
                </div>
            </div>
            <?php
        }
    } catch (PDOException $e) {
        // You can log the error or handle it as needed
        // error_log("Error fetching ripples: " . $e->getMessage());
    }
    ?>
</div>
