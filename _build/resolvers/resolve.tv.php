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
			if ($tv = $modx->getObject('modTemplateVar', array('type' => 'ms2gallery.input'))) {
				$tv_id = $tv->get('id');
				$tvs = $modx->getIterator('modTemplateVarResource', array('tmplvarid' => $tv_id));
				/** @var xPDOObject $value */
				foreach ($tvs as $value) {
					/** @var modResource $resource */
					if ($resource = $modx->getObject('modResource', $value->get('contentid'))) {
						$properties = $resource->getProperties('ms2gallery');
						if (empty($properties)) {
							$properties['media_source'] = $value->get('value');
							$resource->setProperties($properties, 'ms2gallery');
							$resource->save();
						}
					}
					$value->remove();
				}
				$tv->remove();
			}
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;