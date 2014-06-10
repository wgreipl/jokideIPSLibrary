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
	 * IPSxbmc Server Konstanten
	 *
	 * @file          IPSxbmc_Constants.inc.php
	 * @author        Joerg Kling
	 * @version
	 * Version 0.9.4, 07.06.2014<br/>
	 *
	 */

	// Kommunikations Kommandos
	define ('IPSxbmc_CMD_AUDIO',						'AUD');
	define ('IPSxbmc_CMD_ROOM',						'ROO');
	define ('IPSxbmc_CMD_SERVER',						'SRV');	


	// Kommunikations Actions
	define ('IPSxbmc_FNC_POWER',						'PWR');
	define ('IPSxbmc_FNC_VOLUME',						'VOL');
	define ('IPSxbmc_FNC_VOLUME_INC',					'VIN');
	define ('IPSxbmc_FNC_VOLUME_DEC',					'VDE');
	define ('IPSxbmc_FNC_VOLUME_RMP',					'VOLUMERMP');	
	define ('IPSxbmc_FNC_VOLUME_RMPMUTE',				'VOLUMERMPMUTE');
	define ('IPSxbmc_FNC_VOLUME_RMPMUTESLOW',			'VOLUMERMPMUTESLOW');		
	define ('IPSxbmc_FNC_MUTE',						'MUT');
	define ('IPSxbmc_FNC_PLAY',						'PLA');
	define ('IPSxbmc_FNC_PAUSE',						'PAU');
	define ('IPSxbmc_FNC_STOP',						'STP');	
	define ('IPSxbmc_FNC_NEXT',						'NXT');
	define ('IPSxbmc_FNC_PREVIOUS',					'PRV');	
	define ('IPSxbmc_FNC_SYNCPL',						'SYNCPL');
	define ('IPSxbmc_FNC_PLAYPLID',					'PLAYPLID');
	define ('IPSxbmc_FNC_PLAYPLNAME',					'PLAYPLNAME');
	define ('IPSxbmc_FNC_SYNCRD',						'SYNCRD');
	define ('IPSxbmc_FNC_PLAYRDID',					'PLAYRDID');
	define ('IPSxbmc_FNC_PLAYRDNAME',					'PLAYRDNAME');		
	define ('IPSxbmc_FNC_ROOMS',						'ROOMS');
	define ('IPSxbmc_FNC_ROOMSACTIVE',					'ROOMSACTIVE');	
	define ('IPSxbmc_FNC_SHUFFLE',						'SHUFFLE');	
	define ('IPSxbmc_FNC_REPEAT',						'REPEAT');			

	//Define Transport
	define ('IPSxbmc_TRA_PREVIOUS',					'0');	
	define ('IPSxbmc_TRA_PLAY',						'1');
	define ('IPSxbmc_TRA_PAUSE',						'2');	
	define ('IPSxbmc_TRA_STOP',						'3');
	define ('IPSxbmc_TRA_NEXT',						'4');	

	//Define Variavle Values 
	define ('IPSxbmc_VAL_BOOLEAN_FALSE',				0);
	define ('IPSxbmc_VAL_BOOLEAN_TRUE',				1);
	define ('IPSxbmc_VAL_VOLUME_DEFAULT',				20);

	define ('IPSxbmc_VAL_MUTE_OFF',					IPSxbmc_VAL_BOOLEAN_FALSE);
	define ('IPSxbmc_VAL_MUTE_ON',						IPSxbmc_VAL_BOOLEAN_TRUE);
	define ('IPSxbmc_VAL_MUTE_DEFAULT',				IPSxbmc_VAL_BOOLEAN_FALSE);

//	define ('IPSxbmc_VAL_POWER_OFF',					IPSxbmc_VAL_BOOLEAN_FALSE);
//	define ('IPSxbmc_VAL_POWER_ON',					IPSxbmc_VAL_BOOLEAN_TRUE);
	define ('IPSxbmc_VAL_POWER_DEFAULT',				IPSxbmc_VAL_BOOLEAN_FALSE);
	define ('IPSxbmc_VAL_TRANSPORT',				    3);
	define ('IPSxbmc_VAL_PLAYLIST',				    0);
	define ('IPSxbmc_VAL_RADIOSTATION',			  	999);	


	// Variablen Definitionen
	define ('IPSxbmc_VAR_ROOMCOUNT',					'ROOM_COUNT');
	define ('IPSxbmc_VAR_ROOMIDS',						'ROOM_IDS');
	define ('IPSxbmc_VAR_MODESERVERDEBUG',				'MODE_SERVERDEBUG');
	define ('IPSxbmc_VAR_IPADDR',						'IPADDR');
	define ('IPSxbmc_VAR_RINCON',						'RINCON');
	define ('IPSxbmc_VAR_REMOTE',						'REMOTE');
	define ('IPSxbmc_VAR_PLAYLIST',					'PLAYLIST');
	define ('IPSxbmc_VAR_RADIOSTATION',				'RADIOSTATION');	
	define ('IPSxbmc_VAR_ROOMPOWER',					'ROOMPOWER');
	define ('IPSxbmc_VAR_MUTE',						'MUTE');
	define ('IPSxbmc_VAR_VOLUME',						'VOLUME');
	define ('IPSxbmc_VAR_TRANSPORT',					'TRANSPORT');
	define ('IPSxbmc_VAR_SHUFFLE',						'SHUFFLE');
	define ('IPSxbmc_VAR_REPEAT',						'REPEAT');	

	/** @}*/
?>