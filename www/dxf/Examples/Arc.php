<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Arc;

//First create a new drawing
$draw = new Draw;

//Then insert an Arc into the drawing
$draw->insertEntity(new Arc(['point' => [5, 5], 'r' => 10, 'angle' => [270, 90]]));

//Where:
//point[x,y] - center
//r - radius
// angle[x,y] - start angle, end angle
//If you use negative values ​​enclose them in single quotes. For example '-10'
//You can use common properties like 'layer', 'ltype' and 'color'. See related examples.

//Finally save the drawing. Add filename without extension as parameter
$draw->save('arc');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('arc', 'Windows-1251');
?>