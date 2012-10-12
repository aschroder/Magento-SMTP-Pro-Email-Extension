<?php

class Aschroder_SMTPPro_AsyncController extends Mage_Core_Controller_Front_Action {

    public function mailAction() {
        $queueItemId = $this->getRequest()->getParam('queue_item_id');
        $queueItem = Mage::getModel('smtppro/queue')->load($queueItemId);
        $params = json_decode($queueItem->getParams(),true);

        if(Mage::helper('smtppro/mail')->sendMailObject($params['mail_object'],$params['website_model_id'])) {
            $queueItem->delete();
        } else {
            $queueItem->setStatus('failed')->save();
        }
    }

}