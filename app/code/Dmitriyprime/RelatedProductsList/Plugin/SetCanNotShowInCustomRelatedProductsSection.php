<?php

declare(strict_types=1);

namespace Dmitriyprime\RelatedProductsList\Plugin;

use Magento\Catalog\Block\Product\ProductList\Related;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

/**
 * Sets canNotShow field in items on custom related products section on PDP
 */
class SetCanNotShowInCustomRelatedProductsSection
{
    /**
     * Sets canNotShow field in items on custom related products section on PDP
     *
     * @param Related $subject
     * @param Collection $result
     * @return Collection
     */
    public function afterGetItems(
        Related $subject,
        Collection $result
    ) {
        if ($subject->getType() === 'related-custom') {
            foreach ($result as $product) {
                if (!in_array($subject->getProduct()->getId(), $product->getRelatedProductIds())) {
                    $product->setCanNotShow(true);
                }
            }
        }

        return $result;
    }
}
