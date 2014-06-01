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
		 * Retry Count Senden
		 */
		private $retryCount;

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
//			$this->debugEnabled = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_MODESERVERDEBUG, $this->instanceId));
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
		 * Ermittelt ob die Instanzen der Räume installiert sind
		 *
		 * @return boolean Räume installiert
		 */
//		private function GetIPSSonosRoomVariablesEnabled() {
//			$roomIds = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMIDS, $this->instanceId));
//			return ($roomIds <> '');
//		}

		/**
		 * @private
		 *
		 * Liefert ein IPSSonosRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSSonos_Room IPSSonos Room Object
		 */
		private function GetIPSSonosRoom($roomName) {
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
		 * Validieren der Daten und Variablen setzen
		 *
		 * @param string $type Kommando Type
		 * @param string $command Kommando
		 * @param integer $roomId Nummer des Raumes
		 * @param string $function Funktion
		 * @param string $value Wert
		 */
//		private function ValidateAndSetValue ($type, $command, $roomId, $function, $value) {
//			if ($this->ValidateData($type, $command, $roomId, $function, $value)) {
//				$this->SetValue($type, $command, $roomId, $function, $value);
//			}
//		}

		/**
		 * @private
		 *
		 * Setzt den ensprechenden Wert einer Variable auf den Wert der Message
		 *
		 * @param string $type Kommando Type
		 * @param string $command Kommando
		 * @param integer $roomId Nummer des Raumes
		 * @param string $function Funktion
		 * @param string $value Wert
		 */
//		private function SetValue ($type, $command, $roomId, $function, $value) {
//		   if ($type==IPSSONOS_TYP_GET) {
//		      return;
//			}
//		   if ($command==IPSSONOS_CMD_POWER or $command==IPSSONOS_CMD_ROOM) {
//				if ($value===IPSSONOS_VAL_BOOLEAN_TRUE)  $value=true;
//				if ($value===IPSSONOS_VAL_BOOLEAN_FALSE) $value=false;
//			}
//			$modification = false;
//			switch ($command) {
//				case IPSSONOS_CMD_POWER:
//					$variableId  = IPS_GetObjectIDByIdent(IPSSONOS_VAR_MAINPOWER, $this->instanceId);
//					if (GetValue($variableId)<>$value) {
//						SetValue($variableId, $value);
//						$modification = true;
//					}
//					break;
//				case IPSSONOS_CMD_TEXT:
//					break;
//				case IPSSONOS_CMD_ROOM:
//					$room = $this->GetIPSSonosRoom($roomId);
//					if ($room===false) {
//						$modification = true;
//						break;
//					}
//					if ($room->GetValue($command, '')<>$value) {
//						$room->SetValue($command, '', $value);
//						$modification = true;
//					}
//					break;
//				case IPSSONOS_CMD_AUDIO:
//					if ($function==IPSSONOS_FNC_VOLUME)      $value=IPSSONOS_VAL_VOLUME_MAX-$value;
//					$room = $this->GetIPSSonosRoom($roomId);
//					if ($room===false) {
//						$modification = true;
//						break;
//					}
//					if ($room->GetValue($command, $function)<>$value) {
//						$room->SetValue($command, $function, $value);
//						$modification = true;
//					}
//					break;
//				case IPSSONOS_CMD_KEEPALIVE:
//					$modification = true;
//					break;
//				default:
//					$this->LogErr('Unknown Command '.$command);
//			}
//			if ($modification) {
//				SetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_LASTCOMMAND, $this->instanceId), $this->BuildMsg($type, $command, $roomId, $function, $value, false));
//				SetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_LASTERROR,   $this->instanceId), "");
//			}
//		}

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
		private function GetData ($command, $roomName, $function) {
			$result = '';

			switch ($command) {
				case IPSSONOS_CMD_POWER:
					$result = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_MAINPOWER, $this->instanceId));
					break;
				case IPSSONOS_CMD_TEXT:
				case IPSSONOS_CMD_MODE:
				case IPSSONOS_CMD_KEEPALIVE:
					break;
				case IPSSONOS_CMD_ROOM:
					$room = $this->GetIPSSonosRoom($roomId);
					if ($room!==false) {
						$result = $room->GetValue($command, '');
					}
					break;
				case IPSSONOS_CMD_AUDIO:
					$room = $this->GetIPSSonosRoom($roomId);
					if ($room!==false) {
						$result = $room->GetValue($command, $function);
					}
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
			
			// Sonos initialisieren
			$room = $this->GetIPSSonosRoom($roomName);
			$sonos = new PHPSonos($room->IPAddr); 
			
			//*-------------------------------------------------------------*
			switch ($command) {
				case IPSSONOS_CMD_AUDIO:
				
					if ($room->ValidateValue($sonos, $command, $function, $value) == false) {
						return false;
					}
			
					switch ($function) {
						case IPSSONOS_FNC_VOLUME:
							$sonos->SetVolume($value);
							$this->LogInf('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							break;
						case IPSSONOS_FNC_VOLUME_INC:	
								$current_volume = $sonos->GetVolume();
								$new_volume = $current_volume + $value;
								$sonos->SetVolume($new_volume);
								$this->LogInf('Lautstärke im Raum '.$roomName.' um '.$value.' erhöht auf: '.$new_volume);
								$value = $new_volume;
							break;	
						case IPSSONOS_FNC_VOLUME_DEC:	
								$current_volume = $sonos->GetVolume();
								$new_volume = $current_volume - $value;
								$sonos->SetVolume($new_volume);
								$this->LogInf('Lautstärke im Raum '.$roomName.' um '.$value.' verringert auf: '.$new_volume);
								$value = $new_volume;								
							break;
						case IPSSONOS_FNC_PLAY:	
								$sonos->Play();
								$this->LogInf('Abspielen im Raum '.$roomName.' gestartet.');
								$value = IPSSONOS_TRA_PLAY;								
							break;
						case IPSSONOS_FNC_STOP:	
								$sonos->Stop();
								$this->LogInf('Abspielen im Raum '.$roomName.' gestopt.');
								$value = IPSSONOS_TRA_STOP;							
							break;
						case IPSSONOS_FNC_PAUSE:	
								$sonos->Pause();
								$this->LogInf('Abspielen im Raum '.$roomName.' pausiert.');
								$value = IPSSONOS_TRA_PAUSE;								
							break;
						case IPSSONOS_FNC_NEXT:	
								$sonos->Next();
								$this->LogInf('Nächstes Lied im Raum '.$roomName.' abspielen.');
								$function 	= IPSSONOS_FNC_PLAY;								
								$value 		= IPSSONOS_TRA_PLAY;								
							break;		
						case IPSSONOS_FNC_PREVIOUS:	
								$sonos->Previous();
								$this->LogInf('Vorheriges Lied im Raum '.$roomName.' abspielen.');
								$function 	= IPSSONOS_FNC_PLAY;								
								$value 		= IPSSONOS_TRA_PLAY;								
							break;									
						default:
							break;
					}	
					break;
				
				case IPSSONOS_CMD_ROOM:
					
					switch ($function) {
						case IPSSONOS_FNC_POWER:
						
							if ($value == false) {
								IPSSonos_Stop($roomName);
							}
							
							if( IPSSonos_Custom_SetRoomPower($roomName, $value) != true) {
								$this->LogErr('Raum '.$roomName.' konnte nicht eingeschaltet werden, Fehler in benutzerdefinierten Funktione IPSSonos_Custom_SetRoomPower!');
								return false;
								}
							break;							
						default:
							break;
					}						
					break;
					
				default:
					break;
			}	
			
			// Update variables of room
			$room->setvalue($command, $function, $value);
			
			return $result;
		}

		/**
		 * @public
		 *
		 * Empfangen von Befehlen vom IPSSonos Server
		 *
		 * @param string $message Message vom IPSSonos Server
		 */
//		public function ReceiveData($message) {
//			$message = str_replace(chr(13), '', $message);
//			$message = str_replace(chr(10), '', $message);
//			$params  = explode(IPSSONOS_COM_SEPARATOR, $message);
//
//			if ($message=='') return;
//
//			$this->LogCom('Rcv Message: '.$message);
//			switch ($params[0]) {
//				case IPSSONOS_TYP_EVT:
//					if ($params[2] == IPSSONOS_CMD_POWER) {
//						$this->ValidateAndSetValue(IPSSONOS_TYP_SET, IPSSONOS_CMD_POWER, null, null, $params[3]);
//					} elseif ($params[2] == IPSSONOS_CMD_MODE) {
//						$this->ValidateAndSetValue(IPSSONOS_TYP_SET, IPSSONOS_CMD_MODE, null, $params[3], $params[4]);
//					} elseif ($params[2] == IPSSONOS_CMD_KEEPALIVE) {
//						SetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_KEEPALIVECOUNT, $this->instanceId), 0);
//					} elseif ($params[2] == IPSSONOS_CMD_ROOM) {
//						$this->ValidateAndSetValue(IPSSONOS_TYP_SET, IPSSONOS_CMD_ROOM, $params[3], null, $params[4]);
//					} elseif ($params[2] == IPSSONOS_CMD_AUDIO) {
//						$this->ValidateAndSetValue(IPSSONOS_TYP_SET, IPSSONOS_CMD_AUDIO, $params[3], $params[4], $params[5]);
//					} else {
//						//$this->LogErr("Received invalid Message".$message);
//					}
//					break;
//				case IPSSONOS_TYP_GET:
//				case IPSSONOS_TYP_SET:
//					SetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_INPUTBUFFER, $this->instanceId), $message);
//					break;
//				case IPSSONOS_TYP_DBG:
//					break;
//				case IPSSONOS_ERR_UNKNOWNCMD1:
//				case IPSSONOS_ERR_UNKNOWNCMD2:
//				case IPSSONOS_ERR_UNKNOWNCMD3:
//				case IPSSONOS_ERR_UNKNOWNCMD4:
//				case IPSSONOS_ERR_UNKNOWNCMD5:
//					break;
//				default:
//					$this->LogInf("Received unknown Message=".$message.', Type='.$params[0]);
//					break;
//			}
//		}

//		public function SetRoomPower($roomName, $value) {
//		
//			$room_name = IPS_GetName($roomId);
//
//			return IPSSonos_Custom_SetRoomPower($roomName, $value);
//		}
		
}

	/** @}*/
?>