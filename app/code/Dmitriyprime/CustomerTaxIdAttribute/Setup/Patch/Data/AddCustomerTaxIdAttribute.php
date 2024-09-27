<?php

declare(strict_types=1);

namespace Dmitriyprime\CustomerTaxIdAttribute\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Adds 'customer tax id' attribute to customer entity
 */
class AddCustomerTaxIdAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * PatchInitial constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerSetup->addAttribute(Customer::ENTITY, 'customer_tax_id', [
            'type' => 'static',
            'label' => 'Customer tax id',
            'input' => 'text',
            'system' => 0,
            'required' => true,
            'visible' => true,
            'user_defined' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => true,
            'is_searchable_in_grid' => true,
            'is_filterable_in_grid' => true,
            'group' => 'Account Information'
        ]);

        $attributeId = $customerSetup->getAttributeId(Customer::ENTITY, 'customer_tax_id');
        $data = [
            ['form_code' => 'adminhtml_customer', 'attribute_id' => $attributeId],
            ['form_code' => 'customer_account_create', 'attribute_id' => $attributeId],
            ['form_code' => 'customer_account_edit', 'attribute_id' => $attributeId],
            ['form_code' => 'adminhtml_checkout', 'attribute_id' => $attributeId],
        ];
        $adapter = $this->moduleDataSetup->getConnection();
        $adapter->insertMultiple($adapter->getTableName('customer_form_attribute'), $data);
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
