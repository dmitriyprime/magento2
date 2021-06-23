<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Api;

interface GenerateProductsInterface
{
    /**
     * Launches products generation via REST API
     *
     * @param ?string $catName
     * @param ?int $qty
     * @return string
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function generate(?string $catName = null, ?int $qty = null): string;
}
