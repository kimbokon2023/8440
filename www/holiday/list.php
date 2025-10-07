<?php\nrequire_once __DIR__ . '/../common/functions.php';
require_once(includePath('session.php'));  

if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    sleep(1);
    header("Location:" . $WebSite . "login/login_form.php");
    exit;
}   
include getDocumentRoot() . '/load_header.php';
$title_message = '일정표 휴일'; 
?>
 
<link href="css/style.css" rel="stylesheet">   
<title> <?=$title_message?> </title>
 
<style>
    /* 테이블에 테두리 추가 */
    #myTable, #myTable th, #myTable td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    /* 테이블 셀 패딩 조정 */
    #myTable th, #myTable td {
        padding: 8px;
        text-align: center;
    }
</style>
</head> 

<body>         

<?php
$header = isset($_REQUEST['header']) ? $_REQUEST['header'] : '';  

if ($header == 'header')
    require_once(includePath('myheader.php'));

function checkNull($strtmp) {
    return ($strtmp !== null && trim($strtmp) !== '');
}

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';  
$mode = isset($_REQUEST["mode"]) ? $_REQUEST["mode"] : '';

$tablename = 'holiday';

require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
 
$order = " ORDER BY registedate DESC, num desc ";
    
if (checkNull($search)) {
    $sql = "SELECT * FROM ".$DB.".".$tablename." 
            WHERE searchtag LIKE '%$search%' AND is_deleted IS NULL " . $order;    
} else {
    $sql = "SELECT * FROM ".$DB.".".$tablename . " WHERE is_deleted IS NULL " . $order;   
}

try {      
    $stmh = $pdo->query($sql);
    $total_row = $stmh->rowCount();        
?>    

<form id="board_form" name="board_form" method="post" enctype="multipart/form-data">             
    <input type="hidden" id="mode" name="mode" value="<?=$mode?>">             
    <input type="hidden" id="num" name="num"> 
    <input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>">                 
    <input type="hidden" id="header" name="header" value="<?=$header?>">      


<div class="container">
    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content" style="width:600px;">
            <div class="modal-header">
                <span class="modal-title">일정표 휴일</span>
                <span class="close closeBtn">&times;</span>
            </div>
            <div class="modal-body">
                <div class="custom-card"></div>
            </div>
        </div>
    </div>
</div>
<div class="container" style="width:40%;">
    <div class="card">
    <div class="card-header">
		<div class="d-flex justify-content-center align-items-center">  
        <span class="text-center fs-5 me-4"><?=$title_message?></span>    
        <button type="button" class="btn btn-dark btn-sm me-1" onclick='location.href="list.php?header=header"'> 
            <i class="bi bi-arrow-clockwise"></i>
        </button>                                     
    </div>
    </div>
    <div class="card-body">      
	<div class="d-flex justify-content-center align-items-center mt-1 mb-3">   
    ▷ <?= $total_row ?> &nbsp; 
    <div class="inputWrap30">            
        <input type="text" id="search" class="form-control" style="width:150px;" name="search" value="<?=$search?>" onKeyPress="if (event.keyCode == 13) { this.form.submit(); }">
        <button class="btnClear"></button>
    </div>                            
    &nbsp;&nbsp;
    <button class="btn btn-outline-dark btn-sm" type="button" id="searchBtn"> <i class="bi bi-search"></i> </button> &nbsp;&nbsp;&nbsp;&nbsp;            
    <button id="newBtn" type="button" class="btn btn-dark btn-sm me-2"> <i class="bi bi-pencil-square"></i> 신규 </button>            
    <?php if($header !== 'header') 
            print '<button id="closeBtn" type="button" class="btn btn-outline-dark btn-sm"> <i class="bi bi-x-lg"></i> 창닫기 </button>';
    ?>            
    </div>
        
    <div class="row d-flex">   
    <div class="table-responsive ">   
       <table class="table table-hover" id="myTable">         
            <thead class="table-info">
                 <th class="text-center " >번호</th>                 
                 <th class="text-center " >휴일시작</th>
                 <th class="text-center " >휴일종료</th>
                 <th class="text-center " >기간체크</th>
                 <th class="text-center " >내용</th>                                  
            </thead>
            <tbody>                  
            <?php          
            $start_num = $total_row;                  
            while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
                $num = $row['num'];
                $registedate = $row['registedate'];
                $startdate = $row['startdate'];
                $enddate = $row['enddate'];
                $periodcheck = $row['periodcheck'] ? '예' : '아니오';
                $comment = $row['comment'];
            ?>                     
            <tr onclick="loadForm('update', '<?=$num?>');">
                <td class="text-center"><?= $start_num ?></td>                
                <td class="text-center"><?= $startdate ?></td>                
                <td class="text-center">    <?= $enddate === '0000-00-00' ? '' : $enddate ?>  </td>
                <td class="text-center"><?= $periodcheck ?></td>
                <td class="text-start"><?= $comment ?></td>                
            </tr>
        <?php
            $start_num--;
                 } 
              } catch (PDOException $Exception) {
                  print "오류: ".$Exception->getMessage();
              }  
        ?>         
      </tbody>
     </table>
	</div>	
    </div>
    </div>        
    </div>        
    </div>       
