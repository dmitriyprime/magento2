<?php

declare(strict_types=1);

namespace Kogut\ProductsGeneration\Ui\Component\Listing;

use Kogut\ProductsGeneration\Model\Config\Settings;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Framework\UrlInterface;

/**
 * Generate Products button data provider class
 */
class GenerateProductsButton implements ButtonProviderInterface
{
    const XML_PATH_TO_PRODUCT_GENERATION_CONTROLLER = 'generate_products/products/generate';

    /**
     * URL builder
     *
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Settings
     */
    private $config;

    /**
     * @param UrlInterface $urlBuilder
     * @param Settings $config
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Settings $config
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $message = __("Are you sure you want to generate %1 products?", $this->config->getProductsQtyToGenerate())
            ->render();

        return [
            'label' => __('Generate products'),
            'sort_order' => '5',
            'class' => 'generate_products primary',
            'on_click' => sprintf(
                "confirmSetLocation('%s', '%s')",
                $message,
                $this->urlBuilder->getUrl(self::XML_PATH_TO_PRODUCT_GENERATION_CONTROLLER)
            ),
        ];
    }
}
