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

    public function render() {
        $oConfig        = $this->getConfig();
        $sThisTemplate  = parent::render();

        if($oConfig->getRequestParameter("aoc")){
            $this->_aViewData['sAjax']  =  $this->getDependencyGraph();
            $sThisTemplate              = "admin/tpl/popups/ib_dependencyManager_deps_ajax.tpl";
        }

        return $sThisTemplate;
    }

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

        $oDependencyManager     = oxNew('ib_dependencyManager');
        $aDeps                  = $oDependencyManager->getChildDependencies($sModule);
        print_r($aDeps);
    }

    public function getDependencyGraph(){
        $sReturn                = null;
        $oyMLGenerator          = oxNew("ib_DependencyManager_yMLWriter");
        $sModule                = $this->getEditObjectId();

        $oDependencyManager     = oxNew('ib_dependencyManager');
        $aDeps                  = $oDependencyManager->getChildDependencies($sModule);
        $oyMLGenerator->setModuleName($sModule);
        $oyMLGenerator->setModuleList($aDeps);
        $oyMLGenerator->generate();
        $sLines                 = $oyMLGenerator->__toString();

        if(!empty($sLines)){
            $oCurl                  = oxNew("oxCurl");
            $oCurl->setUrl("https://yuml.me/diagram/scruffy/class/");

            $oCurl->setMethod("POST");
            $oCurl->setOption("CURLOPT_FOLLOWLOCATION", true);
            $oCurl->setOption("CURLOPT_REFERER", "https://yuml.me/diagram/scruffy/class/draw");

            $oCurl->setParameters([
                "dsl_text"          => $sLines,
            ]);

            $sReturn                = $oCurl->execute();
        }


        return $sReturn;
    }


}