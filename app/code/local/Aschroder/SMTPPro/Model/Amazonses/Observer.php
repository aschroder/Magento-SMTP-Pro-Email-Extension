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
     * 'template', template model for email being sent
     * 'email', the email object initiating the send
     * 'transport', an initially empty transport Object, will be used if set.
     *
     * @param $observer
     */
    public function beforeSend($observer) 
    {
        $event = $observer->getEvent();
        $storeId = $event->getTemplate()->getStoreId();

        if(!Mage::helper('smtppro/config')->isAmazonSESEnabled($storeId)) {
            return $this;
        }

        Mage::helper('smtppro/debug')->log("Running Amazon SES sender code.");

        $emailTransport = new App_Mail_Transport_AmazonSES(
            array(
                'accessKey' => Mage::getStoreConfig('system/sessettings/aws_access_key', $storeId),
                'privateKey' => Mage::getStoreConfig('system/sessettings/aws_private_key', $storeId) 
            )
        );

        $event->getTransport()->setEmailTransport($emailTransport);

        return $this;
    }

    /**
     * Expects observer with:
     * 'mail', the mail about to be sent
     * 'template', the template being used
     * 'variables', the variables used in the template
     * 'transport', an initially empty transport Object, will be used if set.
     * @todo this needs to be used after I update the Email.php model
     * @param $observer
     */
    // public function beforeSendTemplate($observer) 
    // {
    //     $e = $observer->getEvent();
    //     $storeId = $e->getTemplate()->getStoreId();

    //     $transport = new App_Mail_Transport_AmazonSES(
    //         array(
    //             'accessKey' => Mage::getStoreConfig('system/sessettings/aws_access_key', $storeId),
    //             'privateKey' => Mage::getStoreConfig('system/sessettings/aws_private_key', $storeId) 
    //         )
    //     );
        
    //     $transport = $e->getTransport();
    //     $transport->setTransport($transport);

    //     return $this;
    // }

	
}
