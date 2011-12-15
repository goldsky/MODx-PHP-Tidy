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
 *
 */

$plugins = array();

$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->fromArray(array(
    'id' => 0,
    'name' => 'PHP Tidy',
    'description' => 'Tidy is a binding for the Tidy HTML clean and repair
        utility which allows you to not only clean and otherwise manipulate HTML
        documents, but also traverse the document tree.',
    'snippet' => getCodeContent($sources['source_core'] . '/elements/plugins/phptidy.plugin.php'),
        ), '', true, true);
$properties = include $sources['properties'] . 'phptidy.properties.php';
$plugins[0]->setProperties($properties);
unset($properties);

return $plugins;