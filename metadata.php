<?php
$sMetadataVersion = "1.1";

$aModule = array(
    "id"          => "ib_DependencyManager",
    "title"       => "OXID-DependencyManager",
    "description" => "Abhängigkeitsverwaltung für OXID CE/PE/EE",
    "thumbnail"   => "",
    "version"     => "1.0.0",
    "author"      => "Ilya Beliaev",
    "url"         => "http://blog.php-dev.info",
    "email"       => "info@php-dev.info",
    "extend"      => array(
        'oxModule'              => 'ib_DependencyManager/core/ib_DependencyManager_oxModule',
        'oxModuleInstaller'     => 'ib_DependencyManager/core/ib_DependencyManager_oxModuleInstaller',
    ),
    "files"       => array(
    ),
    "settings"    => array(
        
    ),
    "templates"   => array(),
    "blocks"      => array(),
    "events"      => array(),
);
