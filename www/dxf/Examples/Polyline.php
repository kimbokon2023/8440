<?php
require_once dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'AutoLoader.php';

use Base\Draw;
use Entities\Polyline;

//First create a new drawing
$draw = new Draw;

//Then insert a Polyline into the drawing
$draw->insertEntity(new Polyline(
    [
        'vertex' => [
            ['point' => [0, 0]],
            ['point' => [10, 5]],
            ['point' => [14, 2]]
        ]
    ]
));

//Where:
//vertex =>[point[x,y]] - vertex coordinates

//If you set 'flag' to 1 you will get closed Polyline

$draw->insertEntity(new Polyline(
    ['flag' => 1,
        'vertex' => [
            ['point' => [0, '-10']],
            ['point' => [10, '-5']],
            ['point' => [14, '-8']]
        ]
    ]
));

//Polyline width setting.
//For global settings use width[x,y] - [starting width, ending width]

//Polyline width 2
$draw->insertEntity(new Polyline(
    ['width' => [2, 2],
        'vertex' => [
            ['point' => [0, '-20']],
            ['point' => [10, '-15']],
            ['point' => [14, '-18']]
        ]
    ]
));

//Polyline starting width 3, ending width 1
$draw->insertEntity(new Polyline(
    ['width' => [3, 1],
        'vertex' => [
            ['point' => [0, '-30']],
            ['point' => [5, '-30']],
            ['point' => [10, '-30']],
            ['point' => [15, '-30']]
        ]
    ]
));

//Settings for each segment
$draw->insertEntity(new Polyline(
    [
        'vertex' => [
            ['point' => [0, '-40'], 'width' => [1, 5]],
            ['point' => [5, '-40'], 'width' => [2, 4]],
            ['point' => [10, '-40'], 'width' => [3, 0]],
            ['point' => [15, '-40']]
        ]
    ]
));

//Using bulge
$draw->insertEntity(new Polyline(
    [
        'vertex' => [
            ['point' => [0, '-50'], 'width' => [4, 2]],
            ['point' => [5, '-50'], 'width' => [2, 2], 'bulge' => '-1'],
            ['point' => [10, '-50'], 'width' => [0, 2], 'bulge' => '0.5'],
            ['point' => [15, '-50']]
        ]
    ]
));

// You can draw a circle
$draw->insertEntity(new Polyline(
    ['flag' => 1,
        'vertex' => [
            ['point' => [0, '-60'], 'width' => [2, 2], 'bulge' => '1'],
            ['point' => [5, '-60'], 'width' => [2, 2], 'bulge' => '1'],
        ]
    ]
));

// You can draw an arrow
$draw->insertEntity(new Polyline(
    [
        'vertex' => [
            ['point' => [0, '-70'], 'width' => [2, 2]],
            ['point' => [10, '-70'], 'width' => [4, 0]],
            ['point' => [15, '-70']]
        ]
    ]
));

//If you use negative values ​​enclose them in single quotes. For example '-10'
//You can use common properties like 'layer', 'ltype' and 'color'. See related examples.

//Finally save the drawing. Add filename without extension as parameter
$draw->save('polyline');

//Default charset is 'UTF-8'. If you want to use a different encoding then add
//the second parameter to the method. For example:
//$draw->save('polyline', 'Windows-1251');
