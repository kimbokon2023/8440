<?php
namespace Entities;

class Arc extends Entity
{
    protected $data = [
        'point' => [0, 0],
        'r' => 1,
        'angle' => [0, 180]
    ];

    public function __toString()
    {
        $str = '0' . PHP_EOL . 'ARC' . PHP_EOL . '8' . PHP_EOL . '%s' . PHP_EOL .
        $this->getOptionalProperties() .
        '10' . PHP_EOL . '%.2f' . PHP_EOL . '20' . PHP_EOL . '%.2f' . PHP_EOL .
        '40' . PHP_EOL . '%.2f' . PHP_EOL . '50' . PHP_EOL . '%.2f' . PHP_EOL .
        '51' . PHP_EOL . '%.2f' . PHP_EOL;

        return sprintf(
            $str,
            $this->common['layer'],
            $this->data['point'][0],
            $this->data['point'][1],
            $this->data['r'],
            $this->data['angle'][0],
            $this->data['angle'][1]
        );
    }
}
