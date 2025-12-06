<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 체크 (필요시)
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 6) {
    echo "<script>alert('접근 권한이 없습니다.'); history.back();</script>";
    exit;
}

// 1. 전체 멤버 불러오기 (퇴사자 제외)
$sql_members = "SELECT id, name, part, position FROM mirae8440.member WHERE position != '퇴사' ORDER BY name ASC";
$stmt_members = $pdo->query($sql_members);
$all_members = $stmt_members->fetchAll(PDO::FETCH_ASSOC);

// 2. 현재 설정된 관리자 불러오기
$sql_admins = "SELECT member_id, member_name FROM admin_phomi ORDER BY rank_order ASC";
$stmt_admins = $pdo->query($sql_admins);
$current_admins = $stmt_admins->fetchAll(PDO::FETCH_ASSOC);

// ID만 추출하여 비교용 배열 생성
$admin_ids = array_column($current_admins, 'member_id');

?>
<?php include getDocumentRoot() . '/load_header.php'; ?>
<title>포미스톤 관리자 알람 설정</title>
<style>
    .sortable-list {
        border: 1px solid #ccc;
        min-height: 400px;
        list-style-type: none;
        margin: 0;
        padding: 5px;
        background-color: #f9f9f9;
        max-height: 600px;
        overflow-y: auto;
    }
    .sortable-list li {
        margin: 5px;
        padding: 10px;
        background-color: #fff;
        border: 1px solid #ddd;
        cursor: move;
        border-radius: 4px;
        font-size: 14px;
    }
    .sortable-list li:hover {
        background-color: #e9ecef;
    }
    .sortable-list li.ui-sortable-helper {
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .list-header {
        font-weight: bold;
        text-align: center;
        padding: 10px;
        background-color: #343a40;
        color: white;
        border-radius: 5px 5px 0 0;
    }
    .arrow-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
        color: #6c757d;
    }
    @media (max-width: 768px) {
        .arrow-icon {
            transform: rotate(90deg);
            margin: 10px 0;
        }
    }
</style>

<?php include getDocumentRoot() . '/myheader.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-bell-fill text-warning"></i> 포미스톤 관리자 알람 설정</h3>
        <button type="button" class="btn btn-primary" id="btnSave">
            <i class="bi bi-save"></i> 설정 저장
        </button>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> 왼쪽 목록에서 멤버를 선택하여 오른쪽 '알람 수신 관리자' 목록으로 드래그하세요. 순서를 변경할 수 있습니다.
    </div>

    <div class="row">
        <!-- 전체 멤버 목록 -->
        <div class="col-md-5">
            <div class="list-header">전체 멤버 (퇴사자 제외)</div>
            <ul id="allMembers" class="sortable-list connectedSortable">
                <?php foreach ($all_members as $member): ?>
                    <?php if (!in_array($member['id'], $admin_ids)): ?>
                        <li data-id="<?= $member['id'] ?>" data-name="<?= $member['name'] ?>">
                            <?= $member['name'] ?> (<?= $member['part'] ?>/<?= $member['position'] ?>)
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 화살표 아이콘 (PC: 가로, 모바일: 세로) -->
        <div class="col-md-2 arrow-icon">
            <i class="bi bi-arrow-left-right d-none d-md-block"></i>
            <i class="bi bi-arrow-down-up d-md-none"></i>
        </div>

        <!-- 선택된 관리자 목록 -->
        <div class="col-md-5">
            <div class="list-header bg-success">알람 수신 관리자</div>
            <ul id="selectedAdmins" class="sortable-list connectedSortable">
                <?php foreach ($current_admins as $admin): ?>
                    <li data-id="<?= $admin['member_id'] ?>" data-name="<?= $admin['member_name'] ?>">
                        <?= $admin['member_name'] ?>
                        <span class="float-end text-danger remove-item" style="cursor:pointer;"><i class="bi bi-x-circle"></i></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<!-- jQuery UI 로드 (없을 경우를 대비해 CDN 추가, 하지만 myheader에서 로드되었을 수 있음) -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script>
$(document).ready(function() {
    // Sortable 설정
    $("#allMembers, #selectedAdmins").sortable({
        connectWith: ".connectedSortable",
        placeholder: "ui-state-highlight",
        receive: function(event, ui) {
            // 오른쪽 목록으로 이동했을 때 삭제 버튼 추가
            if ($(this).attr('id') === 'selectedAdmins') {
                 if (ui.item.find('.remove-item').length === 0) {
                     ui.item.append('<span class="float-end text-danger remove-item" style="cursor:pointer;"><i class="bi bi-x-circle"></i></span>');
                 }
            }
            // 왼쪽 목록으로 이동했을 때 삭제 버튼 제거
            else {
                ui.item.find('.remove-item').remove();
            }
        }
    }).disableSelection();

    // 삭제 버튼 클릭 이벤트 (이벤트 위임)
    $(document).on('click', '.remove-item', function() {
        var $li = $(this).closest('li');
        // '전체 멤버' 리스트로 이동
        $li.find('.remove-item').remove();
        $('#allMembers').append($li);
    });

    // 저장 버튼 클릭
    $("#btnSave").click(function() {
        var admins = [];
        $("#selectedAdmins li").each(function(index) {
            admins.push({
                member_id: $(this).data('id'),
                member_name: $(this).data('name'),
                rank_order: index + 1
            });
        });

        $.ajax({
            url: 'save_admin_alarm.php',
            type: 'POST',
            data: { admins: JSON.stringify(admins) },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '저장 완료',
                        text: '관리자 알람 설정이 저장되었습니다.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '오류',
                        text: '저장 중 오류가 발생했습니다: ' + response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: '통신 오류',
                    text: '서버와 통신 중 오류가 발생했습니다.'
                });
            }
        });
    });
});
</script>
