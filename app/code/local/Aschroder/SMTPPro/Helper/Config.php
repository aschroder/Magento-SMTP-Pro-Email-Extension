<?php

/**
 * Various Helper functions for the Aschroder.com SMTP Pro module.
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Helper_Config extends Mage_Core_Helper_Abstract
{


    public function isEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) != "disabled";
    }

    public function isGoogleAppsEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) == "google";
    }
    public function isAmazonSESEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) == "ses";
    }
    public function isSMTPEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) == "smtp";
    }

    


    public function isReplyToStoreEmail($storeId = null)
    {
        return Mage::getStoreConfig('system/general/store_addresses', $storeId);
    }
    public function isSystemSMTPDisabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('system/smtp/disable', $storeId);
    }
    public function getTestSenderEmailAddress($storeId = null)
    {
        return Mage::getStoreConfig('trans_email/ident_general/email', $storeId);
    }
    public function getTestRecipientEmailAddress($storeId = null)
    {
        return Mage::getStoreConfig('contacts/email/recipient_email', $storeId);
    }





    public function getAmazonSESAccessKey($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/sessettings/ses_access_key', $storeId);
    }

    public function getAmazonSESPrivateKey($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/sessettings/ses_private_key', $storeId);
    }





    public function getGoogleAppsEmail($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/googleapps_email', $storeId);
    }

    public function getGoogleAppsPassword($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/googleapps_gpassword', $storeId);
    }









    
    public function getSMTPSettingsHost($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/general/smtp_host', $storeId);
    }

    public function getSMTPSettingsPort($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/general/smtp_port', $storeId);
    }

    public function getSMTPSettingsUsername($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_username', $storeId);
    }

    public function getSMTPSettingsPassword($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_password', $storeId);
    }
    public function getSMTPSettingsSSL($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_ssl', $storeId);
    }

    public function getSMTPSettingsAuthentication($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_authentication', $storeId);
    }


    public function isLogCleaningEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/debug/cleanlog', $storeId);
    }
    /**
     * @param  $storeId
     * @return integer number of days to wait before log is cleaned (ie this is the log cleaning interval time in days)
     */
    public function getLogLifetimeDays($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/debug/cleanlog_after_days', $storeId);
    }
    public function isLogEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/debug/logenabled', $storeId);
    }
    public function getDevelopmentMode($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/debug/development', $storeId);
    }

    public function isDebugLoggingEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/debug/log_debug', $storeId);
    }



}
