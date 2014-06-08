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

	 /**@addtogroup IPSSonos
	 * @{
	 *
	 * @file          IPSSonosChangeSettings.ips.php
	 * @author        Jörg Kling
	 *
	 * @version
	 * Version 2.50.1, 31.01.2012<br/>
	 * AudioMax Action Script
	 *
	 * Dieses Script ist als Action Script für diverse IPSSonos Variablen hinterlegt, um
	 * eine Änderung über das WebFront zu ermöglichen.
	 *
	 */

 	include_once 'IPSSonos.inc.php';

	$variableId    = $_IPS['VARIABLE'];
	$variableValue = $_IPS['VALUE'];
	$variableIdent = IPS_GetIdent($variableId);
// $I = 10 / 0;
	$serverId = IPSUtil_ObjectIDByPath('Program.IPSLibrary.data.modules.IPSSonos.IPSSonos_Server');
	$instanceId = IPS_GetParent($variableId);
	
	if ($serverId<>$instanceId) {
		$roomName = IPS_GetIdent($instanceId);
	}
	
//	$server = new IPSSonos_Server($serverId);

	switch($variableIdent) {


		case IPSSONOS_VAR_ROOMPOWER:
			IPSSonos_SetRoomPower($roomName, $variableValue);			
			break;
//		case IPSSONOS_VAR_BALANCE:
//			IPSSonos_SetBalance($serverId , $roomId, $variableValue);
			break;
		case IPSSONOS_VAR_VOLUME:
			IPSSonos_SetVolume($roomName, $variableValue);
			break;
		case IPSSONOS_VAR_MUTE:
			IPSSonos_SetMute($roomName, $variableValue);
			break;
		case IPSSONOS_VAR_TRANSPORT:
		
			switch($variableValue) {
				case IPSSONOS_TRA_PREVIOUS:
					IPSSonos_PREVIOUS($roomName);				
					break;			
				case IPSSONOS_TRA_PLAY:
					IPSSonos_Play($roomName);				
					break;
				case IPSSONOS_TRA_PAUSE:
					IPSSonos_Pause($roomName);
				case IPSSONOS_TRA_STOP:
					IPSSonos_Stop($roomName);						
					break;
				case IPSSONOS_TRA_NEXT:
					IPSSonos_NEXT($roomName);				
					break;					
				default:
					break;
			}
			
//		case IPSSONOS_VAR_TREBLE:
//			IPSSonos_SetTreble($serverId , $roomId, $variableValue);
//			break;
//		case IPSSONOS_VAR_MIDDLE:
//			IPSSonos_SetMiddle($serverId , $roomId, $variableValue);
//			break;
//		case IPSSONOS_VAR_BASS:
//			IPSSonos_SetBass($serverId , $roomId, $variableValue);
//			break;
//		case IPSSONOS_VAR_INPUTSELECT:
//			IPSSonos_SetInputSelect($serverId , $roomId, $variableValue);
//			break;
//		case IPSSONOS_VAR_INPUTGAIN:
//			IPSSonos_SetInputGain($serverId , $roomId, $variableValue);
//			break;
		default:
			break;
	}
	;
	/** @}*/
?>