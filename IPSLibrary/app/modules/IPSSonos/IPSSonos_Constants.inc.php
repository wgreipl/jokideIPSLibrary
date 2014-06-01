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
	 * IPSSonos Server Konstanten
	 *
	 * @file          IPSSonos_Constants.inc.php
	 * @author        Andreas Brauneis
	 * @version
	 * Version 2.50.1, 31.01.2012<br/>
	 *
	 * Prinzipieller Aufbau der Kommunikation:
	 *   CommandType Command Room Function Value
	 *
	 * Jeder Kommando Teil wird durch einen Separator voneinander getrennt (Semikolon). Terminiert
	 * wird jedes Kommando von einem CR.
	 *
	 * Examples:
	 *   set;svr;pwr;1\<cr\>               IPSSonos Server Ein
	 *   evt;svr;kal;0\<cr\>               Keep alive Message vom Server
	 *   set;svr;roo;00;1\<cr\>            Raumverstärker in Raum 1 einschalten
	 *   set;svr;aud;00;inp;1\<cr\>        Eingang 2 in Raum 1 selektieren
	 *   set;svr;aud;02;vol;08\<cr\>       Lautstärke in Raum 3 auf 8
	 *   set;svr;aud;03;bas;14\<cr\>       Bass Raum 4 auf 14
	 *
	 *
	 */
//	define ('IPSSONOS_COM_SEPARATOR',					';');
//	define ('IPSSONOS_COM_TERMINATOR',				chr(13));
//	define ('IPSSONOS_COM_KEEPALIVE',					60);
//	define ('IPSSONOS_COM_MAXRETRIES',				8);
//	define ('IPSSONOS_COM_WAIT',						50);
//	define ('IPSSONOS_COM_MAXWAIT',					500);

	// Kommunikations Kommando Typen
	define ('IPSSONOS_TYP_SET',						'SET');
	define ('IPSSONOS_TYP_GET',						'GET');
	define ('IPSSONOS_TYP_EVT',						'EVT');
	define ('IPSSONOS_TYP_DBG',						'DBG');

	// Kommunikations Device Type
//	define ('IPSSONOS_DEV_SERVER',					'SVR');

	// Error Codes
//	define ('IPSSONOS_ERR_UNKNOWNCMD1',				'1');
//	define ('IPSSONOS_ERR_UNKNOWNCMD2',				'2');
//	define ('IPSSONOS_ERR_UNKNOWNCMD3',				'3');
//	define ('IPSSONOS_ERR_UNKNOWNCMD4',				'4');
//	define ('IPSSONOS_ERR_UNKNOWNCMD5',				'5');

	// Acknowledge
//	define ('IPSSONOS_VAL_ACKNOWLEDGE',				'0');

	// Kommunikations Kommandos
//	define ('IPSSONOS_CMD_POWER',						'PWR');
//	define ('IPSSONOS_CMD_KEEPALIVE',					'KAL');
	define ('IPSSONOS_CMD_AUDIO',						'AUD');
	define ('IPSSONOS_CMD_ROOM',						'ROO');
//	define ('IPSSONOS_CMD_TEXT',						'TEX');
//	define ('IPSSONOS_CMD_MODE',						'MOD');

	// Kommunikations Actions
//	define ('IPSSONOS_FNC_POWERREQUEST',				'PUS');
	define ('IPSSONOS_FNC_POWER',						'PWR');
//	define ('IPSSONOS_FNC_BALANCE',					'BAL');
	define ('IPSSONOS_FNC_VOLUME',					'VOL');
	define ('IPSSONOS_FNC_VOLUME_INC',					'VIN');
	define ('IPSSONOS_FNC_VOLUME_DEC',					'VDE');
	define ('IPSSONOS_FNC_MUTE',						'MUT');
	define ('IPSSONOS_FNC_PLAY',						'PLA');
	define ('IPSSONOS_FNC_PAUSE',						'PAU');
	define ('IPSSONOS_FNC_STOP',						'STP');	
	define ('IPSSONOS_FNC_NEXT',						'NXT');
	define ('IPSSONOS_FNC_PREVIOUS',					'PRV');	
	

	define ('IPSSONOS_TRA_PREVIOUS',					'0');	
	define ('IPSSONOS_TRA_PLAY',						'1');
	define ('IPSSONOS_TRA_PAUSE',						'2');	
	define ('IPSSONOS_TRA_STOP',						'3');
	define ('IPSSONOS_TRA_NEXT',						'4');	
//	define ('IPSSONOS_FNC_TREBLE',					'TRE');
//	define ('IPSSONOS_FNC_MIDDLE',					'MID');
//	define ('IPSSONOS_FNC_BASS',						'BAS');
//	define ('IPSSONOS_FNC_INPUTSELECT',				'INP');
//	define ('IPSSONOS_FNC_INPUTGAIN',					'GAI');

	// Modes
//	define ('IPSSONOS_MOD_ACKNOWLEDGE',				0);
//	define ('IPSSONOS_MOD_SERVERDEBUG',				1);
//	define ('IPSSONOS_MOD_POWERREQUEST',				2);
//	define ('IPSSONOS_MOD_KEEPALIVE',					3);

	// Max, Min und Default Werte
//	define ('IPSSONOS_VAL_ROOM_MIN',					0);
//	define ('IPSSONOS_VAL_ROOM_MAX',					15);

	define ('IPSSONOS_VAL_BOOLEAN_FALSE',				0);
	define ('IPSSONOS_VAL_BOOLEAN_TRUE',				1);

