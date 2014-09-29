<?php
/**
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_System_Config_Source_Smtp_Option extends Varien_Object
{
	
    public function toOptionArray()
    {
        $options = array(
        	"disabled"   => Mage::helper('smtppro')->__('Disabled'),
            "google"   => Mage::helper('smtppro')->__('Google Apps or Gmail'),
            "smtp"   => Mage::helper('smtppro')->__('Custom SMTP'),
            "sendgrid"   => Mage::helper('smtppro')->__('SendGrid'),
            "ses"   => Mage::helper('smtppro')->__('Amazon SES')
        );
        return $options;
    }
}