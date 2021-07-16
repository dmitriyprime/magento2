<?php

declare(strict_types=1);

namespace Dmitriyprime\CustomerTaxIdAttribute\Plugin\Quote;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\QuoteRepository;

/**
 * Sets customer tax ID attribute to quote model during guest checkout
 */
class SaveCustomerTaxIdAttributeToQuote
{
    private $quoteRepository;

    /**
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(
        QuoteRepository $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Sets customer tax ID attribute to quote model during guest checkout
     *
     * @param ShippingInformationManagement $subject
     * @param int $cartId
     * @param ShippingInformationInterface $addressInformation
     * @throws NoSuchEntityException
     * @SuppressWarnings("PMD.UnusedFormalParameter")
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        int $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        if (!$extAttributes = $addressInformation->getExtensionAttributes())
        {
            return;
        }

        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setData('customer_tax_id', $extAttributes->getCustomerTaxId());
    }
}
