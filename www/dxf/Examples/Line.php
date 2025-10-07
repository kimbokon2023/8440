<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Line;

//First create a new drawing
$draw = new Draw;

//Then insert a Line into the drawing
$draw->insertEntity(new Line(['points' => [[7, '-9'], [12, 34]]]));

//Where:
//point[x1,y1][x2,y2]- startpoint, endpoint
//If you use negative values ​​enclose them in single quotes. For example '-10'
//You can use common properties like 'layer', 'ltype' and 'color'. See related examples.

//Finally save the drawing. Add filename without extension as parameter
$draw->save('line');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('line', 'Windows-1251');
