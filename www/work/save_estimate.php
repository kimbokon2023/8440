<?php
require_once __DIR__ . '/../bootstrap.php';

// 권한 확인
if (!isset($_SESSION["level"]) || $_SESSION["level"] > 5) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => '권한이 없습니다.']);
    exit;
}

// php warning 안나오게 하는 방법
ini_set('display_errors', 'Off');

// 요청 변수 안전하게 초기화
$tmp = array();
$tmp['WJ'] = $_REQUEST["WJ"] ?? '';
$tmp['NJ'] = $_REQUEST["NJ"] ?? '';
$tmp['SJ'] = $_REQUEST["SJ"] ?? '';
$tmp['WJ_HL'] = $_REQUEST["WJ_HL"] ?? '';
$tmp['NJ_HL'] = $_REQUEST["NJ_HL"] ?? '';
$tmp['SJ_HL'] = $_REQUEST["SJ_HL"] ?? '';

// 쉼표 제거 (숫자만 저장)
foreach ($tmp as $key => $value) {
    $tmp[$key] = str_replace(',', '', $value);
}

$obj = (object) $tmp;

// 디버그 출력 (필요시 주석 해제)
// $SelectWork = $_REQUEST["SelectWork"] ?? '';
// print $SelectWork . "<br>";
// print_r($tmp);
// print_r($obj);

write_ini_file($obj, includePath('work/estimate.ini'), false); 

// ini 파일 write
function write_ini_file($assoc_arr, $path, $has_sections = false) {
    $content = "";
    
    if ($has_sections) {
        $i = 0;
        foreach ($assoc_arr as $key => $elem) {
            if ($i > 0) {
                $content .= "\n";
            }
            $content .= "[" . $key . "]\n";
            
            foreach ($elem as $key2 => $elem2) {
                if (is_array($elem2)) {
                    for ($j = 0; $j < count($elem2); $j++) {
                        $content .= $key2 . "[] = \"" . $elem2[$j] . "\"\n";
                    }
                } else if ($elem2 == "") {
                    $content .= $key2 . " = \n";
                } else {
                    if (preg_match('/[^0-9]/i', $elem2)) {
                        $content .= $key2 . " = \"" . $elem2 . "\"\n";
                    } else {
                        $content .= $key2 . " = " . $elem2 . "\n";
                    }
                }
            }
            $i++;
        }
    } else {
        foreach ($assoc_arr as $key => $elem) {
            if (is_array($elem)) {
                for ($i = 0; $i < count($elem); $i++) {
                    $content .= $key . "[] = \"" . $elem[$i] . "\"\n";
                }
            } else if ($elem == "") {
                $content .= $key . " = \n";
            } else {
                if (preg_match('/[^0-9]/i', $elem)) {
                    $content .= $key . " = \"" . $elem . "\"\n";
                } else {
                    $content .= $key . " = " . $elem . "\n";
                }
            }
        }
    }

    if (!$handle = fopen($path, 'w')) {
        return false;
    }

    $success = fwrite($handle, $content);
    fclose($handle);

    return $success;
}
?>

