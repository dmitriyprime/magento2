<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Plugin\Api;

use Dmitriyprime\Vendor\Model\ResourceModel\VendorIds;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Product Repository Plugin
 */
class ProductRepository
{
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
     * Loads vendor Ids after getById() method call
     * @param ProductRepositoryInterface $productRepository
     * @param ProductInterface $product
     * @param $productId
     * @param false $editMode
     * @param null $storeId
     * @param false $forceReload
     * @return ProductInterface
     */
    public function afterGetById(
        ProductRepositoryInterface $productRepository,
        ProductInterface $product,
        $productId,
        $editMode = false,
        $storeId = null,
        $forceReload = false
    ): ProductInterface
    {
        $this->loadVendorIds($product);
        return $product;
    }

    /**
     * Loads vendor Ids after get() method call
     * @param ProductRepositoryInterface $productRepository
     * @param ProductInterface $product
     * @param $sku
     * @param false $editMode
     * @param null $storeId
     * @param false $forceReload
     * @return ProductInterface
     */
    public function afterGet(
        ProductRepositoryInterface $productRepository,
        ProductInterface $product,
        $sku,
        $editMode = false,
        $storeId = null,
        $forceReload = false
    ): ProductInterface
    {
        $this->loadVendorIds($product);
        return $product;
    }

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param ProductInterface $product
     * @param false $saveOptions
     */
    public function beforeSave(
        ProductRepositoryInterface $productRepository,
        ProductInterface $product,
        $saveOptions = false
    ): void {
        $vendorIds = $product->getExtensionAttributes()->getVendorIds();
        $this->vendorIdsResource->saveProductVendorIds($product->getId(), $vendorIds);
    }

    /**
     * Sets vendor ids to extension attribute vendorId field
     * @param ProductInterface $product
     */
    private function loadVendorIds(ProductInterface $product): void
    {
        $vendorIds = $this->vendorIdsResource->getProductVendorIds((int) $product->getId());
        $product->getExtensionAttributes()->setVendorIds($vendorIds);
    }
}
