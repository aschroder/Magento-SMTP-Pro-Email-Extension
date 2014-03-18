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
 
class Aschroder_SMTPPro_Model_Newsletter_Template extends Mage_Newsletter_Model_Template 
{
	
    /**
     * Send mail to subscriber
     *
     * @param   Mage_Newsletter_Model_Subscriber|string   $subscriber   subscriber Model or E-mail
     * @param   array                                     $variables    template variables
     * @param   string|null                               $name         receiver name (if subscriber model not specified)
     * @param   Mage_Newsletter_Model_Queue|null          $queue        queue model, used for problems reporting.
     * @return boolean
     * @deprecated since 1.4.0.1
     **/
    public function send($subscriber, array $variables = array(), $name=null, Mage_Newsletter_Model_Queue $queue=null)
    {

        // Not used in 1.4.0.1+ installs - so this just calls the parent class.
        return parent::send($subscriber, $variables, $name, $queue);
    }
    
}