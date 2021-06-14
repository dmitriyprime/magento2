<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Controller\Adminhtml\Index;

use Dmitriyprime\Vendor\Model\ResourceModel\Vendor;
use Dmitriyprime\Vendor\Model\VendorFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Delete vendor entity action.
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dmitriyprime_Vendor::vendors_delete';

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
     * @param VendorFactory $vendorFactory
     * @param Vendor $vendorResource
     */
    public function __construct(
        Context $context,
        VendorFactory $vendorFactory,
        Vendor $vendorResource
    ) {
        $this->vendorFactory = $vendorFactory;
        $this->vendorResource = $vendorResource;
        parent::__construct($context);
    }

    /**
     * Delete action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('entity_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->vendorFactory->create();
                $this->vendorResource->load($model, $id);
                $this->vendorResource->delete($model);

                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the vendor.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a vendor to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
