<?php 
if(!isset($_SESSION))      
		session_start(); 
if(isset($_SESSION["DB"]))
		$DB = $_SESSION["DB"] ;	
 $level= $_SESSION["level"];
 $user_name= $_SESSION["name"];
 $user_id= $_SESSION["userid"];	
 
$e_line_id = isset($_GET['e_line_id']) ? $_GET['e_line_id'] : ''; 

include $_SERVER['DOCUMENT_ROOT'] . '/common.php';
require_once($_SERVER['DOCUMENT_ROOT'] . "/lib/mydb.php");

$_SESSION["partsep"] = ''; 
$pdo = db_connect();

$firstStep = array();
$secondStep = array();

function getApprovalData($pdo) {
	// print ('tlfgod');
    $approvalData = ['firstStep' => [], 'secondStep' => []];
	
    try {
        $sql = "SELECT id, name, position, part, eworks_level FROM mirae8440.member WHERE part LIKE '%제조%' or  part LIKE '%지원%' ";
        $stmh = $pdo->prepare($sql);    
        $stmh->execute();

        while($row = $stmh->fetch(PDO::FETCH_ASSOC)) {
            if($row["eworks_level"] == "2" or $row["eworks_level"] == "1") {
                $approvalData['firstStep'][] = $row;
            } elseif($row["eworks_level"] == "1") {
                $approvalData['secondStep'][] = $row;
            }
			
			// var_dump($row);			
        }
    } catch (PDOException $Exception) {
        print "오류: " . $Exception->getMessage();
    }
    return $approvalData;
}

$approvalData = getApprovalData($pdo);

// var_dump($approvalData);

$title_message = "결재라인 지정";

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';


// JSON 파일 경로
$filePath = './approvalLine/approvalLine_' . $user_id . '.json';

// print_r($filePath);

if(file_exists($filePath)) {
    $data = json_decode(file_get_contents($filePath), true);


    // var_dump($data);
    // select 요소의 옵션 초기화
    $selectOptions = "";

    // JSON 데이터가 배열인 경우 각 요소 처리
    if(is_array($data)) {
        foreach($data as $approvalLine) {
            if(isset($approvalLine['savedName'])) {
                $savedName = htmlspecialchars($approvalLine['savedName'], ENT_QUOTES, 'UTF-8');
                $selectOptions .= "<option value='{$savedName}'>{$savedName}</option>";
            }
        }

        // 결재라인이 없는 경우
        if(empty($selectOptions)) {
            $selectOptions = "<option> </option>";
        }
    } else {
        $selectOptions = "<option>Invalid data format in file</option>";
    }
} else {
    $selectOptions = "<option> </option>";
}


?>

<style>
.ui-state-highlight {
    background-color: #f0f0f0;
    height: 1.8em;
    line-height: 1.5em;
}

#approvalOrder {
    min-height: 100px;
}

#approvalModal {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1050;
    display: none;
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.approval-line-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px; /* 줄 간격 조정 */
}

.delete-button {
    border: none;
    background: none;
    cursor: pointer;
}

.delete-button ion-icon {
    font-size: 20px; /* 아이콘 크기 조정 */
    color: #ff0000; /* 아이콘 색상 조정 */
}


</style>

<title>  <?=$title_message?>  </title>

 <form id="mainFrm" method="post" enctype="multipart/form-data"   >		
	<input type="hidden" id="SelectWork" name="SelectWork" value="<?=$SelectWork?>"> 
	<input type="hidden" id="vacancy" name="vacancy" > 
	<input type="hidden" id="num" name="num" value=<?=$num?> > 
	<input type="hidden" id="page" name="page" value=<?=$page?> > 
	<input type="hidden" id="mode" name="mode" value=<?=$mode?> > 
	<input type="hidden" id="partsep" name="partsep" value=<?=$partsep?> >             
			
<div class="container">		

