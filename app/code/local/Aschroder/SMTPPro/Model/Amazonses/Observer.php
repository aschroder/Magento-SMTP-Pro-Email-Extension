<?php
/**
 * Big thanks to Christopher Valles
 * https://github.com/christophervalles/Amazon-SES-Zend-Mail-Transport
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_AmazonSES_Observer extends Varien_Object
{
	

    /**
     * Expects observer with:
     * 'mail', the mail about to be sent
     * ['template', template model for email being sent] - only if being sent from a template
     * 'email', the email object initiating the send
     * 'transport', an initially empty transport Object, will be used if set.
     *
     * @param $observer
     */
    public function beforeSend($observer) 
    {
        $event = $observer->getEvent();
        $storeId = $event->getTemplate() ? $event->getTemplate()->getStoreId() : null;

        if(!Mage::helper('smtppro/config')->isAmazonSESEnabled($storeId)) {
            return $this;
        }

        Mage::helper('smtppro/debug')->log("Running Amazon SES sender code.");

        $emailTransport = new App_Mail_Transport_AmazonSES(
            array(
                'accessKey' => Mage::helper('smtppro/config')->getAmazonSESAccessKey($storeId),
                'privateKey' => Mage::helper('smtppro/config')->getAmazonSESPrivateKey($storeId) 
            )
        );

        $event->getTransport()->setEmailTransport($emailTransport);

        return $this;
    }

}
