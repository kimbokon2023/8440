<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Text;

//First create a new drawing
$draw = new Draw;

//Then insert a Text into the drawing
$draw->insertEntity(new Text(['point' => [0, 25], 'txt' => 'Basic text']));

//Where:
//point[x,y] - insertion point
//txt - text value

//Using text height (default value is 3)
$draw->insertEntity(new Text(['point' => [0, 10], 'txt' => 'Text hight 10', 'height' => 10]));

//Using text width (default value is 1)
$draw->insertEntity(new Text(['point' => [0, 5], 'txt' => 'Text width 1.5', 'width' => 1.5]));

//Using oblique angle (default value is 0)
$draw->insertEntity(new Text(['point' => [0, 0], 'txt' => 'Oblique angle 30', 'angle' => 30]));

//Using rotation
$draw->insertEntity(new Text(['point' => [0, '-10'], 'txt' => 'Rotation 120', 'rotation' => 120]));

//Using mirror
//Text is backward (mirrored in X)
$draw->insertEntity(new Text(['point' => [20, '-20'], 'txt' => 'mirrored in X', 'mirror' => 2]));

//Text is upside down (mirrored in Y)
$draw->insertEntity(new Text(['point' => [0, '-25'], 'txt' => 'mirrored in Y', 'mirror' => 4]));

//Alignment
//Array 'justify' is used to align text. The first element is horizontal alignment, the second vertical alignment

//Horizontal alignment (default value is Left)
//right alignment
$draw->insertEntity(new Text(['point' => [0, '-35'], 'txt' => 'right alignment', 'justify' => [2]]));

//Vertical alignment (default value is Baseline)
//middle alignment
$draw->insertEntity(new Text(['point' => [0, '-45'], 'txt' => 'middle alignment', 'justify' => [1, 1]]));

//See Official DXF Reference for all possible combinations.

//You can set the parameter 'style' if you have previously created it. See related example.

//If you use negative values ​​enclose them in single quotes. For example '-10'
//You can use common properties like 'layer', 'ltype' and 'color'. See related examples.

//Finally save the drawing. Add filename without extension as parameter
$draw->save('text');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('text', 'Windows-1251');