<!-- 모달 -->
<div id="approvalModal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">결재라인 관리</h5>
            <button type="button" class="close" onclick="closeModal();">&times;</button>
        </div>
			<!-- ... 기존 모달 내용 ... -->
			<div class="modal-body">
				<div class="d-flex mt-5 mb-5">			    
				<ul id="approvalLineList"></ul> <!-- 결재라인 목록 컨테이너 -->
				</div>
			</div>
        <div class="modal-footer">            
            <button type="button" class="btn btn-dark"  onclick="closeModal();">닫기</button>
        </div>
    </div>
</div>


	
	<div class="card">			

	<div class="card-header mt-3 fs-5 "> 					  				   
	   결재라인 지정
	</div>
	
    <div class="card-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>
						<h6>결재권자 목록</h6>
					</th>
					<th>
						<h6>결재 순서</h6>
					</th>
					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="col" style="width:50%;">
						<ul id="approverList" class="list-group">
							<?php foreach($approvalData["firstStep"] as $approver): ?>
								<li class="list-group-item" data-user-id="<?= $approver['id'] ?>">
									<?= htmlspecialchars($approver['name'] . ' ' . $approver['position']) ?>
								</li>
							<?php endforeach; ?>
							<!-- 더미 항목 추가 -->
							<li class="list-group-item dummy" style="display: none;"></li>
						</ul>

					</td>
					<td class="col" style="width:50%;">
						<ul id="approvalOrder" class="list-group">
							<!-- 드래그 앤 드롭으로 이동된 결재권자가 여기에 표시됨 -->
						</ul>
					</td>
					
				</tr>
			</tbody>
		</table>

			<div class="row ">	  
				<div class="col-sm-7">	 
					<div class="d-flex align-items-center p-2 text-left">
					
					<button  type="button" class="btn btn-outline-primary btn-sm" id="newBtn" > <ion-icon name="document-outline"></ion-icon></button>&nbsp;							
						<span class="text-center me-1"> Load  </span>
						<select name="savedApprovalLines" id="savedApprovalLines" class="form-control" style="width:60%;">
							<?= $selectOptions ?>
						</select>						
					</div>
				</div>
				<div class="col-sm-5" >	 
					<div class="d-flex align-items-center p-2 text-left">			
						<input type="text" name="workprocessval" id="workprocessval" value='<?=$workprocessval?>'  class="form-control" style="width:100%;" >
						<button  type="button" class="btn btn-dark btn-sm" id="SavesettingsBtn" >  <ion-icon name="save-outline"></ion-icon> </button>&nbsp;		
					</div>
				</div>

			</div>

		
		 
		<div class="d-flex p-2  text-left" >	  
		<button  type="button" class="btn btn-outline-dark btn-sm" onclick="self.close();" > <ion-icon name="exit-outline"></ion-icon> 창닫기 </button>&nbsp;		
		<button  type="button" class="btn btn-primary btn-sm"  id="openModalButton"> <ion-icon name="construct-outline"></ion-icon> 관리 </button> &nbsp;
		<button  type="button" class="btn btn-dark btn-sm" id="adaptBtn"> <ion-icon name="checkmark-outline"></ion-icon> 선택 </button> &nbsp;
		
		
		</div>
		
		</div>
	</div>
	</div>
</form>

	
    
</body>
</html>

	  
 <script> 
 
 // 기존 호출에 전달자가 있는 경우 값이 있어 호출하는 경우
 document.addEventListener('DOMContentLoaded', function() {
 	var e_line_id = '<?php echo $e_line_id ?>';
    if (e_line_id && e_line_id !== "") {
        var ids = e_line_id.split("!");
        var approverList = document.getElementById("approverList");
        var approvalOrder = document.getElementById("approvalOrder");

        ids.forEach(function(id) {
            var element = approverList.querySelector('[data-user-id="' + id + '"]');
            if (element) {
                approvalOrder.appendChild(element.cloneNode(true));
                element.remove();
            }
        });
    }
});
 
