<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Line;
use Face\Layer;
use Face\Ltype;

//First create a new drawing
$draw = new Draw;

//Layer has the parameters:
//'name' - layer name,
//'color' - color number, negative if layer is off, default value is 'white' (7)

$greenLayer = new Layer(['color' => 3, 'name' => 'greenLayer']);
$draw->addLayer($greenLayer);
$draw->insertEntity(new Line(['points' => [[0, 50], [80, 50]], 'layer' => 'greenLayer']));

//Default LTYPE for a layer is CONTINUOUS. To change the type first create a new one
$dashed = new Ltype(['name' => 'Dashed', 'desc' => 'Dashed line', 'length' => 15, 'dash' => [12, '-3']]);
$draw->addLtype($dashed);
//then add it to LAYER
$blueDashedLayer = new Layer(['color' => 4, 'name' => 'blueDashed'], $dashed);
$draw->addLayer($blueDashedLayer);
$draw->insertEntity(new Line(['points' => [[0, 30], [80, 30]], 'layer' => 'blueDashed']));

//Using FLAG
//layer is frozen
$frozenLayer = new Layer(['color' => 1, 'name' => 'frozen', 'flag' => 1]);
$draw->addLayer($frozenLayer);
$draw->insertEntity(new Line(['points' => [[0, 10], [80, 10]], 'layer' => 'frozen']));

//layer is locked
$lockedLayer = new Layer(['color' => 2, 'name' => 'locked',  'flag' => 4]);
$draw->addLayer($lockedLayer);
$draw->insertEntity(new Line(['points' => [[0, '-10'], [80, '-10']], 'layer' => 'locked']));

//Finally save the drawing. Add filename without extension as parameter
$draw->save('layer');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('layer', 'Windows-1251');
