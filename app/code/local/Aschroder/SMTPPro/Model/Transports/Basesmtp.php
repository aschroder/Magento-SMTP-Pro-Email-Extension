<?php
/**
 *
 * Abstract base class for all SMTP-based transports
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Transports_Basesmtp {


    public function getTransport($storeId) {

        $_helper = Mage::helper('smtppro');

        $name = $this->getName($storeId);
        $email = $this->getEmail($storeId);
        $password = $this->getPassword($storeId);
        $host = $this->getHost($storeId);
        $port = $this->getPort($storeId);
        $auth = $this->getAuth($storeId);
        $ssl = $this->getSsl($storeId);

        $_helper->log("Using $name Transport.");

        $config = array(
            'ssl' => $ssl,
            'port' => $port,
            'auth' => $auth,
            'username' => $email,
            'password' => $password
        );

        $emailTransport = new Zend_Mail_Transport_Smtp($host, $config);
        return $emailTransport;
    }
}
