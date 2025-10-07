<?php
namespace Base;

use Entities\Insert;
use Face\Layer;
use Face\Ltype;
use Face\Style;

class Draw
{
    use Insertion;

    protected $header;
    protected $ltype = '';
    protected $countLtype = 0;
    protected $layer = '';
    protected $countLayer = 0;
    protected $style;
    protected $countStyle = 0;
    protected $block = '';
    protected $entities = '';

    protected $headTable = '0' . PHP_EOL . 'TABLE' . PHP_EOL . '2' . PHP_EOL;
    protected $endTable = '0' . PHP_EOL . 'ENDTAB' . PHP_EOL;
    protected $headSection = '0' . PHP_EOL . 'SECTION' . PHP_EOL . '2' . PHP_EOL;
    protected $endSection = '0' . PHP_EOL . 'ENDSEC' . PHP_EOL;

    public function __construct()
    {
        $this->header = include 'Header.php';
    }

    public function addLtype(Ltype $ltype)
    {
        $this->ltype .= sprintf('%s', $ltype);
        $this->countLtype++;
    }

    public function addLayer(Layer $layer)
    {
        $this->layer .= sprintf('%s', $layer);
        $this->countLayer++;
    }

    public function addStyle(Style $style)
    {
        $this->style .= sprintf('%s', $style);
        $this->countStyle++;
    }

    public function addBlock(Block $block)
    {
        $this->block .= sprintf('%s', $block);
    }

    public function insertBlock(array $properties, Block $block)
    {
        $insert = new Insert($properties, $block);
        $this->entities .= sprintf('%s', $insert);
    }

    public function save($name, $charset = 'UTF-8')
    {
        $handle = fopen($name . '.dxf', 'w');

        if ($charset === 'UTF-8') {
            fwrite($handle, sprintf('%s', $this));
        } else {
            fwrite($handle, iconv('UTF-8', $charset, sprintf('%s', $this)));
        }
        fclose($handle);
    }

    public function __toString()
    {
        $str =
                $this->headSection .
                'HEADER' . PHP_EOL . $this->header .
                $this->endSection .
                $this->headSection .
                'TABLES' . PHP_EOL .
                $this->headTable .
                'LTYPE' . PHP_EOL . '70' . PHP_EOL .
                $this->countLtype . PHP_EOL .
                $this->ltype .
                $this->endTable .
                $this->headTable .
                'LAYER' . PHP_EOL . '70' . PHP_EOL .
                $this->countLayer . PHP_EOL .
                $this->layer .
                $this->endTable .
                $this->headTable .
                'STYLE' . PHP_EOL . '70' . PHP_EOL .
                $this->countStyle . PHP_EOL .
                $this->style .
                $this->endTable .
                $this->endSection .
                $this->headSection .
                'BLOCKS' . PHP_EOL . $this->block .
                $this->endSection .
                $this->headSection .
                'ENTITIES' . PHP_EOL . $this->entities .
                $this->endSection .
                '0' . PHP_EOL . 'EOF' . PHP_EOL;

        return $str;
    }
}
