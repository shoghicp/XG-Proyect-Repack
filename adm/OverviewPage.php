<?php

##############################################################################
# *																			 #
# * XG PROYECT																 #
# *  																		 #
# * @copyright Copyright (C) 2008 - 2009 By lucky from xgproyect.net      	 #
# *																			 #
# *																			 #
# *  This program is free software: you can redistribute it and/or modify    #
# *  it under the terms of the GNU General Public License as published by    #
# *  the Free Software Foundation, either version 3 of the License, or       #
# *  (at your option) any later version.									 #
# *																			 #
# *  This program is distributed in the hope that it will be useful,		 #
# *  but WITHOUT ANY WARRANTY; without even the implied warranty of			 #
# *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the			 #
# *  GNU General Public License for more details.							 #
# *																			 #
##############################################################################

define('INSIDE'  , true);
define('INSTALL' , false);
define('IN_ADMIN', true);

$xgp_root = './../';
include($xgp_root . 'extension.inc.php');
include($xgp_root . 'common.' . $phpEx);

if ($user['authlevel'] < 1) die(message ($lang['404_page']));

function check_updates()
{
	global $game_config;
	if (function_exists('file_get_contents'))
	{
		$current = explode(";", trim(@file_get_contents('http://www.ugamelaplay.net/xg/rp/last.txt')));

		if (version_compare ( $current[0] , VERSION , ">" ))
		{
			switch($current[2]){
				case 0:
					$current[2] = '<span style="color:yellow;">&bull;</span><span style="color:grey;">&bull;&bull;&bull;&bull;</span>';
					break;
				case 1:
					$current[2] = '<span style="color:yellow;">&bull;&bull;</span><span style="color:grey;">&bull;&bull;&bull;</span>';
					break;
				case 2:
					$current[2] = '<span style="color:yellow;">&bull;&bull;&bull;</span><span style="color:grey;">&bull;&bull;</span>';
					break;
				case 3:
					$current[2] = '<span style="color:yellow;">&bull;&bull;&bull;&bull;</span><span style="color:grey;">&bull;</span>';
					break;
				case 4:
					$current[2] = '<span style="color:yellow;">&bull;&bull;&bull;&bull;&bull;</span><span style="color:grey;"></span>';
					break;
			}
			return $current;
		}
		else
		{
			return false;
		}
	}
}

$parse	=	$lang;

if(file_exists($xgp_root . 'install/') && defined('IN_ADMIN'))
{
	$Message	.= "<font color=\"red\">".$lang['ow_install_file_detected']."</font><br/><br/>";
	$error++;
}

if ($user['authlevel'] >= 3)
{
	if(@fopen("./../config.php", "a"))
	{
		$Message	.= "<font color=\"red\">".$lang['ow_config_file_writable']."</font><br/><br/>";
		$error++;
	}

	$Errors = doquery("SELECT COUNT(*) AS `errors` FROM {{table}} WHERE 1;", 'errors', true);

	if($Errors['errors'] != 0)
	{
		$Message	.= "<font color=\"red\">".$lang['ow_database_errors']."</font><br/><br/>";
		$error++;
	}
	$curr = check_updates();
	if($curr !== false)
	{
		$Message	.= "<span style='font-size:14px;color:lime;'>La version <b style='color:skyblue;'>".$curr[0]."</b> ya esta disponible.";
		if(version_compare ( $curr[1] , XG_BASED , ">" )){
			$Message .= "<br>La nueva version esta basada en XG ".$curr[1].", mas nueva que la actual base";
		}
		$Message 	.= "<br>Prioridad: ".$curr[2];
		$Message	.= " <br> Haz click <a href='http://www.ugamelaplay.net/xg/rp/last.zip' style='font-size:14px;'><b><u>aqui</u></b></a> para descargarla</span><br/><br/>";
		$error++;
	}
}

if($error != 0)
{
	$parse['error_message']		=	$Message;
	$parse['color']				=	"red";}
else
{
	$parse['error_message']		= 	$lang['ow_none'];
	$parse['color']				=	"lime";
}


display( parsetemplate(gettemplate('adm/OverviewBody'), $parse), false, '', true, false);
?>
