<?php
/**
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_SMTP_Observer extends Varien_Object
{
	

    /**
     * Expects observer with:
     * 'mail', the mail about to be sent
     * 'template', template model for email being sent
     * 'email', the email object initiating the send
     * 'transport', an initially empty transport Object, will be used if set.
     *
     * @param $observer
     */
    public function beforeSend($observer) 
    {
        $event = $observer->getEvent();
        $storeId = $event->getTemplate()->getStoreId();

        if(!Mage::helper('smtppro/config')->isSMTPEnabled($storeId)) {
            return $this;
        }

        Mage::helper('smtppro/debug')->log("Running SMTP sender code.");

		$username = Mage::getStoreConfig('system/smtpsettings/username', $storeId);
		$password = Mage::getStoreConfig('system/smtpsettings/password', $storeId);
		$host = Mage::getStoreConfig('system/smtpsettings/host', $storeId);
		$port = Mage::getStoreConfig('system/smtpsettings/port', $storeId);
		$ssl = Mage::getStoreConfig('system/smtpsettings/ssl', $storeId);
		$auth = Mage::getStoreConfig('system/smtpsettings/authentication', $storeId);

		Mage::helper('smtppro/debug')->log("Preparing the SMTP Email transport, details are: username={$username} | host={$host} | port={$port} | ssl={$ssl} | auth={$auth}");
		 
		 // Set up the config array
		 $config = array();

		 if ($auth != "none") {
			$config['auth'] = $auth;
			$config['username'] = $username;
            $config['password'] = $password;
		 }
		 
		 if ($port) {
				$config['port'] = $port;
		 }
		 
		 if ($ssl != "none" ) {
				$config['ssl'] = $ssl;
		 }
		
		$transport = new Zend_Mail_Transport_Smtp($host, $config);

        $event->getTransport()->setEmailTransport($emailTransport);

        return $this;
    }

    /**
     * Expects observer with:
     * 'mail', the mail about to be sent
     * 'template', the template being used
     * 'variables', the variables used in the template
     * 'transport', an initially empty transport Object, will be used if set.
     * @todo this needs to be used after I update the Email.php model
     * @param $observer
     */
    // public function beforeSendTemplate($observer) 
    // {
    //     $e = $observer->getEvent();
    //     $storeId = $e->getTemplate()->getStoreId();

    //     $transport = new App_Mail_Transport_SMTP(
    //         array(
    //             'accessKey' => Mage::getStoreConfig('system/sessettings/aws_access_key', $storeId),
    //             'privateKey' => Mage::getStoreConfig('system/sessettings/aws_private_key', $storeId) 
    //         )
    //     );
        
    //     $transport = $e->getTransport();
    //     $transport->setTransport($transport);

    //     return $this;
    // }

	
}
