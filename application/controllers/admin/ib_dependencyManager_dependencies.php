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

    public function getDependencies(){

        $sOXID      = $this->getEditObjectId();

        $oModule    = oxNew("oxModule");
        $oModule->load($sOXID);
        $aDeps      = $oModule->getDependencies();
        return $aDeps;
    }

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

    public function getIsModuleCompatible($sModuleId, $aDeps){
        $oDependencyManager = oxNew("ib_dependencyManager");
        return $oDependencyManager->getModuleVersionsAreValid($sModuleId, $aDeps);
    }

    public function getIsModuleActive($sModuleId){
        $oModule    = oxNew("oxModule");
        $oModule->load($sModuleId);

        return $oModule->isActive();
    }


}