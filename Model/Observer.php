<?php
/**
 * Observer that logs emails after they have been sent
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Observer {
	
	public function log($observer) {
		
		$event = $observer->getEvent();
		if (Mage::helper('smtppro')->isLogEnabled()) {
			
				Mage::helper('smtppro')->log(
				$event->getTo(),
				$event->getTemplate(),
				$event->getSubject(),
				$event->getEmailBody(),
				$event->getHtml());
		}
		
		// For the self test, if we're sending the contact form notify the self test class
		Mage::log("template=" . $event->getTemplate());
		if($event->getTemplate() == "contacts_email_email_template") {
			include_once Mage::getBaseDir() . "/app/code/community/Aschroder/SMTPPro/controllers/IndexController.php";
			Aschroder_SMTPPro_IndexController::$CONTACTFORM_SENT = true;
		}
		
	}
	
}
