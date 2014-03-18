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
        return Mage::getStoreConfig('system/smtppro/option', $storeId) != "disabled";
    }
    
    /**
     * @deprecated use debug helper instead.
     * @return boolean
     */
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
    
    public function isSMTPEnabled($storeId = null)
    {
        return Mage::getStoreConfig('system/smtppro/option', $storeId) == "smtp";
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
    
}
