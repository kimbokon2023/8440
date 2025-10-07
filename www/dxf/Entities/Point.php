<?php
namespace Entities;

class Point extends Entity
{
    protected $data = [
        'point' => [0, 0],
    ];

    public function __toString()
    {
        $str = '0' . PHP_EOL . 'POINT' . PHP_EOL . '8' . PHP_EOL . '%s' . PHP_EOL .
        $this->getOptionalProperties() .
        '10' . PHP_EOL . '%.2f' . PHP_EOL . '20' . PHP_EOL . '%.2f' . PHP_EOL;
        return sprintf(
            $str,
            $this->common['layer'],
            $this->data['point'][0],
            $this->data['point'][1],
        );
    }
}
