<?php
/**
 * Observer that logs emails after they have been sent
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Observer extends Varien_Object
{
	
	public function log($observer)
	{
		
		$event = $observer->getEvent();
		if (Mage::helper('smtppro')->isLogEnabled()) {
			Mage::helper('smtppro')->log(
				$event->getTo(),
				$event->getTemplate(),
				$event->getSubject(),
				$event->getEmailBody(),
				$event->getHtml()
			);
		}

		Mage::helper('smtppro')->log("*Observer was triggered: ");

		// For the self test, if we're sending the contact form notify the self test class
		if(!Mage::registry('smtppro_email_sent')) {
			Mage::register('smtppro_email_sent', true);
		}
		
		return $this;
	}
	
	public function cleanOldLogs(Varien_Event_Observer $observer) {
	    $log = Mage::getModel('smtppro/email_log')->clean();
	}
	
}
