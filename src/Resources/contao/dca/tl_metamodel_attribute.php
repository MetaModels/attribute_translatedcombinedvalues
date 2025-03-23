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
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <andy.jared@googlemail.com>
 * @author     David Greminger <david.greminger@1up.io>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2024 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

/**
 * Table tl_metamodel_attribute
 */

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['metapalettes']['translatedcombinedvalues extends _simpleattribute_'] = [
    '+advanced' => ['force_combinedvalues'],
    '+display'  => ['combinedvalues_format after description', 'combinedvalues_fields'],
];

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['combinedvalues_fields'] = [
    'label'       => 'combinedvalues_fields.label',
    'description' => 'combinedvalues_fields.description',
    'exclude'     => true,
    'inputType'   => 'multiColumnWizard',
    'eval'        => [
        'useTranslator' => true,
        'tl_class'      => 'clr w50',
        'columnFields'  => [
            'field_attribute' => [
                'label'       => 'field_attribute.label',
                'description' => 'field_attribute.description',
                'exclude'     => true,
                'inputType'   => 'select',
                'reference'   => [
                    'id'         => 'select_values.id',
                    'pid'        => 'select_values.pid',
                    'sorting'    => 'select_values.sorting',
                    'tstamp'     => 'select_values.tstamp',
                    'vargroup'   => 'select_values.vargroup',
                    'varbase'    => 'select_values.varbase',
                    'meta'       => 'select_values.meta',
                    'attributes' => 'select_values.attributes',
                ],
                'eval'        => [
                    'style'  => 'width:100%',
                    'chosen' => 'true',
                ],
            ],
        ],
    ],
    'sql'         => 'blob NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['force_combinedvalues'] = [
    'label'       => 'force_combinedvalues.label',
    'description' => 'force_combinedvalues.description',
    'exclude'     => true,
    'inputType'   => 'checkbox',
    'eval'        => ['tl_class' => 'w50'],
    'sql'         => 'char(1) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['combinedvalues_format'] = [
    'label'       => 'combinedvalues_format.label',
    'description' => 'combinedvalues_format.description',
    'exclude'     => true,
    'inputType'   => 'text',
    'eval'        => ['mandatory' => true, 'tl_class' => 'long'],
    'sql'         => 'text NULL'
];
