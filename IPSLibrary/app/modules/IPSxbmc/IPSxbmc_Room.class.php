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
	 * @file          IPSxbmc_Room.class.php
	 * @author        Andreas Brauneis
	 *
	 */

   /**
    * @class IPSxbmc_Room
    *
    * Definiert ein IPSxbmc_Room Objekt
    *
    * @author Andreas Brauneis
    * @version
    * Version 2.50.1, 31.01.2012<br/>
    */
	class IPSxbmc_Room {

		public $IPAddr;
		public $regvarId;
	    public $instanceId;
		public $roomName;	
	   /**
       * @private
       * ID des IPSxbmc Server
       */

	  
	  
     	/**
       * @private
       * Variablen Mapping der Befehle
       */
		private $functionMapping;

		/**
       * @public
		 *
		 * Initialisiert einen IPSxbmc Raum
		 *
	    * @param $instanceId - ID des IPSxbmc Server.
		 */
      public function __construct($instanceId) {
		$this->instanceId = $instanceId;
		$this->roomName = IPS_GetName((int)$instanceId);

		$this->functionMapping = array(
			IPSxbmc_FNC_VOLUME 			=> IPSxbmc_VAR_VOLUME,
			IPSxbmc_FNC_VOLUME_INC 		=> IPSxbmc_VAR_VOLUME,
			IPSxbmc_FNC_VOLUME_DEC 		=> IPSxbmc_VAR_VOLUME,
			IPSxbmc_FNC_VOLUME_RMP			=> IPSxbmc_VAR_VOLUME,
			IPSxbmc_FNC_VOLUME_RMPMUTE		=> IPSxbmc_VAR_VOLUME,	
			IPSxbmc_FNC_VOLUME_RMPMUTESLOW	=> IPSxbmc_VAR_VOLUME,				
			IPSxbmc_FNC_MUTE 				=> IPSxbmc_VAR_MUTE,
			IPSxbmc_FNC_PLAY 				=> IPSxbmc_VAR_TRANSPORT,
			IPSxbmc_FNC_STOP 				=> IPSxbmc_VAR_TRANSPORT,
			IPSxbmc_FNC_PAUSE 				=> IPSxbmc_VAR_TRANSPORT,
			IPSxbmc_FNC_NEXT 				=> IPSxbmc_VAR_TRANSPORT,
			IPSxbmc_FNC_PREVIOUS			=> IPSxbmc_VAR_TRANSPORT,
			IPSxbmc_FNC_PLAYPLID			=> IPSxbmc_VAR_PLAYLIST,
			IPSxbmc_FNC_PLAYPLNAME			=> IPSxbmc_VAR_PLAYLIST,
			IPSxbmc_FNC_PLAYRDID			=> IPSxbmc_VAR_RADIOSTATION,
			IPSxbmc_FNC_PLAYRDNAME			=> IPSxbmc_VAR_RADIOSTATION,				
			IPSxbmc_FNC_SHUFFLE			=> IPSxbmc_VAR_SHUFFLE,
			IPSxbmc_FNC_REPEAT				=> IPSxbmc_VAR_REPEAT,
			);
			
		$this->IPAddr = GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_IPADDR, $this->instanceId));
		$this->RINCON = GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_COMREGVAR, $this->instanceId));
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
		      case IPSxbmc_CMD_ROOM:
		         $variableName = IPSxbmc_VAR_ROOMPOWER;
		         break;
				case IPSxbmc_CMD_AUDIO:
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
		public function ValidateValue($xbmc, $command, $function, $value) {
			$errorMsg = '';
			$result   = false;
			
			if( Sys_Ping( $this->IPAddr, 200 ) == false) {
				$this->LogErr('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht erreichbar!');
				return false;
			}

			if( $this->GetValue(IPSxbmc_CMD_ROOM, IPSxbmc_FNC_POWER) == false) {
				$this->LogErr('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht eingeschaltet ist!');
				return false;
			}			
			
 			switch($command) {

				case IPSxbmc_CMD_AUDIO:
//				   $roomOk   = $roomId>=0 and $roomId<GetValue(IPS_GetObjectIDByIdent('ROOM_COUNT', $this->instanceId));
					switch($function) {
						case IPSxbmc_FNC_VOLUME: /*0..78*/
//							$result = $roomOk and ($value>=IPSxbmc_VAL_VOLUME_MIN and $value<=IPSxbmc_VAL_VOLUME_MAX);
//							$errorMsg = "Value '$value' for Volume NOT in Range (use ".IPSxbmc_VAL_VOLUME_MIN." <= value <=".IPSxbmc_VAL_VOLUME_MAX.")";
							break;
						case IPSxbmc_FNC_MUTE: /*0..78*/
//							$result = $roomOk and ($value==true or $value==IPSxbmc_VAL_BOOLEAN_TRUE or $value==false or $value==IPSxbmc_VAL_BOOLEAN_FALSE);
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
		}
			/**
		 * @private
		 *
		 * Protokollierung einer Meldung im IPSxbmc Log
		 *
		 * @param string $logType Type der Logging Meldung 
		 * @param string $msg Meldung 
		 */
		private function Log ($logType, $msg) {
			
				IPSLogger_WriteFile("", 'IPSxbmc.log', date('Y-m-d H:i:s').'  '.$logType.' - '.$msg, null);
			
		}	
	}

	/** @}*/
?>