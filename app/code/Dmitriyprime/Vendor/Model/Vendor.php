<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Vendor model
 */
class Vendor extends AbstractModel
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel\Vendor::class);
    }
}
