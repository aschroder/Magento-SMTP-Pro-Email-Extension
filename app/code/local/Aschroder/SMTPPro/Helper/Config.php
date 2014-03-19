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
    const XML_PATH_CLEANLOG = 'system/smtppro/cleanlog';
    const XML_PATH_CLEANLOG_AFTER_DAYS = 'system/smtppro/cleanlog_after_days';


    public function isEnabled($storeId = null)
    {
        return Mage::getStoreConfig('system/smtppro/option', $storeId) != "disabled";
    }
    
    public function isLogEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('system/smtppro/logenabled', $storeId);
    }

    public function isReplyToStoreEmail($storeId = null)
    {
        return Mage::getStoreConfig('system/smtppro/store_addresses', $storeId);
    }
    

    
    public function isGoogleAppsEnabled($storeId = null)
    {
        return Mage::getStoreConfig('system/smtppro/option', $storeId) == "google";
    }
    public function isAmazonSESEnabled($storeId = null)
    {
        return Mage::getStoreConfig('system/smtppro/option', $storeId) == "ses";
    }
    
    public function getDevelopmentMode($storeId = null)
    {
        return Mage::getStoreConfig('system/smtppro/development', $storeId);
    }

    public function isDebugLoggingEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('system/smtppro/log_debug', $storeId);
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
        return Mage::getStoreConfig('system/sessettings/aws_access_key', $storeId);
    }

    public function getAmazonSESPrivateKey($storeId = null)
    {
        return Mage::getStoreConfig('system/sessettings/aws_private_key', $storeId);
    }





    public function getGoogleAppsEmail($storeId = null)
    {
        return Mage::getStoreConfig('system/googlesettings/email', $storeId);
    }

    public function getGoogleAppsPassword($storeId = null)
    {
        return Mage::getStoreConfig('system/googlesettings/gpassword', $storeId);
    }






    public function isSMTPEnabled($storeId = null)
    {
        return Mage::getStoreConfig('system/smtppro/option', $storeId) == "smtp";
    }






    
    public function getSMTPSettingsHost($storeId = null)
    {
        return Mage::getStoreConfigFlag('system/smtpsettings/host', $storeId);
    }

    public function getSMTPSettingsPort($storeId = null)
    {
        return Mage::getStoreConfigFlag('system/smtpsettings/port', $storeId);
    }

    public function getSMTPSettingsUsername($storeId = null)
    {
        return Mage::getStoreConfig('system/smtpsettings/username', $storeId);
    }

    public function getSMTPSettingsPassword($storeId = null)
    {
        return Mage::getStoreConfig('system/smtpsettings/password', $storeId);
    }
    public function getSMTPSettingsSSL($storeId = null)
    {
        return Mage::getStoreConfig('system/smtpsettings/ssl', $storeId);
    }

    public function getSMTPSettingsAuthentication($storeId = null)
    {
        return Mage::getStoreConfig('system/smtpsettings/authentication', $storeId);
    }


    public function isLogCleaningEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_CLEANLOG, $storeId);
    }

    /**
     * @param  $storeId
     * @return integer number of days to wait before log is cleaned (ie this is the log cleaning interval time in days)
     */
    public function getLogLifetimeDays($storeId = null)
    {
        return Mage::getStoreConfig(self::XML_PATH_CLEANLOG_AFTER_DAYS, $storeId);
    }
    


}
