<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ib_DependencyManager_oxModuleInstaller extends ib_DependencyManager_oxModuleInstaller_parent{
    
    protected function _validateDependencies(array $aDeps){
        $blValid        = true;
        /* @var $oModuleList oxModuleList */
        $oModuleList    = oxNew("oxModuleList");
        $aModules       = $oModuleList->getActiveModuleInfo();
        
        foreach($aDeps as $sModuleName => $aModuleDep){
            if(!array_key_exists($sModuleName, $aModules)){
                $oLang                  = oxRegistry::getLang();
                $sMessage               = $oLang->translateString("ib_DependencyManager_MISSING_MODULE");
                $sTranslatedMessage     = sprintf($sMessage, $sModuleName);
                $oException = new oxException($sTranslatedMessage);
                throw $oException;
            }else{
                $blValid = $this->_validateVersions($sModuleName, $aModuleDep);
                if(!$blValid){
                    $oException         = new oxException("ib_DependencyManager_VERSION_MISSMATCH");
                    throw $oException;
                }
            }
        }
        
        return $blValid;
    }
    
    protected function _validateVersions($sModuleName, array $aModuleDep){
        $blVersion   = true;
        /* @var $oModule oxModule */
        $oModule     = oxNew("oxModule");
        $oModule->load($sModuleName);
        $sVersion    = $oModule->getInfo("version");

        if(array_key_exists("minVersion", $aModuleDep)){
            $sMinVersion        = $aModuleDep["minVersion"];
            $blVersion          = version_compare($sVersion, $sMinVersion, ">=");

        }

        if(array_key_exists("maxVersion", $aModuleDep)){
            $sMaxVersion        = $aModuleDep["maxVersion"];
            $blVersion           = version_compare($sVersion, $sMaxVersion, "<=");
        }
        
        return $blVersion;
    }
    
    /**
     * Activate extension by merging module class inheritance information with shop module array
     *
     * @param oxModule $oModule
     *
     * @return bool
     */
    public function activate(oxModule $oModule){
        $blResult   = false;
        $sModuleId  = $oModule->getId();
        
        if($sModuleId){
            $aDeps      = $oModule->getDependencies();
            $blResult   = $this->_validateDependencies($aDeps);
            
            if($blResult == true){
                $blResult = parent::activate($oModule);
            }
        }
        
        return $blResult;
    }
    
    /**
     * Deactivate extension by adding disable module class information to disabled module array
     *
     * @param oxModule $oModule
     *
     * @return bool
     */
    public function deactivate(oxModule $oModule){
        /**
         * @todo implement dependencies
         */
        return parent::deactivate($oModule);
    }
}
