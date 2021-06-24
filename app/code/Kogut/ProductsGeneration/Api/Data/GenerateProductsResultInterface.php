<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Api\Data;

/**
 * Generate products result interface for REST API
 */
interface GenerateProductsResultInterface
{
    /**
     * Retrieves message
     *
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * Sets message field
     *
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void;

    /**
     * Retrieves qty field
     *
     * @return int|null
     */
    public function getQty(): ?int;

    /**
     * Sets qty field
     *
     * @param int $qty
     * @return void
     */
    public function setQty(int $qty): void;

    /**
     * Retrieves category name
     *
     * @return string|null
     */
    public function getCatName(): ?string;

    /**
     * Sets category name
     *
     * @param string $catName
     * @return void
     */
    public function setCatName(string $catName): void;
}
