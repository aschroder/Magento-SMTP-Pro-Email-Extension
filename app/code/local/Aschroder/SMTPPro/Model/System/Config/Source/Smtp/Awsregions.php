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
            'us-west-1' => Mage::helper('adminhtml')->__('US West (N. California)'),
            'eu-west-1' => Mage::helper('adminhtml')->__('EU (Ireland)'),
            'eu-central-1' => Mage::helper('adminhtml')->__('EU (Frankfurt)'),
            'ap-southeast-1' => Mage::helper('adminhtml')->__('Asia Pacific (Singapore)'),
            'ap-northeast-1' => Mage::helper('adminhtml')->__('Asia Pacific (Tokyo)'),
            'ap-southeast-2' => Mage::helper('adminhtml')->__('Asia Pacific (Sydney)'),
            'ap-northeast-2' => Mage::helper('adminhtml')->__('Asia Pacific (Seoul)'),
            'sa-east-1' => Mage::helper('adminhtml')->__('South America (Sao Paulo)')
        );
    }
}