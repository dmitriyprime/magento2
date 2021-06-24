<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Model\Service;

use Kogut\ProductsGeneration\Api\Data\GenerateProductsResultInterface;

/**
 * Generate products result class for REST API endpoint
 */
class GenerateProductsResult implements GenerateProductsResultInterface
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $qty;

    /**
     * @var string
     */
    private $catName;

    /**
     * @inheritDoc
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function getQty(): ?int
    {
        return $this->qty;
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty): void
    {
        $this->qty = $qty;
    }

    /**
     * @inheritDoc
     */
    public function getCatName(): ?string
    {
        return $this->catName;
    }

    /**
     * @inheritDoc
     */
    public function setCatName($catName): void
    {
        $this->catName = $catName;
    }
}
