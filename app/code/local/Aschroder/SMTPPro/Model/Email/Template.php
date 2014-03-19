<?php

/**
 * This class wraps the Template email sending functionality
 * If SMTP Pro is enabled it will send emails using the given 
 * configuration.
 *
 * @author Ashley Schroder (aschroder.com)
 */
 
class Aschroder_SMTPPro_Model_Email_Template extends Mage_Core_Model_Email_Template
{
	
    public function send($email, $name = null, array $variables = array())
    {
        $_helper = Mage::helper('smtppro');

        try {
            // If it's not enabled, just return the parent result.
            if (!$_helper->isEnabled()) {
                $_helper->log('SMTP Pro is not enabled, fall back to parent class'); // commenting this because we don't want to flood the log if the store is not using the extension any more.
                return parent::send($email, $name, $variables);
            }

            $_helper->log("SMTP Pro is sending an email to recipients...");

            // As per parent class - except addition of before and after send events
            if (!$this->isValidForSend()) {
                $_helper->log('Email is not valid for sending, this is a core error that often means there\'s a problem with your email templates.');
                Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
                return false;
            }

            $emails = array_values((array)$email);
            $names = $this->_buildNames($emails, $name);

            $variables['email'] = reset($emails);
            $variables['name'] = reset($names);

            $_helper->log("Sending variables: ". print_r($variables['email'], true));
            $_helper->log("Sending variables: ". print_r($variables['name'], true));

            $this->_updateTemplateStoreId($variables);

            $this->_updateSMTPSettings();

            $mail = $this->getMail();

            $this->_updateReturnPath();

            foreach ($emails as $key => $email) {
                $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
            }

            $this->setUseAbsoluteLinks(true);
            $text = $this->getProcessedTemplate($variables, true);

            if($this->isPlain()) {
                $mail->setBodyText($text);
            } else {
                $mail->setBodyHTML($text);
            }

            $mail->setSubject('=?utf-8?B?' . base64_encode($this->getProcessedTemplateSubject($variables)) . '?=');
            $mail->setFrom($this->getSenderEmail(), $this->getSenderName());


            $this->_transportEmail($emails, $mail, $variables, $text);

            $_helper->log("SMTP Pro email sending process complete.");
            
        } catch (Exception $e) {
            $this->_mail = null;
            $_helper->log("EXCEPTION OCCURRED: " . $e->getMessage());
            Mage::logException($e);
            return false;
        }

        return true;
    }

    /**
     * Updates the template local data with the store ID it's supposed to use based on the template variables or current design config.
     * @return  $this
     */
    protected function _updateTemplateStoreId($variables)
    {
        $storeId = null;
        if(isset($variables['store'])) {
            $storeId = $variables['store'];
        } else {
            $storeId = $this->getDesignConfig()->getStore();
        }
        $this->setStoreId($storeId);
        return $this;
    }

    /**
     * Updates the transport system's return email path according to config or message settings.
     * @return $this
     */
    protected function _updateReturnPath()
    {
        $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
        switch ($setReturnPath) {
            case 1:
                $returnPathEmail = $this->getSenderEmail();
                break;
            case 2:
                $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                break;
            default:
                $returnPathEmail = null;
                break;
        }

        // Don't change the return path.
        // if ($returnPathEmail == null) {
        //     return $this;
        // }

        // Update the return path 
        $mailTransport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
        Zend_Mail::setDefaultTransport($mailTransport);

        return $this;
    }

    /**
     * @throws Exception if failed to send email.
     * @param  array $emails    
     * @param  Mage_Core_Model_Email $mail      
     * @param  array $variables 
     * @param  string $text      
     * @return $this           
     */
    protected function _transportEmail($emails, $mail, $variables, $text)
    {

        $transport = new Varien_Object();
        Mage::dispatchEvent('aschroder_smtppro_template_before_send', array(
            'mail' => $mail,
            'template' => $this,
            'variables' => $variables,
            'transport' => $transport
        ));

        $emailTransport = $transport->getEmailTransport();
        if (!empty($emailTransport)) {
            $mail->send($emailTransport);
        } else {
            $mail->send();
        }

        foreach ($emails as $key => $email) {
            try {
                Mage::dispatchEvent('aschroder_smtppro_after_send', array(
                    'to' => $email,
                    'template_id' => $this->getTemplateId(),
                    'template' => $this,
                    'subject' => $this->getProcessedTemplateSubject($variables),
                    'html' => !$this->isPlain(),
                    'email_body' => $text
                ));
            } catch (Exception $e) {
                $_helper->log("EXCEPTION OCCURRED SENDING WITH [{$key}]{$email}: " . $e->getMessage());
                Mage::logException($e);
            }
        }
        Mage::dispatchEvent('aschroder_smtppro_template_after_send_all', array(
            'mail' => $mail,
            'template' => $this,
            'variables' => $variables,
            'emails'    => $emails
        ));

        $this->_mail = null;

        return $this;
    }

    /**
     * Update ini SMTP port settings with what they have been specified in the config.
     * @return $this
     */
    protected function _updateSMTPSettings()
    {
        // I'm not sure why we are using thew system default config settings here instead 
        // of smtppro/smtpsettings/host, but I'll leave the old code in for now to avoid breaking anything...
        ini_set('SMTP', Mage::getStoreConfig('system/smtp/host', $this->getStoreId()));
        ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port', $this->getStoreId()));

        return $this;
    }

    /**
     * Builds and returns array of names from emails or specified names array.
     * @param  array $emails
     * @param  array|string|null $name  
     * @return array of names       
     */
    protected function _buildNames($emails, $name = null)
    {
        if($name == null) {
            $names = array();
        } elseif(is_array($name)) {
            $names = $name;
        } elseif(is_string($name)) {
            $names = array($name);
        } else {
            $names = (array)$name;
        }

        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        return $names;
    }

}
