<?php
/**
 *
 * Not a real transport - placeholder for disabled option.
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Model_Transports_Basesmtp {


    public function getTransport($storeId) {
       return null; // disabled transport = null
    }
}
