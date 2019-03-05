<?php

/**
 * This file is part of MetaModels/attribute_translatedcombinedvalues.
 *
 * (c) 2012-2019 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_translatedcombinedvalues
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCombinedValuesBundle\EventListener;

use MenAtWork\MultiColumnWizardBundle\Event\GetOptionsEvent;
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
     * Returns the system columns.
     *
     * @return array
     */
    public function getMetaModelsSystemColumns()
    {
        return $this->systemColumns;
    }

    /**
     * Get the metamodel by it's id.
     *
     * @param int $metaModelId MetaModel id.
     *
     * @return \MetaModels\IMetaModel|null
     */
    private function getMetaModelById($metaModelId)
    {
        $name = $this->factory->translateIdToMetaModelName($metaModelId);

        return $this->factory->getMetaModel($name);
    }
}
