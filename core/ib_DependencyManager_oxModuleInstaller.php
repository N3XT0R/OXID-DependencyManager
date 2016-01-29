<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ib_DependencyManager_oxModuleInstaller extends ib_DependencyManager_oxModuleInstaller_parent{
    
    /**
     * Activate extension by merging module class inheritance information with shop module array
     *
     * @param oxModule $oModule
     *
     * @return bool
     */
    public function activate(oxModule $oModule){
        /**
         * @todo implement dependencies
         */
        return parent::activate($oModule);
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
