<?php
namespace Entities;

use Face\Style;

class Text extends Entity
{
    protected $data = [
        'point' => [0, 0],
        'height' => 3,
        'txt' => 'Test',
        'rotation' => null,
        'width' => null,
        'angle' => null,
        'style' => null,
        'mirror' => null,
        'justify' => []
    ];

    public function __construct(array $properties, Style $style = null)
    {
        parent::construct($properties);
        if ($style) {
            foreach ($style->getData() as $key => $value) {
                if (array_key_exists($key, $this->data)) {
                    $this->data[$key] = $value;
                }
            }
            $this->data['style'] = $style->getName();
        }
    }

    public function __toString()
    {
        $str = '0' . PHP_EOL . 'TEXT' . PHP_EOL . '8' . PHP_EOL . '%s' . PHP_EOL .
        $this->getOptionalProperties() .
        '10' . PHP_EOL . '%.2f' . PHP_EOL . '20' . PHP_EOL . '%.2f' . PHP_EOL .
        '40' . PHP_EOL . '%.2f' . PHP_EOL . '1' . PHP_EOL . '%s' . PHP_EOL;

        $result = sprintf(
            $str,
            $this->common['layer'],
            $this->data['point'][0],
            $this->data['point'][1],
            $this->data['height'],
            $this->data['txt']
        );

        if (isset($this->data['rotation'])) {
            $result .= sprintf('50' . PHP_EOL . '%.2f' . PHP_EOL, $this->data['rotation']);
        }
        if (isset($this->data['width'])) {
            $result .= sprintf('41' . PHP_EOL . '%.2f' . PHP_EOL, $this->data['width']);
        }
        if (isset($this->data['angle'])) {
            $result .= sprintf('51' . PHP_EOL . '%.2f' . PHP_EOL, $this->data['angle']);
        }
        if (isset($this->data['style'])) {
            $result .= sprintf('7' . PHP_EOL . '%s' . PHP_EOL, $this->data['style']);
        }
        if (isset($this->data['mirror'])) {
            $result .= sprintf('71' . PHP_EOL . '%d' . PHP_EOL, $this->data['mirror']);
        }
        if (isset($this->data['justify'][0])) {
            $result .= sprintf(
                '72' . PHP_EOL . '%d' . PHP_EOL .
                '11' . PHP_EOL . '%.2f' . PHP_EOL .
                '21' . PHP_EOL . '%.2f' . PHP_EOL,
                $this->data['justify'][0],
                $this->data['point'][0],
                $this->data['point'][1]
            );
        }
        if (isset($this->data['justify'][1])) {
            $result .= sprintf('73' . PHP_EOL . '%d' . PHP_EOL, $this->data['justify'][1]);
        }
        return $result;
    }
}
