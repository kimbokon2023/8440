<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Block;
use Base\Draw;
use Entities\Circle;
use Entities\Line;
use Entities\Text;
use Face\Layer;
use Face\Ltype;

//First create a new drawing
$draw = new Draw;

//BLOCK has properties:
//'name' - block name,
//'point[x,y]' - block base point

//Create a new block
$block1 = new Block(['name' => 'block1', 'point' => [0, 0]]);
//Then you can add entities to the block
$block1->insertEntity(new Text(['point' => [0, 5], 'txt' => 'Block text', 'height' => 5]));
$block1->insertEntity(new Line(['points' => [[0, 4], [35, 4]]]));
//Then add block
$draw->addBlock($block1);
//Then insert the block
$draw->insertBlock(['point' => [0, 100]], $block1);

//When inserting a block, you can set the following options:
//'rotation' - rotation angle (default is 0)
$draw->insertBlock(['point' => ['-50', 100], 'rotation' => 45], $block1);

//scale[x,y] - [X-scale factor, Y-scale factor] (default value is [1,1])
$draw->insertBlock(['point' => [0, 80], 'scale' => [1, 2]], $block1);

//column[1,0] - [column count, column spacing] (default value is [1,0])
$draw->insertBlock(['point' => [0, 60], 'column' => [3, 50]], $block1);

//row[1,0] - [row count, row spacing] (default value is [1,0])
$draw->insertBlock(['point' => [0, 40], 'row' => [2, 7]], $block1);
//You can combine this properties

//You can use common properties like COLOR, LTYPE and LAYER
//Using COLOR
$block2 = new Block(['name' => 'block2', 'point' => [0, 0]]);
//Default color value for ENTITY is BYLAYER. If you want to get BYBLOCK value,
//set color value to 0.
$block2->insertEntity(new Line(['points' => [[0, 0], [5, 10]], 'color' => 0]));
$block2->insertEntity(new Line(['points' => [[0, 0], [10, 0]]]));
$draw->addBlock($block2);
//set the color value when inserting the block
$draw->insertBlock(['point' => [0, 20], 'color' => 5], $block2);

//Using LTYPE
//create a new ltype
$dashed = new Ltype(['name' => 'Dashed', 'desc' => 'Dashed line', 'length' => 10, 'dash' => [7, '-3']]);
$draw->addLtype($dashed);
//create a new block
$block3 = new Block(['name' => 'block3', 'point' => [0, 0]]);
$block3->insertEntity(new Line(['points' => [[0, 0], [50, 0]]]));
//set special name BYBLOCK for the ltype of the entity
$block3->insertEntity(new Line(['points' => [[0, 10], [50, 10]], 'ltype' => 'BYBLOCK']));
$draw->addBlock($block3);
//set the ltype value when inserting the block
$draw->insertBlock(['point' => [0, 0], 'ltype' => 'Dashed'], $block3);

//Using LAYER
//Create a new layer
$greenLayer = new Layer(['color' => 3, 'name' => 'greenLayer']);
$draw->addLayer($greenLayer);
//when creating a block, specify a layer
$block4 = new Block(['name' => 'block4', 'point' => [0, 0], 'layer' => 'greenLayer']);
$block4->insertEntity(new Line(['points' => [[0, 0], [50, 0]]]));
$block4->insertEntity(new Circle(['point' => [70, 0], 'r' => '10']));
$draw->addBlock($block4);
$draw->insertBlock(['point' => [0, '-10']], $block4);

//Finally save the drawing. Add filename without extension as parameter
$draw->save('block');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('block', 'Windows-1251');
