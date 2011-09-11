<?php
/**********************************************************************************
* install_main.php                                                                *
***********************************************************************************
* SMF Arcade                                                                      *
* =============================================================================== *
* Software Version:           SMF Arcade 2.5 RC1.1B                               *
* Software by:                Niko Pahajoki (http://www.madjoki.com)              *
* Copyright 2004-2009 by:     Niko Pahajoki (http://www.madjoki.com)              *
* Support, News, Updates at:  http://www.smfarcade.info                           *
***********************************************************************************
* This program is free software; you may redistribute it and/or modify it under   *
* the terms of the provided license as published by Simple Machines LLC.          *
*                                                                                 *
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
*                                                                                 *
* See the "license.txt" file for details of the Simple Machines license.          *
* The latest version can always be found at http://www.simplemachines.org.        *
**********************************************************************************/

global $txt, $smcFunc, $db_prefix, $modSettings;
global $project_version, $addSettings, $permissions, $tables, $sourcedir;

if (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please run arcadeinstall/index.php instead');

$forced = false;

// Step 1: Create and/or Upgrade tables
doTables(); 

// Step 2: Add Settings to database
doSettings($addSettings); 

// Step 3: Update "Admin Features"
updateAdminFeatures('arcade', !empty($modSettings['arcadeEnabled']));

// Step 4: Add Permissions to database
doPermission($permissions);

// Step 5: Insert SMF Arcade Package Server to list
$request = $smcFunc['db_query']('', '
	SELECT COUNT(*)
	FROM {db_prefix}package_servers
	WHERE name = {string:name}',
	array(
		'name' => 'SMF Arcade Package Server',
	)
);

list ($count) = $smcFunc['db_fetch_row']($request);
$smcFunc['db_free_result']($request);

if ($count == 0 || $forced)
{
	$smcFunc['db_insert']('insert',
		'{db_prefix}package_servers',
		array(
			'name' => 'string',
			'url' => 'string',
		),
		array(
			'SMF Arcade Package Server',
			'http://download.smfarcade.info',
		),
		array()
	);
}

// Step 6: Update Arcade Version in Database
updateSettings(array('arcadeVersion' => $arcade_version));

// Step 7: Add Integration Hooks
doArcadeHooks();
?>