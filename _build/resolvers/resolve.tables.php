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
			$modelPath = $modx->getOption('ms2gallery.core_path',null,$modx->getOption('core_path').'components/ms2gallery/').'model/';
			$modx->addPackage('ms2gallery',$modelPath);

			$manager = $modx->getManager();
			$manager->createObjectContainer('msResourceFile');

			$level = $modx->getLogLevel();
			$modx->setLogLevel(xPDO::LOG_LEVEL_FATAL);

			$manager->addField('msResourceFile', 'properties');
			$manager->addField('msResourceFile', 'hash');
			$manager->addIndex('msResourceFile', 'hash');
			$manager->addField('msResourceFile', 'active');
			$manager->addIndex('msResourceFile', 'active');

			$modx->setLogLevel($level);
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;