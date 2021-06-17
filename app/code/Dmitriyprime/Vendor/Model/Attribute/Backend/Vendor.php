<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Model\Attribute\Backend;

use Dmitriyprime\Vendor\Model\ResourceModel\VendorIds;
use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;

/**
 * Backend model class for vendor attribute
 */
class Vendor extends ArrayBackend
{
    const ATTRIBUTE_NAME_VENDOR = 'vendor';

    /**
     * @var VendorIds
     */
    private $vendorIdsResource;

    /**
     * @param VendorIds $vendorIdsResource
     */
    public function __construct(
        VendorIds $vendorIdsResource
    ) {
        $this->vendorIdsResource = $vendorIdsResource;
    }

    /**
     * Saves product vendor Ids into DB
     *
     * @param \Magento\Framework\DataObject $object
     * @return Vendor
     */
    public function afterSave($object)
    {
        $vendorIds = $object->getData(self::ATTRIBUTE_NAME_VENDOR);
        if (!empty($vendorIds)) {
            $vendorIds = explode(',', $vendorIds);
        }
        $productId = (int) $object->getId();
        if (!empty($vendorIds)) {
            $this->vendorIdsResource->saveProductVendorIds($productId, $vendorIds);
        }

        return parent::afterSave($object);
    }
}
