<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Console\Command;

use Exception;
use Kogut\ProductsGeneration\Model\Config\Settings;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Kogut\ProductsGeneration\Model\Service\CreateCategory;
use Kogut\ProductsGeneration\Model\Service\CreateProduct;
use Kogut\ProductsGeneration\Model\Service\SearchCategoryByName;

/**
 * Class GenerateProducts
 */
class GenerateProducts extends Command
{
    const PRODUCTS_QTY_TO_GENERATE = 'qty';
    const CATEGORY_NAME_TO_ASSIGN_THE_PRODUCTS = 'cat';

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
     * @var Settings
     */
    private $config;

    /**
     * @var State
     */
    private $appState;

    /**
     * @param Settings $config
     * @param CreateProduct $createProductService
     * @param CreateCategory $createCategoryService
     * @param SearchCategoryByName $searchCategoryByNameService
     * @param State $appState
     */
    public function __construct(
        Settings $config,
        CreateProduct $createProductService,
        CreateCategory $createCategoryService,
        SearchCategoryByName $searchCategoryByNameService,
        State $appState
    ) {
        $this->config = $config;
        $this->createProductService = $createProductService;
        $this->createCategoryService = $createCategoryService;
        $this->searchCategoryByNameService = $searchCategoryByNameService;
        $this->appState = $appState;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('setup:generate-products')
            ->setDescription('Generates simple products')
            ->setDefinition($this->getOptionsList());
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(Area::AREA_GLOBAL);
        $categoryNameConfig = $this->config->getCategoryName();
        $productsQtyToGenerateConfig = $this->config->getProductsQtyToGenerate();

        $qty = $input->getOption(self::PRODUCTS_QTY_TO_GENERATE) ?? $productsQtyToGenerateConfig;
        $categoryName = $input->getOption(self::CATEGORY_NAME_TO_ASSIGN_THE_PRODUCTS) ?? $categoryNameConfig;

        $categories = $this->searchCategoryByNameService->search($categoryName);

        if(!empty($categories)) {
            $categoryId = (int) $categories[0]->getId();
        } else {
            $createdCategory = $this->createCategoryService->createCategory($categoryName);
            $categoryId = (int) $createdCategory->getId();
        }

        try {
            for($i = 0; $i < $qty; $i++) {
                $this->createProductService->createSimpleProduct($categoryId);
            }
            $output->writeln("<info>$qty products are generated in category $categoryName.</info>");
            return Cli::RETURN_SUCCESS;
        } catch (Exception $exception) {
            $output->writeln("");
            $output->writeln("<error>{$exception->getMessage()}</error>");
            return Cli::RETURN_FAILURE;
        }
    }

    /**
     * Get list of options for the command
     *
     * @return InputOption[]
     */
    public function getOptionsList()
    {
        return [
            new InputOption(
                self::PRODUCTS_QTY_TO_GENERATE,
                null,
                InputOption::VALUE_OPTIONAL,
                'Products quantity to generate'
            ),
            new InputOption(
                self::CATEGORY_NAME_TO_ASSIGN_THE_PRODUCTS,
                null,
                InputOption::VALUE_OPTIONAL,
                'Category name to assign the products'
            ),
        ];
    }
}
