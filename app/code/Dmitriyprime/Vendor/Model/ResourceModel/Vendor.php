<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Vendor entity resource model.
 */
class Vendor extends AbstractDb
{
    const TABLE_NAME_VENDOR_ENTITY = 'dmitriyprime_vendors';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_VENDOR_ENTITY, 'entity_id');
    }

    /**
     * Returns vendor data containing entity_id and name fields
     *
     * @return array
     */
    public function getVendorsData(): array
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getTable(self::TABLE_NAME_VENDOR_ENTITY), ['entity_id', 'name']);

        $result = $adapter->fetchPairs($select);

        return $result;
    }
}
