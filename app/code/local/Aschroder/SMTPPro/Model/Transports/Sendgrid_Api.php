<?php

class Aschroder_SMTPPro_Model_Transports_Sendgrid_Api
{
    public function getTransport($storeId)
    {
        /* @var $helper Aschroder_SMTPPro_Helper_Data */
        $helper = Mage::helper('smtppro');

        $helper->log("Getting SendGrid API Transport");

        $path = Mage::getModuleDir('', 'Aschroder_SMTPPro');
        include_once $path . '/lib/SendGridAPI.php';

        $transport = new App_Mail_Transport_SendGridAPI(array(
            'apiKey' => $helper->getSendGridAPIKey($storeId)
        ));

        return $transport;
    }
}
