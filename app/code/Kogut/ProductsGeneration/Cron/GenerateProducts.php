<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Cron;

use Kogut\ProductsGeneration\Model\ResourceModel\GenerationItem;
use Kogut\ProductsGeneration\Model\ResourceModel\GenerationItem\CollectionFactory;
use Kogut\ProductsGeneration\Model\Service\CreateProduct;

class GenerateProducts
{
    /**
     * @var CollectionFactory
     */
    private $itemsCollectionFactory;

    /**
     * @var CreateProduct
     */
    private $createProductService;

    /**
     * @var GenerationItem
     */
    private $generationItemResource;


    /**
     * @param CollectionFactory $itemsCollectionFactory
     * @param CreateProduct $createProductService
     * @param GenerationItem $generationItemResource
     */
    public function __construct(
        CollectionFactory $itemsCollectionFactory,
        CreateProduct $createProductService,
        GenerationItem $generationItemResource
    ) {
        $this->itemsCollectionFactory = $itemsCollectionFactory;
        $this->createProductService = $createProductService;
        $this->generationItemResource = $generationItemResource;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $itemsCollection = $this->itemsCollectionFactory->create();

        foreach ($itemsCollection as $item) {
            $itemsCount = $item->getData('qty');
            $categoryId = (int) $item->getData('category_id');

            for($i = 0; $i < $itemsCount; $i++) {
                $this->createProductService->createSimpleProduct($categoryId);
            }

            $this->generationItemResource->delete($item);
        }
    }
}
