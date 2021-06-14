<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Model\Attribute\Source;

use Dmitriyprime\Vendor\Model\ResourceModel\Vendor as VendorResource;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Source model class for vendor attribute
 */
class Vendor extends AbstractSource implements OptionSourceInterface
{
    /**
     * @inheritdoc
     */
    protected $_options;

    /**
     * @var VendorResource
     */
    private $vendorResource;

    /**
     * @param VendorResource $vendorResource
     */
    public function __construct(
        VendorResource $vendorResource
    ) {
        $this->vendorResource = $vendorResource;
    }

    /**
     * Returns all options of vendor attribute entity
     * @return array|array[]
     */
    public function getAllOptions()
    {
        $vendorList = $this->vendorResource->getVendorsData();

        if ($this->_options === null) {
            $options = [];
            foreach ($vendorList as $key => $vendor) {
                $options[] = ['value' => $key, 'label' => $vendor];
            }
            $this->_options = $options;
        }
        return $this->_options;
    }
}
