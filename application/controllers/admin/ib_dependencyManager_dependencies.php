<?php

/**
 *
 * @link      http://php-dev.info
 * @package   ib_dependenyManager
 * @version   1.1.0
 * @author    Ilya Beliaev <info@php-dev.info>
 */
class ib_dependencyManager_dependencies extends oxAdminDetails{

    protected $_sThisTemplate = "admin/tpl/ib_dependencyManager_deps.tpl";

    /**
     * Returns array with required dependencies
     * @return array
     */
    public function getDependencies(){

        $sOXID      = $this->getEditObjectId();

        $oModule    = oxNew("oxModule");
        $oModule->load($sOXID);
        $aDeps      = $oModule->getDependencies();
        return $aDeps;
    }


    /**
     * Checks if a module exists on filesystem
     * @param $sModuleId
     * @return bool
     */
    public function getModuleExists($sModuleId){
        $blResult       = false;
        $sModulesDir    = $this->getConfig()->getModulesDir();
        $oModuleList    = oxNew("oxModuleList");
        $aModules       = $oModuleList->getModulesFromDir($sModulesDir);

        foreach($aModules as $sModule => $aVal){
            if($sModule == $sModuleId){
                $blResult = true;
                break;
            }
        }

        return $blResult;
    }

    /**
     * Get compatibility state from one module
     * @param $sModuleId
     * @param array $aDeps
     * @return bool
     */
    public function getIsModuleCompatible($sModuleId,array $aDeps){
        $oDependencyManager = oxNew("ib_dependencyManager");
        return $oDependencyManager->getModuleVersionsAreValid($sModuleId, $aDeps);
    }

    /**
     * Get activationstate from one module
     * @param $sModuleId
     * @return bool
     */
    public function getIsModuleActive($sModuleId){
        $oModule    = oxNew("oxModule");
        $oModule->load($sModuleId);

        return $oModule->isActive();
    }

    public function deactivateAll(){
        $sModule                = $this->getEditObjectId();
        $oModuleList            = oxNew("oxModuleList");
        $aActiveModules         = array_keys($oModuleList->getActiveModuleInfo());
        $oModuleInstaller       = oxNew("oxModuleInstaller");

        foreach($aActiveModules as $sSubModule){
            $oModule    = oxNew("oxModule");
            $oModule->load($sSubModule);
            $aDeps      = array_keys($oModule->getDependencies());


            if(in_array($sModule, $aDeps) == true){
                $oModuleInstaller->deactivate($oModule);
            }
        }

        $this->_aViewData["updatenav"] = "1";
    }

    public function activateAll(){
        $sModule                = $this->getEditObjectId();

        $oDependencyManager     = oxNew('ib_dependencyManager');
        $aDeps                  = $oDependencyManager->getChildDependencies($sModule);
        print_r($aDeps);

        /**
         *
        $oModuleList            = oxNew("oxModuleList");
        $aActiveModules         = $oModuleList->getDisabledModules();
        $oModuleInstaller       = oxNew("oxModuleInstaller");

        foreach($aActiveModules as $sSubModule){
            $oModule    = oxNew("oxModule");
            $oModule->load($sSubModule);
            $aDeps      = array_keys($oModule->getDependencies());
            
            if(in_array($sModule, $aDeps) == true){
                $oModuleInstaller->activate($oModule);
            }
        }

        $this->_aViewData["updatenav"] = "1";
         * */
    }

}