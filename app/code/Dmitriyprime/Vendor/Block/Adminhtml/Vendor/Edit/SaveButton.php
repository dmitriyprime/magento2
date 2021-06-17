<?php

declare(strict_types=1);

namespace Dmitriyprime\Vendor\Block\Adminhtml\Vendor\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;

/**
 * SaveButton data provider class
 */
class SaveButton extends GenericButton implements ButtonProviderInterface
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
     * Gets Save button data
     *
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->authorization->isAllowed('Dmitriyprime_Vendor::vendors_save')) {
            $data = [
                'label' => __('Save'),
                'class' => 'save primary',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'dmitriyprime_vendor_form.dmitriyprime_vendor_form',
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'back' => 'continue'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'class_name' => Container::SPLIT_BUTTON,
                'options' => $this->getOptions(),
            ];
        }

        return $data;
    }

    /**
     * Retrieve options
     *
     * @return array
     */
    private function getOptions()
    {
        $options = [
            [
                'id_hard' => 'save_and_close',
                'label' => __('Save & Close'),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'dmitriyprime_vendor_form.dmitriyprime_vendor_form',
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'back' => 'close'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $options;
    }
}
