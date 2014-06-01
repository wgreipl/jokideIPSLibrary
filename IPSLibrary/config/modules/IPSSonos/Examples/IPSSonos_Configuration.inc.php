<?
	/**@defgroup IPSSonos_configuration IPSSonos Konfiguration
	 * @ingroup IPSSonos
	 * @{
	 *
	 * IPSSonos Server Konfiguration
	 *
	 * @file          IPSSonos_Configuration.inc.php
	 * @author        Andreas Brauneis
	 * @version
	 * Version 2.50.1, 20.02.2012<br/>
	 *
	 * Änderung an den Parametern erfordert ein erneutes Ausführen des Installations Scripts.
	 *
	 */

	/**
	 * Definition des COM Ports, der für den IPSSonos Server verwendet wird. Ist der Port gesetzt,
	 * wird die Register Variable, die Splitter Instanz und die IO Instanz automatisch angelegt.
	 *
	 * Alternativ kann die zu verwendende Register Variable auch nachträglich in der erzeugten
	 * IPSSonos Instanz gesetzt werden.
	 *
	 */
//	define ('IPSSONOS_CONFIG_COM_PORT',					'COM15');

	/**
	 * Definition der Anzahl der Räume
	 */
//	define ('IPSSONOS_CONFIG_ROOM_COUNT',				3);

	/**
	 * Definition des Namens für den Eingang 1 des IPSSonos Servers
	 */
	define ('IPSSONOS_CONFIG_INPUTNAME1',				'NetPlayer');

	/**
	 * Definition des Namens für den Eingang 2 des IPSSonos Servers
	 */
	define ('IPSSONOS_CONFIG_INPUTNAME2',				'Tuner');

	/**
	 * Definition des Namens für den Eingang 3 des IPSSonos Servers
	 */
	define ('IPSSONOS_CONFIG_INPUTNAME3',				'CD Player');

	/**
	 * Definition des Namens für den Eingang 4 des IPSSonos Servers
	 */
	define ('IPSSONOS_CONFIG_INPUTNAME4',				'');

	/**
	 * Definition des Namens für den Raum 1 des IPSSonos Servers
	 */
//	define ('IPSSONOS_CONFIG_ROOMNAME1',				'Wohnzimmer');

	/**
	 * Definition des Namens für den Raum 2 des IPSSonos Servers
	 */
//	define ('IPSSONOS_CONFIG_ROOMNAME2',				'Schlafzimmer');

	/**
	 * Definition des Namens für den Raum 3 des IPSSonos Servers
	 */
//	define ('IPSSONOS_CONFIG_ROOMNAME3',				'Kueche');

	/**
	 * Definition des Namens für den Raum des IPSSonos Servers
	 */
//	define ('IPSSONOS_CONFIG_ROOMNAME4',				'Zimmer4');
	function IPSSonos_GetRoomConfiguration() {
		return array(
			'Wohnzimmer'		=>		array('192.168.20.108', 'RINCON_000E5829F33A01400'),
			'Schlafzimmer'		=>		array('192.168.20.105', 'RINCON_000E5872E10801400'),
			'Kueche'			=>		array('192.168.20.103', 'RINCON_000E582732C001400'),
			);
	}
	/** @}*/
?>