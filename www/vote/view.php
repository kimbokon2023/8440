<?php
require_once __DIR__ . '/../common/functions.php';
?>
﻿<?php require_once(includePath('session.php')); 

// 첫 화면 표시 문구
$title_message = '우리의 당당한 투표!'; 
?>
  
<?php include getDocumentRoot() . '/load_header.php';

 if(!isset($_SESSION["level"]) || $_SESSION["level"]>8) {          
		 sleep(1);
         header("Location:".$_SESSION["WebSite"]."login/login_form.php"); 
         exit;
   }  
   
?> 
  
<title> <?=$title_message?> </title> 

    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
        }
    </style> 
	
 </head> 
 
<body>
<?php include "../common/modal.php"; ?>
	 
 <?php
 
 $file_dir = '../uploads/'; 
  
 $num=$_REQUEST["num"];
 $page=$_REQUEST["page"];   //페이지번호
 $tablename=$_REQUEST["tablename"];   
   
require_once(includePath('lib/mydb.php'));
$pdo = db_connect();
 
 try{
     $sql = "select * from mirae8440." . $tablename . " where num=?";
     $stmh = $pdo->prepare($sql);  
     $stmh->bindValue(1, $num, PDO::PARAM_STR);      
     $stmh->execute();            
      
     $row = $stmh->fetch(PDO::FETCH_ASSOC);
 	
     $item_num     = $row["num"];
     $item_id      = $row["id"];
     $item_name    = $row["name"];
     $item_nick    = $row["nick"];
     $item_hit     = $row["hit"];
 

     $item_date    = $row["regist_day"];
     $item_date    = substr($item_date,0,10);
     $item_subject = str_replace(" ", "&nbsp;", $row["subject"]);
     $item_content = $row["content"];
     $is_html      = $row["is_html"];
     $noticecheck      = $row["noticecheck"];
     $status      = $row["status"];
     $deadline    = $row["deadline"];
	 
     $votelist = $row["votelist"] ?? '{}'; // votelist 값이 없을 경우의 기본값	 
	 
	 if($noticecheck=='y')
		 $noticecheck_memo='(전체공지)';
	    else
			$noticecheck_memo='';
      
     if ($is_html=='y'){
		// $item_content = str_replace(" ", "&nbsp;", $item_content);
     	// $item_content = str_replace("\n", "<br>", $item_content);
		$item_content =  htmlspecialchars_decode($item_content);     	
     }	
	 
	 // $item_content = str_replace("\n", "<br>", $item_content);
	 $item_content = str_replace("\r", "<br>", $item_content);
 
     $new_hit = $item_hit + 1;
     try{
       $pdo->beginTransaction(); 
       $sql = "update mirae8440." . $tablename . " set hit=? where num=?";   // 글 조회수 증가
       $stmh = $pdo->prepare($sql);  
       $stmh->bindValue(1, $new_hit, PDO::PARAM_STR);      
       $stmh->bindValue(2, $num, PDO::PARAM_STR);           
       $stmh->execute();
       $pdo->commit(); 
       } catch (PDOException $Exception) {
         $pdo->rollBack();
       print "오류: ".$Exception->getMessage();
  }	  
  
	// 초기 프로그램은 $num사용 이후 $id로 수정중임  
	$id=$num;    
?>

  
<style>

.vote-chart-canvas {
	width: 100%;  /* 부모 컨테이너의 폭에 맞춥니다 */
	max-width: 400px; /* 최대 가로 폭을 제한합니다 */		
	/*  aspect-ratio: 1 / 1; 가로 세로 비율을 1:1로 유지합니다 */
}

.form-control-scrollable {
	overflow: auto; /* 내용이 넘칠 때 스크롤바 표시 */
	height: auto;
	white-space: nowrap; /* 수평 스크롤을 위해 내용을 한 줄로 유지 */
}
	
</style>
 
</head>

<form  id="board_form" name="board_form" method="post" enctype="multipart/form-data"> 
  <!-- 전달함수 설정 input hidden -->
	<input type="hidden" id="id" name="id" value="<?=$id?>" >			  								
	<input type="hidden" id="num" name="num" value="<?=$num?>" >			  								
	<input type="hidden" id="parentid" name="parentid" value="<?=$parentid?>" >			  								
	<input type="hidden" id="fileorimage" name="fileorimage" value="<?=$fileorimage?>" >			  								
	<input type="hidden" id="item" name="item" value="<?=$item?>" >			  								
	<input type="hidden" id="upfilename" name="upfilename" value="<?=$upfilename?>" >			  								
	<input type="hidden" id="tablename" name="tablename" value="<?=$tablename?>" >			  								
	<input type="hidden" id="savetitle" name="savetitle" value="<?=$savetitle?>" >			  								
	<input type="hidden" id="pInput" name="pInput" value="<?=$pInput?>" >			  								
	<input type="hidden" id="mode" name="mode" value="<?=$mode?>" >				
	<input type="hidden" id="status" name="status" value="<?=$status?>" >				
 
<?php if ($chkMobile): ?>
    <!-- 모바일 환경일 때 보이는 버튼 -->	  
    <div class="container-fluid p-2 m-1">  	
<?php else: ?>
    <!-- PC 환경일 때 보이는 버튼 -->	
	<div class="container justify-content-center mb-5">  
<?php endif; ?>		

	<div class="card mt-2 mb-5">  
	<div class="card-body">  
		<div class="d-flex mt-3 mb-2 justify-content-center">  
			<h3>  <?=$title_message?> </h3> 
		</div>	
	 <div class="row d-flex m-1 mt-2 mb-2 justify-content-left  align-items-center">  				
	<div class="col-sm-10">  				
    <span class="me-1"> </span>					
	<button type="button" id="closeBtn"  class="btn btn-dark btn-sm me-1" >  &times; 닫기 </button>
	<?php
	// 삭제 수정은 관리자와 글쓴이만 가능토록 함
	if(isset($_SESSION["userid"])) {
	if($_SESSION["userid"]==$item_id || $_SESSION["userid"]=="admin" || 
		  intval($_SESSION["level"]) === 1 )
		{
	?>				
				<button type="button"   class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?=$tablename?>&mode=modify&num=<?=$num?>&page=<?=$page?>&search=<?=$search?>&Bigsearch=<?=$Bigsearch?>&find=<?=$find?>&year=<?=$year?>&search=<?=$search?>&process=<?=$process?>&asprocess=<?=$asprocess?>&fromdate=<?=$fromdate?>&todate=<?=$todate?>&separate_date=<?=$separate_date?>'" >  <i class="bi bi-pencil-square"></i>  수정  </button>			
				<button type="button"   class="btn btn-dark btn-sm me-1" onclick="location.href='write_form.php?tablename=<?=$tablename?>'" >  <i class="bi bi-pencil"></i>  신규 </button>			
				<button type="button"   class="btn btn-danger btn-sm me-1" onclick="javascript:del('delete.php?tablename=<?=$tablename?>&num=<?=$num?>&page=<?=$page?>')" > <i class="bi bi-trash"></i>  삭제   </button>								
	<?php  	}    ?>				
	</div>  
	</div>  
	 
	 <div class="row d-flex p-1 m-1 mt-2 mb-2 justify-content-left  align-items-center">  				
	 <div class="table-responsive">  				
		<table class="table table-bordered">
		   <tbody>
		   <tr>
		     <td class="text-center bg-secondary text-white">
			   진행상태 
			</td>
			<td class="text-center">	<?= $status ?>  </td>
			<td class="text-center bg-secondary text-white">	작성일 </td>
			<td class="text-center">	<?= $item_date ?>   </td>			   
			<td class="text-center bg-secondary text-white">	마감일 </td>
			<td class="text-center">	<?= $deadline ?>   </td>			   
			<td class="text-center bg-secondary text-white">  글제목  </td>
			<td class="text-center">  <?= $item_subject ?> </td>			  
			<td class="text-center bg-secondary text-white">작성자 </td>
			<td class="text-center"> <?= $item_nick ?> </td>
			</tr>
			</tbody>
		</table>
		  	 
	  </div>
	  </div>
	   <div class="row p-2 m-2 mt-1 mb-1 align-items-center"> 
			 <div class="col-sm-8">             	   
			 <span > <?=$item_content ?> </span>
			</div>
			 <div class="col-sm-4 ">     
			    <div class="card">     
					<div class="card-body">     
						<canvas id="voteChart" class="vote-chart-canvas"></canvas>
					</div>					
				</div>
			</div>
		</div>

		 <div class="row d-flex mt-3 mb-1 justify-content-center">  	
				<div class="card p-2"> 
				<div class="card-body">  
					<div class="d-flex mt-2 mb-2 justify-content-center">    
						 <span class=" fs-5 text-center mb-2 me-3"> 투표하기 </span>		
						 <span id="voteNum" class="fs-5 text-center mb-2"> </span>						 
					</div>	 
					<div class="table-responsive">					
						<table class="table table-bordered" id="dynamicTable">
							<thead class="table-primary">
								<tr>
									<th style="width:5%;" class="text-center" >번호</th>
									<th style="width:30%;" class="text-center" >투표항목</th>
									<th style="width:15%;" class="text-center" >결과</th>
									<th style="width:12%;" class="text-center" >선택</th>
									<th style="width:38%;" class="text-center" >투표자</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
				</div>
			</div>
			</div>
		 </div>
		 
		 <?php
			}
		  } catch (PDOException $Exception) {
			   print "오류: ".$Exception->getMessage();
		  }
		 ?>  
			</div>
		</div>
	</div> 
