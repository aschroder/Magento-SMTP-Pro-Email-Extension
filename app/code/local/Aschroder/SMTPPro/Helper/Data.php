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
	
	public function getTransport($id = null) 
	{
		if($this->getSMTP()){
			
			$username = Mage::getStoreConfig('system/smtpsettings/username', $id);
			$password = Mage::getStoreConfig('system/smtpsettings/password', $id);
			$host = Mage::getStoreConfig('system/smtpsettings/host', $id);
			$port = Mage::getStoreConfig('system/smtpsettings/port', $id);
			$ssl = Mage::getStoreConfig('system/smtpsettings/ssl', $id);
			$auth = Mage::getStoreConfig('system/smtpsettings/authentication', $id);
	
			$this->log('Preparing the SMTP Email transport, details are: \n '
			 . "  username=" . $username . "\n"
			 . "  password=" . "MASKED"  /*. $password  */ . "\n" 
			 . "  host=" . $host . "\n"
			 . "  port=" . $port . "\n"
			 . "  ssl=" . $ssl . "\n"
			 . "  auth=" . $auth . "\n"
			);
			 
			 // Set up the config array
			 
			 $config = array();
			 
			 if ($auth != "none") {
				$config['auth'] = $auth;
				$config['username'] = $username;
                $config['password'] = $password;
			 }
			 
			 if ($port) {
					$config['port'] = $port;
			 }
			 
			 if ($ssl != "none" ) {
					$config['ssl'] = $ssl;
			 }
			
			$transport = new Zend_Mail_Transport_Smtp($host, $config);
			
		} else if($this->getGoogleApps()) {
			
			$email = explode(",", Mage::getStoreConfig('system/googlesettings/email', $id));

			// We now allow a load balance of multiple gmail 
			// accounts to get past the 500/day limit.
			
			if (count($email)) {
				
				$email = $email[array_rand($email)];
			} else {
				
				$this->log(
					"No email configured - 
					you need to specify one in the magento configuration, 
					otherwise your connection will fail");
			}
			
			$password = Mage::getStoreConfig('system/googlesettings/gpassword', $id);
			
			$this->log('Preparing the Google Apps/Gmail Email transport, email to send with is: ' . $email);
			$config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => $email, 'password' => $password);
			$transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);

		} else if($this->getSES()) {
			
			// Big thanks to Christopher Valles
			// https://github.com/christophervalles/Amazon-SES-Zend-Mail-Transport
			include_once Mage::getBaseDir() . '/app/code/community/Aschroder/SMTPPro/lib/AmazonSES.php';
			
			$transport = new App_Mail_Transport_AmazonSES(
			    array(
			        'accessKey' => Mage::getStoreConfig('system/sessettings/aws_access_key', $id),
			        'privateKey' => Mage::getStoreConfig('system/sessettings/aws_private_key', $id) 
			    )
			);
			
		} else {
			$this->log("Disabled, or no matching transport");
			return null;
		}
		
		$this->log("Returning transport");
		
		return $transport;
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
		return Mage::helper('smtppro/config')->getGoogleApps();
	}
	/**
	 * @deprecated use config helper instead
	 * @return boolean
	 */
	public function getSES()
	{
		return Mage::helper('smtppro/config')->getSES();
	}
	
	/**
	 * @deprecated use config helper instead
	 * @return boolean
	 */
	public function getSMTP()
	{
		return Mage::helper('smtppro/config')->getSMTP();
	}

}
