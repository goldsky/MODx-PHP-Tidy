<?php

/**
 * PHP Tidy for MODx
 *
 * Copyright 2011 by goldsky <goldsky@fastmail.fm>
 *
 * This file is part of PHP Tidy for MODx, an implementation of PHP Tidy in MODx.
 *
 * PHP Tidy for MODx is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * PHP Tidy for MODx is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * PHP Tidy for MODx; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * Define the MODX path constants necessary for installation
 *
 * @package     phptidy
 * @subpackage  build
 */
$mtime = microtime();
$mtime = explode(' ', $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

define('PKG_NAME', 'PHP Tidy for MODx');
define('PKG_NAME_LOWER', 'phptidy');
define('PKG_VERSION', '1.0.1');
define('PKG_RELEASE', 'rc1');

/* override with your own defines here (see build.config.sample.php) */
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'build.config.php';
require_once realpath(MODX_CORE_PATH . 'model/modx/modx.class.php');

$root = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR;
$sources = array(
    'root' => $root,
    'build' => $root . '_build' . DIRECTORY_SEPARATOR,
    'data' => realpath($root . '_build/data/') . DIRECTORY_SEPARATOR,
    'properties' => realpath($root . '_build/data/properties/') . DIRECTORY_SEPARATOR,
    'lexicon' => realpath(MODX_CORE_PATH . 'components/phptidy/lexicon/') . DIRECTORY_SEPARATOR,
    'docs' => realpath(MODX_CORE_PATH . 'components/phptidy/docs/') . DIRECTORY_SEPARATOR,
    'source_core' => realpath(MODX_CORE_PATH . 'components/phptidy'),
);
unset($root);
require_once $sources['build'] . '/includes/functions.php';

$modx = new modX();
$modx->initialize('mgr');
$modx->setLogLevel(modX::LOG_LEVEL_INFO);
flush();
echo XPDO_CLI_MODE ? '' : '<pre>';
$modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');

$modx->loadClass('transport.modPackageBuilder', '', false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER, PKG_VERSION, PKG_RELEASE);
$builder->registerNamespace(PKG_NAME_LOWER, false, true, '{core_path}components/phptidy/');
$modx->getService('lexicon', 'modLexicon');
$modx->lexicon->load('phptidy:properties');

/* create the plugin object */
$plugin = $modx->newObject('modPlugin');
//$plugin->set('id',1);
$plugin->set('name', PKG_NAME);
$plugin->set('description', 'PHP Tidy plugin for MODx Revolution');
$plugin->set('plugincode', getCodeContent($sources['source_core'] . '/elements/plugins/phptidy.plugin.php'));
$plugin->set('category', 0);

/* add plugin events */
$events = include $sources['data'] . 'transport.plugin.events.php';
if (is_array($events) && !empty($events)) {
    $plugin->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO, 'Packaged in ' . count($events) . ' Plugin Events.');
    flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find plugin events!');
}

/* load plugin properties */
$properties = include $sources['properties'] . 'phptidy.properties.php';
if (is_array($properties)) {
    $modx->log(xPDO::LOG_LEVEL_INFO, 'Set ' . count($properties) . ' plugin properties.');
    flush();
    $plugin->setProperties($properties);
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not set plugin properties.');
}

$attributes = array(
    xPDOTransport::UNIQUE_KEY => 'name',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array(
        'PluginEvents' => array(
            xPDOTransport::PRESERVE_KEYS => true,
            xPDOTransport::UPDATE_OBJECT => false,
            xPDOTransport::UNIQUE_KEY => array('pluginid', 'event'),
        ),
    ),
);

$vehicle = $builder->createVehicle($plugin, $attributes);

$modx->log(modX::LOG_LEVEL_INFO, 'Adding file resolvers to category...');
flush();
$vehicle->resolve('file', array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components' . DIRECTORY_SEPARATOR;",
));
$builder->putVehicle($vehicle);

unset($vehicle);

/* now pack in the license file, readme and setup options */
$modx->log(modX::LOG_LEVEL_INFO, 'Adding package attributes and setup options...');
flush();
$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt')
));

/* zip up package */
$modx->log(modX::LOG_LEVEL_INFO, 'Packing up transport package zip...');
flush();
$builder->pack();

$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tend = $mtime;
$totalTime = ($tend - $tstart);
$totalTime = sprintf("%2.4f s", $totalTime);

$modx->log(modX::LOG_LEVEL_INFO, "\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit();