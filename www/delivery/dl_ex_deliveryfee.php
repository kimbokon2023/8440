<?php
// 에러 표시 설정 (개발 중에는 활성화, 배포 시 비활성화 권장)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../error_log.txt'); // 에러 로그 파일 저장 경로
error_reporting(E_ALL);

// JSON 응답 헤더 설정
header('Content-Type: application/json');

// JSON 응답을 생성하는 함수
function sendResponse($success, $data = null, $message = '') {
    $response = ['success' => $success];
    if ($data !== null) {
        $response['filename'] = $data;
    }
    if ($message !== '') {
        $response['message'] = $message;
    }
    echo json_encode($response);
    exit;
}

// 에러 핸들러 (모든 오류를 예외로 변환)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

try {
    // 요청 방식 확인
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        sendResponse(false, null, '잘못된 요청 방법입니다.');
    }

    // JSON 데이터 읽기
    $data = json_decode(file_get_contents('php://input'), true);

    // JSON 디코딩 오류 및 데이터 유무 확인
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
        sendResponse(false, null, '유효하지 않은 데이터 형식이거나 데이터가 없습니다.');
    }

    // PHPExcel 라이브러리 포함 (경로 확인 필요)
    $phpExcelPath = '../PHPExcel_1.8.0/Classes/PHPExcel.php';
    if (!file_exists($phpExcelPath)) {
        sendResponse(false, null, 'PHPExcel 라이브러리가 존재하지 않습니다. 경로를 확인하세요.');
    }
    require $phpExcelPath;

    // 새로운 PHPExcel 객체 생성
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();

    // 엑셀 1행(A1:F1) 병합 및 스타일 설정
    $sheet->mergeCells('A1:F1');
    foreach ($data as $row) {
       $dateStr = $row['등록일자'];
	}		
	
    $sheet->setCellValue('A1', $dateStr . ' 경동화물');
    $sheet->getStyle('A1:F1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 28 // 글씨 크기 2배
        ],
        'alignment' => [
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
        ]
    ]);

    // 엑셀 1행(G1:K1) 병합 및 세로 정렬 (텍스트 회전)
    $sheet->mergeCells('G1:K1');
    $sheet->setCellValue('G1', "(김포양촌누산1108 영업소   전  983 0406  팩 984 1964)");
    $sheet->getStyle('G1:K1')->applyFromArray([
        'alignment' => [
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM,
            'textRotation' => 90 // 텍스트 90도 회전
        ],
        'font' => [
            'bold' => true,
            'size' => 10
        ]
    ]);

    // 엑셀 헤더 설정
    $headers = [
        '번호', '받을 분', '연락처', '도착지 주소', 
        '보내는 사람', '품명/현장명', '포장', '수량', '운임', 
        '운임구분', '물품가액'
    ];
    
    // 헤더 삽입 (2행부터 시작)
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '2', $header);
        $col++;
    }

    // 헤더 행 스타일 설정 (배경색 + 테두리 추가)
    $headerStyle = [
        'fill' => [
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => ['rgb' => 'D3D3D3'] // 연한 회색
        ],
        'alignment' => [
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
        ],
        'borders' => [
            'allborders' => [
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => ['rgb' => '000000'] // 검정색 테두리
            ]
        ]
    ];
    $sheet->getStyle('A2:K2')->applyFromArray($headerStyle);
	
    // 각 열 너비 설정
    $columnWidths = [
        'A' => 7,   // 번호
        'B' => 12,   // 받을 분
        'C' => 12,   // 연락처
        'D' => 55,   // 도착지 주소
        'E' => 15,   // 보내는 사람
        'F' => 25,   // 품명/현장명
        'G' => 5,   // 포장
        'H' => 5,   // 수량
        'I' => 5,   // 운임
        'J' => 10,   // 운임구분
        'K' => 10   // 물품가액
    ];
    foreach ($columnWidths as $column => $width) {
        $sheet->getColumnDimension($column)->setWidth($width);
    }	

    // 데이터 삽입 (3행부터 시작)
    $rowNumber = 3;
    foreach ($data as $row) {
        $sheet->setCellValue("A{$rowNumber}", $row['번호'] ?? '');
        $sheet->setCellValue("B{$rowNumber}", $row['받을 분'] ?? '');
        $sheet->setCellValue("C{$rowNumber}", $row['연락처'] ?? '');
        $sheet->setCellValue("D{$rowNumber}", $row['도착지 주소'] ?? '');
        $sheet->setCellValue("E{$rowNumber}", $row['보내는 사람'] ?? '');
        $sheet->setCellValue("F{$rowNumber}", $row['품명/현장명'] ?? '');
        $sheet->setCellValue("G{$rowNumber}", $row['포장'] ?? '');
        $sheet->setCellValue("H{$rowNumber}", $row['수량'] ?? '');

        // 운임 및 물품가액은 오른쪽 정렬
        $sheet->setCellValue("I{$rowNumber}", $row['운임'] ?? '');
        $sheet->setCellValue("J{$rowNumber}", $row['운임구분'] ?? '');
        $sheet->setCellValue("K{$rowNumber}", $row['물품가액'] ?? '');

        $rowNumber++;
    }

    // 데이터 범위의 테두리 추가
    $dataRange = 'A2:K' . ($rowNumber - 1);
    $sheet->getStyle($dataRange)->applyFromArray([
        'borders' => [
            'allborders' => [
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => ['rgb' => '000000'] // 검정색 테두리
            ]
        ]
    ]);

    // 운임 및 물품가액 열 오른쪽 정렬 적용
    $sheet->getStyle("I3:I$rowNumber")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $sheet->getStyle("K3:K$rowNumber")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    // 엑셀 파일 저장 경로 설정
    $filename = 'delivery_fee_' . date('YmdHis') . '.xlsx';
    $filePath = '../excelsave/' . $filename;

    // PHPExcel Writer 설정
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

    // 엑셀 파일 저장
    $objWriter->save($filePath);

    if (file_exists($filePath)) {
        sendResponse(true, $filePath, '');
    } else {
        sendResponse(false, null, '엑셀 파일을 저장하지 못했습니다.');
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    sendResponse(false, null, '엑셀 파일 생성 중 오류: ' . $e->getMessage());
}
?>
