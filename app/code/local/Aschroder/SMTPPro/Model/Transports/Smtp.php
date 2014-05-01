<?php
/**
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Transports_Smtp extends Aschroder_SMTPPro_Model_Transports_Basesmtp {

    public function getName($storeId) {
        return "Custom SMTP";
    }
    public function getEmail($storeId) {
        return Mage::helper('smtppro')->getSMTPSettingsUsername($storeId);
    }
    public function getPassword($storeId) {
        return Mage::helper('smtppro')->getSMTPSettingsPassword($storeId);
    }
    public function getHost($storeId) {
        return Mage::helper('smtppro')->getSMTPSettingsHost($storeId);
    }
    public function getPort($storeId) {
        return Mage::helper('smtppro')->getSMTPSettingsPort($storeId);
    }
    public function getAuth($storeId) {
        return Mage::helper('smtppro')->getSMTPSettingsAuthentication($storeId);
    }
    public function getSsl($storeId) {
        return Mage::helper('smtppro')->getSMTPSettingsSSL($storeId);
    }
}