<?php

/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package     MetaModels
 * @subpackage  AttributeCombinedValues
 * @author      Stefan Heimes <stefan_heimes@hotmail.com>
 * @author      Andreas Isaak <andy.jared@googlemail.com>
 * @author      David Greminger <david.greminger@1up.io>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

/**
 * Register the classes
 */
ClassLoader::addClasses(array(
    'MetaModels\Attribute\TranslatedCombinedValues\TranslatedCombinedValues' => 'system/modules/metamodelsattribute_translatedcombinedvalues/MetaModels/Attribute/TranslatedCombinedValues/TranslatedCombinedValues.php',
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array(
    'mm_attr_translatedcombinedvalues'                  => 'system/modules/metamodelsattribute_translatedcombinedvalues/templates',
));