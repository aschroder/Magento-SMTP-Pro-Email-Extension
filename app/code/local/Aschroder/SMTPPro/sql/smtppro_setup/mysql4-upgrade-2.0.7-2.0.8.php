<?php

$installer = $this;

$installer->startSetup();

//create template for email notification
$code = 'SMTP PRO - Notification';
$subject = '{{var store.getFrontendName()}}: SMTP PRO Notification error in send email';
$text = '    
    Email notification Alert <br/>
    <br/>
    Attention: there is an email in queue with an error.
    <br/><br/>
    Info for email with error:<br/>
    Recipient: {{var email_recipient}} <br/>
    Type of email: {{var email_type}} <br/>
    Subjet of email with error: {{var email_subject }}<br/>
    <br/>
    Error generated: {{var message_error}}    
';

$template = Mage::getModel('adminhtml/email_template');

$template->setTemplateSubject($subject)
    ->setTemplateCode($code)
    ->setTemplateText($text)
    ->setModifiedAt(Mage::getSingleton('core/date')->gmtDate())
    ->setAddedAt(Mage::getSingleton('core/date')->gmtDate())
    ->setTemplateType(Mage_Core_Model_Email_Template::TYPE_HTML);

$template->save();

//Alter table core_email_queue adding column failed for identifier with email generate error
Mage::helper('smtppro/mysql4_install')->attemptQuery($installer, "
    ALTER TABLE `{$this->getTable('core_email_queue')}` 
        ADD COLUMN `failed` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `processed_at`;");


$installer->endSetup();