<?php

/**
 * This class wraps the Template email sending functionality
 * If SMTP Pro is enabled it will send emails using the given 
 * configuration.
 *
 * @author Ashley Schroder (aschroder.com)
 */
 
class Aschroder_SMTPPro_Model_Email_Template extends Mage_Core_Model_Email_Template {
	
    public function send($email, $name=null, array $variables = array()) {
    	
    	// If it's not enabled, just return the parent result.
    	if (!Mage::helper('smtppro')->isEnabled()) {
        	 return parent::send($email, $name, $variables);
		} 

		Mage::log('SMTPPro is enabled, sending email in Aschroder_SMTPPro_Model_Email_Template');    	
   	
    	
    	// The remainder of this function closely mirrors the parent
    	// method except for providing the SMTP auth details from the 
    	// configuration. This is not good OO, but the parent class 
    	// leaves little room for useful subclassing. This will probably 
    	// become redundant sooner or later anyway. 		
    	
    	if(!$this->isValidForSend()) {
			Mage::log('SMTPPro: Email not valid for sending - check template, and smtp enabled/disabled setting');    	
    		Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
            return false;
        }

        if (is_null($name)) {
            $name = substr($email, 0, strpos($email, '@'));
        }

        $variables['email'] = $email;
        $variables['name'] = $name;

        $mail = $this->getMail();
        
       	$dev = Mage::helper('smtppro')->getDevelopmentMode();
       	
        if ($dev == "contact") {
        	
			$email = Mage::getStoreConfig('contacts/email/recipient_email', $this->getDesignConfig()->getStore());
			Mage::log("Development mode set to send all emails to contact form recipient: " . $email);
			
        } elseif ($dev == "supress") {
        	
			Mage::log("Development mode set to supress all emails.");
			# we bail out, but report success
        	return true;
        }
        
        if (is_array($email)) {
            foreach ($email as $emailOne) {
                $mail->addTo($emailOne, $name);
            }
        } else {
            $mail->addTo($email, '=?utf-8?B?'.base64_encode($name).'?=');
        }
        

        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);

        if($this->isPlain()) {
            $mail->setBodyText($text);
        } else {
            $mail->setBodyHTML($text);
        }

        $mail->setSubject('=?utf-8?B?'.base64_encode($this->getProcessedTemplateSubject($variables)).'?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

		// If we are using store emails as reply-to's set the header
		// Check the header is not already set by the application.
		// The contact form, for example, set's it to the sender of 
		// the contact. Thanks i960 for pointing this out.

        if (Mage::helper('smtppro')->isReplyToStoreEmail()
			&& !array_key_exists('Reply-To', $mail->getHeaders())) {

        	$mail->addHeader('Reply-To', $this->getSenderEmail());
			Mage::log('ReplyToStoreEmail is enabled, just set Reply-To header: ' . $this->getSenderEmail());
        }

		$transport = Mage::helper('smtppro')->getTransport($this->getDesignConfig()->getStore());
		
        try {
		    
        	Mage::log('About to send email');
	        $mail->send($transport); // Zend_Mail warning..
		    Mage::log('Finished sending email');
		    
			Mage::dispatchEvent('smtppro_email_after_send', 
				 array('to' => $email,
					 'template' => $this->getTemplateId(),
					 'subject' => $this->getProcessedTemplateSubject($variables),
					 'html' => !$this->isPlain(),
					 'email_body' => $text));
			 	
	        $this->_mail = null;
        }
        catch (Exception $e) {
            return false;
        }

        return true;
    }
}
