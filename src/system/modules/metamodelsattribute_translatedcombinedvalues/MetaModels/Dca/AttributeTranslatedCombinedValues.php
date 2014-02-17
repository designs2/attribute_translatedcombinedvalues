<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package     MetaModels
 * @subpackage  TranslatedCombinedValues
 * @author      Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author      Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */


namespace MetaModels\Dca;

use MetaModels\Factory;

/**
 * This class is used from DCA tl_metamodel_attribute for various callbacks.
 *
 * @package    MetaModels
 * @subpackage TranslatedCombinedValues
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 */
class AttributeTranslatedCombinedValues
{
	/**
	 * Fetch all attributes from the parenting MetaModel. Called as options_callback.
	 * User the oncreate_callback.
	 *
	 * @return array
	 */
	public function getAllAttributes()
	{
		$intID  = \Input::getInstance()->get('id');
		$intPID = \Input::getInstance()->get('pid');

		$arrReturn = array();

		// Add meta fields.
		$arrReturn['meta'] = $GLOBALS['METAMODELS_SYSTEM_COLUMNS'];

		if (empty($intPID))
		{
			$objResult = \Database::getInstance()
				->prepare('SELECT pid FROM tl_metamodel_attribute WHERE id=?')
				->limit(1)
				->execute($intID);

			if ($objResult->numRows == 0)
			{
				return $arrReturn;
			}

			$objMetaModel = Factory::byId($objResult->pid);
		}
		else
		{
			$objMetaModel = Factory::byId($intPID);
		}

		foreach ($objMetaModel->getAttributes() as $objAttribute)
		{
			// Prevent recursion!
			if ($objAttribute->get('id') == $intID)
			{
				continue;
			}

			if (isset($GLOBALS['TL_LANG']['tl_metamodel_attribute']['typeOptions'][$objAttribute->get('type')]))
			{
				$strType = $GLOBALS['TL_LANG']['tl_metamodel_attribute']['typeOptions'][$objAttribute->get('type')];
			}
			else
			{
				$strType = get_class($objAttribute);
			}

			$arrReturn['attributes'][$objAttribute->getColName()] = sprintf('%s (%s)', $objAttribute->getName(), $strType);
		}

		return $arrReturn;
	}

}
