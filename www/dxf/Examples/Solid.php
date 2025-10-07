<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Solid;

//First create a new drawing
$draw = new Draw;

//Then insert a Solid into the drawing
$draw->insertEntity(new Solid(['points' => [[0, 5], [2, 10], [8, 6], [10, 12]]]));

//Where:
//point[x1,y1][x2,y2][x3,y3][x4,y4]- four points defining the corners of the solid

//If you specify only three points, then you will get a triangle
$draw->insertEntity(new Solid(['points' => [[20, 5], [22, 10], [28, 6]]]));

//If you use negative values ​​enclose them in single quotes. For example '-10'
//You can use common properties like 'layer', 'ltype' and 'color'. See related examples.

//Finally save the drawing. Add filename without extension as parameter
$draw->save('solid');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('solid', 'Windows-1251');
