<?php

/**
 * This file is part of MetaModels/attribute_translatedcombinedvalues.
 *
 * (c) 2012-2018 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * The MetaModels extension allows the creation of multiple collections of custom items,
 * each with its own unique set of selectable attributes, with attribute extendability.
 * The Front-End modules allow you to build powerful listing and filtering of the
 * data in each collection.
 *
 * @package    MetaModels
 * @subpackage AttributeTranslatedCombinedValues
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     David Greminger <david.greminger@1up.io>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\DcGeneral\Events\Table\Attribute\TranslatedCombinedValues;

use MenAtWork\MultiColumnWizard\Event\GetOptionsEvent;
use MetaModels\DcGeneral\Events\BaseSubscriber;

/**
 * Handle events for tl_metamodel_attribute.combinedvalues_fields.field_attribute.
 */
class Subscriber extends BaseSubscriber
{
    /**
     * {@inheritdoc}
     */
    protected function registerEventsInDispatcher()
    {
        $this->addListener(
            GetOptionsEvent::NAME,
            [$this, 'getOptions']
        );
    }

    /**
     * Retrieve the options for the attributes.
     *
     * @param GetOptionsEvent $event The event.
     *
     * @return void
     */
    public function getOptions(GetOptionsEvent $event)
    {
        if (($event->getEnvironment()->getDataDefinition()->getName() !== 'tl_metamodel_attribute')
            || ($event->getPropertyName() !== 'combinedvalues_fields')
            || ($event->getSubPropertyName() !== 'field_attribute')
        ) {
            return;
        }

        $model     = $event->getModel();
        $metaModel = $this->getMetaModelById($model->getProperty('pid'));

        if (!$metaModel) {
            return;
        }

        $result = [];
        // Add meta fields.
        $result['meta'] = self::getMetaModelsSystemColumns();

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

            $result['attributes'][$attribute->getColName()] = \sprintf(
                '%s [%s]',
                $attribute->getName(),
                $type
            );
        }

        $event->setOptions($result);
    }

    /**
     * Returns the METAMODELS_SYSTEM_COLUMNS (replacement for super globals access).
     *
     * @return array METAMODELS_SYSTEM_COLUMNS
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getMetaModelsSystemColumns()
    {
        return $GLOBALS['METAMODELS_SYSTEM_COLUMNS'];
    }
}
