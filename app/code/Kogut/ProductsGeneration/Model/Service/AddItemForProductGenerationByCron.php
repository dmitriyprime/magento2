<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Kogut\ProductsGeneration\Model\GenerationItemFactory;
use Kogut\ProductsGeneration\Model\ResourceModel\GenerationItem;

/**
 * Class AddItemForProductGenerationByCron
 */
class AddItemForProductGenerationByCron
{
    /**
     * @var GenerationItemFactory
     */
    private $generationItemFactory;

    /**
     * @var GenerationItem
     */
    private $generationItemResource;

    public function __construct(
        GenerationItemFactory $generationItemFactory,
        GenerationItem $generationItemResource
    ) {
        $this->generationItemFactory = $generationItemFactory;
        $this->generationItemResource = $generationItemResource;
    }

    /**
     * Add item for product generation cron job
     * @param int $categoryId
     * @param int $productsQty
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @return void
     */
    public function addItemToSchedule(int $categoryId, int $productsQty): void
    {
        $model = $this->generationItemFactory->create();
        $model->setData('category_id', $categoryId);
        $model->setData('qty', $productsQty);
        $this->generationItemResource->save($model);
    }
}
