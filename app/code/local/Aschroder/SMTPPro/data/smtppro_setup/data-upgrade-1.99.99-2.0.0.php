<?php

/**
 * Migrate AWS Keys from old to new configuration paths
 */

/** @var Aschroder_SMTPPro_Model_Mysql4_Setup $installer */


$installer = $this;

$installer->startSetup();

$emailOptionOldPath = 'system/smtppro/option';
$emailOptionNewPath = 'smtppro/general/option';

$awsAccessKeyOldPath = 'system/sessettings/aws_access_key';
$awsAccessKeyNewPath = 'smtppro/general/ses_access_key';

$awsPrivateKeyOldPath = 'system/sessettings/aws_private_key';
$awsPrivateKeyNewPath = 'smtppro/general/ses_private_key';

$emailOption = Mage::getStoreConfig($emailOptionOldPath, 0);
$installer->setConfigData($emailOptionNewPath, $emailOption);
$installer->deleteConfigData($emailOptionOldPath);

$awsAccessKey = Mage::getStoreConfig($awsAccessKeyOldPath, 0);
$installer->setConfigData($awsAccessKeyNewPath, $awsAccessKey);
$installer->deleteConfigData($awsAccessKeyOldPath);

$awsPrivateKey = Mage::getStoreConfig($awsPrivateKeyOldPath, 0);
$installer->setConfigData($awsPrivateKeyNewPath, Mage::helper('core')->encrypt($awsPrivateKey));
$installer->deleteConfigData($awsPrivateKeyOldPath);

Mage::app()->cleanCache(array(Mage_Core_Model_Config::CACHE_TAG));

$installer->endSetup();
