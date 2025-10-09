<?php
$code = file_get_contents('www/p/index.php');
$tokens = token_get_all($code);
$stack = [];
$line_stack = [];
$currentLine = 1;
foreach ($tokens as $tok) {
    if (is_string($tok)) {
        $content = $tok;
        $length = strlen($content);
        for ($i = 0; $i < $length; $i++) {
            $ch = $content[$i];
            if ($ch === "\n") {
                $currentLine++;
            }
            if ($ch === '{' || $ch === '[' || $ch === '(') {
                $stack[] = $ch;
                $line_stack[] = $currentLine;
            } elseif ($ch === '}' || $ch === ']' || $ch === ')') {
                if (empty($stack)) {
                    echo "Unmatched closing $ch at line $currentLine" . PHP_EOL;
                    exit;
                }
                $open = array_pop($stack);
                $openLine = array_pop($line_stack);
                $pair = $open . $ch;
                if (!in_array($pair, ['{}', '[]', '()'], true)) {
                    echo "Mismatched closing $ch at line $currentLine (opened $open at line $openLine)" . PHP_EOL;
                    exit;
                }
            }
        }
    } else {
        $content = $tok[1];
        $tokenLines = substr_count($content, "\n");
        if ($tok[0] === T_CURLY_OPEN || $tok[0] === T_DOLLAR_OPEN_CURLY_BRACES) {
            $stack[] = '{';
            $line_stack[] = $currentLine;
        }
        $currentLine += $tokenLines;
    }
}
if (!empty($stack)) {
    echo "Unclosed braces remain:" . PHP_EOL;
    foreach ($stack as $i => $brace) {
        $line = $line_stack[$i] ?? '?';
        echo "  $brace opened near line $line" . PHP_EOL;
    }
} else {
    echo "Braces look balanced" . PHP_EOL;
}
