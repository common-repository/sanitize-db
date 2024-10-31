<?php
/*
  Plugin Name: Sanitize DB 
  Description: The plugin senitize the database by removing the unused meta entries and other unlinked records from the database.
  Tags: sanitize, database,optimize, optimization, sanitization, speedup
  Author: Ankit Chugh
  License:           GPL-2.0+
  License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
  Version: 1.0.1
 */

/*
  This program is free software; you can redistribute it and/or
  modify it under the terms of the GNU General Public License
  as published by the Free Software Foundation; either version 2
  of the License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Current plugin version.
define('WPSDB_VERSION', '1.0.1');

// Paths
define('WPSDB_PATH', plugin_dir_path( __FILE__ ));
define('WPSDB_DIRNAME', basename( plugin_basename( WPSDB_PATH ) ));
define('WPSDB_PLUGIN_URL', plugins_url() . '/' . WPSDB_DIRNAME);

// Includes
require_once(WPSDB_PATH . '/admin/admin.php');
require_once(WPSDB_PATH . '/cpt/wpsdb_history.php');
require_once(WPSDB_PATH . '/inc/functions.php');

/*3rd party js plugins used below
1. DataTables.js - https://datatables.net/
2. LoadingBar.js - https://github.com/loadingio/loading-bar/
*/

