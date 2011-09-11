<?php
/**********************************************************************************
* Database.php                                                                    *
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

if (!defined('SMF'))
	die('Hacking attempt...');

/* Contains information about database tables	

	void doSettings()
		- ???

	void doPermission()
		- ???
			
*/

global $addSettings, $tables, $permissions, $columnRename, $boarddir, $boardurl, $smcFunc;

$arcade_version = '2.5 RC1.1';
$arcade_lang_version = '2.5 RC1.1';
$arcade_server = 'http://service.smfarcade.info/arcade';

// Settings array
$addSettings = array(
	'gamesPerPage' => array(25, false),
	'matchesPerPage' => array(25, false),
	'scoresPerPage' => array(50, false),
	'gamesDirectory' => array(str_replace('\\', '/', $boarddir . '/Games'), false),
    'arcadeDBUpdate' => array(0, true),
	'gamesUrl' => array($boardurl . '/Games', false),
	'arcadeEnabled' => array(true, false),
	'arcadeArenaEnabled' => array(false, false),
	'arcadeCheckLevel' => array(1, false),
	'arcadeGamecacheUpdate' => array(1, false),
	'arcadeMaxScores' => array(0, false),
	'arcadePermissionMode' => array(1, false),
	'arcadePostPermission' => array(0, false),
	'arcadePostsPlay' => array(0, false),
	'arcadePostsPlayPerDay' => array(0, false),
	'arcadePostsPlayAverage' => array(0, false),
	'arcadeEnableFavorites' => array(1, false),
	'arcadeEnableRatings' => array(1, false),
	'arcadeShowInfoCenter' => array(1, false),
	'arcadeCommentLen' => array(75, false),
);

// Permissions array
$permissions = array(
	'arcade_view' => array(-1, 0, 2), // Everyone
	'arcade_play' => array(-1, 0, 2), // Everyone
	'arcade_submit' => array(0, 2), // Regular members
	'arcade_admin' => array(), // Only admins will get this
	'arcade_comment_own' => array(0, 2), // Regular members
	'arcade_comment_any' => array(), // Only admins
	'arcade_user_stats_own' => array(0, 2),
	'arcade_user_stats_any' => array(0, 2),
	'arcade_view_arena' => array(-1, 0, 2),
	'arcade_create_match' => array(0, 2),
	'arcade_join_match' => array(0, 2),
	'arcade_join_invite_match' => array(0, 2),
	'arcade_edit_settings_own' => array(0, 2),
	'arcade_edit_settings_any' => array(),
);

?>