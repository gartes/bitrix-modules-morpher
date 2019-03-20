<?php
  
class morpher extends CModule {
 
    var $MODULE_ID = 'morpher';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS;
    var $errors = array();
 
    function __construct() {
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        $this->MODULE_NAME = 'Морфер';
        $this->MODULE_DESCRIPTION = 'Склонение слов по падежам';
    }
    public function DoInstall() {
        $this->InstallDB();
        RegisterModule($this->MODULE_ID);
    }
   
    function InstallDB() {
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/morpher/install/db/install.sql");
        if (!$this->errors) { 
            return true;
        } else { 
            return $this->errors;
        }
    }
     
    function UnInstallDB() {
        global $DB;
        $this->errors = false;
        $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/morpher/install/db/uninstall.sql");
        if (!$this->errors) {
            return true;
        } else {
            return $this->errors;
        }
    }
 
    public function DoUninstall() {
        $this->UnInstallDB();
        UnRegisterModule($this->MODULE_ID);
    }
    
}