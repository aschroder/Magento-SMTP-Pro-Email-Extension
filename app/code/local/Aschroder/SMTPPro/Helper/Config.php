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
    
    public function isEnabled()
    {
        return Mage::getStoreConfig('system/smtppro/option') != "disabled";
    }
    
    /**
     * @deprecated use debug helper instead.
     * @return boolean
     */
    public function isLogEnabled()
    {
        return Mage::helper('smtppro/debug')->isLogEnabled();
    }

    public function isReplyToStoreEmail()
    {
        return Mage::getStoreConfig('system/smtppro/store_addresses');
    }
    
    public function getDevelopmentMode()
    {
        return Mage::helper('smtppro/debug')->getDevelopmentMode();
    }
    
    public function getGoogleApps()
    {
        return Mage::getStoreConfig('system/smtppro/option') == "google";
    }
    public function getSES()
    {
        return Mage::getStoreConfig('system/smtppro/option') == "ses";
    }
    
    public function getSMTP()
    {
        return Mage::getStoreConfig('system/smtppro/option') == "smtp";
    }
    
        
    public function isLogEnabled()
    {
        return Mage::getStoreConfigFlag('system/smtppro/logenabled');
    }
    
    public function getDevelopmentMode()
    {
        return Mage::getStoreConfig('system/smtppro/development');
    }

    public function isDebugLoggingEnabled()
    {
        return Mage::getStoreConfigFlag('system/smtppro/log_debug');
    }
    
}
