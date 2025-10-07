<?php
namespace Base;

use Entities\Entity;

trait Insertion
{
    public function insertEntity(Entity $entity)
    {
        $this->entities .= sprintf('%s', $entity);
    }
}
