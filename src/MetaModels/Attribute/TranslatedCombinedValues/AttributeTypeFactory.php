<?php

/**
 * This file is part of MetaModels/attribute_translatedcombinedvalues.
 *
 * (c) 2012-2016 The MetaModels team.
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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2016 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\Attribute\TranslatedCombinedValues;

use MetaModels\Attribute\AbstractAttributeTypeFactory;

/**
 * Attribute type factory for translated combined values attributes.
 */
class AttributeTypeFactory extends AbstractAttributeTypeFactory
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct();
        $this->typeName  = 'translatedcombinedvalues';
        $this->typeIcon  = 'system/modules/metamodelsattribute_translatedcombinedvalues/html/combinedvalues.png';
        $this->typeClass = 'MetaModels\Attribute\TranslatedCombinedValues\TranslatedCombinedValues';
    }
}
