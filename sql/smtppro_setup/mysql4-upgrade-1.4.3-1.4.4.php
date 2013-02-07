<?php
/**
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @author  Paul Hachmang (@paales)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


$installer = $this;

$installer->startSetup();

Mage::log("Running installer");

$installer->run("ALTER TABLE `{$this->getTable('smtppro_email_log')}` CHANGE `to` `email_to` VARCHAR(255)  NOT NULL  DEFAULT '';");

$installer->endSetup();