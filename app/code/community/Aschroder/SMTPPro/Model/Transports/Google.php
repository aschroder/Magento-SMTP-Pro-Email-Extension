<?php
/**
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Transports_Google extends Aschroder_SMTPPro_Model_Transports_Basesmtp {

    public function getName($storeId) {
        return "Google Apps/Gmail";
    }
    public function getEmail($storeId) {
        return Mage::helper('smtppro')->getGoogleAppsEmail($storeId);
    }
    public function getPassword($storeId) {
        return Mage::helper('smtppro')->getGoogleAppsPassword($storeId);
    }
    public function getHost($storeId) {
        return "smtp.gmail.com";
    }
    public function getPort($storeId) {
        return 587;
    }
    public function getAuth($storeId) {
        return 'login';
    }
    public function getSsl($storeId) {
        return 'tls';
    }
}
