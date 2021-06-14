<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Model\Attribute\Frontend;

use Magento\Eav\Model\Entity\Attribute\Frontend\AbstractFrontend;
use Magento\Framework\DataObject;

/**
 * Frontend model class for vendor attribute
 */
class Vendor extends AbstractFrontend
{
    /**
     * Returns list of vendor labels assigned to product
     * @param DataObject $object
     * @return string
     */
    public function getValue(DataObject $object): string
    {
        $optionsList = $object->getData($this->getAttribute()->getAttributeCode());
        if(!empty($optionsList)) {
            $optionsListArray = explode(',', $object->getData($this->getAttribute()->getAttributeCode()));
            $labels = [];
            foreach ($optionsListArray as $option) {
                $labels[] = $this->getOption($option);
            }
            return implode(', ', $labels);
        } else {
            return '';
        }
    }
}