//	define ('IPSSONOS_VAL_VOLUME_MIN',				0);
//	define ('IPSSONOS_VAL_VOLUME_MAX',				40);
	define ('IPSSONOS_VAL_VOLUME_DEFAULT',			20);

	define ('IPSSONOS_VAL_MUTE_OFF',					IPSSONOS_VAL_BOOLEAN_FALSE);
	define ('IPSSONOS_VAL_MUTE_ON',					IPSSONOS_VAL_BOOLEAN_TRUE);
	define ('IPSSONOS_VAL_MUTE_DEFAULT',				IPSSONOS_VAL_BOOLEAN_FALSE);

//	define ('IPSSONOS_VAL_TREBLE_MIN',				0);
//	define ('IPSSONOS_VAL_TREBLE_MAX',				15);
//	define ('IPSSONOS_VAL_TREBLE_DEFAULT',			7);

//	define ('IPSSONOS_VAL_BALANCE_MIN',				0);
//	define ('IPSSONOS_VAL_BALANCE_MAX',				15);
//	define ('IPSSONOS_VAL_BALANCE_DEFAULT',			0);

//	define ('IPSSONOS_VAL_MIDDLE_MIN',				0);
//	define ('IPSSONOS_VAL_MIDDLE_MAX',				15);
//	define ('IPSSONOS_VAL_MIDDLE_DEFAULT',			7);

//	define ('IPSSONOS_VAL_BASS_MIN',					0);
//	define ('IPSSONOS_VAL_BASS_MAX',					15);
//	define ('IPSSONOS_VAL_BASS_DEFAULT',				7);

//	define ('IPSSONOS_VAL_INPUTSELECT_MIN',			0);
//	define ('IPSSONOS_VAL_INPUTSELECT_MAX',			3);
//	define ('IPSSONOS_VAL_INPUTSELECT_DEFAULT',		0);

//	define ('IPSSONOS_VAL_INPUTGAIN_MIN',				0);
//	define ('IPSSONOS_VAL_INPUTGAIN_MAX',				15);
//	define ('IPSSONOS_VAL_INPUTGAIN_DEFAULT',			7);

//	define ('IPSSONOS_VAL_POWER_OFF',					IPSSONOS_VAL_BOOLEAN_FALSE);
//	define ('IPSSONOS_VAL_POWER_ON',					IPSSONOS_VAL_BOOLEAN_TRUE);
	define ('IPSSONOS_VAL_POWER_DEFAULT',				IPSSONOS_VAL_BOOLEAN_FALSE);
	define ('IPSSONOS_VAL_TRANSPORT',				    3);


	// Variablen Definitionen
//	define ('IPSSONOS_VAR_MAINPOWER',					'MAINPOWER');
//	define ('IPSSONOS_VAR_BUSY',						'BUSY');
//	define ('IPSSONOS_VAR_CONNECTION',				'CONNECTION');
	define ('IPSSONOS_VAR_ROOMCOUNT',					'ROOM_COUNT');
	define ('IPSSONOS_VAR_ROOMIDS',					'ROOM_IDS');
//	define ('IPSSONOS_VAR_PORTID',					'PORT_ID');
//	define ('IPSSONOS_VAR_KEEPALIVECOUNT',			'KEEP_ALIVE_COUNT');
//	define ('IPSSONOS_VAR_KEEPALIVESTATUS',			'KEEP_ALIVE_STATUS');
//	define ('IPSSONOS_VAR_LASTERROR',					'LAST_ERROR');
//	define ('IPSSONOS_VAR_LASTCOMMAND',				'LAST_COMMAND');
//	define ('IPSSONOS_VAR_INPUTBUFFER',				'INPUT_BUFFER');
//	define ('IPSSONOS_VAR_MODESERVERDEBUG',			'MODE_SERVERDEBUG');
//	define ('IPSSONOS_VAR_MODEPOWERREQUEST',			'MODE_POWERREQUEST');
//	define ('IPSSONOS_VAR_MODEEMULATESTATE',			'MODE_EMULATESTATE');
//	define ('IPSSONOS_VAR_MODEACKNOWLEDGE',			'MODE_ACKNOWLEDGE');
	define ('IPSSONOS_VAR_IPADDR',					'IPADDR');
	define ('IPSSONOS_VAR_RINCON',					'RINCON');
	define ('IPSSONOS_VAR_REMOTE',					'REMOTE');
	
	define ('IPSSONOS_VAR_ROOMPOWER',					'ROOMPOWER');
//	define ('IPSSONOS_VAR_BALANCE',					'BALANCE');
	define ('IPSSONOS_VAR_MUTE',						'MUTE');
	define ('IPSSONOS_VAR_VOLUME',					'VOLUME');
	define ('IPSSONOS_VAR_TRANSPORT',					'TRANSPORT');
//	define ('IPSSONOS_VAR_TREBLE',					'TREBLE');
//	define ('IPSSONOS_VAR_MIDDLE',					'MIDDLE');
//	define ('IPSSONOS_VAR_BASS',						'BASS');
//	define ('IPSSONOS_VAR_INPUTSELECT',				'INPUTSELECT');
//	define ('IPSSONOS_VAR_INPUTGAIN',					'INPUTGAIN');
	/** @}*/
?>