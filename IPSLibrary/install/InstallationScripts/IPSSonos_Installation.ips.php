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

	 /**@defgroup IPSSonos_protocol IPSIPSSonos Kommunikations Protokoll
	 * @ingroup IPSIPSSonos
	 * @{
	 *
	 */
	/** @}*/

	 /**@defgroup IPSSonos_install IPSSonos Installation
	 * @ingroup IPSSonos
	 * @{
	 *
	 * IPSSonos Installations File
	 *
	 * @file          IPSSonos_Installation.ips.php
	 * @author        Andreas Brauneis
	 * @version
	 *  Version 2.50.1, 18.02.2012<br/>
	 *
	 * Script zur kompletten Installation der IPSSonos Entertainment Steuerung.
	 *
	 * Vor der Installation sollte noch das File IPSSonos_Configuration.inc.php an die persönlichen
	 * Bedürfnisse angepasst werden.
	 *
	 * @page rquirements_IPSSonos Installations Voraussetzungen
	 * - IPS Kernel >= 2.50
	 * - IPSModuleManager >= 2.50.1
	 * - IPSLogger >= 2.50.1
	 *
	 * @page install_IPSSonos Installations Schritte
	 * Folgende Schritte sind zur Installation der IPSSonos Ansteuerung nötig:
	 * - Laden des Modules (siehe IPSModuleManager)
	 * - Konfiguration (Details siehe Konfiguration)
	 * - Installation (siehe IPSModuleManager)
	 *
	 */

	if (!isset($moduleManager)) {
		IPSUtils_Include ('IPSModuleManager.class.php', 'IPSLibrary::install::IPSModuleManager');

		echo 'ModuleManager Variable not set --> Create "default" ModuleManager'."\n";
		$moduleManager = new IPSModuleManager('IPSSonos');
	}

	$moduleManager->VersionHandler()->CheckModuleVersion('IPS','2.50');
	$moduleManager->VersionHandler()->CheckModuleVersion('IPSLogger','2.50.1');
	$moduleManager->VersionHandler()->CheckModuleVersion('IPSModuleManager','2.50.1');

	IPSUtils_Include ("IPSInstaller.inc.php",             "IPSLibrary::install::IPSInstaller");
	IPSUtils_Include ("IPSSonos_Constants.inc.php",       "IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Configuration.inc.php",   "IPSLibrary::config::modules::IPSSonos");

	$WFC10_Enabled        = $moduleManager->GetConfigValue('Enabled', 'WFC10');
	$WFC10_ConfigId       = $moduleManager->GetConfigValueIntDef('ID', 'WFC10', GetWFCIdDefault());
	$WFC10_Path           = $moduleManager->GetConfigValue('Path', 'WFC10');
	$WFC10_TabPaneItem    = $moduleManager->GetConfigValue('TabPaneItem', 'WFC10');
	$WFC10_TabPaneParent  = $moduleManager->GetConfigValue('TabPaneParent', 'WFC10');
	$WFC10_TabPaneName    = $moduleManager->GetConfigValue('TabPaneName', 'WFC10');
	$WFC10_TabPaneIcon    = $moduleManager->GetConfigValue('TabPaneIcon', 'WFC10');
	$WFC10_TabPaneOrder   = $moduleManager->GetConfigValueInt('TabPaneOrder', 'WFC10');
	$WFC10_TabItem        = $moduleManager->GetConfigValue('TabItem', 'WFC10');
	$WFC10_TabName        = $moduleManager->GetConfigValue('TabName', 'WFC10');
	$WFC10_TabIcon        = $moduleManager->GetConfigValue('TabIcon', 'WFC10');
	$WFC10_TabOrder       = $moduleManager->GetConfigValueInt('TabOrder', 'WFC10');

	$Mobile_Enabled       = $moduleManager->GetConfigValue('Enabled', 'Mobile');
	$Mobile_Path          = $moduleManager->GetConfigValue('Path', 'Mobile');
	$Mobile_PathOrder     = $moduleManager->GetConfigValueInt('PathOrder', 'Mobile');
	$Mobile_PathIcon      = $moduleManager->GetConfigValue('PathIcon', 'Mobile');
	$Mobile_Name          = $moduleManager->GetConfigValue('Name', 'Mobile');
	$Mobile_Order         = $moduleManager->GetConfigValueInt('Order', 'Mobile');
	$Mobile_Icon          = $moduleManager->GetConfigValue('Icon', 'Mobile');

