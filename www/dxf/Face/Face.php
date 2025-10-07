<?php
namespace Face;

use Base\Constructor;

abstract class Face
{
    use Constructor;
    protected $common = [
        'name' => 'test',
        'flag' => 0
    ];
    protected $data;

    public function __construct(array $properties)
    {
        $this->construct($properties);
    }

    abstract public function __toString();
}
