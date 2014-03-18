<?php

/**
 * Various Helper functions for the Aschroder.com SMTP Pro module.
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Helper_Data extends Mage_Core_Helper_Abstract 
{
	
	// Keeping this function for backward compatibility 
	// It will be dropped eventually so call getTransport() from now on!
	public function getSMTPProTransport($id = null)
	{
		return $this->getTransport($id);
	}
	
	// Keeping this function for backward compatibility 
	// It will be dropped eventually so call getTransport() from now on!
	public function getGoogleAppsEmailTransport($id = null) {
		return $this->getTransport($id);
	}
	
	/**
	 * @deprecated you can no longer get the transport this way. the email transport is set by the mail system's observer (ie see Model/GoogleApps/Observer.php)
	 * @param  int $storeId
	 * @return $this
	 */
	public function getTransport($storeId = null) 
	{
		$this->log("WARNING: Deprecated transport method was called in SMTPPro/Helper/Data.php");
		return null;
	}
	











	/**
	 * @deprecated please use the debug helper emailLog() function with the same parameters.
	 * @param  [type] $to       [description]
	 * @param  [type] $template [description]
	 * @param  [type] $subject  [description]
	 * @param  [type] $email    [description]
	 * @param  [type] $isHtml   [description]
	 * @return [type]           [description]
	 */
    public function log($to, $template=null, $subject=null, $email=null, $isHtml=null)
    {
    	if($template == null && $subject == null && $email == null && $isHtml == null) {
    		return Mage::helper('smtppro/debug')->log($to);
    	}
        return Mage::helper('smtppro/debug')->emailLog($to, $template, $subject, $email, $isHtml);
    }
	
	public function isEnabled()
	{
		return Mage::helper('smtppro/config')->isEnabled();
	}
	
	/**
	 * @deprecated use debug helper instead.
	 * @return boolean
	 */
	public function isLogEnabled()
	{
		return Mage::helper('smtppro/config')->isLogEnabled();
	}

	/**
	 * @deprecated use config helper instead
	 * @return boolean
	 */
	public function isReplyToStoreEmail()
	{
		return Mage::helper('smtppro/config')->isReplyToStoreEmail();
	}
	
	/**
	 * @deprecated use config helper instead
	 * @return boolean
	 */
	public function getDevelopmentMode()
	{
		return Mage::helper('smtppro/config')->getDevelopmentMode();
	}
	
	/**
	 * @deprecated use config helper instead
	 * @return boolean
	 */
	public function getGoogleApps()
	{
		return Mage::helper('smtppro/config')->isGoogleAppsEnabled();
	}
	/**
	 * @deprecated use config helper instead
	 * @return boolean
	 */
	public function getSES()
	{
		return Mage::helper('smtppro/config')->isAmazonSESEnabled();
	}
	
	/**
	 * @deprecated use config helper instead
	 * @return boolean
	 */
	public function getSMTP()
	{
		return Mage::helper('smtppro/config')->isSMTPEnabled();
	}

}
