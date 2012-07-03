<?php
/**
 * @copyright  Copyright (c) 2009 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Ashley Schroder
 */

class Aschroder_SMTPPro_Model_System_Config_Source_Smtp_Option
{
	
	    public function toOptionArray()
    {
        return array(
        	"disabled"   => Mage::helper('adminhtml')->__('Disabled'),
            "google"   => Mage::helper('adminhtml')->__('Google Apps/Gmail'),
            "smtp"   => Mage::helper('adminhtml')->__('SMTP'),
            "ses"   => Mage::helper('adminhtml')->__('SES (experimental)')
        );
    }
}