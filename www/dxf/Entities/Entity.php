<?php
namespace Entities;

use Base\Constructor;

abstract class Entity
{
    use Constructor;

    protected $common = [
        'layer' => 0,
        'ltype' => null,
        'color' => null,
    ];
    protected $data;

    public function __construct(array $properties = [])
    {
        $this->construct($properties);
    }

    public function getOptionalProperties()
    {
        $optional = '';
        if (isset($this->common['ltype'])) {
            $optional .= '6' . PHP_EOL . $this->common['ltype'] . PHP_EOL;
        }
        if (isset($this->common['color'])) {
            $optional .= '62' . PHP_EOL . sprintf('%d', $this->common['color']) . PHP_EOL;
        }
        return $optional;
    }

    abstract public function __toString();
}
