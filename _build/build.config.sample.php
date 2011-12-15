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
define('MODX_BASE_PATH', dirname(dirname(__FILE__)) . '/');
define('MODX_CORE_PATH', MODX_BASE_PATH . 'core/');
define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'www/manager/');
define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'www/connectors/');
define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'www/assets/');

define('MODX_BASE_URL', '/modx/www/');
define('MODX_CORE_URL', '/modx/core/');
define('MODX_MANAGER_URL', '/modx/www/manager/');
define('MODX_CONNECTORS_URL', '/modx/www/connectors/');
define('MODX_ASSETS_URL', '/modx/www/assets/');
