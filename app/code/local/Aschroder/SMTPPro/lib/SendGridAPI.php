<?php

class App_Mail_Transport_SendGridAPI extends Zend_Mail_Transport_Abstract
{
    private $apiKey;

    public function __construct(array $config = array())
    {
        if (!array_key_exists('apiKey', $config)) {
            throw new Zend_Mail_Transport_Exception('This transport requires a SendGrid API key');
        }

        $this->apiKey = $config['apiKey'];
    }

    public function _sendMail()
    {
        if (!$this->_mail) {
            throw new Zend_Mail_Transport_Exception('Invalid/unset mail property');
        }

        $email = new SendGrid\Mail\Mail();

        foreach (explode(',', $this->recipients) as $recipient) {
            $email->addTo($recipient);
        }

        $email->setFrom($this->_mail->getFrom());
        $email->setSubject($this->_mail->getSubject());

        if (($text = $this->_mail->getBodyText()) && $text->getRawContent()) {
            $email->addContent('text/plain', $text->getRawContent());
        }

        if (($html = $this->_mail->getBodyHtml()) && $html->getRawContent()) {
            $email->addContent('text/html', $html->getRawContent());
        }

        $sendGrid = new SendGrid($this->apiKey);

        $response = $sendGrid->send($email);

        if ($response->statusCode() >= 400) {
            throw new Zend_Mail_Transport_Exception('Unable to send email to SendGrid');
        }
    }
}
