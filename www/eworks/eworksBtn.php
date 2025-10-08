<?php require_once __DIR__ . '/../bootstrap.php';

// 각 상황에 따른 버튼을 구현하기 위한 부분 결재상황별 버튼이 다르게 나와야 한다.
 
// 함수 선언 문자열안에 문자열이 있는지 검사해서 결과값을 리턴하는 함수
function contains($string, $substring) {
    if (strpos($string, $substring) !== false) {
        return true;
    } else {
        return false;
    }
}

isset($_REQUEST["e_num"])  ? $e_num = $_REQUEST["e_num"] :   $e_num=""; 
isset($_REQUEST["status"])  ? $status = $_REQUEST["status"] :   $status="draft"; 
isset($_REQUEST["done"])  ? $done = $_REQUEST["done"] :   $done=""; 

?>

<div class="row p-1 mt-1 mb-1 justify-content-center">
   <div class="d-flex justify-content-center mb-2"  id="comments-container">	
    <?php
    try {
        $sql_ripple = "SELECT * FROM mirae8440.eworks_ripple WHERE parent=? AND is_deleted IS NULL "  ;
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
             <div class="card ripple-item" id="ripple-<?=$ripple_num?>" style="width:80%">
                <div class="row justify-content-center">
                    <div class="card-body">
                        <span class="mt-1 mb-2">▶&nbsp;&nbsp;<?=$ripple_content?> ✔&nbsp;&nbsp;작성자: <?=$ripple_nick?> | <?=$ripple_date?>
                        <?php
                        if (isset($_SESSION["userid"])) {
                            if ($_SESSION["userid"] == "admin" || $_SESSION["userid"] == $ripple_id || $_SESSION["level"] === 1) {
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
    } catch (PDOException $Exception) {
        // print "오류: ".$Exception->getMessage();
    }
    ?>
</div>

  <div class="row p-1 mb-1 justify-content-center"> 	 
   <div class="card justify-content-center" style="width:80% "> 
	   <div class="row justify-content-center">
	   <div class="card-body"> 
		<div class="row d-flex mt-3 mb-1 justify-content-center">     												
			<div class="d-flex">     
					 <span class="form-control badge bg-dark text-center fs-6" style="width:10%;"> <i class="bi bi-chat-dots"></i> 의견  </span>
					 
					&nbsp;
					<textarea rows="1" class="form-control" id="ripple_content" name="ripple_content" required></textarea>
					&nbsp;	
					  <button type="button" class="form-control  btn btn-dark btm-sm"  style="width:10%;" onclick="eworks_insert_ripple('<?=$e_num?>')"> <i class="bi bi-floppy-fill"></i> 저장</button>
										
				</div>			
			</div>			
			
			
				</div>			
			</div>			
		</div>			
	</div>	

<div class="d-flex justify-content-end">	

<?php

$myTurn = false ; // 현재 결재 차례임

try {
     $sql = "select * from mirae8440.eworks where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $e_num, PDO::PARAM_STR);      
     $stmh->execute();   

    $row = $stmh->fetch(PDO::FETCH_ASSOC) ;
    include getDocumentRoot() . "/eworks/_row.php";

    $arr = explode("!", $e_line_id);
    $approval_time = explode("!", $e_confirm_id);
    $last_approved_id = end($approval_time); // 마지막으로 결재한 사용자 ID

    //나의 결재 차례인지 확인하는 로직

		
	if ($status !== 'reject' && $status !== 'end') {
		if (empty($e_confirm_id)) {
			// e_confirm_id가 비어 있으면 첫 번째 결재자가 현재 차례인지 확인
			$myTurn = ($arr[0] == $user_id);
		} else {
			$approval_time = explode("!", $e_confirm_id);
			$last_approved_id = end($approval_time); // 마지막으로 결재한 사용자 ID

			$index = array_search($last_approved_id, $arr);
			if ($index !== false && isset($arr[$index + 1]) && $arr[$index + 1] == $user_id) {
				$myTurn = true; // 현재 결재 차례임
			}
		}
	}
	
   }catch (PDOException $Exception) {
       print "오류: ".$Exception->getMessage();
     }  

  if( $myTurn && $done!=='done' ) 	 // 승인자이름에 포함되면?
		{		
		print '<button id="eworks_approvalBtn" class="btn btn-primary btn-sm me-2  "><i class="bi bi-window-dock"></i> 승인</button>			 ';
		print '<button id="eworks_rejectBtn" class="btn btn-danger btn-sm me-2 "><i class="bi bi-arrow-counterclockwise"></i>반려</button>			 ';																			
		print '<button id="eworks_waitBtn" class="btn btn-secondary btn-sm me-2  "><i class="bi bi-hourglass"></i>보류</button>				';
	 }
 else if( $status === 'draft' || $status === '' || $status === null ) 	
{
		print '<button id="eworks_saveBtn" class="btn btn-dark btn-sm me-2 "><i class="bi bi-floppy-fill"></i>저장</button>				';
		print '<button id="eworks_delBtn" class="btn btn-danger btn-sm  me-2 "><i class="bi bi-trash"></i> 삭제</button>			 ';																			
		print '<button id="eworks_sendBtn" class="btn btn-primary  btn-sm  me-2 "><i class="bi bi-window-dock"></i>상신</button>		 ';

  }
  else if( $status === 'reject' )
{
		print '<button id="eworks_saveBtn" class="btn btn-dark btn-sm  me-2 "><i class="bi bi-floppy-fill"></i>저장</button>				';
		print '<button id="eworks_approvalBtn" class="btn btn-primary btn-sm  me-2 "><i class="bi bi-credit-card-2-front-fill"></i> 재승인요청</button>			 ';
		print '<button id="eworks_delBtn" class="btn btn-danger me-2 "><i class="bi bi-trash"></i> 삭제</button>			 ';																					
  }   
 
  else if( $status === 'wait' )
{		
		print '<button id="eworks_approvalBtn" class="btn btn-primary btn-sm me-2  "><i class="bi bi-window-dock"></i> 승인</button>			 ';
		print '<button id="eworks_delBtn" class="btn btn-danger"><i class="bi bi-trash"></i> 삭제</button>			 ';																					
  }   
 
  else if( $status === 'ing' &&  $user_id == $author_id )
{		
    		
  }   
 
  else if( $status === 'send' )
{	
	if($user_id !== $author_id)		
		print '<button id="eworks_approvalBtn" class="btn btn-primary btn-sm me-2  "><i class="bi bi-credit-card-2-front-fill"></i> 승인</button> ';																				
	else
		print '<button id="eworks_recallBtn" class="btn btn-dark btn-sm  me-2 "><i class="bi bi-arrow-counterclockwise"></i> 회수</button>';

  }   

 ?>

</div>
</div>

