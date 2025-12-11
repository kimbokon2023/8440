<?php
require_once __DIR__ . '/../bootstrap.php';
require_once getDocumentRoot() . '/load_GoogleDrive.php';

// 세션 변수 초기화
$WebSite = $_SESSION["WebSite"] ?? '';
$user_id = $_SESSION["userid"] ?? '';
$userid = $_SESSION["userid"] ?? '';
$level = $_SESSION["level"] ?? 0;
$admin = $_SESSION["admin"] ?? 0;

// 첫 화면 표시 문구
$title_message = 'AI prompt';
?>

<?php
include getDocumentRoot() . '/load_header.php';

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}
?>
<title> <?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?> </title>

<style>
    /* 모바일 환경 최적화 */
    @media (max-width: 768px) {
        /* body와 html 오버플로우 방지 */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }
        
        * {
            max-width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* 컨테이너 최적화 */
        .container,
        .container-fluid {
            padding: 0.5rem !important;
            max-width: 100vw !important;
            width: 100% !important;
            box-sizing: border-box !important;
            margin: 0 auto !important;
            overflow-x: hidden !important;
        }
        
        /* 카드 최적화 */
        .card {
            margin: 0.5rem auto !important;
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
            box-sizing: border-box !important;
            overflow-x: hidden !important;
        }
        
        .card-body {
            padding: 0.75rem !important;
            overflow-x: hidden !important;
        }
        
        /* 제목 영역 최적화 */
        .d-flex.mt-3.mb-4.justify-content-center h5 {
            font-size: 1.25rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            text-align: center !important;
        }
        
        /* 버튼 그룹 최적화 */
        .d-flex.p-1.m-1.mt-1.mb-1.justify-content-left {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
            padding: 0.5rem !important;
        }
        
        .d-flex.p-1.m-1.mt-1.mb-1.justify-content-left button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0.25rem 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
        
        /* 제목/정보 영역 최적화 */
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-center.bg-secondary {
            flex-direction: column !important;
            align-items: stretch !important;
            padding: 0.75rem !important;
            margin: 0.5rem 0 !important;
        }
        
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-center.bg-secondary .col-7,
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-center.bg-secondary .col-5 {
            width: 100% !important;
            max-width: 100% !important;
            text-align: left !important;
            margin-bottom: 0.5rem !important;
        }
        
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-center.bg-secondary .col-5 {
            text-align: left !important;
            font-size: 0.875rem !important;
        }
        
        /* 콘텐츠 영역 최적화 */
        .row.d-flex.p-2.m-2.mt-1.mb-1.justify-content-left {
            padding: 0.75rem !important;
            margin: 0.5rem 0 !important;
            overflow-x: hidden !important;
        }
        
        /* iframe 최적화 */
        iframe {
            width: 100% !important;
            max-width: 100% !important;
            height: 400px !important;
            min-height: 300px !important;
            box-sizing: border-box !important;
        }
        
        /* 이미지 최적화 */
        #displayImage img {
            max-width: 100% !important;
            height: auto !important;
            width: auto !important;
        }
        
        #displayImage .row {
            margin: 0.5rem 0 !important;
        }
        
        #displayImage .d-flex {
            flex-direction: column !important;
            align-items: center !important;
        }
        
        /* 파일 링크 최적화 */
        #displayFile {
            padding: 0.5rem !important;
            margin: 0.5rem 0 !important;
        }
        
        #displayFile .row {
            margin: 0.5rem 0 !important;
        }
        
        #displayFile a {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            max-width: 100% !important;
            display: inline-block !important;
        }
        
        /* 텍스트 오버플로우 방지 */
        * {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 텍스트 요소 강제 줄바꿈 */
        p, div, h1, h2, h3, h4, h5, h6, label, strong, em, b, i, u, span, td, th {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* span 요소 줄바꿈 처리 */
        span {
            display: inline-block !important;
            overflow: visible !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        
        /* 모든 div 요소 오버플로우 방지 */
        div {
            max-width: 100vw !important;
            overflow-x: hidden !important;
            box-sizing: border-box !important;
        }
        
        /* '기간' 버튼 숨기기 */
        #showdate {
            display: none !important;
        }
        
        /* 모달 최적화 */
        .modal {
            padding: 0 !important;
            overflow: hidden !important;
        }
        
        .modal-dialog {
            margin: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
        }
        
        .modal-content {
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: 100vh !important;
            max-height: 100vh !important;
            border-radius: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            box-sizing: border-box !important;
        }
        
        .modal-header {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-title {
            font-size: 1rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }
        
        .modal-body {
            flex: 1 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            padding: 0.75rem !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            -webkit-overflow-scrolling: touch !important;
        }
        
        .modal-footer {
            padding: 0.75rem 0.5rem !important;
            flex-shrink: 0 !important;
            flex-direction: column !important;
            gap: 0.5rem !important;
        }
        
        .modal-footer button {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0.5rem !important;
            font-size: 1rem !important;
        }
    }
</style>

</head>

<body>
    <?php include getDocumentRoot() . "/common/modal.php"; ?>

    <?php
    // 요청 파라미터 초기화
    $num = $_REQUEST["num"] ?? '';
    $page = $_REQUEST["page"] ?? 1;
    $tablename = $_REQUEST["tablename"] ?? '';
    
    // 기타 변수 초기화
    $search = $_REQUEST["search"] ?? '';
    $Bigsearch = $_REQUEST["Bigsearch"] ?? '';
    $find = $_REQUEST["find"] ?? '';
    $year = $_REQUEST["year"] ?? '';
    $process = $_REQUEST["process"] ?? '';
    $asprocess = $_REQUEST["asprocess"] ?? '';
    $fromdate = $_REQUEST["fromdate"] ?? '';
    $todate = $_REQUEST["todate"] ?? '';
    $separate_date = $_REQUEST["separate_date"] ?? '';
    $item = '';
    $mode = '';
    $timekey = date('YmdHis');
    $noticecheck_memo = '';

    require_once(includePath('lib/mydb.php'));
    $pdo = db_connect();

    // 변수 초기화
    $item_content = '';

    try {
        $sql = "select * from mirae8440." . $tablename . " where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $num, PDO::PARAM_STR);
        $stmh->execute();

        $row = $stmh->fetch(PDO::FETCH_ASSOC);
        $item_num = $row["num"];
        $item_id = $row["id"];
        $item_name = $row["name"];
        $item_nick = $row["nick"];
        $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
        $content = $row["content"];
        $item_date = $row["regist_day"];
        $item_date = substr($item_date, 0, 10);
        $item_hit = $row["hit"];
        $is_html = $row["is_html"];
        $division = $row["division"];
        $searchtext = $row["searchtext"];
    } catch (PDOException $ex) {
        error_log($tablename . " 조회 오류: " . $ex->getMessage());
    }

    if ($is_html != "y") {
        $item_content = str_replace(" ", "&nbsp;", $item_content);
        $item_content = str_replace("\n", "<br>", $item_content);
    }

    $new_hit = $item_hit + 1;
    try {
        $pdo->beginTransaction();
        $sql = "update mirae8440." . $tablename . " set hit=? where num=?";
        $stmh = $pdo->prepare($sql);
        $stmh->bindValue(1, $new_hit, PDO::PARAM_STR);
        $stmh->bindValue(2, $num, PDO::PARAM_STR);
        $stmh->execute();
        $pdo->commit();
    } catch (PDOException $ex) {
        $pdo->rollBack();
        error_log($tablename . " 조회수 업데이트 오류: " . $ex->getMessage());
    }

    // 초기 프로그램은 $num사용 이후 $id로 수정중임
    $id = $num;
    $author_id = $item_id;

    require_once getDocumentRoot() . '/load_GoogleDriveSecond.php';
    
    // load_GoogleDriveSecond.php에서 정의될 변수들 초기화 (정의되지 않은 경우 대비)
    $savefilename_arr = $savefilename_arr ?? array();
    $saveimagename_arr = $saveimagename_arr ?? array();
    ?>

    <form id="board_form" name="board_form" method="post" enctype="multipart/form-data">
        <input type="hidden" id="tablename" name="tablename" value="<?= $tablename ?>">
        <input type="hidden" id="id" name="id" value="<?= $id ?>">
        <input type="hidden" id="num" name="num" value="<?= $num ?>">
        <input type="hidden" id="item" name="item" value="<?= $item ?>">
        <input type="hidden" id="mode" name="mode" value="<?= $mode ?>">
        <input type="hidden" id="timekey" name="timekey" value="<?= $timekey ?>">
    </form>

    <div class="container">
        <div class="card mt-2 mb-4">
            <div class="card-body">
                <div class="d-flex mt-3 mb-4 justify-content-center">
                    <h5>  <?= htmlspecialchars($title_message, ENT_QUOTES, 'UTF-8') ?> </h5>
                </div>
                <div class="d-flex p-1 m-1 mt-1 mb-1 justify-content-left align-items-center">
                    <button type="button" id="closeBtn" class="btn btn-dark btn-sm me-1"> &times; 닫기 </button>
                    <?php
                    // 삭제 수정은 관리자와 글쓴이만 가능토록 함
                    if ($_SESSION["userid"] == $item_id || $_SESSION["userid"] == "admin" || $_SESSION["level"] === 1) {
                        // URL 파라미터를 배열로 만들어서 안전하게 처리
                        $modifyParams = http_build_query(array(
                            'tablename' => $tablename,
                            'mode' => 'modify',
                            'num' => $num,
                            'page' => $page,
                            'search' => $search,
                            'Bigsearch' => $Bigsearch,
                            'find' => $find,
                            'year' => $year,
                            'process' => $process,
                            'asprocess' => $asprocess,
                            'fromdate' => $fromdate,
                            'todate' => $todate,
                            'separate_date' => $separate_date
                        ));
                    ?>
                        <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?<?= htmlspecialchars($modifyParams, ENT_QUOTES, 'UTF-8') ?>'">  <i class="bi bi-pencil-square"></i>  수정  </button>
                        <button type="button" class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>'">  <i class="bi bi-pencil"></i>  신규 </button>
                        <button type="button" class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?tablename=<?= htmlspecialchars($tablename, ENT_QUOTES, 'UTF-8') ?>&num=<?= htmlspecialchars($num, ENT_QUOTES, 'UTF-8') ?>&page=<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>')"> <i class="bi bi-trash"></i>  삭제   </button>
                    <?php } ?>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-center bg-secondary text-white align-items-center">
                            <div class="col-7 text-start fw-bold fs-6"> 구분 : <?= htmlspecialchars($division, ENT_QUOTES, 'UTF-8') ?> | <?= $item_subject ?> </div>
                            <div class="col-5 text-end"> <?= htmlspecialchars($noticecheck_memo, ENT_QUOTES, 'UTF-8') ?> |<?= htmlspecialchars($item_nick, ENT_QUOTES, 'UTF-8') ?> | 조회 : <?= htmlspecialchars($item_hit, ENT_QUOTES, 'UTF-8') ?> | <?= htmlspecialchars($item_date, ENT_QUOTES, 'UTF-8') ?>   </div>
                        </div>

                        <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-left">
                            <iframe style="width: 100%; height: 500px; border: none;" sandbox="allow-same-origin allow-scripts" srcdoc="<?php echo htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); ?>">
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="row d-flex p-2 m-2 mt-1 mb-1 justify-content-left">
                    <div id="displayImage" class="row d-flex mt-1 mb-1 justify-content-center" style="display:none;">
                    </div>

                    <div id="displayFile" class="d-flex mt-1 mb-1 justify-content-center" style="display:none;">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#closeModalBtn").click(function() {
                $('#myModal').modal('hide');
            });

            // 하단복사 버튼
            $("#closeBtn1").click(function() {
                $("#closeBtn").click();
            })

            $("#closeBtn").click(function() {
                self.close();
            });
        });

        function del(href) {
            var user_id = '<?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?>';
            var author_id = '<?php echo htmlspecialchars($author_id, ENT_QUOTES, 'UTF-8'); ?>';
            var admin = '<?php echo htmlspecialchars($admin, ENT_QUOTES, 'UTF-8'); ?>';
            if (user_id !== author_id && admin !== '1') {
                Swal.fire({
                    title: '삭제불가',
                    text: "작성자와 관리자만 삭제가능합니다.",
                    icon: 'error',
                    confirmButtonText: '확인'
                });
            } else {
                Swal.fire({
                    title: '자료 삭제',
                    text: "삭제는 신중! 정말 삭제하시겠습니까?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '삭제',
                    cancelButtonText: '취소'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "delete.php",
                            type: "post",
                            data: $("#board_form").serialize(),
                            dataType: "json",
                            success: function(data) {
                                console.log(data);
                                Toastify({
                                    text: "파일 삭제완료 ",
                                    duration: 2000,
                                    close: true,
                                    gravity: "top",
                                    position: "center",
                                    style: {
                                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                                    },
                                }).showToast();
                                setTimeout(function() {
                                    if (window.opener && !window.opener.closed) {
                                        window.opener.restorePageNumber();
                                        window.opener.location.reload();
                                        window.close();
                                    }
                                }, 1000);
                            },
                            error: function(jqxhr, status, error) {
                                console.log(jqxhr, status, error);
                            }
                        });
                    }
                });
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            displayFileLoad();
            displayImageLoad();
        });

        // 기존 있는 이미지 화면에 보여주기
        function displayImageLoad() {
            $('#displayImage').show();
            var saveimagename_arr = <?php echo json_encode($saveimagename_arr); ?>;

            $("#displayImage").html('');
            saveimagename_arr.forEach(function(pic, index) {
                var thumbnail = pic.thumbnail || '/assets/default-thumbnail.png';
                var realName = pic.realname || '다운로드 파일';
                var link = pic.link || '#';
                var fileId = pic.fileId || null;

                if (!fileId) {
                    console.error("fileId가 누락되었습니다. index: " + index, pic);
                    return;
                }

                // HTTPS 변환 (Mixed Content 방지)
                if (window.location.protocol === 'https:' && thumbnail.startsWith('http:')) {
                    thumbnail = thumbnail.replace('http:', 'https:');
                }
                if (window.location.protocol === 'https:' && link.startsWith('http:')) {
                    link = link.replace('http:', 'https:');
                }
                
                $("#displayImage").append(
                    "<div class='row mt-2 mb-1'>" +
                    "<div class='d-flex justify-content-center mt-1 mb-1'>" +
                    "<a href='#' onclick=\"popupCenter('" + link + "', 'imagePopup', 800, 600); return false;\">" +
                    "<img id='pic" + index + "' src='" + thumbnail + "' class='img-fluid' style='max-width:100%; height:auto;'>" +
                    "</a>" +
                    "</div>" +
                    "</div>"
                );
            });
        }

        // 기존 파일 불러오기 (Google Drive에서 가져오기)
        function displayFileLoad() {
            $('#displayFile').show();
            var data = <?php echo json_encode($savefilename_arr); ?>;

            $("#displayFile").html('');

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(fileData, i) {
                    var realName = fileData.realname || '다운로드 파일';
                    var link = fileData.link || '#';
                    var fileId = fileData.fileId || null;

                    if (!fileId) {
                        console.error("fileId가 누락되었습니다. index: " + i, fileData);
                        return;
                    }

                    // HTTPS 변환 (Mixed Content 방지)
                    var safeLink = link;
                    if (window.location.protocol === 'https:' && link.startsWith('http:')) {
                        safeLink = link.replace('http:', 'https:');
                    }
                    
                    $("#displayFile").append(
                        "<div class='row mb-3'>" +
                        "<div id='file" + i + "' class='col d-flex align-items-center justify-content-center'>" +
                        "<a href='#' onclick=\"popupCenter('" + safeLink + "', 'filePopup', 800, 600); return false;\" style='word-wrap:break-word;overflow-wrap:break-word;max-width:100%;'>" +
                        realName +
                        "</a> &nbsp; &nbsp; " +
                        "</div>" +
                        "</div>"
                    );
                });
            } else {
                $("#displayFile").append(
                    "<div class='text-center text-muted'>No attached files</div>"
                );
            }
        }
    </script>

</body>

</html>
    