<?php
/**
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Ashley Schroder
 */

class Aschroder_SMTPPro_Model_System_Config_Source_Smtp_Authentication
{
	
	    public function toOptionArray()
    {
    	// There are 3 possibilities: PLAIN, LOGIN and CRAM-MD5, plus no authentication
    	// http://framework.zend.com/manual/en/zend.mail.smtp-authentication.html
        return array(
        	"none"   => Mage::helper('adminhtml')->__('None'),
            "login"   => Mage::helper('adminhtml')->__('Login'),
            "plain"   => Mage::helper('adminhtml')->__('Plain'),
            "crammd5"   => Mage::helper('adminhtml')->__('CRAM-MD5')
        );
    }
}