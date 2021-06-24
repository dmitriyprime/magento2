<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Api;

/**
 * Generate products interface for rest API endpoint
 */
interface GenerateProductsInterface
{
    /**
     * Launches products generation via REST API
     *
     * @param ?string $catName
     * @param ?int $qty
     * @return \Kogut\ProductsGeneration\Api\Data\GenerateProductsResultInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function generate(
        ?string $catName = null,
        ?int $qty = null
    ): \Kogut\ProductsGeneration\Api\Data\GenerateProductsResultInterface;
}
