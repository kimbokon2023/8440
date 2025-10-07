<?php
namespace Face;

class Layer extends Face
{
    protected $data = [
        'color' => 7
    ];
    protected $ltype = 'CONTINUOUS';

    public function __construct(array $properties, Ltype $ltype = null)
    {
        parent::__construct($properties);
        if ($ltype) {
            $this->ltype = $ltype->getName();
        }
    }

    public function __toString()
    {
        $str = '0' . PHP_EOL . 'LAYER' . PHP_EOL . '2' . PHP_EOL . '%s' . PHP_EOL .
                '70' . PHP_EOL . '%d' . PHP_EOL . '62' . PHP_EOL . '%d' . PHP_EOL .
                '6' . PHP_EOL . '%s' . PHP_EOL;

        return sprintf(
            $str,
            $this->common['name'],
            $this->common['flag'],
            $this->data['color'],
            $this->ltype
        );
    }
}
