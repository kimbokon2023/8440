<?php
namespace Face;

class Ltype extends Face
{
    protected $data = [
        'desc' => null,
        'length' => 0,
        'dash' => []
    ];
    protected $numberDashes = 0;
    protected $dashString = '';

    public function __construct(array $properties)
    {
        parent::__construct($properties);
        $this->countDashes();
    }

    public function countDashes()
    {
        for ($i = 0; $i < count($this->data['dash']); $i++) {
            $this->dashString .= sprintf('49' . PHP_EOL . '%.2f' . PHP_EOL, $this->data['dash'][$i]);
            $this->numberDashes++;
        }
    }

    public function getName()
    {
        return $this->common['name'];
    }

    public function __toString()
    {
        $str = '0' . PHP_EOL . 'LTYPE' . PHP_EOL . '2' . PHP_EOL . '%s' . PHP_EOL .
                '70' . PHP_EOL . '%d' . PHP_EOL . '3' . PHP_EOL . '%s' . PHP_EOL .
                '72' . PHP_EOL . '65' . PHP_EOL . '73' . PHP_EOL . '%d' . PHP_EOL .
                '40' . PHP_EOL . '%.2f' . PHP_EOL . $this->dashString;

        return sprintf(
            $str,
            $this->common['name'],
            $this->common['flag'],
            $this->data['desc'],
            $this->numberDashes,
            $this->data['length']
        );
    }
}
