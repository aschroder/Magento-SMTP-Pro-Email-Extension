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

		return $this;
	}
	
	public function cleanOldLogs(Varien_Event_Observer $observer)
	{
		$lifetime = Mage::helper('smtppro/config')->getLogLifetimeDays();
	    $log = Mage::getModel('smtppro/email_log')->clean($lifetime);
	    return $this;
	}
	
}
