<?php

/**
 * This class wraps the Newsletter email sending functionality
 * 
 * If SMTPPro is enabled it will send emails using the given 
 * configuration.
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Aschroder_SMTPPro_Model_Newsletter_Template
	extends Mage_Newsletter_Model_Template {
	
	
	 /**
     * Send mail to subscriber
     *
     * @param   Mage_Newsletter_Model_Subscriber|string   $subscriber   subscriber Model or E-mail
     * @param   array                                     $variables    template variables
     * @param   string|null                               $name         receiver name (if subscriber model not specified)
     * @param   Mage_Newsletter_Model_Queue|null          $queue        queue model, used for problems reporting.
     * @return boolean
     **/
    public function send($subscriber, array $variables = array(), $name=null, Mage_Newsletter_Model_Queue $queue=null)
    {
        if(!$this->isValidForSend()) {
            return false;
        }
        
        // If it's not enabled, just return the parent result.
    	if (!Mage::helper('smtppro')->isEnabled()) {
        	 return parent::send($subscriber, $variables, $name, $queue);
		} 

		Mage::log('SMTPPro is enabled, sending email in Aschroder_SMTPPro_Model_Newsletter_Template'); 
        

        $email = '';
        if ($subscriber instanceof Mage_Newsletter_Model_Subscriber) {
            $email = $subscriber->getSubscriberEmail();
            if (is_null($name) && ($subscriber->hasCustomerFirstname() || $subscriber->hasCustomerLastname()) ) {
                $name = $subscriber->getCustomerFirstname() . ' ' . $subscriber->getCustomerLastname();
            }
        } else {
            $email = (string) $subscriber;
        }

		$mail = $this->getMail();

        if (Mage::getStoreConfigFlag(Mage_Newsletter_Model_Subscriber::XML_PATH_SENDING_SET_RETURN_PATH)) {
        	// This is important for SPAM I think, what value should it be?
            $mail->setReturnPath($this->getTemplateSenderEmail());
        }

        $transport = Mage::helper('smtppro')->getTransport();
        
        $dev = Mage::helper('smtppro')->getDevelopmentMode();
       	
        if ($dev == "contact") {
        	
			$email = Mage::getStoreConfig('contacts/email/recipient_email');
			Mage::log("Development mode set to send all emails to contact form recipient: " . $email);
			
        } elseif ($dev == "supress") {
        	
			Mage::log("Development mode set to supress all emails.");
			# we bail out, but report success
        	return true;
        }

        $mail->addTo($email, $name);
        $text = $this->getProcessedTemplate($variables, true);

        if($this->isPlain()) {
            $mail->setBodyText($text);
        } else {
            $mail->setBodyHTML($text);
        }

        $mail->setSubject($this->getProcessedTemplateSubject($variables));
        $mail->setFrom($this->getTemplateSenderEmail(), $this->getTemplateSenderName());
        
         // If we are using store emails as reply-to's set the header
        if (Mage::helper('smtppro')->isReplyToStoreEmail()) {
        	// Later versions of Zend have a method for this, and disallow direct header setting...
			if (method_exists($mail, "setReplyTo")) {
				$mail->setReplyTo($this->getTemplateSenderEmail(), $this->getTemplateSenderName());
			} else {
	        	$mail->addHeader('Reply-To', $this->getTemplateSenderEmail());
			}
			Mage::log('ReplyToStoreEmail is enabled, just set Reply-To header: ' . $this->getTemplateSenderEmail());
        }

        try {
        	
        	Mage::log('About to send email');
        	$mail->send($transport);
			Mage::log('Finished sending email');
			
			 Mage::dispatchEvent('smtppro_email_after_send', 
			 	array('to' => $email,
			 			'template' => $this->getTemplateId(),
			 			'subject' => $mail->getSubject(),
			 			'html' => !$this->isPlain(),
			 			'email_body' => $text));
				
			
            $this->_mail = null;
            if(!is_null($queue)) {
                $subscriber->received($queue);
            }
        }
        catch (Exception $e) {
            if($subscriber instanceof Mage_Newsletter_Model_Subscriber) {
                // If letter sent for subscriber, we create a problem report entry
                $problem = Mage::getModel('newsletter/problem');
                $problem->addSubscriberData($subscriber);
                if(!is_null($queue)) {
                    $problem->addQueueData($queue);
                }
                $problem->addErrorData($e);
                $problem->save();

                if(!is_null($queue)) {
                    $subscriber->received($queue);
                }
            } else {
                // Otherwise throw error to upper level
                throw $e;
            }
            return false;
        }

        return true;
    }
    
}