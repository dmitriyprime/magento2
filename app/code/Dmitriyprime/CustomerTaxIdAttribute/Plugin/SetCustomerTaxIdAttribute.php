<?php

declare(strict_types=1);

namespace Dmitriyprime\CustomerTaxIdAttribute\Plugin;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteManagement;

/**
 * Sets customer tax ID attribute to quote model during checkout with registered customer
 */
class SetCustomerTaxIdAttribute
{

    /**
     * Sets customer tax ID attribute to quote model during checkout with registered customer
     *
     * @param QuoteManagement $subject
     * @param Quote $quote
     * @param array $orderData
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSubmit(
        QuoteManagement $subject,
        Quote $quote, $orderData = []
    ): void {
        $customerTaxIdAttribute = $quote->getCustomer()->getCustomAttribute('customer_tax_id');
        if ($customerTaxIdAttribute) {
            $customerTaxIdAttributeValue = $customerTaxIdAttribute->getValue();
            $quote->setData('customer_tax_id', $customerTaxIdAttributeValue);
        }
    }
}
