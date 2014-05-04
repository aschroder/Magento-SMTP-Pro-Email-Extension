<?php

/**
 * Installation helper to make installs more consistent, trackable, repeatable and debugable.
 *
 *
 * @author Jay El-Kaake
 * @copyright  Copyright (c) 2014 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Helper_Mysql4_Install extends Mage_Core_Helper_Abstract 
{

    protected $ex_stack = array ();
    
    protected $_setup = null;
    
    /**
     * alter table for each column update and ignore duplicate column errors
     * This is used since "if column not exists" function does not exist
     * for MYSQL
     *
     * @param unknown_type $installer
     * @param string $table_name
     * @param array $columns
     * @return Aschroder_SMTPPro_Helper_Mysql4_Install
     */
    public function addColumns(&$installer, $table_name, $columns)
    {
        foreach($columns as $column) {
            $sql = "ALTER TABLE {$table_name} ADD COLUMN({$column});";
            // run SQL and ignore any errors including (Duplicate column errors)
            try {
                $installer->run($sql);
            } catch(Exception $ex) {
                $this->addInstallProblem($ex);
            }
        
        }
        return $this;
    }
    /**
     * Attempt to add a foreign key constraint on two tables
     *
     * @param unknown_type $installer
     * @param string $table1_name
     * @param string $column1
     * @param string $table2_name
     * @param string $column2=null (uses column1 if null)
     * @param string $on_delete='CASCADE'
     * @param string $on_update='NO ACTION'
     * @return Aschroder_SMTPPro_Helper_Mysql4_Install
     */
    public function addFKey(&$installer, $key_name, $table1_name, $column1, $table2_name, $column2=null, $on_delete='NO ACTION', $on_update='NO ACTION')
    {
        try {
            if(empty($column2)) {
                $column2 = $column1;
            }
            $installer->getConnection()
                ->addConstraint(
                    $key_name,
                    $table1_name, 
                    $column1,
                    $table2_name, 
                    $column2,
                    $on_delete, 
                    $on_update
           );
        } catch(Exception $ex) {
            $this->addInstallProblem($ex);
        }
        
        return $this;
    }
    
    /**
     * Adds an exception problem to the stack of problems that may
     * have occured during installation.
     * Ignores duplicate column name errors; ignore if the msg starts with "SQLSTATE[42S21]: Column already exists"
     * @param Exception $ex
     */
    public function addInstallProblem(Exception $ex)
    {
        if (strpos($ex->getMessage (), "SQLSTATE[42S21]: Column already exists") !== false)
            return $this;
        if (strpos($ex->getMessage (), "SQLSTATE[42000]: Syntax error or access violation: 1091 Can't DROP") !== false 
                && strpos($ex->getMessage (), "check that column/key exists") !== false )
            return $this;
        $this->ex_stack [] = $ex;
        return $this;
    }
    
    /**
     * Returns true if any problems occured after installation
     * @return boolean 
     */
    public function hasProblems()
    {
        return sizeof($this->ex_stack ) > 0;
    }
    /**
     * Returns a string of problems that occured after any installation scripts were run through this helper
     * @return string message to display to the user
     */
    public function getProblemsString()
    {
        $msg = $this->__("The following errors occured while trying to install the module.");
        $msg .= "\n<br>";
        foreach($this->ex_stack as $ex_i => $ex) {
            $msg .= "<b>#{$ex_i}: </b>";
            if(Mage::getIsDeveloperMode()) {
                $msg .= nl2br($ex);
            } else {
                $msg .= $ex->getMessage ();
            }
            $msg .= "\n<br>";
        }
        $msg .= "\n<br>";
        $msg .= $this->__("If any of these problems were unexpected, I recommend that you contact the module support team to avoid problems in the future.");
        return $msg;
    }
    
    /**
     * Clears any insall problems (exceptions) that were in the stack
     */
    public function clearProblems()
    {
        $this->ex_stack = array ();
        return $this;
    }
    
    /**
     * alter table for each column update and ignore duplicate column errors
     * This is used since "if column not exists" function does not exist
     * for MYSQL
     *
     * @param unknown_type $installer
     * @param string $table_name
     * @param array $columns
     * @return Aschroder_SMTPPro_Helper_Mysql4_Install
     */
    public function dropColumns(&$installer, $table_name, $columns)
    {
        foreach($columns as $column) {
            $sql = "ALTER TABLE {$table_name} DROP COLUMN  {$column} ;";
            // run SQL and ignore any errors including (Duplicate column errors)
            try {
                $installer->run($sql);
            } catch(Exception $ex) {
                $this->addInstallProblem($ex);
            }
        
        }
        return $this;
    }
    
    /**
     * Runs a SQL query using the install resource provided and 
     * remembers any errors that occur. 
     *
     * @param unknown_type $installer
     * @param string $sql
     * @return Aschroder_SMTPPro_Helper_Mysql4_Install
     */
    public function attemptQuery(&$installer, $sql)
    {
        try {
            $installer->run($sql);
        } catch(Exception $ex) {
            $this->addInstallProblem($ex);
        }
        return $this;
    }
    
    /**
     * Creates an installation message notice in the backend.
     * @param string $msg_title
     * @param string $msg_desc
     * @param string $url=null if null default URL is used.
     */
    public function createInstallNotice($msg_title, $msg_desc, $url = null, $severity = null)
    {
        $message = Mage::getModel('adminnotification/inbox');
        $message->setDateAdded(date("c", time () ));
        
        if ($url == null) {
            $url = "https://github.com/aschroder/Magento-SMTP-Pro-Email-Extension";
        }
        
        if ($severity === null) {
            $severity = Mage_AdminNotification_Model_Inbox::SEVERITY_NOTICE;
        }
        
        // If problems occured increase severity and append logged messages.
        if (Mage::helper('smtppro/mysql4_install' )->hasProblems ()) {
            $severity = Mage_AdminNotification_Model_Inbox::SEVERITY_MINOR;
            $msg_title .= " Problems may have occured during installation.";
            $msg_desc .= " " . Mage::helper('smtppro/mysql4_install' )->getProblemsString ();
            Mage::helper('smtppro/mysql4_install' )->clearProblems ();
        }
        
        $message->setTitle($msg_title);
        $message->setDescription($msg_desc);
        $message->setUrl($url);
        $message->setSeverity($severity);
        $message->save ();
        
        return $this;
    }
    
    /**
     * Add an EAV entity attribute to the database.
     * 
     * @param string $entity_type       entity type (catalog_product, order, order_item, etc)   
     * @param string $attribute_code    attribute code  
     * @param array $data               eav attribute data
     */
    public function addAttribute($entity_type, $attribute_code, $data)
    {
        try {
            $this->_getSetupSingleton ()->addAttribute($entity_type, $attribute_code, $data);
        } catch(Exception $ex) {
            $this->addInstallProblem($ex);
        }
        return $this;
    }
    
    /**
     * Clears cache and prepares anything that needs to generally happen before running DB install scripts.
     */
    public function prepareForDb() 
    { 
        
        try {
            Mage::app()->getCacheInstance()->flush();
            
        } catch(Exception $ex) {
            $this->addInstallProblem("Problem clearing cache:". $ex);
        }
        
        return $this;
    }
    
    /**
     * @return Mage_Eav_Model_Entity_Setup
     */
    protected function _getSetupSingleton() {
        if ($this->_setup == null) {
            $this->_setup = new Mage_Eav_Model_Entity_Setup('core_setup');
        }
        return $this->_setup;
    }
    
}
