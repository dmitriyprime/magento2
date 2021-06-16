<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Generate products resource model.
 */
class GenerationItem extends AbstractDb
{
    const TABLE_NAME_PRODUCTS_GENERATION = 'kogut_products_generation';
    const ID_FIELD_NAME_FOR_PRODUCTS_GENERATION_TABLE = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(
            self::TABLE_NAME_PRODUCTS_GENERATION,
            self::ID_FIELD_NAME_FOR_PRODUCTS_GENERATION_TABLE
        );
    }
}
