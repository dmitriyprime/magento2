<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Controller\Adminhtml\Products;

use Kogut\ProductsGeneration\Model\Service\CreateCategory;
use Kogut\ProductsGeneration\Model\Service\CreateProduct;
use Kogut\ProductsGeneration\Model\Service\SearchCategoryByName;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Kogut\ProductsGeneration\Model\Config\Settings;
use Kogut\ProductsGeneration\Model\Service\AddItemForProductGenerationByCron;

class Generate extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Kogut_ProductsGeneration::config';

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
     * @param Context $context
     * @param RedirectFactory $resultRedirectFactory
     * @param CreateProduct $createProductService
     * @param CreateCategory $createCategoryService
     * @param Settings $config
     * @param SearchCategoryByName $searchCategoryByNameService
     * @param AddItemForProductGenerationByCron $addItemForProductGenerationByCronService
     */
    public function __construct(
        Context $context,
        RedirectFactory $resultRedirectFactory,
        CreateProduct $createProductService,
        CreateCategory $createCategoryService,
        Settings $config,
        SearchCategoryByName $searchCategoryByNameService,
        AddItemForProductGenerationByCron $addItemForProductGenerationByCronService
    ) {
        parent::__construct($context);
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->config = $config;
        $this->createProductService = $createProductService;
        $this->createCategoryService = $createCategoryService;
        $this->searchCategoryByNameService = $searchCategoryByNameService;
        $this->addItemForProductGenerationByCronService = $addItemForProductGenerationByCronService;
    }

    /**
     * Generates products
     * @return Redirect
     */
    public function execute()
    {
        $categoryName = $this->config->getCategoryName();
        $productsQtyToGenerate = $this->config->getProductsQtyToGenerate();

        $categories = $this->searchCategoryByNameService->search($categoryName);

        if(!empty($categories)) {
            $categoryId = (int) $categories[0]->getId();
        } else {
            $createdCategory = $this->createCategoryService->createCategory($categoryName);
            $this->messageManager->addSuccessMessage(__('Category ' . $createdCategory->getName() . ' is created.'));
            $categoryId = (int) $createdCategory->getId();
        }

        if($productsQtyToGenerate <= 100) {
            for($i = 0; $i < $productsQtyToGenerate; $i++) {
                $this->createProductService->createSimpleProduct($categoryId);
            }
            $this->messageManager->addSuccessMessage(__("$productsQtyToGenerate new products are generated"));
        } else {
            $this->addItemForProductGenerationByCronService->addItemToSchedule($categoryId, $productsQtyToGenerate);
            $successMessageText = "$productsQtyToGenerate products are scheduled for generation by cron job.";
            $this->messageManager->addSuccessMessage(__($successMessageText));
        }
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('catalog/product/index');
        return $resultRedirect;
    }
}
