<?php

/**
 * Created by PhpStorm.
 * User: N3X-Home
 * Date: 08.05.2017
 * Time: 23:29
 */
class ib_DependencyManager_yMLWriter extends oxSuperCfg{

    protected $_aModuleList = [];
    protected $_aLineList   = [];
    protected $_sModuleName = "";

    public function setModuleList(array $aModuleList = []){
        $this->_aModuleList = $aModuleList;
        return $this;
    }

    public function getModuleList(){
        return $this->_aModuleList;
    }

    public function setLineList(array $aLineList = []){
        $this->_aLineList   = $aLineList;
        return $this;
    }

    public function getLineList(){
        return $this->_aLineList;
    }

    public function setModuleName($sModuleName){
        $this->_sModuleName = $sModuleName;
        return $this;
    }

    public function getModuleName(){
        return $this->_sModuleName;
    }

    public function generate(){
        $aModuleList    = $this->getModuleList();
        $aLineList      = $this->_recursiveyMLGeneration($aModuleList);
        $aLineList      = array_unique($aLineList);
        $this->setLineList($aLineList);
        return $this;
    }

    protected function _recursiveyMLGeneration(array $aList, array $aLineList = [], $sModuleName = "") {
        if(empty($sModuleName)){
            $sModuleName = $this->getModuleName();
        }

        foreach ($aList as $sModule => $aChildModuleList) {
            $aLineList[] = "[$sModuleName]^-[$sModule]";
            $iChilds     = count($aChildModuleList);

            if ($iChilds == 1) {
                $sKey           = key($aChildModuleList);
                $aLineList[]    = "[$sModule]^-[" . $sKey . "]";
            } elseif ($iChilds > 1) {
                $aLines         = $this->_recursiveyMLGeneration($aChildModuleList, $aLineList, $sModule);
                $aLineList      = array_merge($aLineList, $aLines);
            }
        }

        return $aLineList;
    }

    public function __toString(){
        $aLineList  = $this->getLineList();
        $sLineList  = implode(", ", $aLineList);
        return $sLineList;
    }
}