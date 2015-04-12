<?php

/**
 * Queue usage options
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2013 ASchroder Consulting Ltd
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_System_Config_Source_Smtp_Queue
{

    public function toOptionArray()
    {
        return array(
            'default' => Mage::helper('adminhtml')->__('Default'),
            'never' => Mage::helper('adminhtml')->__('Never'),
        );
    }
}