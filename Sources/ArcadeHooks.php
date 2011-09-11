<?php

/**********************************************************************************
* ArcadeHooks.php                                                                 *
***********************************************************************************
* SMF Arcade                                                                      *
* =============================================================================== *
* Software Version:           SMF Arcade 2.5 RC1.1C                               *
* Software by:                Niko Pahajoki (http://www.madjoki.com)              *
* Copyright 2004-2011 by:     Niko Pahajoki (http://www.madjoki.com)              *
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
	
function smfArcade_array_insert(&$input, $key, $insert, $where = 'before', $strict = false)
{
	$position = array_search($key, array_keys($input), $strict);
	
	// Key not found -> insert as last
	if ($position === false)
	{
		$input = array_merge($input, $insert);
		return;
	}
	
	if ($where === 'after')
		$position += 1;

	// Insert as first
	if ($position === 0)
		$input = array_merge($insert, $input);
	else
		$input = array_merge(
			array_slice($input, 0, $position, true),
			$insert,
			array_slice($input, $position, null, true)
		);
}
	
function smfArcade_actions(&$actionArray)
{	
	loadLanguage('Arcade');		
	$actionArray['arcade'] = array('Arcade.php', 'Arcade');	
	
}


function smfArcade_load_permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	global $context;
	loadLanguage('Arcade');
		
	$permissionList['membergroup'] += array(
			'arcade_view' => array(false, 'arcade', 'arcade'),
			'arcade_play' => array(false, 'arcade', 'arcade'),
			'arcade_submit' => array(false, 'arcade', 'arcade'),
			'arcade_comment' => array(true, 'arcade', 'arcade', 'arcade_moderate'),
			'arcade_user_stats' => array(true, 'arcade', 'arcade', 'arcade_moderate'),
			'arcade_edit_settings' => array(true, 'arcade', 'arcade', 'arcade_moderate'),
			'arcade_create_match' => array(false, 'arcade', 'arcade'),
			'arcade_join_match' => array(false, 'arcade', 'arcade'),
			'arcade_join_invite_match' => array(false, 'arcade', 'arcade'),
			'arcade_admin' => array(false, 'arcade', 'administrate'),		
	);
	
	$context['non_guest_permissions'] = array_merge(
		$context['non_guest_permissions'],
		array(
			'arcade_admin',
			'arcade_create_match',
			'arcade_join_match',
			'arcade_join_invite_match',
			'arcade_comment',
			'arcade_edit_settings',
			'arcade_user_stats',		
		)
	);
	
	$permissionGroups['membergroup']['simple'] += array(
			'arcade',		
	);	
	$permissionGroups['membergroup']['classic'] += array(
			'arcade',		
	);	
		
}

function smfArcade_menu_buttons(&$menu_buttons)
{
	global $context, $modSettings, $scripturl, $txt;
	loadLanguage('Arcade');
			
	$context['allow_arcade'] = allowedTo('arcade_view') && !empty($modSettings['arcadeEnabled']);	
	if (!$context['allow_admin'])
		{$context['allow_admin'] = allowedTo('arcade_admin');}
	
	smfArcade_array_insert($menu_buttons, 'search',
		array(
			'arcade' => array(
                'title' => $txt['arcade'],
				'href' => $scripturl . '?action=arcade',
				'show' => $context['allow_arcade'],
				'active_button' => false,
				'sub_buttons' => array(
				),			
			),
		)
	);
}

function smfArcade_core_features(&$core_features)
{
	global $context, $modSettings, $txt;	
	loadLanguage('Arcade');	
	smfArcade_array_insert($core_features, 'w',
		array(
			'arcade' => array(
			'url' => 'action=admin;area=arcade',
			'settings' => array(
				'arcadeEnabled' => 1,
				),
			),
		)
	);
}

function smfArcade_profile_areas(&$profile_areas)
{
	global $modSettings, $txt;
	loadLanguage('Arcade');	
	$profile_areas['profile_action']['areas'] += 
		array(
			'arcadeChallenge' => array(
				'label' => $txt['sendArcadeChallenge'],
				'file' => 'Profile-Arcade.php',
				'function' => 'arcadeChallenge',
				'enabled' => !empty($modSettings['arcadeArenaEnabled']) && !empty($modSettings['arcadeEnabled']),
				'permission' => array(
					'own' => array(),
					'any' => array('arcade_create_match'),
					),
				)
	);
	$profile_areas['info']['areas'] += 
		array(
			'arcadeStats' => array(
					'label' => $txt['arcadeStats'],
					'file' => 'Profile-Arcade.php',
					'function' => 'arcadeStats',
					'enabled' => !empty($modSettings['arcadeEnabled']),
					'permission' => array(
						'own' => array('arcade_user_stats_any', 'arcade_user_stats_own'),
						'any' => array('arcade_user_stats_any'),
					),
				)
	);
	$profile_areas['edit_profile']['areas'] += 
		array(
			'arcadeSettings' => array(
					'label' => $txt['arcadeSettings'],
					'file' => 'Profile-Arcade.php',
					'function' => 'arcadeSettings',
					'enabled' => !empty($modSettings['arcadeEnabled']),
					'permission' => array(
						'own' => array('arcade_edit_settings_any', 'arcade_edit_settings_own'),
						'any' => array('arcade_edit_settings_any'),
					),
				)
	);
	
}

function smfArcade_admin_areas(&$admin_areas)
{
	global $txt, $modSettings;
	loadLanguage('Arcade');
		
	smfArcade_array_insert($admin_areas, 'members',
			array(
				'arcade' => array(
				'title' => $txt['arcade_admin'],
				'permission' => array('arcade_admin'),
				'areas' => array(
					'arcade' => array(
						'label' => $txt['arcade_general'],
						'file' => 'ArcadeAdmin.php',
						'function' => 'ArcadeAdmin',
						'enabled' => !empty($modSettings['arcadeEnabled']),
						'permission' => array('arcade_admin'),
						'subsections' => array(
							'main' => array($txt['arcade_general_information']),
							'settings' => array($txt['arcade_general_settings']),
							'permission' => array($txt['arcade_general_permissions']),
						),
					),
					'managegames' => array(
						'label' => $txt['arcade_manage_games'],
						'file' => 'ManageGames.php',
						'function' => 'ManageGames',
						'enabled' => !empty($modSettings['arcadeEnabled']),
						'permission' => array('arcade_admin'),
						'subsections' => array(
							'main' => array($txt['arcade_manage_games_edit_games']),
							'install' => array($txt['arcade_manage_games_install']),
							'upload' => array($txt['arcade_manage_games_upload']),
						),
					),
					'arcadecategory' => array(
						'label' => $txt['arcade_manage_category'],
						'file' => 'ArcadeAdmin.php',
						'function' => 'ArcadeAdminCategory',
						'enabled' => !empty($modSettings['arcadeEnabled']),
						'permission' => array('arcade_admin'),
						'subsections' => array(
							'list' => array($txt['arcade_manage_category_list']),
							'new' => array($txt['arcade_manage_category_new']),
						),
					),
					'arcademaintenance' => array(
						'label' => $txt['arcade_maintenance'],
						'file' => 'ArcadeMaintenance.php',
						'function' => 'ArcadeMaintenance',
						'enabled' => !empty($modSettings['arcadeEnabled']),
						'permission' => array('arcade_admin'),
						'subsections' => array(
							'main' => array($txt['arcade_maintenance_main']),
							'highscore' => array($txt['arcade_maintenance_highscore']),
						),
					),
				),
			),
		)
	);
}

function smfArcade_submit()
{
	global $sourcedir;
	// Check for arcade actions
	// IBPArcade v2.x.x Games support
	if (isset($_REQUEST['act']) && strtolower($_REQUEST['act']) == 'arcade')
	{
		$_REQUEST['action'] = 'arcade';

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'newscore')
			$_REQUEST['sa'] = 'ibpsubmit2';

		require_once($sourcedir . '/Arcade.php');
		return 'Arcade';
	}
	// IBPArcade v3.x.x Games support
	elseif (isset($_REQUEST['autocom']) && $_REQUEST['autocom'] == 'arcade')
	{
		$_REQUEST['action'] = 'arcade';

		if (isset($_REQUEST['do']) && $_REQUEST['do'] == 'savescore')
			$_REQUEST['sa'] = 'ibpsubmit3';
		elseif (isset($_REQUEST['do']) && $_REQUEST['do'] == 'verifyscore')
			$_REQUEST['sa'] = 'ibpverify';

		require_once($sourcedir . '/Arcade.php');
		return 'Arcade';
	}
	elseif (isset($_REQUEST['play']) && !isset($_REQUEST['game']))
	{
		$_REQUEST['game'] = $_REQUEST['play'];
		unset($_REQUEST['play']);
		$_REQUEST['sa'] = 'play';

		require_once($sourcedir . '/Arcade.php');
		return 'Arcade';
	}
	elseif (isset($_REQUEST['highscore']) && !isset($_REQUEST['game']))
	{
		$_REQUEST['game'] = $_REQUEST['highscore'];
		unset($_REQUEST['highscore']);
		$_REQUEST['sa'] = 'highscore';

		require_once($sourcedir . '/Arcade.php');
		return 'Arcade';
	}
	elseif ((isset($_REQUEST['game']) || isset($_REQUEST['match'])) && !isset($_REQUEST['action']))
	{
		require_once($sourcedir . '/Arcade.php');
		return 'Arcade';
	}
	else 
	{
		return false;
	}
}
?>