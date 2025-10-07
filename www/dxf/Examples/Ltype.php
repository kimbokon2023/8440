<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Circle;
use Entities\Line;
use Face\Ltype;

//First create a new drawing
$draw = new Draw;

//LTYPE has the parameters:
//'name' - linetype name,
//'desc' - descriptive text for linetype,
//'length' - total pattern length
//and array 'dash' - dash length 1, dash length 2 and so on.
//Array 'dash' can take the following values:
//Positive values - line length
//negative values - empty space length
//zero - dot.

//Examples:
//Continuous line
$continuous = new Ltype(['name' => 'Continuous', 'desc' => 'Solid line']);
$draw->addLtype($continuous);

//Dashed line
$dashed = new Ltype(['name' => 'Dashed', 'desc' => 'Dashed line', 'length' => 15, 'dash' => [12, '-3']]);
$draw->addLtype($dashed);

//Long-dash dot line
$long = new Ltype(['name' => 'LongDashDot', 'desc' => 'LongDashDot line', 'length' => 30, 'dash' => [24, '-3', 0, '-3']]);
$draw->addLtype($long);

//Using LTYPE
$draw->insertEntity(new Circle(['point' => [0, 50], 'r' => 20, 'ltype' => 'Dashed']));
$draw->insertEntity(new Line(['points' => [[0, 0], [80, 0]], 'ltype' => 'LongDashDot']));

//Finally save the drawing. Add filename without extension as parameter
$draw->save('ltype');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('ltype', 'Windows-1251');
