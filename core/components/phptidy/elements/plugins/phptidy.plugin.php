<?php

/**
 * PHP Tidy for MODx
 * Tidy is a binding for the Tidy HTML clean and repair utility which allows you
 * to not only clean and otherwise manipulate HTML documents, but also traverse
 * the document tree.
 *
 * Copyright 2011 by goldsky <goldsky@fastmail.fm>
 *
 * This file is part of PHP Tidy for MODx.
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
 * PHP Tidy for MODx; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @link        http://php.net/manual/en/book.tidy.php
 * @author      goldsky <goldsky@fastmail.fm>
 * @package     phptidy
 * @subpackage  plugin
 */

if ($modx->event->name !== 'OnWebPagePrerender'
        || !class_exists('tidy')
) {
    return;
}

// Specify configuration
if (!function_exists("fixJson")) {
    function fixJson(array $array) {
        $fixed = array();
        foreach ($array as $k => $v) {
            $fixed[] = array(
                'name' => $v['name'],
                'desc' => $v['desc'],
                'type' => $v['xtype'],
                'options' => empty($v['options']) ? '' : $v['options'],
                'value' => $v['value'],
                'lexicon' => $v['lexicon'],
            );
        }
        return $fixed;
    }
}

ob_start();
include $modx->getOption('core_path') . 'components/phptidy/elements/plugins/phptidy.plugin.default.properties.js';
$json = ob_get_contents();
ob_end_clean();

$properties = $modx->fromJSON($json);
$properties = fixJson($properties);
$defaultProperties = array();
foreach ($properties as $k => $v) {
    $defaultProperties[$v['name']] = $v['value'];
}

// Set non-default configs
$tidyConfig = array();
foreach ($scriptProperties as $k => $v) {
    if ($defaultProperties[$k] !== $scriptProperties[$k]) {
        // convert boolean strings (by a generated drop down options) to be uhm... boolean.
        $v = (strtolower($v) === "true" || strtolower($v) === "yes" || strtolower($v) === "1") ? 1 : $v;
        $v = (strtolower($v) === "false" || strtolower($v) === "no" || strtolower($v) === "0") ? 0 : $v;
        $tidyConfig[$k] = $v;
    }
}

// Tidy
$tidy = new tidy;
$tidy->parseString($modx->resource->_output, $tidyConfig);
$modx->resource->_output = $tidy;

return;