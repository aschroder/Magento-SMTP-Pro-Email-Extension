<?php

/**
 * This is the Email Service Table
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Block_Adminhtml_Table
    extends Mage_Adminhtml_Block_System_Config_Form_Field {

   public function render(Varien_Data_Form_Element_Abstract $element) {
       // This is included dynamically so it can be updated from time to time without the need for extension updates.
       $html = '<iframe src="//smtppro-static.appspot.com/esp/esptable.html" style="border: none; height: 1000px; width: 100%;"></iframe>';
       return '<tr>' . $html . '</tr>';
    }

}
