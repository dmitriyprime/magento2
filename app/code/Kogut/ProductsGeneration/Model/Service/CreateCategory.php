<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class CreateCategory
{
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(
        CategoryFactory $categoryFactory,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Creates category
     * @param string $categoryName
     * @return Category
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function createCategory(string $categoryName): Category
    {
        $category = $this->categoryFactory->create();
        $category->setName($categoryName);
        $category->setParentId(2);
        $category->setIsActive(true);
        $category->setIncludeInMenu(true);
        $this->categoryRepository->save($category);

        return $category;
    }
}
