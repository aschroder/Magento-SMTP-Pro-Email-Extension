<?php
/**
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Mysql4_Email_Log_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	
    protected function _construct() {
        $this->_init('smtppro/email_log');
    }
}