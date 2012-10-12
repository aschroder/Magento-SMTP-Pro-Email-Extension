<?php
/**
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


$installer = $this;

$installer->startSetup();

Mage::log("Running installer Aschroder_SMTPPro upgrade to 1.4.4");

$installer->run("
DROP TABLE IF EXISTS `{$this->getTable('smtppro_async_queue')}`;
CREATE TABLE `{$this->getTable('smtppro_async_queue')}` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `params` longtext NOT NULL,
  `status` varchar(255) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
