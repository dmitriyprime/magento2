<?php

declare(strict_types=1);

namespace Dmitriyprime\RelatedProductsList\ViewModel;

use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Custom Related products section View Model
 */
class RelatedProductsList extends View implements ArgumentInterface
{
    /**
     * @var Collection
     */
    private $_itemCollection;

    /**
     * Gets related products collection items
     *
     * @return Collection
     */
    public function getItems(): Collection
    {
        $currentProduct = $this->getProduct();

        if ($this->_itemCollection === null) {
            $this->_itemCollection = $currentProduct->getRelatedProductCollection()->addAttributeToSelect('*')->
                setPositionOrder()->addStoreFilter();
            $this->_itemCollection->load();

            foreach ($this->_itemCollection as $item) {
                if (!in_array($currentProduct->getId(), $item->getRelatedProductIds())) {
                    $item->setCanNotShow(true);
                }
            }
        }

        return $this->_itemCollection;
    }
}
