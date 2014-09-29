<?php
/**
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Block_Log extends Mage_Adminhtml_Block_Widget_Grid_Container {
	
    /**
     * Block constructor
     */
    public function __construct() {
    	$this->_blockGroup = 'smtppro';
        $this->_controller = 'log';
        $this->_headerText = Mage::helper('cms')->__('Email Log');
        parent::__construct();
        
        // Remove the add button
        $this->_removeButton('add');
    }

}
