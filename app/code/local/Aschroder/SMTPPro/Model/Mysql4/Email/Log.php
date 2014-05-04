<?php
/**
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Mysql4_Email_Log extends Mage_Core_Model_Mysql4_Abstract {
    /**
     * Resource model initialization
     */
    protected function _construct() {
        $this->_init('smtppro/email_log', 'email_id');
    }

    /**
     * Clean up log table.
     * 
     * @param int|null $lifetime Lifetime of entries in days
     * @return Aschroder_SMTPPro_Model_Mysql4_Email_Log
     */
    public function clean($lifetime = null) {
        if (!Mage::helper('smtppro')->isLogCleaningEnabled()) {
            return $this;
        }
        if (is_null($lifetime)) {
            $lifetime = Mage::helper('smtppro')->getLogLifetimeDays();
        }
        $cleanTime = $this->formatDate(time() - $lifetime * 3600 * 24, false);

        $readAdapter    = $this->_getReadAdapter();
        $writeAdapter   = $this->_getWriteAdapter();

        while (true) {
            $select = $readAdapter->select()
                ->from(
                    $this->getMainTable(),
                    $this->getIdFieldName()
                )
                ->where('log_at < ?', $cleanTime)
                ->order('log_at ASC')
                ->limit(100);

            $logIds = $readAdapter->fetchCol($select);
            
            if (!$logIds) {
                break;
            }

            $condition = array($this->getIdFieldName() . ' IN (?)' => $logIds);

            // remove email log entries
            $writeAdapter->delete($this->getMainTable(), $condition);
        }
        
        return $this;
    }
}