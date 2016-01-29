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
        
        foreach($aDeps as $sDepModule){
            if(!array_key_exists($sDepModule, $aModules)){
                $oLang                  = oxRegistry::getLang();
                $sMessage               = $oLang->translateString("ib_DependencyManager_MISSING_MODULE");
                $sTranslatedMessage     = sprintf($sMessage, $sDepModule);
                $oException = new oxException($sMessage);
                throw $oException;
            }
        }
        
        return $blValid;
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
