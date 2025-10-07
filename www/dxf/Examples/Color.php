<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Line;
use Entities\Solid;
use Entities\Text;

//First create a new drawing
$draw = new Draw;

//You can set the color value in the range from 1 to 255. Default color value is 0 (BYLAYER).
//If you want to get the color value BYBLOCK, set the value of the color parameter to 0.

//Red line
$draw->insertEntity(new Line(['points' => [[0, 40], [20, 40]], 'color' => 1]));

//Green text
$draw->insertEntity(new Text(['point' => [0, 30], 'txt' => 'Just green text', 'color' => 3]));

//Navy Blue solid
$draw->insertEntity(new Solid(['points' => [[0, 0], [5, 20], [10, 10]], 'color' => 160]));

//Finally save the drawing. Add filename without extension as parameter
$draw->save('color');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('color', 'Windows-1251');
