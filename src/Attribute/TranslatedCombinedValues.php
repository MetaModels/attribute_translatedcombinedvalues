<?php

/**
 * This file is part of MetaModels/attribute_translatedcombinedvalues.
 *
 * (c) 2012-2024 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_translatedcombinedvalues
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Andreas Isaak <andy.jared@googlemail.com>
 * @author     David Greminger <david.greminger@1up.io>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCombinedValuesBundle\Attribute;

use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use MetaModels\Attribute\TranslatedReference;
use MetaModels\Helper\TableManipulator;
use MetaModels\IMetaModel;
use MetaModels\ITranslatedMetaModel;
use RuntimeException;

use function array_diff;
use function array_merge;
use function is_array;
use function trigger_error;
use function trim;
use function vsprintf;

/**
 * This is the MetaModelAttribute class for handling combined values.
 */
class TranslatedCombinedValues extends TranslatedReference
{
    /**
     * Table manipulator.
     *
     * @var TableManipulator
     */
    private TableManipulator $tableManipulator;

    /**
     * Instantiate an MetaModel attribute.
     *
     * Note that you should not use this directly but use the factory classes to instantiate attributes.
     *
     * @param IMetaModel            $objMetaModel     The MetaModel instance this attribute belongs to.
     * @param array                 $arrData          The information array, for attribute information, refer to
     *                                                documentation of table tl_metamodel_attribute and documentation
     *                                                of the certain attribute classes for information what values are
     *                                                understood.
     * @param Connection|null       $connection       Database connection.
     * @param TableManipulator|null $tableManipulator Table manipulator.
     */
    public function __construct(
        IMetaModel $objMetaModel,
        array $arrData = [],
        Connection $connection = null,
        TableManipulator $tableManipulator = null
    ) {
        parent::__construct($objMetaModel, $arrData, $connection);

        if (null === $tableManipulator) {
            // @codingStandardsIgnoreStart
            @trigger_error(
                'Table manipulator argument is missing. Fallback will be dropped in MetaModels 3.',
                E_USER_DEPRECATED
            );
            // @codingStandardsIgnoreEnd
            $tableManipulator = System::getContainer()->get('metamodels.table_manipulator');
            assert($tableManipulator instanceof TableManipulator);
        }
        $this->tableManipulator = $tableManipulator;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValueTable()
    {
        return 'tl_metamodel_translatedtext';
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributeSettingNames()
    {
        return array_merge(
            parent::getAttributeSettingNames(),
            [
                'combinedvalues_fields',
                'combinedvalues_format',
                'force_combinedvalues',
                'isunique',
                'mandatory',
                'filterable',
                'searchable',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldDefinition($arrOverrides = [])
    {
        $arrFieldDef = parent::getFieldDefinition($arrOverrides);

        $arrFieldDef['inputType'] = 'text';

        // we do not need to set mandatory, as we will automatically update our value when isunique is given.
        if ($this->get('isunique')) {
            $arrFieldDef['eval']['mandatory'] = false;
        }

        // If "force_combinedvalues" is true set alwaysSave and readonly to true.
        if ($this->get('force_combinedvalues')) {
            $arrFieldDef['eval']['alwaysSave'] = true;
            $arrFieldDef['eval']['readonly']   = true;
        }

        return $arrFieldDef;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function modelSaved($objItem)
    {
        // combined values already defined and no update forced, get out!
        $value = $objItem->get($this->getColName());
        if (is_array($value) && !((bool) ($value['value'] ?? false)) && !((bool) $this->get('force_combinedvalues'))) {
            return;
        }

        $arrCombinedValues = [];
        foreach (StringUtil::deserialize($this->get('combinedvalues_fields')) as $strAttribute) {
            if ($this->isMetaField($strAttribute['field_attribute'])) {
                $strField            = $strAttribute['field_attribute'];
                $arrCombinedValues[] = $objItem->get($strField);
            } else {
                $arrValues           = $objItem->parseAttribute($strAttribute['field_attribute'], 'text', null);
                $arrCombinedValues[] = $arrValues['text'];
            }
        }

        $strCombinedValues = vsprintf($this->get('combinedvalues_format'), $arrCombinedValues);
        $strCombinedValues = trim($strCombinedValues);

        $metaModel      = $this->getMetaModel();
        $activeLanguage = $this->getActiveLanguage($metaModel);
        assert('' !== $activeLanguage);

        // we need to fetch the attribute values for all attribs in the combinedvalues_fields and update the database
        // and the model accordingly.
        if ($this->get('isunique')) {
            // ensure uniqueness.
            $strBaseCombinedValues = $strCombinedValues;
            $arrIds                = [$objItem->get('id')];
            $intCount              = 2;
            while (array_diff((array) $this->searchForInLanguages($strCombinedValues, [$activeLanguage]), $arrIds)) {
                $intCount++;
                $strCombinedValues = $strBaseCombinedValues . ' (' . $intCount . ')';
            }
        }

        $arrData = $this->widgetToValue($strCombinedValues, $objItem->get('id'));
        $this->setTranslatedDataFor([$objItem->get('id') => $arrData], $activeLanguage);
        $objItem->set($this->getColName(), $arrData);
    }

    /**
     * {@inheritdoc}
     */
    public function get($strKey)
    {
        if ($strKey === 'force_alias') {
            $strKey = 'force_combinedvalues';
        }

        return parent::get($strKey);
    }

    /**
     * Check if we have a metafield from metatmodels.
     *
     * @param string $strField The selected value.
     *
     * @return boolean True => Yes we have | False => nope.
     */
    protected function isMetaField($strField)
    {
        $strField = trim($strField);

        return $this->tableManipulator->isSystemColumn($strField);
    }

    /**
     * Returns the METAMODELS_SYSTEM_COLUMNS (replacement for super globals access).
     *
     * @return array METAMODELS_SYSTEM_COLUMNS
     *
     * @deprecated Method will be dropped in MetaModels 3. Use the TableManipulator instead.
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    public function getMetaModelsSystemColumns()
    {
        return $GLOBALS['METAMODELS_SYSTEM_COLUMNS'];
    }

    /**
     * @psalm-suppress DeprecatedMethod
     */
    private function getActiveLanguage(mixed $metaModel): string
    {
        if ($metaModel instanceof ITranslatedMetaModel) {
            return $metaModel->getLanguage();
        }
        if ($metaModel->isTranslated(false)) {
            return $metaModel->getActiveLanguage(false);
        }
        throw new RuntimeException('How shall I save to an untranslated MetaModel?');
    }
}
