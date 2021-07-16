define([
    'jquery',
    'mage/utils/wrapper'
], function ($, wrapper) {
    'use strict';

    return function (payloadFunction) {
        return wrapper.wrap(payloadFunction, function (originalPayloadFunction, payload) {
            originalPayloadFunction(payload);

            var customerTaxIdAttribute = $('[name="custom_attributes[customer_tax_id]"]').val();
            payload.addressInformation.extension_attributes = {
                'customer_tax_id' : customerTaxIdAttribute
            }
        });
    };
});
