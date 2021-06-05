<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Magento\Catalog\Api\CategoryListInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;

class SearchCategoryByName
{
    /**
     * @var CategoryListInterface
     */
    private $categoryList;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @param CategoryListInterface $categoryList
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        CategoryListInterface $categoryList,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->categoryList = $categoryList;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * Searches category by name
     * @param string $categoryName
     * @return array
     */
    public function search(string $categoryName): array
    {
        $filterByName = $this->filterBuilder
            ->setField('name')
            ->setValue($categoryName)
            ->setConditionType('eq')
            ->create();
        $this->searchCriteriaBuilder->addFilters([$filterByName]);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        return $this->categoryList->getList($searchCriteria)->getItems();
    }
}
