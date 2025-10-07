<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Circle;

//First create a new drawing
$draw = new Draw;

//Then insert a Circle into the drawing
$draw->insertEntity(new Circle(['point' => [10, 10], 'r' => 10]));

//Where:
//point[x,y] - center
//r - radius
//If you use negative values ​​enclose them in single quotes. For example '-10'
//You can use common properties like 'layer', 'ltype' and 'color'. See related examples.

//Finally save the drawing. Add filename without extension as parameter
$draw->save('circle');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('circle', 'Windows-1251');
