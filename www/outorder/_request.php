<?php
/**
 * 외주 주문 요청 파라미터 처리
 * 로컬 및 서버 환경 모두 지원
 */

// 요청 변수 초기화 (?? '' 형태)
$search = $_REQUEST["search"] ?? '';
$list = $_REQUEST["list"] ?? 0;
$scale = $_REQUEST["scale"] ?? 50;
$page = $_REQUEST["page"] ?? 1;

// 체크 필터 변수 초기화 (?? '' 형태)
$check_draw = $_REQUEST["check_draw"] ?? $_POST["check_draw"] ?? '';         // 도면 미설계 List
$notorder = $_REQUEST["notorder"] ?? $_POST["notorder"] ?? '';               // 미발주 부품 List
$check = $_REQUEST["check"] ?? $_POST["check"] ?? '';                        // 미출고 List
$plan_output_check = $_REQUEST["plan_output_check"] ?? $_POST["plan_output_check"] ?? '0';  // 출고예정
$output_check = $_REQUEST["output_check"] ?? $_POST["output_check"] ?? '0'; // 출고완료
$team_check = $_REQUEST["team_check"] ?? $_POST["team_check"] ?? '0';       // 시공팀미지정
$measure_check = $_REQUEST["measure_check"] ?? $_POST["measure_check"] ?? '0';  // 본천장설계

// 정렬 관련 변수 초기화 (?? '' 형태)
$cursort = $_REQUEST["cursort"] ?? $_POST["cursort"] ?? '0';  // 현재 정렬 상태
$sortof = $_REQUEST["sortof"] ?? $_POST["sortof"] ?? '0';     // 정렬 필드
$stable = $_REQUEST["stable"] ?? $_POST["stable"] ?? '0';     // 정렬 안정화 플래그

// 정렬 로직 처리
if ($sortof != '0') {
    
    if ($sortof == 1 && $stable == 0) {
        // 접수일 클릭되었을 때
        if ($cursort != 1) {
            $cursort = 1;
        } else {
            $cursort = 2;
        }
    }
    
    if ($sortof == 2 && $stable == 0) {
        // 착공일 클릭되었을 때
        if ($cursort != 3) {
            $cursort = 3;
        } else {
            $cursort = 4;
        }
    }
    
    if ($sortof == 3 && $stable == 0) {
        // 발주일 클릭되었을 때
        if ($cursort != 5) {
            $cursort = 5;
        } else {
            $cursort = 6;
        }
    }
    
    if ($sortof == 4 && $stable == 0) {
        // 도면작성일 클릭되었을 때
        if ($cursort != 7) {
            $cursort = 7;
        } else {
            $cursort = 8;
        }
    }
    
    if ($sortof == 5 && $stable == 0) {
        // 출고일 클릭되었을 때
        if ($cursort != 9) {
            $cursort = 9;
        } else {
            $cursort = 10;
        }
    }
    
    if ($sortof == 6 && $stable == 0) {
        // 청구 클릭되었을 때
        if ($cursort != 11) {
            $cursort = 11;
        } else {
            $cursort = 12;
        }
    }
    
    if ($sortof == 7 && $stable == 0) {
        // 검사일 클릭되었을 때
        if ($cursort != 13) {
            $cursort = 13;
        } else {
            $cursort = 14;
        }
    }
}

// 데이터베이스 연결
require_once("../lib/mydb.php");

try {
    $pdo = db_connect();
} catch (Exception $ex) {
    error_log("DB 연결 실패: " . $ex->getMessage());
    die("데이터베이스 연결에 실패했습니다.");
}

?>