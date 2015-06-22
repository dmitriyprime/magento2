<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Setup\Model;

use Magento\Framework\Module\FullModuleList;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Setup\UninstallInterface;

/**
 * Class for collecting all Uninstall interfaces in all modules
 */
class UninstallCollector
{
    /**
     * Object manager
     *
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Constructor
     *
     * @param ObjectManagerProvider $objectManagerProvider
     */
    public function __construct(
        ObjectManagerProvider $objectManagerProvider
    ) {
        $this->objectManager = $objectManagerProvider->get();
    }

    /**
     * Collect Uninstall classes from modules
     *
     * @return UninstallInterface[]
     */
    public function collectUninstall()
    {
        $uninstallList = [];
        /** @var \Magento\Setup\Module\DataSetup $setup */
        $setup = $this->objectManager->get('Magento\Setup\Module\DataSetup');
        $result = $setup->getConnection()->select()->from($setup->getTable('setup_module'), ['module']);
        // go through modules
        foreach ($setup->getConnection()->fetchAll($result) as $row) {
            $uninstallClassName = str_replace('_', '\\', $row['module']) . '\Setup\Uninstall';
            if (class_exists($uninstallClassName)) {
                $uninstallClass = $this->objectManager->create($uninstallClassName);
                if (is_subclass_of($uninstallClass, 'Magento\Framework\Setup\UninstallInterface')) {
                    $uninstallList[$row['module']] = $uninstallClass;
                }
            }
        }

        return $uninstallList;
    }
}
