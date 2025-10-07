<?php
namespace Entities;

class Polyline extends Entity
{
    protected $data = [
        'flag' => null,
        'width' => [],
        'vertex' => [
            [
                'point' => [0, 0],
                'width' => [],
                'bulge' => null
            ]
        ]
    ];

    public function __toString()
    {
        $str = '0' . PHP_EOL . 'POLYLINE' . PHP_EOL . '66' . PHP_EOL . '1' . PHP_EOL .
        '8' . PHP_EOL . '%s' . PHP_EOL . $this->getOptionalProperties() .
        '10' . PHP_EOL . '0' . PHP_EOL . '20' . PHP_EOL . '0' . PHP_EOL;

        $result = sprintf(
            $str,
            $this->common['layer']
        );

        if (isset($this->data['flag'])) {
            $result .= sprintf('70' . PHP_EOL . '%d' . PHP_EOL, $this->data['flag']);
        }

        if (isset($this->data['width'][0])) {
            $result .= sprintf(
                '40' . PHP_EOL . '%.2f' . PHP_EOL . '41' . PHP_EOL . '%.2f' . PHP_EOL,
                $this->data['width'][0],
                $this->data['width'][1]
            );
        }

        for ($i = 0; $i < count($this->data['vertex']); $i++) {
            $result .= '0' . PHP_EOL . 'VERTEX' . PHP_EOL .
            sprintf('8' . PHP_EOL . '%s' . PHP_EOL, $this->common['layer']);

            if (isset($this->data['vertex'][$i]['width'][0])) {
                $result .= sprintf(
                    '40' . PHP_EOL . '%.2f' . PHP_EOL . '41' . PHP_EOL . '%.2f' . PHP_EOL,
                    $this->data['vertex'][$i]['width'][0],
                    $this->data['vertex'][$i]['width'][1]
                );
            }
            if (isset($this->data['vertex'][$i]['bulge'])) {
                $result .= sprintf(
                    '42' . PHP_EOL . '%.2f' . PHP_EOL,
                    $this->data['vertex'][$i]['bulge'],
                );
            }
            $result .= sprintf(
                '10' . PHP_EOL . '%.2f' . PHP_EOL . '20' . PHP_EOL . '%.2f' . PHP_EOL,
                $this->data['vertex'][$i]['point'][0],
                $this->data['vertex'][$i]['point'][1],
            );
        }
        $result .= '0' . PHP_EOL . 'SEQEND' . PHP_EOL;
        return $result;
    }
}
