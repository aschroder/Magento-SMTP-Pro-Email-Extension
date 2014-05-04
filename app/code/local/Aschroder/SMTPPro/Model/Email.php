<?php
/**
 * This class wraps the Basic email sending functionality
 * If SMTPPro is enabled it will send emails using the given
 * configuration.
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Email extends Mage_Core_Model_Email {


     public function send() {

        $_helper = Mage::helper('smtppro');

        // If it's not enabled, just return the parent result.
        if (!$_helper->isEnabled()) {
            return parent::send();
        }

        if (Mage::getStoreConfigFlag('system/smtp/disable')) {
            return $this;
        }

        $mail = new Zend_Mail();

        if (strtolower($this->getType()) == 'html') {
            $mail->setBodyHtml($this->getBody());
        } else {
            $mail->setBodyText($this->getBody());
        }

        $mail->setFrom($this->getFromEmail(), $this->getFromName())
            ->addTo($this->getToEmail(), $this->getToName())
            ->setSubject($this->getSubject());

        $transport = new Varien_Object(); // for observers to set if required
        Mage::dispatchEvent('aschroder_smtppro_before_send', array(
            'mail' => $mail,
            'email' => $this,
            'transport' => $transport
        ));

        if ($transport->getTransport()) { // if set by an observer, use it
            $mail->send($transport->getTransport());
        } else {
            $mail->send();
        }

        Mage::dispatchEvent('aschroder_smtppro_after_send', array(
            'to' => $this->getToName(),
            'subject' => $this->getSubject(),
            'template' => "n/a",
            'html' => (strtolower($this->getType()) == 'html'),
            'email_body' => $this->getBody()));

        return $this;
    }
}
