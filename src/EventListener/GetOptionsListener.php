<?php

/**
 * This file is part of MetaModels/attribute_translatedcombinedvalues.
 *
 * (c) 2012-2022 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_translatedcombinedvalues
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2022 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCombinedValuesBundle\EventListener;

use ContaoCommunityAlliance\DcGeneral\Data\ModelId;
use MenAtWork\MultiColumnWizardBundle\Event\GetOptionsEvent;
use MetaModels\Attribute\IInternal;
use MetaModels\IFactory;

/**
 * Class GetOptionsListener
 */
class GetOptionsListener
{
    /**
     * MetaModel factory.
     *
     * @var IFactory
     */
    private $factory;

    /**
     * MetaModels system columns.
     *
     * @var array
     */
    private $systemColumns;

    /**
     * GetOptionsListener constructor.
     *
     * @param IFactory $factory       MetaModel factory.
     * @param array    $systemColumns System columns.
     */
    public function __construct(IFactory $factory, array $systemColumns)
    {
        $this->factory       = $factory;
        $this->systemColumns = $systemColumns;
    }

    /**
     * Check if the event is intended for us.
     *
     * @param GetOptionsEvent $event The event to test.
     *
     * @return bool
     */
    private function isEventForMe(GetOptionsEvent $event)
    {
        return
            ($event->getEnvironment()->getDataDefinition()->getName() === 'tl_metamodel_attribute')
            && ($event->getPropertyName() === 'combinedvalues_fields')
            && ($event->getSubPropertyName() === 'field_attribute');
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
        if (null !== $event->getOptions() || !$this->isEventForMe($event)) {
            return;
        }

        $model       = $event->getModel();
        $metaModelId = $model->getProperty('pid');
        if (!$metaModelId) {
            $metaModelId = ModelId::fromSerialized(
                $event->getEnvironment()->getInputProvider()->getParameter('pid')
            )->getId();
        }

        $metaModelName = $this->factory->translateIdToMetaModelName($metaModelId);
        $metaModel     = $this->factory->getMetaModel($metaModelName);

        if (!$metaModel) {
            return;
        }

        $result = [];

        // Fetch all attributes except for the current attribute.
        foreach ($metaModel->getAttributes() as $attribute) {
            if ($attribute->get('id') === $model->getId()) {
                continue;
            }

            // Hide virtual types.
            if ($attribute instanceof IInternal) {
                continue;
            }

            $result['attributes'][$attribute->getColName()] = \sprintf(
                '%s [%s, "%s"]',
                $attribute->getName(),
                $attribute->get('type'),
                $attribute->getColName()
            );
        }

        // Add meta fields.
        $result['meta'] = $this->systemColumns;

        $event->setOptions($result);
    }
}
