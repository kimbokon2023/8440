<?php
namespace Entities;

use Base\Block;

class Insert extends Entity
{
    protected $data = [
        'point' => [0, 0],
        'scale' => [1, 1],
        'rotation' => 0,
        'column' => [1, 0],
        'row' => [1, 0]
    ];
    protected $block;
    protected $layer;

    public function __construct(array $properties, Block $block)
    {
        parent::__construct($properties);
        $this->block = $block->getName();
        $this->layer = $block->getLayer();
    }

    public function __toString()
    {
        $str =
                '0' . PHP_EOL . 'INSERT' . PHP_EOL . '8' . PHP_EOL . '%s' . PHP_EOL .
                '2' . PHP_EOL . '%s' . PHP_EOL . 
                $this->getOptionalProperties() .
                '10' . PHP_EOL . '%.2f' . PHP_EOL .'20' . PHP_EOL . '%.2f' . PHP_EOL . 
                '41' . PHP_EOL . '%.2f' . PHP_EOL .'42' . PHP_EOL . '%.2f' . PHP_EOL . 
                '50' . PHP_EOL . '%.2f' . PHP_EOL .'70' . PHP_EOL . '%.2f' . PHP_EOL .
                '44' . PHP_EOL . '%.2f' . PHP_EOL .'71' . PHP_EOL . '%.2f' . PHP_EOL . 
                '45' . PHP_EOL . '%.2f' . PHP_EOL;

        return sprintf(
            $str,
            $this->layer,
            $this->block,
            $this->data['point'][0],
            $this->data['point'][1],
            $this->data['scale'][0],
            $this->data['scale'][1],
            $this->data['rotation'],
            $this->data['column'][0],
            $this->data['column'][1],
            $this->data['row'][0],
            $this->data['row'][1]
        );
    }
}