</form>

  <form id="Form1" name="Form1">
    <input type="hidden" id="num" name="num" value="<?=$num?>" >
  </form>  

</body>
</html>     
 
<script> 
$(document).on('click', '.vote-button', function() {
    var user_name = '<?php echo $user_name; ?>';
    var itemIndex = $(this).data('item-index');

    updateVote(user_name, itemIndex);
});

function updateTotalVoters() {
    var totalVoters = 0;

    $('#dynamicTable tbody tr').each(function() {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        totalVoters += voters.length;
    });

    $('#voteNum').text('(' + totalVoters + '명 투표)');
}

function updateVote(user_name, itemIndex) {
	
	var status = '<?php echo $status; ?>';
	
	if(status==='진행중')
	{	
	
    var alreadyVotedIndex = -1;

    // 기존 투표 확인 및 새 투표 업데이트
    $('#dynamicTable tbody tr').each(function(index) {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ');

        if (voters.includes(user_name)) {
            alreadyVotedIndex = index; // 이미 투표한 항목 인덱스 저장
            voters = voters.filter(voter => voter !== user_name); // 기존 투표 제거
            $(this).find('input[name="col3[]"]').val(voters.join(', '));
        }
    });

    // 새로운 투표 추가 (이전 투표와 다른 경우에만)
    if (alreadyVotedIndex !== itemIndex) {
        var newVoters = $('#dynamicTable tbody').find('tr').eq(itemIndex).find('input[name="col3[]"]').val().split(', ');
        newVoters.push(user_name);
        $('#dynamicTable tbody').find('tr').eq(itemIndex).find('input[name="col3[]"]').val(newVoters.join(', '));
    }
	
    let columns = {
        col1: [],
        col2: [],
        col3: []
    };	

	$('#dynamicTable tbody tr').each(function(index) {
		var col1 = $(this).find('input[name="col1[]"]').val();
		var col2 = $(this).find('input[name="col2[]"]').val();
		var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);

		if (index === itemIndex && !voters.includes(user_name)) {
			voters.push(user_name); // 중복되지 않는 경우에만 추가
		} else if (index !== itemIndex) {
			voters = voters.filter(voter => voter !== user_name); // 다른 행에서는 제거
		}

		// 업데이트된 참여자 목록으로 col3 값을 설정
		$(this).find('input[name="col3[]"]').val(voters.join(', '));

		if (col1 && col2) {
			columns.col1.push(col1);
			columns.col2.push(col2);
			columns.col3.push(voters.join(', '));
		}
	});

    const dataToSend = {
        num: '<?php echo $num; ?>',
        data: columns
    };

		// AJAX 요청 보내기
		  $.ajax({
			url: 'makejsonlist.php',
			type: 'POST',
			contentType: 'application/json',
			data: JSON.stringify(dataToSend),
			success: function(response) {
				console.log(response);
				Swal.fire({
					title: '투표 성공',
					text: '투표가 성공적으로 되었습니다.',
					icon: 'success'
				}).then((result) => {
					if (result.isConfirmed) {
						location.reload(); // 여기서 페이지를 리로드합니다.
					}
				});
			},
			error: function(error) {
				console.error('투표 실패: ', error);
				Swal.fire(
					'투표 실패',
					'투표 중 오류가 발생했습니다. 다시 시도해주세요.',
					'error'
				);        
			}
		});			
		updateResults(); // 결과 업데이트
		updateTotalVoters(); // 총 투표자 수 업데이트		
	}
}

