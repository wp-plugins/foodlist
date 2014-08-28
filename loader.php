<?php
/**
 * @version   $Id$
 * @package   AP Participation Form
 * @copyright Copyright (C) 2012 Denis Voytyuk / http://artprima.eu/. All rights reserved.
 */

// as loader can be included from somewhere else we need to make sure that it is not loaded yet,
// and we put the last param to false to avoid possible autoloads (as this file obviously cannot
// be autoloaded)
if (!class_exists('Symfony\Component\ClassLoader\UniversalClassLoader', false)) {
    require_once __DIR__.'/UniversalClassLoader.php';
}

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Foodlist\Project\WordPress\Plugin\Foodlist\Manager as PluginManager;

//calling anonymous function to avoid creating globals
call_user_func(function(){

    $_loader = new UniversalClassLoader();

    $namespaces = array();
    if (!class_exists('Artprima\Lib', false)) {
        $namespaces['Artprima'] = array(
            __DIR__.'/lib',
        );
    }
    $namespaces['Foodlist'] = array(
        __DIR__.'/lib',
    );


    // register classes with namespaces
    $_loader->registerNamespaces($namespaces);

    // register prefixed style classes
    //$_loader->registerPrefixes(array(
    //    'Twig_'    => __DIR__.'/lib/Twig/lib',
    //));

    $_loader->register();
    if (!class_exists('Artprima\Lib', false)) {
        new Artprima\Lib; //
    }
    
    // Finally, init the plugin
    PluginManager::getInstance()->registerActivationHooks();
    PluginManager::getInstance()->init(plugins_url('', 'foodlist/plugin.php'), __DIR__);
});
