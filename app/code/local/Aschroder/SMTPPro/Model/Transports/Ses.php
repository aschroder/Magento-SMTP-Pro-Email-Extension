<?php
/**
 *
 * Amazon SES API Transport
 *
 * Big thanks to Christopher Valles
 * https://github.com/christophervalles/Amazon-SES-Zend-Mail-Transport
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Transports_Ses {


    public function getTransport($storeId) {

        $_helper = Mage::helper('smtppro');
        $_helper->log("Getting Amazon SES Transport");

        $path = Mage::getModuleDir('', 'Aschroder_SMTPPro');
        include_once $path . '/lib/AmazonSES.php';

        $emailTransport = new App_Mail_Transport_AmazonSES(
            array(
                'accessKey' => $_helper->getAmazonSESAccessKey($storeId),
                'privateKey' => $_helper->getAmazonSESPrivateKey($storeId)
            )
        );

        return $emailTransport;
    }
}
