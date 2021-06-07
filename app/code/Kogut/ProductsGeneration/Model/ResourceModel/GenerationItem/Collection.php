<?php

namespace Kogut\ProductsGeneration\Model\ResourceModel\GenerationItem;

use Kogut\ProductsGeneration\Model\GenerationItem;
use Kogut\ProductsGeneration\Model\ResourceModel\GenerationItem as GenerationItemResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Generation products scheduled items collection.
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(GenerationItem::class, GenerationItemResource::class);
    }
}
