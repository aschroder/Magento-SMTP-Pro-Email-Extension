<?php
/**
 * @author Fabrizio Branca
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Aschroder_SMTPPro_Model_System_Config_Source_Smtp_Awsregions
{
    public function toOptionArray()
    {
        return array(
            'us-east-1' => Mage::helper('adminhtml')->__('US East (N. Virginia)'),
            'us-west-2' => Mage::helper('adminhtml')->__('US West (Oregon)'),
            'ap-south-1' => Mage::helper('adminhtml')->__('Asia Pacific (Mumbai)'),
            'ap-southeast-2' => Mage::helper('adminhtml')->__('Asia Pacific (Sydney)'),
            'eu-central-1' => Mage::helper('adminhtml')->__('EU (Frankfurt)'),
            'eu-west-1' => Mage::helper('adminhtml')->__('EU (Ireland)')
        );
    }
}
