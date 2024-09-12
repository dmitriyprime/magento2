<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Vendor entity resource model.
 */
class VendorIds extends AbstractDb
{
    const TABLE_NAME_VENDORS_PRODUCTS_LINK = 'dmitriyprime_vendors_link';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_VENDORS_PRODUCTS_LINK, 'vendor_id');
    }

    /**
     * Returns vendor ids
     *
     * @param int $productId
     * @return array
     */
    public function getProductVendorIds(int $productId): array
    {
        $adapter = $this->getConnection();
        $select = $adapter->select()
            ->from($this->getTable(self::TABLE_NAME_VENDORS_PRODUCTS_LINK), ['vendor_id'])
            ->where('product_id = ?', $productId);

        return $adapter->fetchCol($select);
    }

    /**
     * Save product vendor Ids into DB
     *
     * @param int $productId
     * @param array $vendorIds
     */
    public function saveProductVendorIds(int $productId, array $vendorIds): void
    {
        $adapter = $this->getConnection();
        $adapter->delete($this->getTable(self::TABLE_NAME_VENDORS_PRODUCTS_LINK), ['product_id = ?'=> $productId]);
        $data = [];
        foreach ($vendorIds as $vendorId) {
            $data[] = ['product_id' => $productId, 'vendor_id' => (int) $vendorId];
        }
        $adapter->insertMultiple($this->getTable(self::TABLE_NAME_VENDORS_PRODUCTS_LINK), $data);
    }
}
