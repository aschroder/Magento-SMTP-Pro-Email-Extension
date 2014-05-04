<?php
/**
 * 
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Email_Log extends Mage_Core_Model_Abstract
{
    
    /**
     * Model initialization
     *
     */
    protected function _construct()
    {
        $this->_init('smtppro/email_log');
    }
    
    /**
     * clean up log table
     * 
     * @param int|null $lifetime Lifetime of entries in days
     */
    public function clean($lifetime = null)
    {
        $this->getResource()->clean($lifetime);
        return $this;
    }
}