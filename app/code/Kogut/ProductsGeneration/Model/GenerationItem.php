<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Generation item model class
 *
 * @method string getQty()
 * @method \Kogut\ProductsGeneration\Model\GenerationItem setQty(string $value)
 * @method string getCategoryId()
 * @method \Kogut\ProductsGeneration\Model\GenerationItem setCategoryId(string $value)
 */
class GenerationItem extends AbstractModel
{
    /**
     * Defines resource model class
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\GenerationItem::class);
    }
}
