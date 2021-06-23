<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Kogut\ProductsGeneration\Api\GenerateProductsInterface;
use Kogut\ProductsGeneration\Model\Config\Settings;

/**
 * Generates products via Api
 */
class GenerateProductsViaApi implements GenerateProductsInterface
{
    /**
     * @var Settings
     */
    private $config;

    /**
     * @var CreateProduct
     */
    private $createProductService;

    /**
     * @var CreateCategory
     */
    private $createCategoryService;

    /**
     * @var SearchCategoryByName
     */
    private $searchCategoryByNameService;

    /**
     * @var AddItemForProductGenerationByCron
     */
    private $addItemForProductGenerationByCronService;

    /**
     * @param Settings $config
     * @param CreateProduct $createProductService
     * @param CreateCategory $createCategoryService
     * @param SearchCategoryByName $searchCategoryByNameService
     * @param AddItemForProductGenerationByCron $addItemForProductGenerationByCronService
     */
    public function __construct(
        Settings $config,
        CreateProduct $createProductService,
        CreateCategory $createCategoryService,
        SearchCategoryByName $searchCategoryByNameService,
        AddItemForProductGenerationByCron $addItemForProductGenerationByCronService
    ) {
        $this->config = $config;
        $this->createProductService = $createProductService;
        $this->createCategoryService = $createCategoryService;
        $this->searchCategoryByNameService = $searchCategoryByNameService;
        $this->addItemForProductGenerationByCronService = $addItemForProductGenerationByCronService;
    }

    /**
     * Generates products via API
     *
     * @param ?string $catName
     * @param ?int $qty
     * @return array
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function generate(?string $catName = null, ?int $qty = null): string
    {
        $categoryNameConfig = $this->config->getCategoryName();
        $productsQtyToGenerateConfig = $this->config->getProductsQtyToGenerate();

        $qty = $qty ?? $productsQtyToGenerateConfig;
        $categoryName = $catName ?? $categoryNameConfig;

        $categories = $this->searchCategoryByNameService->search($categoryName);

        if (!empty($categories)) {
            $categoryId = (int) $categories[0]->getId();
        } else {
            $createdCategory = $this->createCategoryService->createCategory($categoryName);
            $categoryId = (int) $createdCategory->getId();
        }

        try {
            if ($qty <= 100) {
                for ($i = 0; $i < $qty; $i++) {
                    $this->createProductService->createSimpleProduct($categoryId);
                }

                return json_encode(["message" => "$qty products were created in $categoryName category"]);

            } else {
                $this->addItemForProductGenerationByCronService->addItemToSchedule($categoryId, $qty);

                return json_encode(["message" => "$qty products were scheduled to generate by cron"]);
            }
        } catch (\Exception $exception) {
            return json_encode(["message" => $exception->getMessage()]);
        }
    }
}
