<?php

/**
 * Created by PhpStorm.
 * User: ILBE
 * Date: 31.03.2016
 * Time: 13:18
 */
class ib_dependencyManager extends oxSuperCfg{


    /**
     * Validates that one moduleversion is compatible with another
     * @param array $aReqVersion
     * @param $sVersion
     * @return bool|mixed
     */
    public function getIsValidVersion(array $aReqVersion, $sVersion){
        $blVersion = true;
        if(array_key_exists("minVersion", $aReqVersion)){
            if(!empty($aModuleDep["minVersion"])){
                $sMinVersion        = $aReqVersion["minVersion"];
                $blVersion          = version_compare($sVersion, $sMinVersion, ">=");
            }
        }

        if(array_key_exists("maxVersion", $aReqVersion) && $blVersion == true){
            if(!empty($aReqVersion["maxVersion"])){
                $sMaxVersion        = $this->_replaceWildcards($aReqVersion["maxVersion"]);
                $blVersion          = version_compare($sVersion, $sMaxVersion, "<=");
            }
        }

        return $blVersion;
    }

    /**
     * replace wildcard-sign with a value that will be never in use
     * @param $sVersion
     * @return mixed
     */
    protected function _replaceWildcards($sVersion){
        $sReplaced = str_replace("*", "999999", $sVersion);
        return $sReplaced;
    }


    public function getModuleVersionsAreValid($sModuleName, array $aModuleDep, $sVersion = null){
        if($sVersion === null){
            /* @var $oModule oxModule */
            $oModule     = oxNew("oxModule");
            $oModule->load($sModuleName);
            $sVersion    = $oModule->getInfo("version");
        }

        $blVersion       = $this->getIsValidVersion($aModuleDep, $sVersion);

        return $blVersion;
    }

    /**
     * Validate that Shopversion is compatible
     * @param array $aModuleDep
     * @return bool|mixed
     */
    public function getShopVersionIsValid(array $aModuleDep){
        $oConfig            = $this->getConfig();
        $sShopVersion       = $oConfig->getVersion();
        $blVersion          = $this->getIsValidVersion($aModuleDep, $sShopVersion);

        return $blVersion;
    }

    public function getChildDependencies($sModuleName){
        $oConfig        = $this->getConfig();
        $sModDir        = $oConfig->getModulesDir();
        $aDeps          = array();

        $oModuleList    = oxNew("oxModuleList");
        $aModuleArrList = $oModuleList->getModulesFromDir($sModDir);
        $aModuleList    = array();

        /**
         * @var oxModule|ib_DependencyManager_oxModule $oModule
         */
        foreach($aModuleArrList as $oModule){
            $aDependencies  = $oModule->getDependencies();
            if(count($aDependencies) > 0){
                $sName                  = $oModule->getId();
                $aModuleList[$sName]    = $aDependencies;
            }
        }

        /**
         * get child-modules from all paths of full dependency-tree
         */
        foreach($aModuleList as $sModule => $aDepenceOn){
            if(array_key_exists($sModuleName, $aDepenceOn)){
                if(!array_key_exists($sModule, $aDeps)){
                    $aDeps[$sModule]    = [];
                    $aChildChilds       = $this->getChildDependencies($sModule);
                    if(count($aChildChilds) > 0){
                        $aDeps[$sModule]          = $aChildChilds;

                    }
                }
            }
        }

        return $aDeps;
    }
}