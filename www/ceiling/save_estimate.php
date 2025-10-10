<?php
/**
 * save_estimate.php
 * 견적 단가 정보를 INI 파일에 저장
 */

session_start();

// 에러 표시 설정
ini_set('display_errors', '0');

// REQUEST 변수 안전하게 초기화
$bon_unit_12 = isset($_REQUEST['bon_unit_12']) ? $_REQUEST['bon_unit_12'] : '';
$bon_unit_13to17 = isset($_REQUEST['bon_unit_13to17']) ? $_REQUEST['bon_unit_13to17'] : '';
$bon_unit_18 = isset($_REQUEST['bon_unit_18']) ? $_REQUEST['bon_unit_18'] : '';
$lc_unit_12 = isset($_REQUEST['lc_unit_12']) ? $_REQUEST['lc_unit_12'] : '';
$lc_unit_13to17 = isset($_REQUEST['lc_unit_13to17']) ? $_REQUEST['lc_unit_13to17'] : '';
$lc_unit_18 = isset($_REQUEST['lc_unit_18']) ? $_REQUEST['lc_unit_18'] : '';

// 배열에 데이터 저장
$tmp = array();
$tmp['bon_unit_12'] = $bon_unit_12;
$tmp['bon_unit_13to17'] = $bon_unit_13to17;
$tmp['bon_unit_18'] = $bon_unit_18;
$tmp['lc_unit_12'] = $lc_unit_12;
$tmp['lc_unit_13to17'] = $lc_unit_13to17;
$tmp['lc_unit_18'] = $lc_unit_18;

// 객체로 변환
$obj = (object) $tmp;

// 디버깅 출력 (개발 환경에서만 사용)
// print_r($tmp);
// print_r($obj);

// INI 파일에 저장
write_ini_file($obj, 'estimate.ini', false); 


/**
 * INI 파일 작성 함수
 * 
 * @param mixed $assoc_arr 저장할 데이터 (배열 또는 객체)
 * @param string $path INI 파일 경로
 * @param bool $has_sections 섹션 사용 여부
 * @return bool 성공 여부
 */
function write_ini_file($assoc_arr, $path, $has_sections = false)
{
    $content = "";
    
    if ($has_sections) {
        // 섹션이 있는 경우
        $i = 0;
        foreach ($assoc_arr as $key => $elem) {
            if ($i > 0) {
                $content .= "\n";
            }
            $content .= "[" . $key . "]\n";
            
            foreach ($elem as $key2 => $elem2) {
                if (is_array($elem2)) {
                    // 배열 값 처리
                    for ($j = 0; $j < count($elem2); $j++) {
                        $content .= $key2 . "[] = \"" . $elem2[$j] . "\"\n";
                    }
                } elseif ($elem2 == "") {
                    // 빈 값 처리
                    $content .= $key2 . " = \n";
                } else {
                    // 일반 값 처리
                    if (preg_match('/[^0-9]/i', $elem2)) {
                        // 숫자가 아닌 경우 따옴표 추가
                        $content .= $key2 . " = \"" . $elem2 . "\"\n";
                    } else {
                        // 숫자인 경우 따옴표 없이
                        $content .= $key2 . " = " . $elem2 . "\n";
                    }
                }
            }
            $i++;
        }
    } else {
        // 섹션이 없는 경우
        foreach ($assoc_arr as $key => $elem) {
            if (is_array($elem)) {
                // 배열 값 처리
                for ($i = 0; $i < count($elem); $i++) {
                    $content .= $key . "[] = \"" . $elem[$i] . "\"\n";
                }
            } elseif ($elem == "") {
                // 빈 값 처리
                $content .= $key . " = \n";
            } else {
                // 일반 값 처리
                if (preg_match('/[^0-9]/i', $elem)) {
                    // 숫자가 아닌 경우 따옴표 추가
                    $content .= $key . " = \"" . $elem . "\"\n";
                } else {
                    // 숫자인 경우 따옴표 없이
                    $content .= $key . " = " . $elem . "\n";
                }
            }
        }
    }
    
    // 파일 열기
    if (!$handle = fopen($path, 'w')) {
        return false;
    }
    
    // 파일 쓰기
    $success = fwrite($handle, $content);
    fclose($handle);
    
    return $success;
}

?>