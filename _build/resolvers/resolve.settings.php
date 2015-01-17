<?php
/**
 * Resolve creating db tables
 *
 * @var xPDOObject $object
 * @var array $options
 */

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;


	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
			if ($setting = $modx->getObject('modSystemSetting', array('key' => 'ms2gallery_thumbnail_size'))) {
				$setting->remove();
			}
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;