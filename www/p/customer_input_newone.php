<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/session.php");

$tablename = 'work';

$mode = $_REQUEST["mode"] ?? "";
$num = $_REQUEST["num"] ?? "";
$page = $_REQUEST["page"] ?? 1;
$option = $_REQUEST["option"] ?? "";

include $_SERVER['DOCUMENT_ROOT'] . '/load_header.php';    	  
require_once("../lib/mydb.php");
$pdo = db_connect();

try {
    $sql = "SELECT * FROM $DB.$tablename WHERE num = ?";
    $stmh = $pdo->prepare($sql); 
    $stmh->bindValue(1, $num, PDO::PARAM_STR); 
    $stmh->execute();
    $count = $stmh->rowCount();              
    if ($count < 1) {  
        print "검색결과가 없습니다.<br>";
    } else {
        $row = $stmh->fetch(PDO::FETCH_ASSOC);
    }

    include $_SERVER['DOCUMENT_ROOT'] . '/work/_row.php'; 

    // customer 필드 가져오기 (Json형태의 값)
    $customer_data = isset($row["customer"]) ? $row["customer"] : '{}';
    // JSON 데이터를 PHP 객체로 디코딩
    $customer_object = json_decode($customer_data);
	
	// print $customer_data ;
    if ($customer_data ==='{}')
		{
        // JSON 디코딩에 실패한 경우 처리
        // (올바르지 않은 JSON 형식일 경우, null을 반환합니다)
        echo "<h1> 서명이 존재하지 않습니다. </h1>";
    } else {
        // 디코딩된 데이터를 각 변수에 할당
        $customer_date = isset($customer_object->customer_date) ? $customer_object->customer_date : date('Y-m-d');
        $ordercompany = isset($customer_object->ordercompany) ? $customer_object->ordercompany : '미래기업';
        $workplacename = isset($customer_object->workplacename) ? $customer_object->workplacename : '';
        $workname = isset($customer_object->workname) ? $customer_object->workname : '부대공사';
        $totalsu = isset($customer_object->totalsu) ? $customer_object->totalsu : '00 개소';
        $pjnum = isset($customer_object->pjnum) ? $customer_object->pjnum : '';        
        $worker = isset($customer_object->worker) ? $customer_object->worker : '';
        $customer_name = isset($customer_object->customer_name) ? $customer_object->customer_name : '';
        $image_url = isset($customer_object->image_url) ? $customer_object->image_url : '';
    }

} catch (PDOException $Exception) {
    print "오류: " . $Exception->getMessage();
}

$fs = 'fs-3';   

// Define the pattern to match the desired substring
$pattern = '/\(([A-Za-z][^)]*)\)/';

// Execute the regular expression
if (preg_match($pattern, $workplacename, $matches)) {
    $pjnum = $matches[1];
} else {
    $pjnum = ''; // Default value if no match is found
}

// var_dump( $image_url);

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title> 공사완료 확인서 </title>
</head>
<body>
    <form id="board_form" name="board_form" method="post" enctype="multipart/form-data"> 
        <input type="hidden" id="num" name="num" value="<?=$num?>">      
        <input type="hidden" id="image_url" name="image_url" value="<?=$image_url?>">      

        <div class="container-fluid">
            <div class="card mt-4">
                <div class="card-header text-center">
                    <span class="card-title fs-1"> 공사 현장 정보</span> 
                </div> 
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> JOB NO </span>
                                <input type="text" class="text-start <?=$fs?> form-control" name="pjnum" id="pjnum" value="<?=$pjnum?>">
                            </div>
                        </div>                        
                        <div class="row">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> 현장명 </span>
                                <input type="text" class="form-control <?=$fs?>" id="workplacename"  name="workplacename" value="<?=$workplacename?>">
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> 작업내용 </span>
                                <input type="text" class="form-control <?=$fs?>" name="workname" id="workname" value="부대공사">
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> 작업수량 </span>
                                <input type="text" class="text-start <?=$fs?> form-control" name="totalsu" id="totalsu" value="<?=$totalsu?>">
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> 시공소장 </span>
                                <input type="text" class="text-start <?=$fs?> form-control" name="worker" id="worker" value="<?=$worker?>">
                            </div>
                        </div>
                        <div class="row mt-5 mb-5">                        
                            <div class="d-flex justify-content-center fs-2">
                                상기 현장의 공사가 완료 되었음을 확인함. 
                            </div>                        
                        </div>
                        <div class="row mt-5">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> 확인일자 </span>
                                <input type="date" class="form-control <?=$fs?>" id="customer_date" name="customer_date" value="<?=$customer_date?>">                                                    
                            </div>                        
                        </div>
                        <div class="row mb-3">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> 확인자 </span>
                                <input type="text" class="form-control <?=$fs?>" id="customer_name" name="customer_name" value="<?=$customer_name?>">                                                    
                            </div>                        
                        </div>
                        <div class="row mb-3">                        
                            <div class="input-group mb-1">
                                <span class="input-group-text fs-2" style="width:22%;"> 서명 </span>
                                <span class="fs-2 text-center" style="width:30%;">
                                    <?php
                                    if (!empty($image_url)) {
                                        print '<img id="signatureBtn" src="../work/' . $image_url . '" style="width:60%;">';
                                    }
                                    ?>
                                 </span>
                                <button type="button" id="exesignatureBtn" class="btn btn-success"> 서명하기 </button>
                            </div>                        
                        </div>
                        <div class="row mb-2">
                            <div class="d-flex align-items-center">                                
                                &nbsp; &nbsp; &nbsp; &nbsp;
                                <button type="button" id="saveBtn" class="btn btn-dark me-2"> 저장 </button>
                                <button type="button" id="printBtn" class="btn btn-primary me-2">확인서 보기</button>
								<button type="button" class="btn btn-outline-secondary" onclick="self.close();">닫기</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<script>
