<?php

/**
 * An SMTP self test for the SMTPPro Magento extension
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Aschroder_SMTPPro_IndexController extends Mage_Adminhtml_Controller_Action 
{

    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contacts/email/email_template';
    const XML_PATH_ENABLED          = 'contacts/contacts/enabled';
		
	/**
	 * @deprecated DO NOT USE.
	 */
	public static $CONTACTFORM_SENT = true;
	private $TEST_EMAIL = "hello.default@example.com";

	public function indexAction() 
	{

		Mage::helper('smtppro')->log("Running SMTP Pro Self Test");
		
		#report development mode for debugging
		$dev = Mage::helper('smtppro/config')->getDevelopmentMode();
		Mage::helper('smtppro')->log("Development mode: " . $dev);
		
		$success = true;
		$websiteModel = Mage::app()->getWebsite($this->getRequest()->getParam('website'));
		$storeId = $websiteModel->getStore()->getId();
		$this->TEST_EMAIL = Mage::helper('smtppro/config')->getTestSenderEmailAddress($storeId);

		$msg = "ASchroder.com SMTP Pro Self-test results";
		
		$msg = $msg . "<br/>Testing outbound connectivity to Server:";
		Mage::helper('smtppro')->log("Raw connection test....");
		
		
		$googleapps = Mage::helper('smtppro/config')->isGoogleAppsEnabled();
		$smtpEnabled = Mage::helper('smtppro/config')->isSMTPEnabled();
		$sesEnabled = Mage::helper('smtppro/config')->isAmazonSESEnabled();
		
		if($googleapps) {
			$msg = $msg . "<br/>Using Google Apps/Gmail configuration options";
			$host = "smtp.gmail.com";
			$port = 587;
		} else if ($smtpEnabled) {
			$msg = $msg . "<br/>Using SMTP configuration options";
			$host = Mage::helper('smtppro/config')->getSMTPSettingsHost($storeId);
			$port = Mage::helper('smtppro/config')->getSMTPSettingsPort($storeId);
		} else if ($sesEnabled) {
			// no connectivity test - either disabled or SES...
			$msg = $msg . "<br/> Connection to Amazon SES server not tested (...yet)";
			Mage::helper('smtppro')->log("skipped, SES.");
		} else {
			$msg = $msg . "<br/> extension disabled, cannot test outbound connectivity";
			Mage::helper('smtppro')->log("skipped, disabled.");
		}
		

		if ($googleapps || $smtpEnabled) {
			$fp = false;
			
			try {
				$fp = fsockopen($host, $port, $errno, $errstr, 15);
			} catch ( Exception $e) {
				// An error will be reported below.
			}
	
			Mage::helper('smtppro')->log("Complete");
	
			if (!$fp) {
				$success = false;
				$msg = $msg . "<br/>Failed to connect to SMTP server. Reason: " . $errstr . "(" . $errno . ")";
			 	$msg = $msg . "<br/> This extension requires an outbound SMTP connection on port: " . $port;
			} else {
				$msg = $msg . "<br/> Connection to Host SMTP server successful.";
				fclose($fp);
			}
		}

		$to = Mage::helper('smtppro/config')->getTestRecipientEmailAddress($storeId);

		$mail = new Zend_Mail();
		$sub = "Test Email From ASchroder.com SMTP Pro Module";
		$body = 
			"Hi,\n\n" .
			"If you are seeing this email then your " .
			"SMTP Pro settings are correct! \n\n" .
			"For more information about this extension and " .
			"tips for using it please visit ASchroder.com.\n\n" .
			"Regards,\nAshley";

	        $mail->addTo($to)
	        	->setFrom($this->TEST_EMAIL)
        		->setSubject($sub)
	            ->setBodyText($body);

		if ($dev != "supress") {
			
			Mage::helper('smtppro')->log("Actual email sending test....");
			$msg = $msg . "<br/> Sending test email to your contact form address " . $to . ":";
			
	        try {
				$transport = Mage::helper('smtppro')->getTransport($websiteModel->getId());
				
			 	
				$mail->send($transport);
				
				Mage::dispatchEvent('smtppro_email_after_send',
				array(
					'to' => $to,
		 			'template' => "SMTPPro Self Test",
					'subject' => $sub,
					'html' => false,
		 			'email_body' => $body
	 			));
				
				$msg = $msg . "<br/> Test email was sent successfully.";
				Mage::helper('smtppro')->log("Test email was sent successfully");
				
				
	    	} catch (Exception $e) {
				$success = false;
				$msg = $msg . "<br/> Unable to send test email. Exception message was: " . $e->getMessage() . "...";
			 	$msg = $msg . "<br/> Please check and double check your username and password.";
				Mage::helper('smtppro')->log("Test email was not sent successfully: " . $e->getMessage());
	    	}
		} else {
			Mage::helper('smtppro')->log("Not sending test email - all mails currently supressed");
			$msg = $msg . "<br/> No test email sent, development mode is set to supress all emails.";
		}
		
		// Now we test that the actual core overrides are occuring as expected.
		// We trigger the contact form email, as though a user had done so.

		Mage::helper('smtppro')->log("Actual contact form submit test...");
		
		$this->_sendTestContactFormEmail();
		
		// If everything worked as expected, the observer will have set this value to true.
		if (Mage::registry('smtppro_email_sent')) {
			$msg = $msg . "<br/> Contact Form test email used SMTPPro to send correctly.";
		} else {
			$success = false;
			$msg = $msg . "<br/> Contact Form test email did not use SMTPPro to send.";
		}
		
		Mage::helper('smtppro')->log("Complete");

		if($success) {
			$msg = $msg . "<br/> Testing complete, if you are still experiencing difficulties please visit  <a target='_blank' href='http://aschroder.com'>ASchroder.com</a> to contact me.";
			Mage::getSingleton('adminhtml/session')->addSuccess($msg);
		} else {
			$msg = $msg . "<br/> Testing failed,  please review the reported problems and if you need further help visit  <a target='_blank' href='http://aschroder.com'>ASchroder.com</a> to contact me.";
			Mage::getSingleton('adminhtml/session')->addError($msg);
		}
 
		$this->_redirectReferer();

		return $this;
	}

	private function _sendTestContactFormEmail() 
	{
		
		$postObject = new Varien_Object();
		$postObject->setName("SMTPPro Tester");
		$postObject->setComment("If you get this email then everything seems to be in order.");
		$postObject->setEmail($this->TEST_EMAIL);
		
		$mailTemplate = Mage::getModel('core/email_template');
		/* @var $mailTemplate Mage_Core_Model_Email_Template */
		
		include Mage::getBaseDir() . '/app/code/core/Mage/Contacts/controllers/IndexController.php';
		
		$mailTemplate->setDesignConfig(array('area' => 'frontend'))
			->setReplyTo($postObject->getEmail())
			->sendTransactional(
				Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
				Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
				Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
				null,
				array('data' => $postObject)
			);

		return $this;
	}

} 
