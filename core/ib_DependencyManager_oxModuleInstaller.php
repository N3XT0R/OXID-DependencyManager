<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ib_DependencyManager_oxModuleInstaller extends ib_DependencyManager_oxModuleInstaller_parent{

    protected $_oDependencyManager;

    /**
     * Set DependencyManager
     * @param ib_dependencyManager $oDependencyManager
     * @return $this
     */
    public function setDependencyManager(ib_dependencyManager $oDependencyManager){
        $this->_oDependencyManager = $oDependencyManager;
        return $this;
    }

    /**
     * Get DependencyManager
     * @return ib_dependencyManager
     */
    public function getDependencyManager(){
        if(!isset($this->_oDependencyManager)){
            $oDependencyManager = oxNew("ib_dependencyManager");
            $this->setDependencyManager($oDependencyManager);
        }

        return $this->_oDependencyManager;
    }

    protected function _validateDependencies(array $aDeps){

        $blValid        = true;
        /* @var $oModuleList oxModuleList */
        $oModuleList    = oxNew("oxModuleList");
        $aModules       = $oModuleList->getActiveModuleInfo();

        /**
         * Validate dependencies to other modules
         */
        foreach($aDeps as $sModuleName => $aModuleDep){
            if(!array_key_exists($sModuleName, $aModules)){
                $oLang                  = oxRegistry::getLang();
                $sMessage               = $oLang->translateString("ib_DependencyManager_MISSING_MODULE");
                $sTranslatedMessage     = sprintf($sMessage, $sModuleName);
                $oException = new oxException($sTranslatedMessage);
                throw $oException;
            }else{
                $oDependencyManager     = $this->getDependencyManager();
                $blValid                = $oDependencyManager->getModuleVersionsAreValid($sModuleName, $aModuleDep);
                if(!$blValid){
                    $oException         = new oxException("ib_DependencyManager_VERSION_MISSMATCH");
                    throw $oException;
                }
            }
        }
        
        return $blValid;
    }


    /**
     * @param oxModule $oModule
     * @return bool|mixed
     * @throws oxException
     */
    public function activate(oxModule $oModule){
        $blResult   = false;
        $sModuleId  = $oModule->getId();
        
        if($sModuleId){
            /**
             * @todo test it
             */
            /*
            $aOXIDVersion           = $oModule->getRequiredOXIDVersion();
            $oDependencyManager     = $this->getDependencyManager();
            $blShopValid            = $oDependencyManager->getShopVersionIsValid($aOXIDVersion);

            if($blShopValid === false){
                $oException         = new oxException("ib_DependencyManager_OXIDVERSION_MISSMATCH");
                throw $oException;
            }
            */

            $aDeps          = $oModule->getDependencies();
            $blResult       = $this->_validateDependencies($aDeps);

            if($blResult == true){
                $blResult = parent::activate($oModule);
            }
        }
        
        return $blResult;
    }

    /**
     * @param oxModule $oModule
     * @return bool
     * @throws oxException
     */
    public function deactivate(oxModule $oModule){
        $blResult   = false;
        $sModuleId  = $oModule->getId();

        if($sModuleId){
            $oModuleList = oxNew("oxModuleList");
            $aModules    = $oModuleList->getActiveModuleInfo();

            foreach($aModules as $sActiveModule => $sPath){
                $oSubModule = oxNew("oxModule");
                $oSubModule->load($sActiveModule);
                $aDeps   = $oSubModule->getDependencies();

                if(array_key_exists($sModuleId, $aDeps)){

                    $blIsActive = $oSubModule->isActive();
                    if($blIsActive == true){
                        $oLang                  = oxRegistry::getLang();
                        $sMessage               = $oLang->translateString("ib_DependencyManager_DEP_ACTIVE");
                        $sTranslatedMessage     = sprintf($sMessage, $sActiveModule);
                        $oException = new oxException($sTranslatedMessage);
                        throw $oException;
                        break;
                    }
                }
            }

            $blResult = parent::deactivate($oModule);
        }

       return $blResult;
    }
}
