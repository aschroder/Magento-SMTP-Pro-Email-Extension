<?php

/**
 * Various Helper functions for the Aschroder.com SMTP Pro module.
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Helper_Data extends Mage_Core_Helper_Abstract
{

    const LOG_FILE = 'aschroder_smtppro.log';

    public function getTransport($storeId = null)
    {

        $option = Mage::getStoreConfig('smtppro/general/option', $storeId);
        return Mage::getModel("smtppro/transports_$option")->getTransport($storeId);
    }


    public function log($m)
    {
        if ($this->isDebugLoggingEnabled()) {
            Mage::log($m, null, self::LOG_FILE);
        }
    }

    public function logEmailSent($to, $template, $subject, $email, $isHtml)
    {
        if ($this->isLogEnabled()) {
            $log = Mage::getModel('smtppro/email_log')
                ->setEmailTo($to)
                ->setTemplate($template)
                ->setSubject($subject)
                ->setEmailBody($isHtml ? $email : nl2br($email))
                ->save();
        }
        return $this;
    }

    // General config
    public function isEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) != "disabled";
    }

    public function isGoogleAppsEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) == "google";
    }

    public function isAmazonSESEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) == "ses";
    }

    public function isSMTPEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) == "smtp";
    }

    public function isSendGridEnabled($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/option', $storeId) == "sendgrid";
    }


    // logging config
    public function isLogCleaningEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/debug/cleanlog', $storeId);
    }

    public function getLogLifetimeDays($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/debug/cleanlog_after_days', $storeId);
    }

    public function isLogEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/debug/logenabled', $storeId);
    }

    public function isDebugLoggingEnabled($storeId = null)
    {
        return Mage::getStoreConfigFlag('smtppro/debug/log_debug', $storeId);
    }

    // transport config
    public function getAmazonSESAccessKey($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/ses_access_key', $storeId);
    }

    public function getAmazonSESPrivateKey($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/ses_private_key', $storeId);
    }

    public function getAmazonSESRegion($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/ses_region', $storeId);
    }

    public function getGoogleAppsEmail($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/googleapps_email', $storeId);
    }

    public function getGoogleAppsPassword($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/googleapps_gpassword', $storeId);
    }


    public function getSendGridEmail($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/sendgrid_email', $storeId);
    }

    public function getSendGridPassword($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/sendgrid_password', $storeId);
    }

    public function getMailUpUsername($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/mailup_email', $storeId);
    }

    public function getMailUpPassword($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/mailup_password', $storeId);
    }

    public function getSMTPSettingsHost($storeId = null)
    {
        return trim(Mage::getStoreConfig('smtppro/general/smtp_host', $storeId));
    }

    public function getSMTPSettingsPort($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_port', $storeId);
    }

    public function getSMTPSettingsUsername($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_username', $storeId);
    }

    public function getSMTPSettingsPassword($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_password', $storeId);
    }

    public function getSMTPSettingsSSL($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_ssl', $storeId);
    }

    public function getSMTPSettingsAuthentication($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/general/smtp_authentication', $storeId);
    }

    public function getQueueUsage($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/queue/usage', $storeId);
    }
    public function isQueueBypassed($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/queue/usage', $storeId) == "never";
    }
    public function getQueuePerCron($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/queue/percron', $storeId);
    }
    public function getQueuePause($storeId = null)
    {
        return Mage::getStoreConfig('smtppro/queue/pause', $storeId);
    }


    // These are not the droids you're looking for...

    // Keeping this function for backward compatibility
    // It will be dropped eventually so call getTransport() from now on!
    public function getSMTPProTransport($id = null)
    {
        return $this->getTransport($id);
    }

    // Keeping this function for backward compatibility
    // It will be dropped eventually so call getTransport() from now on!
    public function getGoogleAppsEmailTransport($id = null)
    {
        return $this->getTransport($id);
    }

}
