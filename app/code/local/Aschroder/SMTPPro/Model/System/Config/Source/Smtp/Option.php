<?php
/**
 * @copyright  Copyright (c) 2009 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Ashley Schroder
 */

class Aschroder_SMTPPro_Model_System_Config_Source_Smtp_Option extends Varien_Object
{
	
    public function toOptionArray()
    {
        $options = array(
        	"disabled"   => Mage::helper('smtppro')->__('Disabled'),
            "google"   => Mage::helper('smtppro')->__('Google Apps/Gmail'),
            "smtp"   => Mage::helper('smtppro')->__('Advanced SMTP'),
            "ses"   => Mage::helper('smtppro')->__('Amazon SES')
        );
        return $options;
    }
}