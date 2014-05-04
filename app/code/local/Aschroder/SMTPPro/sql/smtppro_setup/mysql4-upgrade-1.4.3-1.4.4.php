<?php
/**
 *
 * @author  Paul Hachmang (@paales)
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


$installer = $this;

$installer->startSetup();

Mage::helper('smtppro/mysql4_install')->attemptQuery($installer, "
    ALTER TABLE `{$this->getTable('smtppro_email_log')}` 
        CHANGE `to` `email_to` VARCHAR(255)  NOT NULL  DEFAULT '';
");

$installer->endSetup();