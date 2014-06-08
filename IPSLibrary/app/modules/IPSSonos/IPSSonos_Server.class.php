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

	/**@defgroup IPSSonos IPSSonos Multiroom Steuerung
	 * @ingroup hardware
	 * @{
	 *
	 * Klasse zur Kommunikation mit dem IPSSonos Device 
	 *
	 * @file          IPSSonos_Server.class.php
	 * @author        Andreas Brauneis
	 *
	 */

	IPSUtils_Include ("IPSLogger.inc.php",              "IPSLibrary::app::core::IPSLogger");
	IPSUtils_Include ("IPSSonos_Constants.inc.php",     "IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Configuration.inc.php", "IPSLibrary::config::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Room.class.php",        "IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Custom.inc.php",        "IPSLibrary::config::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos.inc.php", 				"IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("PHPSonos.inc.php", 				"IPSLibrary::app::modules::IPSSonos");

   /**
    * @class IPSSonos_Server
    *
    * Definiert ein IPSSonos_Server Objekt
    *
    * @author Andreas Brauneis
    * @version
    * Version 2.50.1, 31.01.2012<br/>
    */
	class IPSSonos_Server {

		/**
		 * @private
		 * ID des IPSSonos Server
		 */
		private $instanceId;

		/**
		 * @private
		 * IPadress
		 */
		private $IPAddr;

		/**
		 * @private
		 * Debugging of IPSSonos Server Enabled/Disabled
		 */
		private $debugEnabled;

		/** 
		 * @public
		 *
		 * Initializes the IPSSonos Server
		 *
		 * @param integer $instanceId - ID des IPSSonos Server.
		 */
		public function __construct($instanceId) {
			$this->instanceId   = $instanceId;
			$this->IPAddr		= GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_IPADDR, $this->instanceId));
			$this->debugEnabled = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_MODESERVERDEBUG, $this->instanceId));
			$this->retryCount  = 0;
		}

		/**
		 * @private
		 *
		 * Protokollierung einer Meldung im IPSSonos Log
		 *
		 * @param string $logType Type der Logging Meldung 
		 * @param string $msg Meldung 
		 */
		private function Log ($logType, $msg) {
			if ($this->debugEnabled) {
				IPSLogger_WriteFile("", 'IPSSonos.log', date('Y-m-d H:i:s').'  '.$logType.' - '.$msg, null);
			}
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Error Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogErr($msg) {
			IPSLogger_Err(__file__, $msg);
			$this->Log('Err', $msg);
//			$variableId  = IPS_GetObjectIDByIdent(IPSSONOS_VAR_LASTERROR, $this->instanceId);
//			SetValue($variableId, $msg);
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Info Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogInf($msg) {
			IPSLogger_Inf(__file__, $msg);
			$this->Log('Inf', $msg);
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Debug Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogDbg($msg) {
			IPSLogger_Dbg(__file__, $msg);
			$this->Log('Dbg', $msg);
		}

		/**
		 * @private
		 *
		 * Protokollierung einer Kommunikations Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogCom($msg) {
			IPSLogger_Com(__file__, $msg);
			$this->Log('Com', $msg);
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Trace Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogTrc($msg) {
			IPSLogger_Trc(__file__, $msg);
			$this->Log('Trc', $msg);
		}
		

	
		
		/**
		 * @private
		 *
		 * Liefert ein IPSSonosRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSSonos_Room IPSSonos Room Object
		 */
		private function GetRoom($roomName) {
			$roomIds = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMIDS, $this->instanceId));
			if ($roomIds=="") {
				return false;
			}
			$roomIds        = explode(',',  $roomIds);
			$roomInstanceId = false;
			$IPSSonosRoom   = false;
			foreach ($roomIds as $roomId){
				if ($roomName == IPS_GetName((int)$roomId)) {
					$roomInstanceId = (int)$roomId;
					$IPSSonosRoom = new IPSSonos_Room($roomInstanceId);
				}
			}

			return $IPSSonosRoom;
		}

		/**
		 * @private
		 *
		 * Liefert ein IPSSonosRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSSonos_Room IPSSonos Room Object
		 */
		private function GetAllRooms() {
			$roomIds = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMIDS, $this->instanceId));
			if ($roomIds=="") {
				return false;
			}
			$roomIds        = explode(',',  $roomIds);
			foreach ($roomIds as $roomId){
				$room_array[] = IPS_GetName((int)$roomId);
			}

			return $room_array;
		}

		/**
		 * @private
		 *
		 * Lesen der IPSSonos Werte aus den Instanz Variablen
		 *
		 * @param string $type Kommando Type
		 * @param string $command Kommando
		 * @param integer $roomId Nummer des Raumes
		 * @param string $function Funktion
		 * @return string Wert
		 */
		public function GetData ($command, $roomName, $function) {
			$result = '';
			switch ($command) {

				case IPSSONOS_CMD_ROOM:		
					
					$room = $this->GetRoom($roomName);				
					$result = $room->GetValue ($command, $function);

					break;
				case IPSSONOS_CMD_AUDIO:
						$room = $this->GetRoom($roomName);				
						$result = $room->GetValue ($command, $function);
					break;
				
				case IPSSONOS_CMD_SERVER:
					
					switch ($function) {
						case IPSSONOS_FNC_ROOMS:
							$result = $this->GetAllRooms();
							break;	
						case IPSSONOS_FNC_ROOMSACTIVE:
							$result = $this->GetActiveRooms();
							break;									
						default:
							break;
					}						
					break;					
				default:
					break;	
				default:
					$this->LogErr('Unknown Command '.$command);
			}
			return $result;
		}


		/**
		 * @public
		 *
		 * Senden von Befehlen zum IPSSonos Server
		 *
	    * @param string $type Kommando Type
	    * @param string $command Kommando
	    * @param integer $roomId Raum (0-15)
	    * @param string $function Funktion
	    * @param string $value Wert
	    * @return boolean TRUE für OK, FALSE bei Fehler
		 */
		public function SendData($command, $roomName, $function, $value) {
			$result = false;
					
			//*-------------------------------------------------------------*
			switch ($command) {
				case IPSSONOS_CMD_AUDIO:
				
					// Sonos initialisieren
					$room = $this->GetRoom($roomName);
					$sonos = new PHPSonos($room->IPAddr); 	
					
					if ($room->ValidateValue($sonos, $command, $function, $value) == false) {
						return false;
					}					
					switch ($function) {
						case IPSSONOS_FNC_VOLUME:
							$sonos->SetVolume($value);
							$this->LogInf('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;
						case IPSSONOS_FNC_VOLUME_RMP:
							$ramp_type =  "SLEEP_TIMER_RAMP_TYPE";
							$sonos->RampToVolume( $ramp_type, $value);
							$this->LogInf('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;
						case IPSSONOS_FNC_VOLUME_RMPMUTE: 
							$sonos->RampToVolume("AUTOPLAY_RAMP_TYPE", $value);
							$this->LogInf('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;
						case IPSSONOS_FNC_VOLUME_RMPMUTESLOW: 
							$sonos->RampToVolume("ALARM_RAMP_TYPE", $value);
							$this->LogInf('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;									
						case IPSSONOS_FNC_VOLUME_INC:	
								$current_volume = $sonos->GetVolume();
								$new_volume = $current_volume + $value;
								$sonos->SetVolume($new_volume);
								$this->LogInf('Lautstärke im Raum '.$roomName.' um '.$value.' erhöht auf: '.$new_volume);
								$value = $new_volume;
								$result = true;
							break;	
						case IPSSONOS_FNC_VOLUME_DEC:	
								$current_volume = $sonos->GetVolume();
								$new_volume = $current_volume - $value;
								$sonos->SetVolume($new_volume);
								$this->LogInf('Lautstärke im Raum '.$roomName.' um '.$value.' verringert auf: '.$new_volume);
								$value = $new_volume;
								$result = true;								
							break;
						case IPSSONOS_FNC_PLAY:	
								$sonos->Play();
								$this->LogInf('Abspielen im Raum '.$roomName.' gestartet.');
								$value = IPSSONOS_TRA_PLAY;
								$result = true;
							break;
						case IPSSONOS_FNC_STOP:	
								$sonos->Stop();
								$this->LogInf('Abspielen im Raum '.$roomName.' gestopt.');
								$value = IPSSONOS_TRA_STOP;	
								$result = true;
							break;
						case IPSSONOS_FNC_PAUSE:	
								$sonos->Pause();
								$this->LogInf('Abspielen im Raum '.$roomName.' pausiert.');
								$value = IPSSONOS_TRA_PAUSE;
								$result = true;
							break;
						case IPSSONOS_FNC_NEXT:	
								$sonos->Next();
								$this->LogInf('Nächstes Lied im Raum '.$roomName.' abspielen.');
								$function 	= IPSSONOS_FNC_PLAY;								
								$value 		= IPSSONOS_TRA_PLAY;
								$result = true;
							break;		
						case IPSSONOS_FNC_PREVIOUS:	
								$sonos->Previous();
								$this->LogInf('Vorheriges Lied im Raum '.$roomName.' abspielen.');
								$function 	= IPSSONOS_FNC_PLAY;								
								$value 		= IPSSONOS_TRA_PLAY;
								$result = true;
							break;
						case IPSSONOS_FNC_MUTE:	
								$sonos->SetMute($value);
								$this->LogInf('Mute im Raum '.$roomName.' gesetzt auf: '.$value);
								$function 	= IPSSONOS_FNC_MUTE;
								$result = true;
							break;								
						case IPSSONOS_FNC_PLAYPLNAME:	
								if ($this->SetQueuePlaylistByName($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogInf('Playlist '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Playlist '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;
						case IPSSONOS_FNC_PLAYPLID:	
								if ($this->SetQueuePlaylistByID($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogInf('Playlist mit ID '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Playlist mit ID '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;
						case IPSSONOS_FNC_PLAYRDNAME:	
								if ($this->SetQueueRadiostationByName($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogInf('Radiostation '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Radiostation '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;
						case IPSSONOS_FNC_PLAYRDID:	
								if ($this->SetQueueRadiostationByID($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogInf('Playlist mit ID '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Radiostation mit ID '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;							
						case IPSSONOS_FNC_SHUFFLE:
								$repeat = IPSSonos_GetRepeat($roomName);
								if ($repeat == true) {
									if ($value == true) { // SHUFFLE == true
										$mode = "SHUFFLE";
									}
									else { // SHUFFLE == false
										$mode = "REPEAT_ALL";
									}
								}
								else { //$repeat == false
									if ($value == true) { // SHUFFLE == true
										$mode = "SHUFFLE_NOREPEAT";
									}
									else { // SHUFFLE == false
										$mode = "NORMAL";
									}								
								}
								$sonos->SetPlayMode($mode);
								$this->LogInf('Mode im Raum '.$roomName.' gesetzt auf: '.$mode);
								$result = true;
							break;								
						case IPSSONOS_FNC_REPEAT:
								$shuffle = IPSSonos_GetShuffle($roomName);
								if ($shuffle == true) {
									if ($value == true) { // Repeat == true
										$mode = "SHUFFLE";
									}
									else { // Repeat == false
										$mode = "SHUFFLE_NOREPEAT";
									}
								}
								else { //$shuffle == false
									if ($value == true) { // Repeat == true
										$mode = "REPEAT_ALL";
									}
									else { // Repeat == false
										$mode = "NORMAL";
									}								
								}					
								$sonos->SetPlayMode($mode);
								$this->LogInf('Mode im Raum '.$roomName.' gesetzt auf: '.$mode);
								$result = true;
							break;								
						default:
							break;
					}

					// Update variables of room
					$room->setvalue($command, $function, $value);						
					break;
				
				case IPSSONOS_CMD_ROOM:
				
					// Sonos initialisieren
					$room = $this->GetRoom($roomName);
					$sonos = new PHPSonos($room->IPAddr);
					
					switch ($function) {
						case IPSSONOS_FNC_POWER:
							
							//Switch Room
							if ($value == false) {
								$result = IPSSonos_Stop($roomName);
							}
							//
							if( IPSSonos_Custom_SetRoomPower($roomName, $value) == true) {
								$this->LogInf('Power im Raum '.$roomName.' gesetzt auf: '.$value.'!');
								$result = true;
							}
							else {
								$this->LogErr('Raum '.$roomName.' konnte nicht geschaltet werden, Fehler in benutzerdefinierten Funktione IPSSonos_Custom_SetRoomPower!');
								$result = false;
								}
							break;							
						default:
							break;
					}
					
					// Update variables of room
					if ($result)
						$room->setvalue($command, $function, $value);
					break;
					
				case IPSSONOS_CMD_SERVER:
					
					switch ($function) {
						case IPSSONOS_FNC_SYNCPL:
							$result = $this -> Sync_Playlists();
							break;
						case IPSSONOS_FNC_SYNCRD:
							$result = $this -> Sync_Radiostations();
							break;								
						default:
							break;
					}						
					break;					
				default:
					break;
			}	
			

			
			return $result;
		}
		
		
		private function SetQueuePlaylistByName($room, $sonos, $playlist) {
			
			$result			= false;
			$sonos_lists 	= $sonos->GetSONOSPlaylists();
			$playlistid		= 0;
			
			if ($sonos_lists <> null) {
				foreach ($sonos_lists as $playlist_key => $ls_playlist) {

					$Playlist_File 		= urldecode($ls_playlist['file']);
					$Playlist_Title 	= $ls_playlist['title'];
					
					if ($Playlist_Title === $playlist) {
						$sonos->ClearQueue();
						$sonos->AddToQueue($Playlist_File);
						$sonos->SetQueue("x-rincon-queue:".$room->RINCON."#0");
						$result = true;
						
						// Update variables of room
						$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYPLNAME, $playlistid);	
						
					}
					$playlistid = $playlistid + 1;
				}
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos Playlist: '.$playlist);	
			return $result;
		}
		
		private function SetQueuePlaylistByID($room, $sonos, $playlistid) {
			$result = false;
			$sonos_lists = $sonos->GetSONOSPlaylists();
		
			if ($sonos_lists <> null) {

				$Playlist_File 		= urldecode($sonos_lists[$playlistid]['file']);				
				$sonos->ClearQueue();
				$sonos->AddToQueue($Playlist_File);
				$sonos->SetQueue("x-rincon-queue:".$room->RINCON."#0");
				$result = true;
				
				// Update variables of room
				$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYPLID, $playlistid);	
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos PlaylistID: '.$playlistid);		
			return $result;
		}				
		private function SetQueueRadiostationByName($room, $sonos, $radiostation) {
			
			$result			= false;
			$sonos_lists  	= $sonos->Browse("R:0/0","c");
			$playlistid		= 0;
			
			if ($sonos_lists <> null) {
				foreach ($sonos_lists as $list_key => $ls_list) {

					$List_File 		= urldecode($ls_list['res']);
					$List_Title 	= $ls_list['title'];
					
					if ($List_Title === $radiostation) {
					
						$sonos->ClearQueue();
						$sonos->SetRadio(urldecode($List_File));
						$result = true;
						
						// Update variables of room
						$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYRDNAME, $radiostation);						
					}
					$playlistid = $playlistid + 1;
				}
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos Radiostation: '.$radiostation);	
			return $result;
		}
		
		private function SetQueueRadiostationByID($room, $sonos, $radiostationid) {
			$result = false;
			$browselist = $sonos->Browse("R:0/0","c");
		
			if ($browselist <> null) {
//$sonos->SetRadio(urldecode($browselist[$radiostationid]['res'])); 	
//$sonos = new PHPSonos($Player_IP); //Sonos ZP IPAdresse
//$browselist = $sonos->Browse("R:0/0","c");
//$sonos->SetRadio(urldecode($browselist[$Radio_No]['res']));
			
				$sonos->ClearQueue();
				$sonos->SetRadio(urldecode($browselist[$radiostationid]['res'])); 
				$result = true;
				
				// Update variables of room
				$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYRDID, $radiostationid);	
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos RadiostationID: '.$radiostationid);		
			return $result;
		}
		/**
		 * @public
		 *
		 * Liefert ein IPSSonosRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSSonos_Room IPSSonos Room Object
		 */
		private function Sync_Playlists() {
		
			// Delete old entries		
			$varprofile = IPS_GetVariableProfile('IPSSonos_Playlists');

			foreach ($varprofile["Associations"] as $Variable => $Line) {
					$value = $Line["Value"];
				IPS_SetVariableProfileAssociation('IPSSonos_Playlists', $value, null, null, null);
			};
			
			//Get new Playlists from Sonos Server
			$playlist_key	= 0;
			$sonos 			= new PHPSonos($this->IPAddr); 
			$sonos_lists 	= $sonos->GetSONOSPlaylists();
			if ($sonos_lists != null) {
				foreach ($sonos_lists as $playlist_key => $ls_playlist) {
	//				$Playlist_Sonos_ID 	= $ls_playlist['id'];
					$Playlist_Title 	= $ls_playlist['title'];
					IPS_SetVariableProfileAssociation('IPSSonos_Playlists', $playlist_key, $Playlist_Title, null, null);
					$playlist_key 		= $playlist_key + 1;
				}
				$this->LogInf('Playlists erfolgreich synchronisiert, '.$playlist_key.' Einträge vorhanden.');
				return true;
			}	
			else {
				$this->LogErr('Fehler beim Synchronisieren der Sonsos Playlists!');	
				return false;
			}
				

		}		
		/**
		 * @public
		 *
		 * Liefert ein IPSSonosRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSSonos_Room IPSSonos Room Object
		 */
		private function Sync_Radiostations() {

			// Delete old entries		
			$varprofile = IPS_GetVariableProfile('IPSSonos_Radiostations');

			foreach ($varprofile["Associations"] as $Variable => $Line) {
					$value = $Line["Value"];
					IPS_SetVariableProfileAssociation('IPSSonos_Radiostations', $value, null, null, null);
			};

			//Get new Playlists from Sonos Server
			$list_key	= 0;
			$sonos 			= new PHPSonos($this->IPAddr); 
			$radiostations_lists 	= $sonos->Browse("R:0/0","c"); 
			if ($radiostations_lists != null) {
				foreach ($radiostations_lists as $radiostation_key => $ls_radiostation) {
					$Radiostation_Title 	= $ls_radiostation['title'];
					IPS_SetVariableProfileAssociation('IPSSonos_Radiostations', $list_key, $Radiostation_Title, null, null);
					$list_key 		= $list_key + 1;
				}
				$this->LogInf('Radiostations erfolgreich synchronisiert, '.$list_key.' Einträge vorhanden.');
				return true;
			}	
			else {	
				$this->LogErr('Fehler beim Synchronisieren der Sonsos Radiostations!');	
				return false;
			}
				

		}				
}

	/** @}*/
?>