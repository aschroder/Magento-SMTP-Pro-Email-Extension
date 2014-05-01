<?php

/**
 * Test the Email sending and configuration integrity
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 ASchroder Consulting Ltd
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Aschroder_SMTPPro_TestController extends Mage_Adminhtml_Controller_Action 
{

    // We use the contacts form template as a test template
    const XML_PATH_TEST_TEMPLATE   = 'contacts/email/email_template';

    /**
     * @todo change this
     * @var string
     */
    protected $TEST_EMAIL = "test@example.com";


    protected $EXPECTED_REWRITE_CLASSES = array(
        "email_rewrite" => "Aschroder_SMTPPro_Model_Email",
        "email_template_rewrite" => "Aschroder_SMTPPro_Model_Email_Template"
    );

    protected $KNOWN_ERRORS = array(
        "/Email address is not verified/" => "Either your sending or receiving email address is not verified.<br/>Please check your Amazon SES console and confirm the email addresses are verified and the region matches.",
        "/RequestExpired/" => "Check the date and time on your server is set correctly.<br/>Amazon's servers think your server time is more than 5 minutes different to their time and are rejecting the request.",
        "/Request is missing Authentication Token/" => "Check that you have an access key and secret key."
    );

    public function indexAction()
    {
        $_helper = Mage::helper('smtppro');

        $_helper->log("Running SMTPPro Self Test");

        $success = true;
        $websiteModel = Mage::app()->getWebsite($this->getRequest()->getParam('website'));
        $this->TEST_EMAIL = Mage::getStoreConfig('trans_email/ident_general/email', $websiteModel->getId());

        $msg = "SMTPPro Self Test Results";

        if (!$_helper->isEnabled()) {
            $msg = $msg . "<br/> Extension disabled, cannot run test.";
            $_helper->log("skipped, disabled.");
            Mage::getSingleton('adminhtml/session')->addError($msg);
            $this->_redirectReferer();
            return;
        }

        // check the re-writes have not clashed
        $_helper->log("Checking config re-writes have not clashed.");

        $email_rewrite = "".Mage::getConfig()->getNode('global/models/core/rewrite/email');
        $email_template_rewrite = "".Mage::getConfig()->getNode('global/models/core/rewrite/email_template');

        if ($this->checkRewrite($this->EXPECTED_REWRITE_CLASSES["email_rewrite"], $email_rewrite)) {
            $success = false;
            $msg = $msg . "<br/> Detected overwrite conflict: $email_rewrite";
            $_helper->log("Detected overwrite conflict: $email_rewrite");
        }
        if ($this->checkRewrite($this->EXPECTED_REWRITE_CLASSES["email_template_rewrite"], $email_template_rewrite)) {
            $success = false;
            $msg = $msg . "<br/> Detected overwrite conflict: $email_template_rewrite";
            $_helper->log("Detected overwrite conflict: $email_template_rewrite");
        }

        // Hosts often block SMTP outbound connections, so we check for that here

        $transport = $_helper->getTransport($websiteModel->getId());

        if (is_subclass_of($transport, 'Aschroder_SMTPPro_Model_Transports_Basesmtp')) {
            $_helper->log("Raw connection test for SMTP options.");
            $fp = false;

            try {
                $fp = fsockopen($transport->getHost(), $transport->getPort(), $errno, $errstr, 15);
            } catch ( Exception $e) {
                // An error will be reported below.
            }

            Mage::helper('smtppro')->log("Complete");

            if (!$fp) {
                $success = false;
                $_helper->log("Failed to connect to SMTP server. Reason: " . $errstr . "(" . $errno . ")");
                $msg = $msg . "<br/>Failed to connect to SMTP server. Reason: " . $errstr . "(" . $errno . ")";
                $msg = $msg . "<br/> This extension requires an outbound SMTP connection on port: " . $transport->getPort();
            } else {
                $_helper->log("Connection to Host SMTP server successful");
                $msg = $msg . "<br/> Connection to Host SMTP server successful.";
                fclose($fp);
            }
        } else {
            $_helper->log("Skipping raw connection test for non-SMTP options.");
        }

        $to = Mage::getStoreConfig('contacts/email/recipient_email', $websiteModel->getId());

        $mail = new Zend_Mail();
        $sub = "Test Email From SMTPPro Magento Extension";
        $body =
                "Hi,\n\n" .
                "This is a Test Email from your Magento Store. If you are seeing this email then your " .
                "SMTPPro settings are correct! \n\n" .
                "For more information about this extension and " .
                "tips for using it please visit magesmtppro.com or contact me, support@aschroder.com.\n\n" .
                "Regards,\nAshley";

        $mail->addTo($to)
                ->setFrom($this->TEST_EMAIL)
                ->setSubject($sub)
                ->setBodyText($body);

        $_helper->log("Actual email sending test....");
        $msg = $msg . "<br/> Sending test email to your contact form address: " . $to . " from: " . $this->TEST_EMAIL;

        try {

            $transport = new Varien_Object(); // for observers to set if required
            Mage::dispatchEvent('aschroder_smtppro_before_send', array(
                'mail' => $mail,
                'email' => $this,
                'transport' => $transport
            ));

            $emailTransport = $transport->getTransport();

            if (!empty($emailTransport)) {

                $mail->send($emailTransport);

                Mage::dispatchEvent('aschroder_smtppro_after_send', array('to' => $to,
                    'template' => "Email Self Test",
                    'subject' => $sub,
                    'html' => false,
                    'email_body' => $body));

                $msg = $msg . "<br/> Test email was sent successfully.";
                $_helper->log("Test email was sent successfully");

            } else {
                $success = false;
                $_helper->log("Failed to find transport for test....");
                $msg = $msg . "<br/> Failed to find transport for test. ";
            }

        } catch (Exception $e) {
            $success = false;
            $msg = $msg . "<br/> Unable to send test email.";
            if ($help = $this->knowError($e->getMessage())) {
                $msg = $msg . "<br/> " . $help;
            } else {
                $msg = $msg . "<br/> Exception message was: " . $e->getMessage() . "...";
                $msg = $msg . "<br/> Please check the user guide for frequent error messages and their solutions.";
            }
            $_helper->log("Test email was not sent successfully: " . $e->getMessage() . "\n See exception log for more details.");
            Mage::logException($e);
        }

        $_helper->log("Checking that a template exists for the default locale and that email communications are enabled....");

        try {

            $mailTemplate = Mage::getModel('core/email_template');
            $testTemplateId = Mage::getStoreConfig(self::XML_PATH_TEST_TEMPLATE);

            if (is_numeric($testTemplateId)) {
                $mailTemplate->load($testTemplateId);
            } else {
                $localeCode = Mage::getStoreConfig('general/locale/code');
                $mailTemplate->loadDefault($testTemplateId, $localeCode);
            }

            $mailTemplate->setSenderName("Test Name");
            $mailTemplate->setSenderEmail("test@email.com");

            if ($mailTemplate->isValidForSend()) {
                $msg = $msg . "<br/> Default templates exist.";
                $msg = $msg . "<br/> Email communications are enabled.";
                $_helper->log("Default templates exist and email communications are enabled.");
            } else {
                $success = false;
                $msg = $msg . "<br/> Could not find default template, or template not valid, or email communications disabled in Advanced > System settings.";
                $msg = $msg . "<br/> Please check that you have templates in place for your emails. These are in app/locale, or custom defined in System > Transaction Emails. Also check Advanced > System settings to ensure email communications are enabled.";
                $_helper->log("Could not find default template, or template not valid, or email communications disabled in Advanced > System settings");
            }

        } catch (Exception $e) {

            $success = false;
            $msg = $msg . "<br/> Could not test default template validity.";
            $msg = $msg . "<br/> Exception message was: " . $e->getMessage() . "...";
            $msg = $msg . "<br/> Please check that you have templates in place for your emails. These are in app/locale, or custom defined in System > Transaction Emails.";
            $_helper->log("Could not test default template validity: " . $e->getMessage());
        }

        $_helper->log("Checking that tables are created....");

        try {

            $logName = Mage::getSingleton('core/resource')->getTableName("smtppro_email_log");
            $logExists = Mage::getSingleton('core/resource')->getConnection('core_read')->isTableExists($logName);

            if (!$logExists) {
                $success = false;
                $msg = $msg . "<br/> Could not find required database tables.";
                $msg = $msg . "<br/> Please try to manually re-run the table creation script. For assistance please contact us.";
                $_helper->log("Could not find required tables.");
            } else {
                $msg = $msg . "<br/> Required database tables exist.";
                $_helper->log("Required database tables exist.");
            }

        } catch (Exception $e) {

            $success = false;
            $msg = $msg . "<br/> Could not find required database tables.";
            $msg = $msg . "<br/> Exception message was: " . $e->getMessage() . "...";
            $msg = $msg . "<br/> Please try to manually re-run the table creation script. For assistance please contact us.";
            $_helper->log("Could not find required tables: " . $e->getMessage());
        }


        $_helper->log("Complete");

        if ($success) {
            $msg = $msg . "<br/> Testing complete, if you are still experiencing difficulties please visit <a target='_blank' href='http://magesmtppro.com'>the support page</a> or contact me via <a target='_blank' href='mailto:support@aschroder.com'>support@aschroder.com</a> for support.";
            Mage::getSingleton('adminhtml/session')->addSuccess($msg);
        } else {
            $msg = $msg . "<br/> Testing failed,  please review the reported problems and if you need further help visit  <a target='_blank' href='http://magesmtppro.com'>the support page</a> or contact me via <a target='_blank' href='mailto:support@aschroder.com'>support@aschroder.com</a> for support.";
            Mage::getSingleton('adminhtml/session')->addError($msg);
        }

        $this->_redirectReferer();
    }

    private function knowError($message) {

        foreach($this->KNOWN_ERRORS as $known => $help) {
            if (preg_match($known, $message)) {
                return $help;
            }
        }
        return false;
    }

    private function checkRewrite($expected, $actual) {

        return $expected != $actual &&
            !is_subclass_of($actual, $expected);
    }
}