$(document).ready(function() {
    // Select 옵션 변경 시 이벤트 핸들러
    $('#savedApprovalLines').change(function() {
        var selectedName = $(this).val();
        updateApprovalLine(selectedName);
		console.log(selectedName);
    });
	
	var e_line_id = '<?php echo $e_line_id ?>';
	
// 페이지 로드 시 첫 번째 옵션을 선택하고 이벤트 트리거
    if(!e_line_id)
    $('#savedApprovalLines').prop('selectedIndex', 0).trigger('change');


    function updateApprovalLine(savedName) {
        $.ajax({
            url: './getApprovalLine.php', // 서버측 PHP 파일 경로
            type: 'POST',
            data: { savedName: savedName },
            dataType: 'json',
            success: function(response) {
                // 화면 업데이트 로직
				console.log(response);
                updateApprovalOrderList(response.approvalOrder);
            },
            error: function(xhr, status, error) {
                console.error(error); // 오류 처리
            }
        });
    }

	function updateApprovalOrderList(approvalOrder) {
		var approvalOrderList = $('#approvalOrder');
		var approverList = $('#approverList');
		approvalOrderList.empty(); // 최종 결재 순서 목록 비우기

		approvalOrder.forEach(function(item) {
			var listItem = $('<li class="list-group-item"></li>')
				.text(item.name)
				.data('user-id', item['user-id']);
			approvalOrderList.append(listItem);

			// 같은 'user-id'를 가진 항목을 'approverList'에서 제거
			approverList.find('li').not('.dummy').each(function() {
				if ($(this).data('user-id') === item['user-id']) {
					$(this).remove();
				}
			});
		});

		// 모든 변경 사항 적용 후 'dummy' 항목 다시 추가
		if (!approverList.find('.dummy').length) {
			approverList.append('<li class="list-group-item dummy"></li>');
		}
		
	}

			
    $("#approverList, #approvalOrder").sortable({
        connectWith: ".list-group",
        placeholder: "ui-state-highlight",
        receive: function(event, ui) {
            checkDummy($(this));
        },
        over: function(event, ui) {
            checkDummy($(this));
        },
        out: function(event, ui) {
            checkDummy($(this));
        },
        stop: function(event, ui) {
            checkDummy($(this));
        }
    }).disableSelection();

    function checkDummy(list) {
        // 리스트에 항목이 없으면 더미 항목을 표시
        if (list.children(':not(.dummy)').length === 0) {
            list.children('.dummy').show();
        } else {
            list.children('.dummy').hide();
        }
    }

    // 초기 상태에서 더미 항목의 상태를 확인
    $("#approverList, #approvalOrder").each(function() {
        checkDummy($(this));
    });
	
 
	// 모달창 닫기	 	 
	$("#closeModalBtn").click(function(){ 
		$('#myModal').modal('hide');
	});
			
	$("#closeBtn").click(function(){    // 저장하고 창닫기	
		 });


			 
$("#SavesettingsBtn").click(function() {
    var inputName = $("#workprocessval").val().trim();
    if (!inputName) {
        alert('저장할 결재라인을 입력하세요');
        return; // 함수 실행 중단
    }

    var selectedApprovalLine = getCurrentApprovalLine();

    $.ajax({
        url: './saveApprovalLine.php',
        type: 'POST',
        data: JSON.stringify({
            userId: '<?= $user_id ?>',
            savedName: inputName,
            approvalOrder: selectedApprovalLine
        }),
        contentType: "application/json; charset=utf-8",
        success: function(response) {
            console.log(response);
            Toastify({
                text: "저장되었습니다.",
                duration: 2000,
                close: true,
                gravity: "top",
                position: 'center',        
            }).showToast();

            // 새로운 결재라인 옵션 추가 및 선택
            $('#savedApprovalLines').append($('<option>', {
                value: inputName,
                text: inputName
            })).val(inputName).trigger('change');
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

function getCurrentApprovalLine() {
    var approvalLine = [];
    $("#approvalOrder li:not(.dummy)").each(function(index) {
        var userId = $(this).data('user-id');
        var name = $(this).text();
        var position = $(this).data('position');
        var order = index + 1;
        if (userId) {
            approvalLine.push({
                'order': order,
                'user-id': userId,
                'name': name + (position ? ' ' + position : '')
            });
        }
    });
    return approvalLine;
}

    
    $("#refreshBtn").click(function() {
		location.reload();
    });

    // New 버튼 클릭 이벤트 핸들러
    $("#newBtn").click(function() {
        resetApprovalLists();
    });

    // 모달 열기 이벤트
    $("#openModalButton").click(function() {
        fetchApprovalLines(); // 결재라인 목록 가져오기
		openModal();
    });

    // 선택
	$("#adaptBtn").click(function() {
		var approvalOrderTexts = $("#approvalOrder li")
			.map(function() {
				return $(this).text().trim(); // 각 항목의 텍스트 추출
			}).get() // jQuery 객체를 일반 배열로 변환
			.join("!"); // 콤마로 구분하여 하나의 문자열로 결합

		// 부모 창의 e_line input 요소에 값을 설정
		window.opener.$("#e_line").val(approvalOrderTexts);
				
	// id를 같은 형식으로 저장하기	
    var approvalOrderIDs = $("#approvalOrder li")
        .map(function() {
            return $(this).data('user-id'); // 각 항목의 data-user-id 속성 추출
        }).get() // jQuery 객체를 일반 배열로 변환
        .join("!"); // ":" 문자로 구분하여 하나의 문자열로 결합

    // 부모 창의 e_line_id input 요소에 값을 설정
    window.opener.$("#e_line_id").val(approvalOrderIDs);

		// 필요한 경우 현재 창을 닫음
		window.close();
	});



});

function resetApprovalLists() {
    var approverList = $('#approverList');
    var approvalOrderList = $('#approvalOrder');

    // approverList 초기화
    approverList.empty();
    <?php foreach($approvalData["firstStep"] as $approver): ?>
        approverList.append('<li class="list-group-item" data-user-id="<?= $approver['id'] ?>"><?= htmlspecialchars($approver['name'] . ' ' . $approver['position']) ?></li>');
    <?php endforeach; ?>
    // 더미 항목 추가
    approverList.append('<li class="list-group-item dummy" style="display: none;"></li>');

    // approvalOrderList 비우기
    approvalOrderList.empty();
}


// 서버에서 결재라인 목록 가져오기
function fetchApprovalLines() {
    $.ajax({
        url: './getApprovalLines.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response); // 서버 응답 로깅
            renderApprovalLines(response);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}


// 결재라인 목록 렌더링
function renderApprovalLines(data) {
    var approvalLines = Object.values(data);
    
    if (!Array.isArray(approvalLines)) {
        console.error("Invalid data type: ", approvalLines);
        return;
    }

    var listContainer = $('#approvalLineList');
    listContainer.empty(); // 목록 초기화

    var table = $('<table><tbody></tbody></table>').addClass('table table-hover table-bordered table-sm');
    listContainer.append(table);

    approvalLines.forEach(function(line) {
        var row = $('<tr></tr>').addClass('approval-line-item');
        var nameCell = $('<td></td>').text(line.savedName);
        var deleteButtonCell = $('<td></td>').addClass('text-end');
        var deleteButton = $('<button></button>')
            .addClass('btn btn-danger btn-sm ')
            .append('<ion-icon name="trash-outline"></ion-icon>')
            .click(function(event) {
                event.preventDefault();
                deleteApprovalLine(line.savedName, row);
            });

        deleteButtonCell.append(deleteButton);
        row.append(nameCell).append(deleteButtonCell);
        table.append(row);
    });
}



// 결재라인 삭제
function deleteApprovalLine(savedName, listItem) {
    $.ajax({
        url: './deleteApprovalLine.php',
        type: 'POST',
        data: { savedName: savedName },
        success: function(response) {
            // 성공적으로 삭제되면 항목을 화면에서 제거
            listItem.remove();
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}


function openModal() {
    document.getElementById("approvalModal").style.display = "block";
	
	e.preventDefault(); // 기본 이벤트 동작 막기
}

function closeModal() {
    document.getElementById("approvalModal").style.display = "none";
	location.reload(); // 현재 페이지 리로드
}


  </script>