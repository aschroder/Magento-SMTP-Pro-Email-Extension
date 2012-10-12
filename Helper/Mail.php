<?php
class Aschroder_SMTPPro_Helper_Mail {

    public function sendMailObject($mailObject,$websiteModelId=0) {
        try {
            $transport = Mage::helper('smtppro')->getTransport($websiteModelId);

            $mail = unserialize($mailObject);
            $mail->send($transport);

            return true;
        } catch(Exception $e) {
            Mage::log("Error sending mail with sendMailObject; ".$e->getMessage());
        }
    }

}