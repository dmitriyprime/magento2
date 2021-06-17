<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Controller\Adminhtml\Index;

use Dmitriyprime\Vendor\Model\ResourceModel\Vendor;
use Dmitriyprime\Vendor\Model\VendorFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Edit vendor entity action.
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dmitriyprime_Vendor::vendors';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var VendorFactory
     */
    private $vendorFactory;

    /**
     * @var Vendor
     */
    private $vendorResource;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param VendorFactory $vendorFactory
     * @param Vendor $vendorResource
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        VendorFactory $vendorFactory,
        Vendor $vendorResource
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->vendorFactory = $vendorFactory;
        $this->vendorResource = $vendorResource;
        parent::__construct($context);
    }

    /**
     * Edit vendor entity
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // Get ID and create model
        $id = $this->getRequest()->getParam('entity_id');
        $model = $this->vendorFactory->create();

        // Initial checking
        if ($id) {
            $this->vendorResource->load($model, $id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This vendor no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');
            }
        }

        // Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Dmitriyprime_Vendor::vendors');
        $resultPage->addBreadcrumb(__('Vendors'), __('Vendors'));
        $resultPage->addBreadcrumb(__('Manage Vendors'), __('Manage Vendors'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Vendor') : __('New Vendor'),
            $id ? __('Edit Vendor') : __('New Vendor')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Vendors'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Vendor'));

        return $resultPage;
    }
}
