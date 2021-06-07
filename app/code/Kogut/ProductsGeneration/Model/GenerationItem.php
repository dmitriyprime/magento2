<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model;

use Magento\Framework\Model\AbstractModel;

class GenerationItem extends AbstractModel
{
    /**
     * Define resource model class
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\GenerationItem::class);
    }
}
