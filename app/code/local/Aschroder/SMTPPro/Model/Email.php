<?php
/**
 * This class wraps the Basic email sending functionality
 * If SMTPPro is enabled it will send emails using the given
 * configuration.
 *
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Email extends Mage_Core_Model_Email 
{
    public function send()
    {
    	try {

	        // If it's not enabled, just return the parent result.
	        if (!Mage::helper('smtppro/config')->isEnabled()) {
	            return parent::send();
	        }

	        if (Mage::helper('smtppro/config')->isSystemSMTPDisabled($storeId)) {
	            return $this;
	        }

	        $mail = new Zend_Mail();

	        if (strtolower($this->getType()) == 'html') {
	            $mail->setBodyHtml($this->getBody());
	        } else {
	            $mail->setBodyText($this->getBody());
	        }

	        $mail->setFrom($this->getFromEmail(), $this->getFromName())
	            ->addTo($this->getToEmail(), $this->getToName())
	            ->setSubject($this->getSubject());

	        $transport = new Varien_Object(); // for observers to set if required
	        Mage::dispatchEvent('aschoder_smtppro_before_send', array(
	            'mail' => $mail,
	            'email' => $this,
	            'transport' => $transport
	        ));

	        $emailTransport = $transport->getEmailTransport();
	        if (!empty($emailTransport)) {
	            $mail->send($emailTransport);
	        } else {
	            $mail->send();
	        }

	        Mage::dispatchEvent('aschoder_smtppro_after_send', array(
	            'to' => $this->getToName(),
	            'subject' => $this->getSubject(),
	            'template' => "n/a",
	            'html' => (strtolower($this->getType()) == 'html'),
	            'email_body' => $this->getBody()
            ));

	    } catch(Exception $e) {
            Mage::helper('smtppro/debug')->log("WARNING: " . $e->getMessage());
            Mage::logException($e);
            return false;
	    }
        return $this;
    }

}
