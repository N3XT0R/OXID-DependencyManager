<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ib_DependencyManager_oxModule extends ib_DependencyManager_oxModule_parent{
    
    /**
     * Returns array of module Dependencies.
     *
     * @return array
     */
    public function getDependencies(){
        $aResult = array();
        if(isset($this->_aModule['dependencies'])){
            $aResult = $this->_aModule['dependencies'];
        }
        
        return $aResult;
    }
}
