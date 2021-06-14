<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Model\ResourceModel\Vendor;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Dmitriyprime\Vendor\Model\Vendor;
use Dmitriyprime\Vendor\Model\ResourceModel\Vendor as VendorResource;

/**
 * Vendor entity collection
 */
class Collection extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(Vendor::class, VendorResource::class);
    }
}
