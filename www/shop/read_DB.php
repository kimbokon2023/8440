<?php
session_start();
header('Content-Type: text/html; charset=utf-8');

// Request variables with null safety
$num = $_REQUEST["num"] ?? '';

if(empty($num)) {
    echo '<div class="alert alert-warning">작품 번호가 없습니다.</div>';
    exit;
}

require_once("../lib/mydb.php");
$pdo = db_connect();

try{
    $sql = "select * from mirae8440.shopitem where num = ?";
    $stmh = $pdo->prepare($sql);  
    $stmh->bindValue(1, $num, PDO::PARAM_INT);
    $stmh->execute();                  
    $row = $stmh->fetch(PDO::FETCH_ASSOC);
    
    if(!$row) {
        echo '<div class="alert alert-warning">데이터를 찾을 수 없습니다.</div>';
        exit;
    }
    
    $num = $row["num"];
    $catagory = $row["catagory"];
    $dporder = $row["dporder"];
    $item = $row["item"];
    $itemdes = $row["itemdes"];
    $sale = $row["sale"];
    $price = $row["price"];
    $saleprice = $row["saleprice"];
    $filename1 = $row["filename1"];
    $youtube1 = $row["youtube1"];
    $youtube2 = $row["youtube2"];
    
    // HTML 출력
    ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <?php if($filename1 && $filename1 != ''): ?>
                    <img src="<?= $filename1 ?>" class="img-fluid mb-3" alt="<?= $item ?>" style="max-height: 400px; object-fit: contain;">
                <?php else: ?>
                    <div class="alert alert-secondary">이미지가 없습니다.</div>
                <?php endif; ?>
                
                <?php if($youtube1 && $youtube1 != ''): ?>
                    <h6 class="mt-3">레이저컷 영상</h6>
                    <div class="ratio ratio-16x9 mb-3">
                        <iframe src="<?= $youtube1 ?>" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
                
                <?php if($youtube2 && $youtube2 != ''): ?>
                    <h6>완성품 영상</h6>
                    <div class="ratio ratio-16x9">
                        <iframe src="<?= $youtube2 ?>" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%" class="table-light">작품번호</th>
                            <td><?= $num ?></td>
                        </tr>
                        <tr>
                            <th class="table-light">카테고리</th>
                            <td><?= $catagory ?></td>
                        </tr>
                        <tr>
                            <th class="table-light">DP 순서</th>
                            <td><?= $dporder ?></td>
                        </tr>
                        <tr>
                            <th class="table-light">아이템명</th>
                            <td><strong><?= $item ?></strong></td>
                        </tr>
                        <tr>
                            <th class="table-light">상세설명</th>
                            <td style="white-space: pre-wrap;"><?= $itemdes ?></td>
                        </tr>
                        <tr>
                            <th class="table-light">Sale 여부</th>
                            <td><span class="badge bg-<?= $sale == '적용' ? 'danger' : 'secondary' ?>"><?= $sale ?></span></td>
                        </tr>
                        <tr>
                            <th class="table-light">최초 가격</th>
                            <td><span class="text-primary fw-bold"><?= $price > 0 ? number_format($price) . '원' : '-' ?></span></td>
                        </tr>
                        <tr>
                            <th class="table-light">판매 가격</th>
                            <td>
                                <span class="text-danger fw-bold fs-5"><?= $saleprice > 0 ? number_format($saleprice) . '원' : '-' ?></span>
                                <?php if($price > 0 && $saleprice > 0 && $price > $saleprice): ?>
                                    <span class="badge bg-warning text-dark ms-2"><?= round(($price - $saleprice) / $price * 100) ?>% 할인</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    
} catch (PDOException $Exception) {
    echo '<div class="alert alert-danger">오류: ' . $Exception->getMessage() . '</div>';
}
?>