$(document).ready(function() {
        <?php
        $customer_data = $customer_data ?? '';
        if (!empty($customer_data)) {
            echo "try {
                var customerData = JSON.parse('" . addslashes($customer_data) . "');
            } catch (e) {
                console.error('JSON 파싱 오류:', e);
                var customerData = {};
            }";
        } else {
            echo "var customerData = {};";
        }
        ?>

        if (!customerData.customer_date || customerData.customer_date.trim() === "") {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var formattedDate = yyyy + '-' + mm + '-' + dd;
            var customerDateElement = document.getElementById("customer_date");
            if (customerDateElement !== null) {
                customerDateElement.value = formattedDate;
            }
        } else {
            var customerDateElement = document.getElementById("customer_date");
            if (customerDateElement !== null) {
                customerDateElement.value = customerData.customer_date;
            }
        }

        var ordercompanyElement = document.getElementById("ordercompany");
        if (ordercompanyElement !== null) {
            ordercompanyElement.value = customerData.ordercompany || '미래기업';
        }

        var workplacenameElement = document.getElementById("workplacename");
        if (workplacenameElement !== null) {
            workplacenameElement.value = customerData.workplacename || '<?php echo $workplacename; ?>';
        }
		else
		{
			workplacenameElement.value = '<?php echo $workplacename; ?>';
		}

        var worknameElement = document.getElementById("workname");
        if (worknameElement !== null) {
            worknameElement.value = customerData.workname || '부대공사';
        }

        var totalsuElement = document.getElementById("totalsu");
        if (totalsuElement == null) {
            totalsuElement.value = customerData.totalsu || '';
        }        

        var pjnumElement = document.getElementById("pjnum");
        if (pjnumElement == null) {
            // JavaScript로 정규 표현식을 사용하여 pjnum 추출
            var workplacename = customerData.workplacename || '';
            var pattern = /\(([A-Za-z][^)]*)\)/;
            var matches = pattern.exec(workplacename);
            pjnumElement.value = matches ? matches[1] : '';
        }

        var workerElement = document.getElementById("worker");
        if (workerElement == null) {
            workerElement.value = customerData.worker || '';
        }
        var customerNameElement = document.getElementById("customer_name");
        if (customerNameElement !== null) {
            customerNameElement.value = customerData.customer_name || '';
        }

        var imageURLElement = document.getElementById("image_url");
        if (imageURLElement !== null) {
            imageURLElement.value = customerData.image_url || '';
        }
		
    });

    function captureReturnKey(e) {
        if (e.keyCode == 13 && e.srcElement.type != 'textarea') return false;
    }

    function recaptureReturnKey(e) {
        if (e.keyCode == 13) exe_search();
    }

    $(document).ready(function() {

        function saveCustomerData(callback) {
            const num = $("#num").val();

            var form = $('#board_form')[0];
            form.querySelectorAll("input, select").forEach(function(element) {
               // console.log(element.name, element.value);
            });

            var customerData = {};
            
            var customerDateElement = document.getElementById("customer_date");
            if (customerDateElement !== null) {
                customerData.customer_date = customerDateElement.value;
            }

            var ordercompanyElement = document.getElementById("ordercompany"); 
            if (ordercompanyElement !== null) {
                customerData.ordercompany = ordercompanyElement.value;
            }

            var workplacenameElement = document.getElementById("workplacename");
            if (workplacenameElement !== null) {
                customerData.workplacename = workplacenameElement.value;
            }

            var worknameElement = document.getElementById("workname");
            if (worknameElement !== null) {
                customerData.workname = worknameElement.value;
            }

            var totalsuElement = document.getElementById("totalsu");
            if (totalsuElement !== null) {
                customerData.totalsu = totalsuElement.value;
            }

            var pjnumElement = document.getElementById("pjnum");
            if (pjnumElement !== null) {
                customerData.pjnum = pjnumElement.value;
            }

            var workerElement = document.getElementById("worker");
            if (workerElement !== null) {
                customerData.worker = workerElement.value;
            }

            var customerNameElement = document.getElementById("customer_name");
            if (customerNameElement !== null) {
                customerData.customer_name = customerNameElement.value;
            }

            var imageUrlElement = document.getElementById("image_url");
            if (imageUrlElement !== null) {
                customerData.image_url = imageUrlElement.value;
            }

            var formData = new FormData(form);
            formData.append('customerData', JSON.stringify(customerData));

            $.ajax({
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                url: "customer_save.php",
                type: "post",
                data: formData,
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    Toastify({
                        text: "내용이 저장되었습니다.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: 'right',
                    }).showToast();

                    if (callback) callback();
                },
                error: function(jqxhr, status, error) {
                    console.log(jqxhr, status, error);
                    if (callback) callback();
                }
            });
        }

        $("#saveBtn").click(function() { 
            saveCustomerData();
        });

        $("#exesignatureBtn").click(function() { 
			saveCustomerData();
            saveCustomerData(function() {
                const num = $("#num").val();
                popupCenter('signature_pad.php?num=' + num, '고객서명', 800, 800); 
            });
        });

        $("#printBtn").click(function() { 
            saveCustomerData(function() {
                const num = $("#num").val();
                popupCenter('customer_print_newone.php?num=' + num , '공 사 완 료 확 인 서', 1000, 900); 
            });
        });
    });
</script>
</body>
</html>