//	$IPSSonosRoomInstallation     = $moduleManager->GetConfigValueBool('IPSSonosRoomInstallation');

	/* ---------------------------------------------------------------------- */
	/* IPSSonos Installation                                                  */
	/* ---------------------------------------------------------------------- */
	
	//Delete previous installation
	DeleteCategory($moduleManager->GetModuleCategoryID('data'));
	
	$CategoryIdData     = $moduleManager->GetModuleCategoryID('data');
	$CategoryIdApp      = $moduleManager->GetModuleCategoryID('app');
//	$CategoryIdHardware = CreateCategoryPath('Modules.IPSSonos');

 	$id_ScriptSyncPlaylists    	= IPS_GetScriptIDByName('IPSSonos_SyncPlaylists',  		$CategoryIdApp);
 	$id_ScriptSyncRadiostations	= IPS_GetScriptIDByName('IPSSonos_SyncRadiostations',  	$CategoryIdApp);	
	$id_ScriptSettings       	= IPS_GetScriptIDByName('IPSSonos_ChangeSettings', 		$CategoryIdApp);


	CreateProfile_Count 		('IPSSonos_Volume',       		0,       1, 100,       "", "%");
	CreateProfile_Switch 		('IPSSonos_Mute',            	'Aus', 'An', "", -1, 0x00ff00);
	CreateProfile_Switch 		('IPSSonos_Connection',      	'Deaktiviert', 'Aktiv', "", 0xaa0000, 0x0000ff, 'LockOpen', 'LockClosed');
	CreateProfile_Associations 	('IPSSonos_Transport',       	array("|<", "Play", "Pause", "Stop","|>"));
	CreateProfile_Associations 	('IPSSonos_Playlists',       	array("Playlists wurden noch nicht synchronisiert!"));
	CreateProfile_Associations 	('IPSSonos_Radiostations',      array("Radiostationen wurden noch nicht synchronisiert!"));	
	CreateProfile_Switch 		('IPSSonos_Shuffle',            'Aus', 'An', "", -1, 0x00ff00);
	CreateProfile_Switch 		('IPSSonos_Repeat',            	'Aus', 'An', "", -1, 0x00ff00);
	
	$id_IPSSonosServerId = CreateDummyInstance("IPSSonos_Server", $CategoryIdData, 10);
	$id_RoomIds          = CreateVariable(IPSSONOS_VAR_ROOMIDS,         3 /*String*/,  $id_IPSSonosServerId, 310, '',                      null,              '',    '');
	$id_RoomCount        = CreateVariable(IPSSONOS_VAR_ROOMCOUNT,       1 /*Integer*/, $id_IPSSonosServerId, 320, '',                      null,              0,     '');
	$id_IPAddr        	 = CreateVariable(IPSSONOS_VAR_IPADDR,       	3 /*String*/,  $id_IPSSonosServerId, 400, '',               	   null,              '',    '');
	$id_ModeServerDebug  = CreateVariable(IPSSONOS_VAR_MODESERVERDEBUG, 0 /*Boolean*/, $id_IPSSonosServerId, 500, '~Switch',               null,			false,  	'');

	//Populate values from config
	$ServerConfig = IPSSonos_GetServerConfiguration();
	SetValue($id_IPAddr, $ServerConfig[0]);
	SetValue($id_ModeServerDebug, $ServerConfig[1]);
	
	// ===================================================================================================
	// Add Rooms
	// ===================================================================================================

		$RoomId=0;
		$RoomConfig = IPSSonos_GetRoomConfiguration();

		foreach ($RoomConfig as $GroupName=>$GroupData) {
			$RoomId = $RoomId + 1;
			$RoomInstanceId = CreateDummyInstance($GroupName, $CategoryIdData, 100+$RoomId);
			$RoomIds[]      = $RoomInstanceId;

			$PowerId        	= CreateVariable(IPSSONOS_VAR_ROOMPOWER,	0 /*Boolean*/, $RoomInstanceId,  10, '~Switch',              	$id_ScriptSettings, IPSSONOS_VAL_POWER_DEFAULT, 'Power');
			$VolumeId      		= CreateVariable(IPSSONOS_VAR_VOLUME,		1 /*Integer*/, $RoomInstanceId,  40, 'IPSSonos_Volume',      	$id_ScriptSettings, IPSSONOS_VAL_VOLUME_DEFAULT, 'Intensity');
			$MutingId       	= CreateVariable(IPSSONOS_VAR_MUTE,			0 /*Boolean*/, $RoomInstanceId,  50, 'IPSSonos_Mute',        	$id_ScriptSettings, IPSSONOS_VAL_MUTE_DEFAULT, 'Speaker');
			$TransportId    	= CreateVariable(IPSSONOS_VAR_TRANSPORT,	1 /*Integer*/, $RoomInstanceId,  60, 'IPSSonos_Transport',   	$id_ScriptSettings, IPSSONOS_VAL_TRANSPORT, 'Speaker');
			$IPAdrr         	= CreateVariable(IPSSONOS_VAR_IPADDR,		3 /*String*/,  $RoomInstanceId,  100, '', 						null, 				'', '');
			$RINCON         	= CreateVariable(IPSSONOS_VAR_RINCON,		3 /*String*/,  $RoomInstanceId,  110, '', 						null, 				'', '');
			$PLAYLIST         	= CreateVariable(IPSSONOS_VAR_PLAYLIST,    	1 /*Integer*/, $RoomInstanceId,  120, 'IPSSonos_Playlists', 	$id_ScriptSettings, IPSSONOS_VAL_PLAYLIST, '');
			$RADIOSTATION       = CreateVariable(IPSSONOS_VAR_RADIOSTATION,	1 /*Integer*/, $RoomInstanceId,  130, 'IPSSonos_Radiostations',	$id_ScriptSettings, IPSSONOS_VAL_RADIOSTATION, '');
			$ShuffleId       	= CreateVariable(IPSSONOS_VAR_SHUFFLE,		0 /*Boolean*/, $RoomInstanceId,  140, 'IPSSonos_Shuffle',    	$id_ScriptSettings, false, '');
			$RepeatId       	= CreateVariable(IPSSONOS_VAR_REPEAT,		0 /*Boolean*/, $RoomInstanceId,  150, 'IPSSonos_Repeat',     	$id_ScriptSettings, false, '');

			//         $remoteControlId  = CreateVariable(IPSSONOS_VAR_REMOTE,   	 3 /*String*/,  $RoomInstanceId,  310 , '~HTMLBox', null, '<iframe frameborder="0" width="100%" src="../user/IPSSonosPlayer/IPSSonosPlayer_MP3Control.php" height=255px </iframe>');

// Ab hier von Netplayer
//	$sourceId              = CreateVariable("Source",          1 /*Integer*/,  $CategoryIdData, 110 , 'NetPlayer_Source', $actionScriptId, 0 /*CD*/);
//	$controlId             = CreateVariable("Control",         1 /*Integer*/,  $CategoryIdData, 120 , 'NetPlayer_Control', $actionScriptId, 2 /*Stop*/);
//	$albumId               = CreateVariable("Album",           3 /*String*/,   $CategoryIdData, 130, '~String');
//	$interpretId           = CreateVariable("Interpret",       3 /*String*/,   $CategoryIdData, 140, '~String');
//	$categoryId            = CreateVariable("Category",        1 /*Integer*/,  $CategoryIdData, 150 , 'NetPlayer_Category', $actionScriptId, 0);
//	$cdAlbumNavId          = CreateVariable("CDAlbumNav",      1 /*Integer*/,  $CategoryIdData, 160 , 'NetPlayer_CDAlbumNav', $actionScriptId, -1);
//	$cdAlbumListId         = CreateVariable("CDAlbumList",     1 /*Integer*/,  $CategoryIdData, 170 , 'NetPlayer_CDAlbumList', $actionScriptId, -1);
//	$cdTrackNavId          = CreateVariable("CDTrackNav",      1 /*Integer*/,  $CategoryIdData, 180 , 'NetPlayer_CDTrackNav', $actionScriptId, -1);
//	$cdTrackListId         = CreateVariable("CDTrackList",     1 /*Integer*/,  $CategoryIdData, 190 , 'NetPlayer_CDTrackList', $actionScriptId, -1);
//	$radioNavId            = CreateVariable("RadioNav",        1 /*Integer*/,  $CategoryIdData, 200 , 'NetPlayer_RadioNav', $actionScriptId, -1);
//	$radioListId           = CreateVariable("RadioList",       1 /*Integer*/,  $CategoryIdData, 210 , 'NetPlayer_RadioList', $actionScriptId,-1);
//	$controlTypeId         = CreateVariable("ControlType",     1 /*Integer*/,  $CategoryIdData, 300 , '', null, 0);
//	$remoteControlId       = CreateVariable("RemoteControl",   3 /*String*/,   $CategoryIdData, 310 , '~HTMLBox', null, '<iframe frameborder="0" width="100%" src="../user/NetPlayer/NetPlayer_MP3Control.php" height=255px </iframe>');
//	$mobileControlId       = CreateVariable("MobileControl",   3 /*String*/,   $CategoryIdData, 320 , '~HTMLBox', null, '<iframe frameborder="0" width="100%" src="../user/NetPlayer/NetPlayer_Mobile.php" height=1000px </iframe>');

// Werte aus Config zuweisen
	SetValue($IPAdrr, $GroupData[0]);
	SetValue($RINCON, $GroupData[1]);
	}

	SetValue($id_RoomIds, implode(',',$RoomIds));
	SetValue($id_RoomCount, $RoomId);

	// ----------------------------------------------------------------------------------------------------------------------------
	// Webfront Installation
	// ----------------------------------------------------------------------------------------------------------------------------
	if ($WFC10_Enabled) {
		$categoryIdWebFront         = CreateCategoryPath($WFC10_Path);
		EmptyCategory($categoryIdWebFront);
		$categoryIdWebFrontLeft   = CreateCategory('Left',   $categoryIdWebFront, 100);
		$categoryIdWebFrontRight  = CreateCategory('Right', $categoryIdWebFront, 200);

		$instanceIdServer  = CreateDummyInstance('IPSSonos Server', $categoryIdWebFrontLeft, 10);
		CreateLink('Playlists synchronisieren',   $id_ScriptSyncPlaylists, $instanceIdServer, 100);
		CreateLink('Radiostationen synchronisieren',   $id_ScriptSyncRadiostations, $instanceIdServer, 110);		


//			$roomNames = array(1=>IPSSONOS_CONFIG_ROOMNAME1, 2=>IPSSONOS_CONFIG_ROOMNAME2, 3=>IPSSONOS_CONFIG_ROOMNAME3);
//			for ($roomId=1;$roomId<=3;$roomId++) {
			$RoomId = 1;

			foreach ($RoomConfig as $GroupName=>$GroupData) {
				$roomCategoryId = CreateCategory($GroupName, $categoryIdWebFrontRight, 10*$RoomId);
				$roomInstanceId = IPS_GetObjectIdByIdent($GroupName, $CategoryIdData);

//				CreateLink('IPSSonos'.$roomId. ' ('.$roomNames[$roomId].')', IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER, $roomInstanceId),   $categoryIdWebFrontRight, $roomId);

				CreateLink('Power',                IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER,   	$roomInstanceId),   $roomCategoryId, 10);
				CreateLink($GroupName,             IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER,   	$roomInstanceId),   $instanceIdServer, 10*$RoomId);

