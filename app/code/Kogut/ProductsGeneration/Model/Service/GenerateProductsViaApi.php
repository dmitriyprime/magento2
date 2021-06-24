<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Kogut\ProductsGeneration\Api\Data\GenerateProductsResultInterface;
use Kogut\ProductsGeneration\Api\GenerateProductsInterface;
use Kogut\ProductsGeneration\Model\Config\Settings;
use Psr\Log\LoggerInterface;

/**
 * Generates products via Api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
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
     * @var GenerateProductsResultFactory
     */
    private $generateProductsResultFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Settings $config
     * @param CreateProduct $createProductService
     * @param CreateCategory $createCategoryService
     * @param SearchCategoryByName $searchCategoryByNameService
     * @param AddItemForProductGenerationByCron $addItemForProductGenerationByCronService
     * @param GenerateProductsResultFactory $generateProductsResultFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        Settings $config,
        CreateProduct $createProductService,
        CreateCategory $createCategoryService,
        SearchCategoryByName $searchCategoryByNameService,
        AddItemForProductGenerationByCron $addItemForProductGenerationByCronService,
        GenerateProductsResultFactory $generateProductsResultFactory,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->createProductService = $createProductService;
        $this->createCategoryService = $createCategoryService;
        $this->searchCategoryByNameService = $searchCategoryByNameService;
        $this->addItemForProductGenerationByCronService = $addItemForProductGenerationByCronService;
        $this->generateProductsResultFactory = $generateProductsResultFactory;
        $this->logger = $logger;
    }

    /**
     * Generates products via API
     *
     * @param ?string $catName
     * @param ?int $qty
     * @return GenerateProductsResultInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function generate(?string $catName = null, ?int $qty = null): GenerateProductsResultInterface
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
                /** @var \Kogut\ProductsGeneration\Model\Service\GenerateProductsResult $result */
                $result = $this->generateProductsResultFactory->create();
                $result->setMessage("$qty products were created in $categoryName category");
                $result->setQty($qty);
                $result->setCatName($categoryName);

                return $result;

            } else {
                $this->addItemForProductGenerationByCronService->addItemToSchedule($categoryId, $qty);
                /** @var \Kogut\ProductsGeneration\Model\Service\GenerateProductsResult $result */
                $result = $this->generateProductsResultFactory->create();
                $result->setMessage("$qty products were scheduled to generate by cron in $categoryName category");
                $result->setQty($qty);
                $result->setCatName($categoryName);

                return $result;
            }
        } catch (\Throwable $exception) {
            $this->logger->error($exception);
            /** @var \Kogut\ProductsGeneration\Model\Service\GenerateProductsResult $result */
            $result = $this->generateProductsResultFactory->create();
            $result->setMessage("Something went wrong. See exception log for details.");

            return $result;
        }
    }
}
