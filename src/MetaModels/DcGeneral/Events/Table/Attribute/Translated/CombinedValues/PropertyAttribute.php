<?php
/**
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * PHP version 5
 * @package    MetaModels
 * @subpackage Core
 * @author      Stefan Heimes <stefan_heimes@hotmail.com>
 * @author      David Greminger <david.greminger@1up.io>
 * @copyright  The MetaModels team.
 * @license    LGPL.
 * @filesource
 */

namespace MetaModels\DcGeneral\Events\Table\Attribute\Translated\CombinedValues;

use ContaoCommunityAlliance\Contao\EventDispatcher\Event\CreateEventDispatcherEvent;
use ContaoCommunityAlliance\DcGeneral\Contao\View\Contao2BackendView\Event\GetPropertyOptionsEvent;
use ContaoCommunityAlliance\DcGeneral\Factory\Event\BuildDataDefinitionEvent;
use MetaModels\DcGeneral\Events\BaseSubscriber;
use MetaModels\Factory;

/**
 * Handle events for tl_metamodel_attribute.combinedvalues_fields.field_attribute.
 */
class PropertyAttribute
    extends BaseSubscriber
{
    /**
     * Register all listeners to handle creation of a data container.
     *
     * @param CreateEventDispatcherEvent $event The event.
     *
     * @return void
     */
    public static function registerEvents(CreateEventDispatcherEvent $event)
    {
        $dispatcher = $event->getEventDispatcher();
        self::registerBuildDataDefinitionFor(
            'tl_metamodel_attribute',
            $dispatcher,
            __CLASS__.'::registerTableMetaModelAttributeEvents'
        );
    }

    /**
     * Register the events for table tl_metamodel_attribute.
     *
     * @param BuildDataDefinitionEvent $event The event being processed.
     *
     * @return void
     */
    public static function registerTableMetaModelAttributeEvents(BuildDataDefinitionEvent $event)
    {
        static $registered;
        if ($registered) {
            return;
        }
        $registered = true;
        $dispatcher = $event->getDispatcher();

        self::registerListeners(
            array(
                GetPropertyOptionsEvent::NAME => __CLASS__.'::getOptions',
            ),
            $dispatcher,
            array('tl_metamodel_attribute', 'combinedvalues_fields', 'field_attribute')
        );
    }

    /**
     * Retrieve the options for the attributes.
     *
     * @param GetPropertyOptionsEvent $event The event.
     *
     * @return void
     */
    public static function getOptions(GetPropertyOptionsEvent $event)
    {
        $model     = $event->getModel();
        $metaModel = Factory::byId($model->getProperty('pid'));

        if (!$metaModel) {
            return;
        }

        $result = array();
        // Add meta fields.
        $result['meta'] = $GLOBALS['METAMODELS_SYSTEM_COLUMNS'];

        // Fetch all attributes except for the current attribute.
        foreach ($metaModel->getAttributes() as $attribute) {
            if ($attribute->get('id') === $model->getId()) {
                continue;
            }

            $type = $event
                ->getEnvironment()
                ->getTranslator()
                ->translate('typeOptions.'.$attribute->get('type'), 'tl_metamodel_attribute');

            if ($type == 'typeOptions.'.$attribute->get('type')) {
                $type = $attribute->get('type');
            }

            $result['attributes'][$attribute->getColName()] = sprintf(
                '%s [%s]',
                $attribute->getName(),
                $type
            );
        }

        $event->setOptions($result);
    }
}
