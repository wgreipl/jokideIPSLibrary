<?
	/**
	 * This file is part of the IPSLibrary.
	 *
	 * The IPSLibrary is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published
	 * by the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * The IPSLibrary is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with the IPSLibrary. If not, see http://www.gnu.org/licenses/gpl.txt.
	 */    

	 /**@addtogroup IPSxbmc
	 * @{
	 *
	 * @file          IPSxbmcChangeSettings.ips.php
	 * @author        Jörg Kling
	 *
	 * @version
	 * Version 2.50.1, 31.01.2012<br/>
	 * AudioMax Action Script
	 *
	 * Dieses Script ist als Action Script für diverse IPSxbmc Variablen hinterlegt, um
	 * eine Änderung über das WebFront zu ermöglichen.
	 *
	 */

 	include_once 'IPSxbmc.inc.php';

	$variableId    = $_IPS['VARIABLE'];
	$variableValue = $_IPS['VALUE'];
	$variableIdent = IPS_GetIdent($variableId);

	$serverId = IPSUtil_ObjectIDByPath('Program.IPSLibrary.data.modules.IPSxbmc.IPSxbmc_Server');
	$instanceId = IPS_GetParent($variableId);
	
	if ($serverId<>$instanceId) {
		$roomName = IPS_GetIdent($instanceId);
	}
	

	switch($variableIdent) {


		case IPSxbmc_VAR_ROOMPOWER:
			IPSxbmc_SetRoomPower($roomName, $variableValue);			
			break;
		case IPSxbmc_VAR_VOLUME:
			IPSxbmc_SetVolume($roomName, $variableValue);
			break;
		case IPSxbmc_VAR_MUTE:
			IPSxbmc_SetMute($roomName, $variableValue);
			break;
		case IPSxbmc_VAR_TRANSPORT:
		
			switch($variableValue) {
				case IPSxbmc_TRA_PREVIOUS:
					IPSxbmc_PREVIOUS($roomName);				
					break;			
				case IPSxbmc_TRA_PLAY:
					IPSxbmc_Play($roomName);				
					break;
				case IPSxbmc_TRA_PAUSE:
					IPSxbmc_Pause($roomName);
					break;
				case IPSxbmc_TRA_STOP:
					IPSxbmc_Stop($roomName);						
					break;
				case IPSxbmc_TRA_NEXT:
					IPSxbmc_NEXT($roomName);				
					break;					
				default:
					break;
			}
			break;
		case IPSxbmc_VAR_PLAYLIST:
			IPSxbmc_PlayPlaylistByID($roomName, $variableValue);
			break;
		case IPSxbmc_VAR_RADIOSTATION:
			IPSxbmc_PlayRadiostationByID($roomName, $variableValue);
			break;			
		case IPSxbmc_VAR_SHUFFLE:
			IPSxbmc_SetShuffle($roomName, $variableValue);
			break;
		case IPSxbmc_VAR_REPEAT:
			IPSxbmc_SetRepeat($roomName, $variableValue);
			break;			
		default:
			break;
	}
	;
	/** @}*/
?>