</form>

</body>
</html>

<script>
$(document).ready(function() {
    // Loader 숨기기
    var loader = document.getElementById('loadingOverlay');
    if (loader) {
        loader.style.display = 'none';
    }

    // Modal 닫기 기능
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    // 신규 버튼 클릭 시
    $("#newBtn").on("click", function() {		
        loadForm('insert');
    });

    // 검색 버튼 클릭 시
    $("#searchBtn").on("click", function() {
        $("#board_form").submit();
    });
});

function loadForm(mode, num = null) {
    if (num == null) {
        $("#mode").val('insert');
    } else {
        $("#mode").val('update');
        $("#num").val(num);
    }       

    $.ajax({
        type: "POST",
        url: "fetch_modal.php",
        data: { mode: mode, num: num },
        dataType: "html",
        success: function(response) {
            document.querySelector(".modal-body .custom-card").innerHTML = response;
            $("#myModal").show();

            // 동적 기간체크 기능 추가
            $("#periodcheck").on("change", function() {
                if ($(this).is(":checked")) {
                    $("#enddate").parent().show();  // 종료일 필드와 라벨 표시
                } else {
                    $("#enddate").parent().hide();  // 종료일 필드와 라벨 숨기기
                    $("#enddate").val(""); // 종료일 초기화
                }
            });

            // 초기 로드 시 체크박스 상태에 따른 필드 표시/숨김
            if ($("#periodcheck").is(":checked")) {
                $("#enddate").parent().show();  // 종료일 필드와 라벨 표시
            } else {
                $("#enddate").parent().hide();  // 종료일 필드와 라벨 숨기기
            }

            $("#closeBtn").on("click", function() {
                $("#myModal").hide();
            });
			
            $(".closeBtn").on("click", function() {
                $("#myModal").hide();
            });

            // 저장 버튼
            $("#saveBtn").on("click", function() {
                var formData = $("#board_form").serialize();

                $.ajax({
                    url: "process.php",
                    type: "post",
                    data: formData,
                    success: function(response) {
                        Toastify({
                            text: "저장완료",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "center",
                            backgroundColor: "#4fbe87",
                        }).showToast();

                        $("#myModal").hide();
                        location.reload();
                    },
                    error: function(jqxhr, status, error) {
                        console.log(jqxhr, status, error);
                    }
                });
            });

            // 삭제 버튼
            $("#deleteBtn").on("click", function() {
                deleteHoliday(num);
            });
        },
        error: function(jqxhr, status, error) {
            console.log("AJAX Error: ", status, error);
        }
    });
}

function deleteHoliday(num) {
    Swal.fire({
        title: '자료 삭제',
        text: "정말 삭제하시겠습니까?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '삭제',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.isConfirmed) {
            $("#mode").val('delete');
            $("#num").val(num);
            var formData = $("#board_form").serialize();

            $.ajax({
                url: "process.php",
                type: "post",
                data: formData,
                success: function(response) {
                    Toastify({
                        text: "삭제 완료",
                        duration: 2000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        backgroundColor: "#ff5f5f",
                    }).showToast();

                    location.reload();
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                }
            });
        }
    });
}
</script>
