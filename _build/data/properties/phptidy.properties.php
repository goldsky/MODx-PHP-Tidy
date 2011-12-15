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
if (!function_exists("fixJson")) {

    /**
     * Convert the JSON array for database
     * @param   array   JSON array
     * @return  array   converted array
     */
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
include dirname(__FILE__) . '/phptidy.plugin.recommend.properties.js';
$json = ob_get_contents();
ob_end_clean();

$properties = $modx->fromJSON($json);
$properties = fixJson($properties);

return $properties;