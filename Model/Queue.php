<?php
class Aschroder_SMTPPro_Model_Queue extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('smtppro/queue');
    }
}