function updateResults() {
    var totalVotes = 0;

    // 전체 투표 수 계산
    $('#dynamicTable tbody tr').each(function() {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        totalVotes += voters.length;
    });

    // 각 항목의 투표 결과 업데이트
    $('#dynamicTable tbody tr').each(function() {
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        var voteCount = voters.length;
        var votePercentage = totalVotes > 0 ? (voteCount / totalVotes) * 100 : 0;
        
        $(this).find('.resultVote').css('width', votePercentage + '%').attr('aria-valuenow', votePercentage).text(voteCount + ' 표');
    });
}


$(document).ready(function() {
    var i = 1;

    // '-' 버튼 이벤트 핸들러: 현재 행 삭제
    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
    });

    var piclistObj = {};
    try {
        piclistObj = JSON.parse('<?php echo addslashes($votelist); ?>');
    } catch (e) {
        console.error("JSON 파싱 오류: ", e);
    }

    // Row_COUNT를 piclistObj의 col2 배열 길이에 따라 동적으로 설정
    const Row_COUNT = piclistObj.col2 ? piclistObj.col2.length : 0;
    const COL_NAMES = 3; // 이제 열의 수는 3개입니다.
    const column = Array.from({ length: COL_NAMES }, function(_, i) { return 'col' + (i+1); });

    // 데이터가 없는 경우에만 초기 행 추가
    if (!piclistObj.col2 || piclistObj.col2.length === 0) {
        $('#dynamicTable tbody').append('<tr id="row1">' +
            '<td><input type="text" name="col1[]" class="form-control text-center"  value="1" /></td>' +
            '<td><input type="text" name="col2[]" class="form-control" readonly /></td>' +
            '<td> </td>' +
            '<td class="text-center"><button type="button" class="btn btn-success btn-sm text-center vote-button" data-item-index="0">투표하기</button></td>' +
            '<td class="text-center"><input type="text" name="col3[]" class="form-control form-control-scrollable" readonly /></td>' + // 참여자 목록을 추가합니다.
        '</tr>');
    }

    const data = Array.from({ length: Row_COUNT }, function(_, i) {
        var row = {};
        column.forEach(function(col, index) {
            row[col] = (piclistObj[col] && piclistObj[col][i] ? piclistObj[col][i] : '');
        });
        return row;
    });

    data.forEach(function(row, index) {
        // col2에 값이 있는 경우에만 행을 추가합니다.
        if (row.col2) {
            $('#dynamicTable tbody').append('<tr>' +
                '<td><input type="text" name="col1[]" class="form-control text-center" value="' + (index + 1) + '" /></td>' +
                '<td> <input type="text" name="col2[]" class="form-control" readonly value="' + row.col2 + '" /></td>' +
                '<td>   <div class="progress">  <div class="progress-bar resultVote" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>  </div>    </td>' +
                '<td class="text-center"><button type="button" class="btn btn-success btn-sm text-center vote-button" data-item-index="' + index + '">투표하기</button></td>' +
                '<td class="text-center"><input type="text" name="col3[]" class="form-control form-control-scrollable" value="' + (row.col3 || '') + '" readonly /></td>' + // 참여자 목록을 추가합니다.
            '</tr>');
        }
    });
	
    updateResults(); // 결과 업데이트
    updateTotalVoters(); // 총 투표자 수 업데이트
	
	// 투표결과를 차트로 보여주기	
    var ctx = document.getElementById('voteChart').getContext('2d');
    var voteLabels = []; // 투표 항목의 이름을 저장할 배열
    var voteData = []; // 각 항목에 대한 투표 수를 저장할 배열

    // 테이블에서 투표 데이터 수집
    $('#dynamicTable tbody tr').each(function() {
		var item = $(this).find('td:nth-child(2) input').val(); // 투표 항목 이름
        console.log(item);
        
        var voters = $(this).find('input[name="col3[]"]').val().split(', ').filter(Boolean);
        var voteCount = voters.length; // 투표 수

        voteLabels.push(item);
        voteData.push(voteCount);
    });

    // 파이 차트 생성
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: voteLabels,
            datasets: [{
                label: '투표 결과',
                data: voteData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    // 추가 색상 필요 시 여기에 추가
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    // 추가 색상 필요 시 여기에 추가
                ],
                borderWidth: 1
            }]
        },        
        plugins: {
            datalabels: {
                color: '#000000', // 라벨 색상
                formatter: function(value, context) {
                    return context.chart.data.labels[context.dataIndex] + ': ' + value;
                }
            }
        }    
});  // end of chart function
	

});

function del(href) {    
    var user_id  = '<?php echo  $user_id ; ?>' ;
    var item_id  = '<?php echo  $item_id ; ?>' ;
    var admin  = '<?php echo  $admin ; ?>' ;
	if( user_id !== item_id && admin !== '1' )
	{
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
					url:'delete.php',
					type:'post',
					data: $("#Form1").serialize(),
					dataType: 'json',
					}).done(function(data){		
						Toastify({
							text: "파일 삭제완료 ",
							duration: 2000,
							close:true,
							gravity:"top",
							position: "center",
							style: {
								background: "linear-gradient(to right, #00b09b, #96c93d)"
							},
						}).showToast();	
						setTimeout(function(){
							if (window.opener && !window.opener.closed) {
								// window.opener.restorePageNumber(); // 부모 창에서 페이지 번호 복원
								window.opener.location.reload(); // 부모 창 새로고침
								 $('#closeBtn').click();
							}							
							
						}, 1000);
			
					  
					});
            }
        });
    }
}

$(document).ready(function(){	

	$('#closeBtn').click(function() {
		window.close(); // 현재 창 닫기
	});
});
   
</script>

