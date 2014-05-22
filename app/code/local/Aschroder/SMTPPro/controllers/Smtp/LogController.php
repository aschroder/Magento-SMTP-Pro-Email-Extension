<?php

/**
 * Controller for the Log viewing operations
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class Aschroder_SMTPPro_Smtp_LogController
	extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
            ->_setActiveMenu('system/tools')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('System'), Mage::helper('adminhtml')->__('System'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Tools'), Mage::helper('adminhtml')->__('Tools'))
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Email Log'), Mage::helper('adminhtml')->__('Email Log'));
        return $this;
    }	
		
	public function indexAction() {
		
		  $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('smtppro/log'))
            ->renderLayout();
		
	}	
	
	public function viewAction() {
		
		  $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('smtppro/log_view'))
            ->renderLayout();
            
		
	}	
	
} 
