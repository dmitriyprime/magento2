<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Controller\Adminhtml\Index;

use Dmitriyprime\Vendor\Model\ResourceModel\Vendor as VendorResource;
use Dmitriyprime\Vendor\Model\Vendor;
use Dmitriyprime\Vendor\Model\VendorFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save vendor entity action.
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Dmitriyprime_Vendor::vendors_save';

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var VendorFactory
     */
    private $vendorFactory;

    /**
     * @var VendorResource
     */
    private $vendorResource;

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param VendorFactory $vendorFactory
     * @param VendorResource $vendorResource
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        VendorFactory $vendorFactory,
        VendorResource $vendorResource
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->vendorFactory = $vendorFactory;
        $this->vendorResource = $vendorResource;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (empty($data['entity_id'])) {
                $data['entity_id'] = null;
            }

            $model = $this->vendorFactory->create();

            $id = $this->getRequest()->getParam('entity_id');
            if ($id) {
                $this->vendorResource->load($model, $id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This vendor no longer exists.'));
                    /** @var Redirect $resultRedirect */
                    $resultRedirect = $this->resultRedirectFactory->create();

                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $this->vendorResource->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the vendor.'));
                $this->dataPersistor->clear('dmitriyprime_vendor');

                return $this->processReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the vendor.'));
            }

            $this->dataPersistor->set('dmitriyprime_vendor', $data);

            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process back button
     *
     * @param Vendor $model
     * @param array $data
     * @param Redirect $resultRedirect
     * @return mixed
     */
    private function processReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect;
    }
}