//				CreateLink('Eingang',              IPS_GetObjectIDByIdent(IPSSONOS_VAR_INPUTSELECT, 	$roomInstanceId),   $roomCategoryId, 20);
//				CreateLink('Verstärkung',          IPS_GetObjectIDByIdent(IPSSONOS_VAR_INPUTGAIN,   	$roomInstanceId),   $roomCategoryId, 30);
				CreateLink('Lautstärke',           IPS_GetObjectIDByIdent(IPSSONOS_VAR_VOLUME,      	$roomInstanceId),   $roomCategoryId, 40);
				CreateLink('Muting',               IPS_GetObjectIDByIdent(IPSSONOS_VAR_MUTE,        	$roomInstanceId),   $roomCategoryId, 50);
				CreateLink('Player',               IPS_GetObjectIDByIdent(IPSSONOS_VAR_TRANSPORT,   	$roomInstanceId),   $roomCategoryId, 50);
				CreateLink('Playlist',             IPS_GetObjectIDByIdent(IPSSONOS_VAR_PLAYLIST,   		$roomInstanceId),   $roomCategoryId, 60);
				CreateLink('Radiostation',         IPS_GetObjectIDByIdent(IPSSONOS_VAR_RADIOSTATION,   	$roomInstanceId),   $roomCategoryId, 70);
				CreateLink('Shuffle',              IPS_GetObjectIDByIdent(IPSSONOS_VAR_SHUFFLE,     	$roomInstanceId),   $roomCategoryId, 80);
				CreateLink('Repeat',               IPS_GetObjectIDByIdent(IPSSONOS_VAR_REPEAT,      	$roomInstanceId),   $roomCategoryId, 90);

				//				CreateLink('Balance',              IPS_GetObjectIDByIdent(IPSSONOS_VAR_BALANCE,     $roomInstanceId),   $roomCategoryId, 60);
