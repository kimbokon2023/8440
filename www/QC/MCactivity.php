<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();

// 기초 자료를 불러오는 코드
include getDocumentRoot() . "/qc/load_DB.php";
?>

<div class="container">
    <div class="card mt-2 mb-2">
        <div class="card-body">            
            <h5 class="fw-bolder mb-4">점검 장비 - 실제 점검한 세부사항은 QR코드 클릭</h5>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php
                for ($i = 0; $i < count($mcnum_arr); $i++) {
                    $num = $mcnum_arr[$i];
                    $mcno = $mcno_arr[$i];
                    $mcname = $mcname_arr[$i];
                    $mcspec = $mcspec_arr[$i];
                    $mcmaker = $mcmaker_arr[$i];
                    $mcmain = $mcmain_arr[$i];
                    $mcsub = $mcsub_arr[$i];
                    $qrcode = $qrcode_arr[$i];					
                ?>
                    <div class="col mb-2">
                        <div class="card h-100" onclick="choiceMC(<?= $num ?>, '<?= $mcmain ?>', '<?= $mcsub ?>', '<?= $mcno ?>');">
                            <!-- 장비 정보-->
                            <div class="card-body p-2">
                                <div class="text-center">
                                    <h5 class="fw-bolder"><?= $mcname ?></h5>
                                </div>
                                <div class="text-center">
                                    <span class="fw-bolder"><?= $mcspec ?></span>
                                </div>
                                <div class="text-center">
                                    <span class="fw-bolder">점검(정): <?= $mcmain ?></span>
                                </div>
                                <div class="text-center">
                                    <span class="fw-bolder">점검(부): <?= $mcsub ?></span>
                                </div>
                                <div class="text-center">
                                    <span class="fw-bolder">
                                        <img src="<?= $qrcode_tmp ?>" style="width:100%;height:100%;">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
