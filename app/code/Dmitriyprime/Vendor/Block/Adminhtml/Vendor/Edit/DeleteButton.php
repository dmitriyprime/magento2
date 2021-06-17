<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Block\Adminhtml\Vendor\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * DeleteButton data provider class
 */
class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    private $authorization;

    /**
     * @param Context $context
     * @param AuthorizationInterface $authorization
     */
    public function __construct(
        Context $context,
        AuthorizationInterface $authorization
    ) {
        parent::__construct($context);
        $this->authorization = $authorization;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getPostId() && $this->authorization->isAllowed('Dmitriyprime_Vendor::vendors_delete')) {
            $data = [
                'label' => __('Delete Vendor'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }

        return $data;
    }

    /**
     * URL to send delete requests to.
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['entity_id' => $this->getPostId()]);
    }
}
