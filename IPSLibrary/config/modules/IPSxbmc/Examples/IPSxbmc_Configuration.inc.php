<?
	/**@defgroup IPSSonos_configuration IPSSonos Konfiguration
	 * @ingroup IPSSonos
	 * @{
	 *
	 * IPSSonos Server Konfiguration
	 *
	 * @file          IPSSonos_Configuration.inc.php
	 * @author        Jörg Kling
	 * @version
	 * Version 2.50.1, 01.06.2014<br/>
	 *
	 * Änderung an den Parametern erfordert ein erneutes Ausführen des Installations Scripts.
	 *
	 */


	function IPSSonos_GetRoomConfiguration() {
		return array(
			'Wohnzimmer'		=>		array('192.168.20.108', 'RINCON_000E5829F33A01400'),
			'Schlafzimmer'		=>		array('192.168.20.105', 'RINCON_000E5872E10801400'),
			'Kueche'			=>		array('192.168.20.103', 'RINCON_000E582732C001400'),
			);
	}
	/** @}*/
?>