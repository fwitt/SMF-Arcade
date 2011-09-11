<?php
/**********************************************************************************
* Subs-Install.php                                                                *
***********************************************************************************
* SMF Arcade                                                                      *
* =============================================================================== *
* Software Version:           SMF Arcade 2.5 RC1.1C                               *
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

function doTables()
{
	global $db_prefix, $modSettings, $smcFunc;

	/* A whole bunch of needed arrays... */
	/* SMF Arcade 2.5 settings variables and values */
	$smfac_settings_names = array('arcadeCheckLevel', 'arcadeCommentLen', 'arcadeEnabled', 'arcadeEnableFavorites', 'arcadeEnableRatings', 'GamecacheUpdate', 'arcadePermissionMode','arcadeShowInfoCenter', 'arcadeArenaEnabled', 'arcadeDBUpdate', 'arcadeVersion');
	$smfac_settings_values = array('1','75','1','1','1','1','0','1','1','0','2.5 RC1.1C');

	/*     SMF Arcade 2.5 db tables/columns    */
	$smfac_tables = array('arcade_games', 'arcade_categories', 'arcade_scores', 'arcade_favorite', 'arcade_files', 'arcade_game_info', 'arcade_settings', 'arcade_rates', 'arcade_matches', 'arcade_matches_players', 'arcade_matches_results', 'arcade_matches_rounds');
	$smf_arcade_db = array(
		'arcade_games' => array('id_game', 'internal_name', 'game_name', 'game_file', 'game_directory', 'description', 'help', 'thumbnail', 'thumbnail_small', 'submit_system', 'id_cat', 'enabled', 'local_permissions', 'score_type', 'member_groups', 'game_rating', 'id_champion', 'id_champion_score', 'extra_data', 'num_plays', 'num_rates', 'num_favorites'),
'arcade_categories' => array('id_cat', 'cat_name', 'num_games', 'cat_order', 'special', 'member_groups'),
		'arcade_scores' => array('id_score', 'id_game', 'id_member', 'score', 'duration', 'end_time', 'champion_from', 'champion_to', 'position', 'personal_best', 'score_status', 'member_ip', 'player_name', 'comment', 'validate_hash'),
		'arcade_favorite' => array('id_favorite', 'id_member', 'id_game'),
		'arcade_files' => array('id_file', 'id_game', 'file_type', 'game_name', 'status', 'game_file', 'game_directory'),
		'arcade_game_info' => array('internal_name', 'game_name', 'description', 'info_url', 'download_url'),
		'arcade_settings' => array('id_member', 'variable', 'value'),
		'arcade_rates' => array('id_member', 'id_game', 'rating', 'rate_time'),
		'arcade_matches' => array('id_match', 'name', 'id_member', 'private_game', 'status', 'created', 'updated', 'num_players', 'current_players', 'num_rounds', 'current_round', 'match_data'),
		'arcade_matches_players' => array('id_match', 'id_member', 'status', 'score', 'player_data'),
		'arcade_matches_results' => array('id_match', 'id_member', 'round', 'score', 'duration', 'end_time', 'score_status', 'validate_hash'),
		'arcade_matches_rounds' => array('id_match', 'round', 'id_game', 'status'));

	$tablesTypes = array(
		'arcade_games' => array('int(10) unsigned NOT NULL AUTO_INCREMENT', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'text NOT NULL', 'text NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'varchar(15) NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'tinyint(3) unsigned NOT NULL DEFAULT 0', 'varchar(255) NOT NULL DEFAULT "-2,-1,0,2"', 'float NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'text NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0'),
		'arcade_categories' => array('int(10) unsigned NOT NULL AUTO_INCREMENT', 'varchar(20) NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 1', 'int(10) unsigned NOT NULL DEFAULT 0', 'varchar(255) NOT NULL DEFAULT "-2,-1,0,2"'),
		'arcade_scores' => array('int(10) unsigned NOT NULL AUTO_INCREMENT', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'float NOT NULL', 'float NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'tinyint(3) unsigned NOT NULL DEFAULT 0', 'varchar(30) NOT NULL', 'varchar(15) NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL'),
		'arcade_favorite' => array('int(10) unsigned NOT NULL AUTO_INCREMENT', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL'),					
		'arcade_files' => array ('int(10) unsigned NOT NULL AUTO_INCREMENT', 'int(10) unsigned NOT NULL', 'varchar(30) NOT NULL default "game"', 'varchar(255) NOT NULL', 'int(10) unsigned NOT NULL default 0', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL'),                         
		 'arcade_game_info' => array('varchar(255) NOT NULL', 'varchar(255) NOT NULL', 'text NOT NULL', 'varchar(255) NOT NULL', 'varchar(255) NOT NULL'),
		 'arcade_settings' => array('int(11) NOT NULL', 'varchar(30) NOT NULL', 'text NOT NULL'),
		 'arcade_rates' => array('int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0'),
		 'arcade_matches' => array('int(10) unsigned NOT NULL AUTO_INCREMENT', 'varchar(255) NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 2', 'int(10) unsigned NOT NULL DEFAULT 1', 'int(10) unsigned NOT NULL DEFAULT 1', 'int(10) unsigned NOT NULL DEFAULT 0', 'text NOT NULL'),
		 'arcade_matches_players' => array('int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'text NOT NULL'),
		 'arcade_matches_results' => array('int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'float NOT NULL DEFAULT 0', 'float NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL DEFAULT 0', 'varchar(30) NOT NULL', 'varchar(255) NOT NULL'),
		 'arcade_matches_rounds' => array('int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0', 'int(10) unsigned NOT NULL', 'int(10) unsigned NOT NULL DEFAULT 0'));		
		
				   				   
	/*   Check the settings variables...  create or alter them to match smf arcade v2.5 defaults   */
	$tableName = 'settings';
	$i = 0;
	foreach ($smfac_settings_names as $columnName)
		{
			$request = false;
    		$a = false;    		 
    		$value = $smfac_settings_values[$i];		
			$ArcadeSettingsUpdate = array($columnName => $value);
			updateSettings($ArcadeSettingsUpdate);
			/*
			if (isset($modSettings[$columnName])) {$a = true;}	 
    		if ($a == false)
	    		{$request = $smcFunc['db_query']('', "INSERT INTO `{db_prefix}$tableName` (`variable` , `value`) VALUES ('$columnName', '$value')");}		  
			elseif ($a == true && $modSettings[$columnName] != $value)
	    		{$request = $smcFunc['db_query']('', "UPDATE `{$db_prefix}$tableName` SET `value` = '$value' WHERE `{$db_prefix}$tableName`.`variable` = '$columnName' LIMIT 1");}	
			*/	  
  			$i++;
		}	

	/*  Add extra needed tables/columns if they do not exist */
	foreach ($smfac_tables as $table1)
		{
			$result = false;
			$z = false;
			$z = check_table_Arcade($table1);

			if ($z == false)
				{
					$i = 0;
					$var = false;
					foreach ($smf_arcade_db[$table1] as $column)
						{
							$var .= "`".$column."` ". $tablesTypes[$table1][$i] . ",";
							$i++;
						}	
					$var .= "PRIMARY KEY(`".$smf_arcade_db[$table1][0]."`)"; 
					$result = $smcFunc['db_query']('', "CREATE TABLE {$db_prefix}{$table1} ($var)");
				}
			else
				{			
					$i = 0;
					foreach ($smf_arcade_db[$table1] as $column)
						{	
							if (empty($tablesTypes[$table1][$i])) {break;}
							$y = false;		
							$y = checkFieldArcade($table1,$column);
							$type = $tablesTypes[$table1][$i];
							if ($y == false)
								{$request = $smcFunc['db_query']('', "ALTER TABLE {$db_prefix}$table1 ADD $column $type");}
							$i++;	
						}	
				}
		}

} 

function doSettings($addSettings)
{
	global $smcFunc, $modSettings;

	$update = array();

	foreach ($addSettings as $variable => $value)
	{
		list ($value, $overwrite) = $value;

		if ($overwrite || !isset($modSettings[$variable]))
			$update[$variable] = $value;
	}

	if (!empty($update))
		updateSettings($update);
}

function doPermission($permissions)
{
	global $smcFunc;

	$perm = array();

	foreach ($permissions as $permission => $default)
	{
		$result = $smcFunc['db_query']('', '
			SELECT COUNT(*)
			FROM {db_prefix}permissions
			WHERE permission = {string:permission}',
			array(
				'permission' => $permission
			)
		);

		list ($num) = $smcFunc['db_fetch_row']($result);

		if ($num == 0)
		{
			foreach ($default as $grp)
				$perm[] = array($grp, $permission);
		}
	}

	if (empty($perm))
		return;

	$smcFunc['db_insert']('insert',
		'{db_prefix}permissions',
		array(
			'id_group' => 'int',
			'permission' => 'string'
		),
		$perm,
		array()
	);
}

function doArcadeHooks()
{
	/* Insert integration hooks */
	add_integration_function('integrate_pre_include', '$sourcedir/ArcadeHooks.php');
	add_integration_function('integrate_actions', 'smfArcade_actions');
	add_integration_function('integrate_load_permissions', 'smfArcade_load_permissions');
	add_integration_function('integrate_menu_buttons', 'smfArcade_menu_buttons');
	add_integration_function('integrate_admin_areas', 'smfArcade_admin_areas');
	add_integration_function('integrate_core_features', 'smfArcade_core_features');
	add_integration_function('integrate_profile_areas', 'smfArcade_profile_areas');	
}

function updateAdminFeatures($item, $enabled = false)
{
	global $modSettings;

	$admin_features = isset($modSettings['admin_features']) ? explode(',', $modSettings['admin_features']) : array('cd,cp,k,w,rg,ml,pm');

	if (!is_array($item))
		$item = array($item);

	if ($enabled)
		$admin_features = array_merge($admin_features, $item);
	else
		$admin_features = array_diff($admin_features, $item);

	updateSettings(array('admin_features' => implode(',', $admin_features)));

	return true;
}

/* Check if the column exists */
function checkFieldArcade($tableName,$columnName)
{
	$checkTable = false;
	$checkTable = check_table_Arcade($tableName);
	if ($checkTable == true)
		{
			global $db_prefix, $smcFunc;
			$check = false;
			$checkval = false;
			$check = $smcFunc['db_query']('', "DESCRIBE {$db_prefix}$tableName $columnName");
			$checkval = $smcFunc['db_num_rows']($check);
			$smcFunc['db_free_result']($check);
			if ($checkval > 0) {return true;}
		}
	return false;
} 

/*  Check if table exists  */
function check_table_Arcade($table)
{
	global $db_prefix, $smcFunc;
	$check = false;
	$checkval = false;
	$check = $smcFunc['db_query']('', "SHOW TABLES LIKE '{$db_prefix}$table'");
	$checkval = $smcFunc['db_num_rows']($check);
	$smcFunc['db_free_result']($check);
	if ($checkval >0) {return true;}
	return false;
}	

?>