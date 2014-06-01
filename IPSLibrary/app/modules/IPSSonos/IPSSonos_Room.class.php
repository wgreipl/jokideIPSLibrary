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
	 * @file          IPSSonos_Room.class.php
	 * @author        Andreas Brauneis
	 *
	 */

   /**
    * @class IPSSonos_Room
    *
    * Definiert ein IPSSonos_Room Objekt
    *
    * @author Andreas Brauneis
    * @version
    * Version 2.50.1, 31.01.2012<br/>
    */
	class IPSSonos_Room {

		public $IPAddr;
		public $RINCON;
		
	   /**
       * @private
       * ID des IPSSonos Server
       */
      private $instanceId;
	  private $roomName;
	  
	  
     	/**
       * @private
       * Variablen Mapping der Befehle
       */
		private $functionMapping;

		/**
       * @public
		 *
		 * Initialisiert einen IPSSonos Raum
		 *
	    * @param $instanceId - ID des IPSSonos Server.
		 */
      public function __construct($instanceId) {
		$this->instanceId = $instanceId;
		$this->roomName = IPS_GetName((int)$instanceId);

		$this->functionMapping = array(
			IPSSONOS_FNC_VOLUME 			=> IPSSONOS_VAR_VOLUME,
			IPSSONOS_FNC_VOLUME_INC 		=> IPSSONOS_VAR_VOLUME,
			IPSSONOS_FNC_VOLUME_DEC 		=> IPSSONOS_VAR_VOLUME,
			IPSSONOS_FNC_MUTE 				=> IPSSONOS_VAR_MUTE,
			IPSSONOS_FNC_PLAY 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_STOP 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_PAUSE 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_NEXT 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_PREVIOUS			=> IPSSONOS_VAR_TRANSPORT,			
//			   IPSSONOS_FNC_BALANCE 		=> IPSSONOS_VAR_BALANCE,
//			   IPSSONOS_FNC_INPUTSELECT 	=> IPSSONOS_VAR_INPUTSELECT,
//			   IPSSONOS_FNC_INPUTGAIN 		=> IPSSONOS_VAR_INPUTGAIN,
//			   IPSSONOS_FNC_TREBLE 			=> IPSSONOS_VAR_TREBLE,
//			   IPSSONOS_FNC_MIDDLE 			=> IPSSONOS_VAR_MIDDLE,
//			   IPSSONOS_FNC_BASS 			=> IPSSONOS_VAR_BASS,
			);
			
		$this->IPAddr = GetValue(IPS_GetObjectIDByIdent('IPADDR', $this->instanceId));
		$this->RINCON = GetValue(IPS_GetObjectIDByIdent('RINCON', $this->instanceId));
		}

		/**
       * @public
		 *
		 * Liefert den zugehörigen Variablen Namen für eine Message
		 *
	    * @param string $command Kommando
	    * @param string $function Funktion
		 */
		private function GetVariableName($command, $function) {
		   switch($command) {
		      case IPSSONOS_CMD_ROOM:
		         $variableName = IPSSONOS_VAR_ROOMPOWER;
		         break;
				case IPSSONOS_CMD_AUDIO:
		      	$variableName = $this->functionMapping[$function];
		         break;
            default:
               throw new Exception('Unknown Command "'.$command.'", VariableName could NOT be found !!!');
		   }
	      return $variableName;
		}

		/**
       * @public
		 *
		 * Liefert den aktuellen Wert für eine Message
		 *
	    * @param string $command Kommando
	    * @param string $function Funktion
	    * @return string Wert
		 */
		public function GetValue ($command, $function) {
		   $name = $this->GetVariableName($command, $function);
			return GetValue(IPS_GetObjectIDByIdent($name, $this->instanceId));
		}

		/**
       * @public
		 *
		 * Setzt den Wert einer Variable auf den Wert einer Message
		 *
	    * @param string $command Kommando
	    * @param string $function Funktion
	    * @param string $value Wert
		 */
		public function SetValue ($command, $function, $value) {
		   $name        = $this->GetVariableName($command, $function);
	      $variableId  = IPS_GetObjectIDByIdent($name, $this->instanceId);
	      if (GetValue($variableId)<>$value) {
		 		SetValue($variableId, $value);
			}
		}
		/**
		 * @private
		 *
		 * Validierung der Daten
		 *
	    * @param string $type Kommando Type
	    * @param string $command Kommando
	    * @param integer $roomId Raum (1-4)
	    * @param string $function Funktion
	    * @param string $value Wert
	    * @return boolean TRUE für OK, FALSE bei Fehler
		 */
		public function ValidateValue($sonos, $command, $function, $value) {
			$errorMsg = '';
			$result   = false;
			
			if( Sys_Ping( $this->IPAddr, 200 ) == false) {
				$this->LogErr('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht erreichbar!');
				return false;
			}

			if( $this->GetValue(IPSSONOS_CMD_ROOM, IPSSONOS_FNC_POWER) == false) {
				$this->LogErr('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht eingeschaltet ist!');
				return false;
			}			
			
 			switch($command) {

				case IPSSONOS_CMD_AUDIO:
//				   $roomOk   = $roomId>=0 and $roomId<GetValue(IPS_GetObjectIDByIdent('ROOM_COUNT', $this->instanceId));
					switch($function) {
						case IPSSONOS_FNC_VOLUME: /*0..78*/
//							$result = $roomOk and ($value>=IPSSONOS_VAL_VOLUME_MIN and $value<=IPSSONOS_VAL_VOLUME_MAX);
//							$errorMsg = "Value '$value' for Volume NOT in Range (use ".IPSSONOS_VAL_VOLUME_MIN." <= value <=".IPSSONOS_VAL_VOLUME_MAX.")";
							break;
						case IPSSONOS_FNC_MUTE: /*0..78*/
//							$result = $roomOk and ($value==true or $value==IPSSONOS_VAL_BOOLEAN_TRUE or $value==false or $value==IPSSONOS_VAL_BOOLEAN_FALSE);
//							$errorMsg = "Value '$value' for Mute NOT in Range (use 0,1 or boolean)";
							break;
						default:
//							$errorMsg = "Unknonw function '$function' for Command '$command'";
					}
					break;
				default:
//					$errorMsg = "Unknonw Command '$command'";
			}
			if (!$result) {
//				$this->LogErr($errorMsg);
			}
			return true;
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
		 * Protokollierung einer Meldung im IPSSonos Log
		 *
		 * @param string $logType Type der Logging Meldung 
		 * @param string $msg Meldung 
		 */
		private function Log ($logType, $msg) {
			
				IPSLogger_WriteFile("", 'IPSSonos.log', date('Y-m-d H:i:s').'  '.$logType.' - '.$msg, null);
			
		}	
	}

	/** @}*/
?>