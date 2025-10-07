<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Text;
use Face\Style;

//First create a new drawing
$draw = new Draw;

//The parameters WIDTH, HEIGHT, ANGLE and MIRROR are similar to the parameters of the same name in the Entity Text.
//See related example. You can create style with these options and specify this style when creating text. For example.

$style1 = new Style(['height' => 7, 'angle' => 30, 'name' => 'style1']);
$draw->addStyle($style1);
$draw->insertEntity(new Text(['point' => [0, 50], 'txt' => 'text height 7 angle 30'], $style1));

//Default font is SIMPLEX.SHX. You can change it.
$arial = new Style(['height' => 5, 'font' => 'Arial.ttf', 'name' => 'Arial']);
$draw->addStyle($arial);
$draw->insertEntity(new Text(['point' => [0, 40], 'txt' => 'text Arial'], $arial));

//if you want to get a vertically oriented text, then set the value of the flag parameter to 4
$vertical = new Style(['height' => 5, 'flag' => 4, 'name' => 'Vertical']);
$draw->addStyle($vertical);
$draw->insertEntity(new Text(['point' => [0, 30], 'txt' => 'text vertical'], $vertical));

//Finally save the drawing. Add filename without extension as parameter
$draw->save('style');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('style', 'Windows-1251');
