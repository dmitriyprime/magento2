<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Setup\Patch\Data;

use Dmitriyprime\Vendor\Model\Attribute\Source\Vendor as VendorSource;
use Dmitriyprime\Vendor\Model\Attribute\Backend\Vendor as VendorBackend;
use Dmitriyprime\Vendor\Model\Attribute\Frontend\Vendor as VendorFrontend;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Adds vendor attribute to all attribute sets
 */
class AddProductAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        /** @var CategorySetup $categorySetup */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);

        $categorySetup->addAttribute(Product::ENTITY, 'vendor', [
            'type' => 'varchar',
            'label' => 'Vendor',
            'input' => 'multiselect',
            'group' => 'Product Details',
            'required' => false,
            'user_defined' => true,
            'searchable' => true,
            'filterable' => true,
            'comparable' => true,
            'source' => VendorSource::class,
            'backend' => VendorBackend::class,
            'frontend' => VendorFrontend::class,
            'sort_order' => 50,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible_in_advanced_search' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_filterable_in_grid' => true,
            'visible' => true,
            'is_html_allowed_on_front' => true,
            'visible_on_front' => true
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
