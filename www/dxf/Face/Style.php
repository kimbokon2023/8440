<?php
namespace Face;

class Style extends Face
{
    protected $data = [
        'height' => 3,
        'width' => 1,
        'angle' => 0,
        'mirror' => 0,
        'last' => 1,
        'font' => 'SIMPLEX.SHX',
        'bigfont' => null
    ];

    public function getName()
    {
        return $this->common['name'];
    }

    public function getData()
    {
        return $this->data;
    }

    public function __toString()
    {
        $str = '0' . PHP_EOL . 'STYLE' . PHP_EOL . '2' . PHP_EOL . '%s' . PHP_EOL .
                '70' . PHP_EOL . '%d' . PHP_EOL . '40' . PHP_EOL . '%.2f' . PHP_EOL .
                '41' . PHP_EOL . '%.2f' . PHP_EOL . '50' . PHP_EOL . '%.2f' . PHP_EOL .
                '71' . PHP_EOL . '%d' . PHP_EOL . '42' . PHP_EOL . '%.2f' . PHP_EOL .
                '3' . PHP_EOL . '%s' . PHP_EOL . '4' . PHP_EOL . '%s' . PHP_EOL;

        return sprintf(
            $str,
            $this->common['name'],
            $this->common['flag'],
            $this->data['height'],
            $this->data['width'],
            $this->data['angle'],
            $this->data['mirror'],
            $this->data['last'],
            $this->data['font'],
            $this->data['bigfont']
        );
    }
}
