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

	/**@defgroup IPSSonos_api IPSSonos API
	 * @ingroup IPSSonos
	 * @{
	 *
	 * IPSSonos Server API
	 *
	 * @file          IPSSonos.inc.php
	 * @author        jokie
	 * @version
	 * Version 1.0.0, 31.08.2014<br/>
	 *
	 * Dieses File kann von anderen Scripts per INCLUDE eingebunden werden und enthält Funktionen
	 * um alle IPSSonos Funktionen bequem per Funktionsaufruf steueren zu können.
	 *
	 */

 	include_once 'IPSSonos_Server.class.php';

// ----------------------------------------------------------------------------------------------------------------------------
// Interfaces für Server
// ----------------------------------------------------------------------------------------------------------------------------

	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_SyncPlaylists() {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SYNCPL, null);
	}	
	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_SyncRadiostations() {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SYNCRD, null);
	}
		
	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
//	function IPSSonos_QueryData() {
//		$server = IPSSonos_GetServer();
//		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_QUERYDATA, null);
//	}	

	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_SwitchAllRoomsOff() {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_ALLROOMSOFF, null);
	}		

	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */	
	function IPSSonos_GetAllRooms() {
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_ROOMS, null);
	}	

	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */	
	function IPSSonos_GetAllActiveRooms() {
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_ROOMSACTIVE, null);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */	
	function IPSSonos_SetQuery($value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SETQUERY, $value);
	}	

	/**
	 *  @brief Brief
	 *  
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */	
	function IPSSonos_SetQueryTime($value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SETQUERYTIME, $value);
	}	
	
// ----------------------------------------------------------------------------------------------------------------------------
// Interfaces für Räume
// ----------------------------------------------------------------------------------------------------------------------------
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_SetRoomPower($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_ROOM, $roomName, IPSSONOS_FNC_POWER, $value);
	}
	
	/**
	 * Status Raumverstärker lesen
	 *
	 * @param int $instanceId ID des IPSSonos Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return boolean Status Raumverstärker
	 */
	function IPSSonos_GetRoomPower($roomName) {
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_ROOM, $roomName, IPSSONOS_FNC_POWER, null);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_Play($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAY, null);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_Pause($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PAUSE, null);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_Stop($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_STOP, null);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_Next($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_NEXT, null);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_Previous($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PREVIOUS, null);
	}	

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_RampToVolumeMute($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_RMPMUTE, $value);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_RampToVolumeMuteSlow($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_RMPMUTESLOW, $value);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_RampToVolume($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_RMP, $value);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_SetShuffle($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_SHUFFLE, $value);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_GetShuffle($roomName){
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_SHUFFLE, null);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_SetRepeat($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_REPEAT, $value);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_GetRepeat($roomName){
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_REPEAT, null);
	}		


	/**
	 * Laustärke setzen
	 *
	 * @param int $instanceId  ID des IPSSonos Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Lautstärke (0-40)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSSonos_SetVolume($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME, $value);
	}
	/**
	 * Laustärke erhöhen
	 *
	 * @param int $instanceId  ID des IPSSonos Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Lautstärke (0-40)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSSonos_IncVolume($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_INC, $value);
	}
	/**
	 * Laustärke verringern
	 *
	 * @param int $instanceId  ID des IPSSonos Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Lautstärke (0-40)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSSonos_DecVolume($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_DEC, $value);
	}
		
	/**
	 * Laustärke lesen
	 *
	 * @param int $instanceId  ID des IPSSonos Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return int Lautstärke (0-40)
	 */
	function IPSSonos_GetVolume($instanceId, $roomId) {
		$server = IPSSonos_GetServer($instanceId);
		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomId, IPSSONOS_FNC_VOLUME, null);
	}

	/**
	 * Muting setzen
	 *
	 * @param int $instanceId ID des IPSSonos Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param string $value TRUE oder '1' für An, FALSE oder '0' für Aus
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSSonos_SetMute($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_MUTE, $value);
	}

	/**
	 * Status Muting lesen
	 *
	 * @param int $instanceId ID des IPSSonos Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return boolean Status Muting
	 */
	function IPSSonos_GetMute($roomName) {
		$server = IPSSonos_GetServer($instanceId);
		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomId, IPSSONOS_FNC_MUTE, null);
	}
	
	/**
	 * Switch Mute
	 *
	 * @param int $instanceId ID des IPSSonos Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return boolean Status Muting
	 */
	function IPSSonos_SwitchMute($roomName) {
		$server = IPSSonos_GetServer($instanceId);
		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomId, IPSSONOS_FNC_MUTE, null);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_PlayPlaylistByID($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYPLID, $value);
	}	

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_PlayPlaylistByName($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYPLNAME, $value);
	}
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
		 */	function IPSSonos_PlayRadiostationByID($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYRDID, $value);
	}	

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Parameter_Description
	 *  @param [in] $value Parameter_Description
	 *  @return Return_Description
	 *  
	 *  @details Details
	 */
	function IPSSonos_PlayRadiostationByName($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYRDNAME, $value);
	}		
	
// ----------------------------------------------------------------------------------------------------------------------------
// Hilfsfunktionen
// ----------------------------------------------------------------------------------------------------------------------------
	 
	/**
	 *  @brief Get Server
	 *  
	 *  @return IPSSonos Server Object
	 *  
	 */	 
	function IPSSonos_GetServer() {
	   	$instanceId = IPSUtil_ObjectIDByPath('Program.IPSLibrary.data.modules.IPSSonos.IPSSonos_Server');
		return new IPSSonos_Server($instanceId);
	}
	
	/**
	 *  @brief 
	 *  
	 *  @return IPSSonos Server Object
	 *  
	 */	 
	function WriteBoolean($value) {
	   	if ($value)  {
			$result = 'an';
		}
		else {
			$result = 'aus';
		}
		return $result;
	}

   /** @}*/


?>