//				CreateLink('Höhen',                IPS_GetObjectIDByIdent(IPSSONOS_VAR_TREBLE,      $roomInstanceId),   $roomCategoryId, 70);
//				CreateLink('Mitten',               IPS_GetObjectIDByIdent(IPSSONOS_VAR_MIDDLE,      $roomInstanceId),   $roomCategoryId, 80);
//				CreateLink('Bass',                 IPS_GetObjectIDByIdent(IPSSONOS_VAR_BASS,        $roomInstanceId),   $roomCategoryId, 90);
//				CreateLink('Remote',               IPS_GetObjectIDByIdent(IPSSONOS_VAR_REMOTE,      $roomInstanceId),   $roomCategoryId, 90);

				$RoomId = $RoomId + 1;
			}



		$tabItem = $WFC10_TabPaneItem.$WFC10_TabItem;
		DeleteWFCItems($WFC10_ConfigId, $tabItem);
		CreateWFCItemTabPane   ($WFC10_ConfigId, $WFC10_TabPaneItem, $WFC10_TabPaneParent,  $WFC10_TabPaneOrder, $WFC10_TabPaneName, $WFC10_TabPaneIcon);
		CreateWFCItemSplitPane ($WFC10_ConfigId, $tabItem,           $WFC10_TabPaneItem,    $WFC10_TabOrder,     $WFC10_TabName,     $WFC10_TabIcon, 1 /*Vertical*/, 30 /*Width*/, 0 /*Target=Pane1*/, 0/*UsePerc*/, 'true');
		CreateWFCItemCategory  ($WFC10_ConfigId, $tabItem.'_Left',   $tabItem,   10, '', '', $categoryIdWebFrontLeft   /*BaseId*/, 'false' /*BarBottomVisible*/);
		CreateWFCItemCategory  ($WFC10_ConfigId, $tabItem.'_Right',  $tabItem,   20, '', '', $categoryIdWebFrontRight   /*BaseId*/, 'true' /*BarBottomVisible*/);

		ReloadAllWebFronts();
	}


	/** @}*/
?>
