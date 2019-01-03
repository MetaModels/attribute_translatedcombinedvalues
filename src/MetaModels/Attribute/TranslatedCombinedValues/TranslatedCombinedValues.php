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
 * @author     Andreas Isaak <andy.jared@googlemail.com>
 * @author     David Greminger <david.greminger@1up.io>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\Attribute\TranslatedCombinedValues;

use MetaModels\Attribute\TranslatedReference;

/**
 * This is the MetaModelAttribute class for handling combined values.
 */
class TranslatedCombinedValues extends TranslatedReference
{
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
        return \array_merge(
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
     */
    public function modelSaved($objItem)
    {
        // combined values already defined and no update forced, get out!
        if ($objItem->get($this->getColName()) && (!$this->get('force_combinedvalues'))) {
            return;
        }

        $arrCombinedValues = [];
        foreach (\deserialize($this->get('combinedvalues_fields')) as $strAttribute) {
            if ($this->isMetaField($strAttribute['field_attribute'])) {
                $strField            = $strAttribute['field_attribute'];
                $arrCombinedValues[] = $objItem->get($strField);
            } else {
                $arrValues           = $objItem->parseAttribute($strAttribute['field_attribute'], 'text', null);
                $arrCombinedValues[] = $arrValues['text'];
            }
        }

        $strCombinedValues = \vsprintf($this->get('combinedvalues_format'), $arrCombinedValues);
        $strCombinedValues = \trim($strCombinedValues);

        // we need to fetch the attribute values for all attribs in the combinedvalues_fields and update the database
        // and the model accordingly.
        if ($this->get('isunique')) {
            // ensure uniqueness.
            $strLanguage           = $this->getMetaModel()->getActiveLanguage();
            $strBaseCombinedValues = $strCombinedValues;
            $arrIds                = [$objItem->get('id')];
            $intCount              = 2;
            while (\array_diff($this->searchForInLanguages($strCombinedValues, [$strLanguage]), $arrIds)) {
                $intCount++;
                $strCombinedValues = $strBaseCombinedValues .' ('.$intCount.')';
            }
        }

        $arrData = $this->widgetToValue($strCombinedValues, $objItem->get('id'));

        $this->setTranslatedDataFor([$objItem->get('id') => $arrData], $this->getMetaModel()->getActiveLanguage());
        $objItem->set($this->getColName(), $arrData);
    }

    /**
     * {@inheritdoc}
     */
    public function get($strKey)
    {
        if ($strKey == 'force_alias') {
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
        $strField = \trim($strField);

        if (\in_array($strField, $this->getMetaModelsSystemColumns())) {
            return true;
        }

        return false;
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
