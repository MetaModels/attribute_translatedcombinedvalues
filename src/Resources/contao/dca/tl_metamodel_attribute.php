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
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Andreas Isaak <andy.jared@googlemail.com>
 * @author     David Greminger <david.greminger@1up.io>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2019 The MetaModels team.
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
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['combinedvalues_fields'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => [
        'tl_class'     => 'clr w50',
        'columnFields' => [
            'field_attribute' => [
                'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['field_attribute'],
                'exclude'   => true,
                'inputType' => 'select',
                'reference' => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values'],
                'eval'      => [
                    'style'  => 'width:100%',
                    'chosen' => 'true',
                ],
            ],
        ],
    ],
    'sql'       => 'blob NULL'
];

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['force_combinedvalues'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['force_combinedvalues'],
    'exclude'   => true,
    'inputType' => 'checkbox',
    'eval'      => ['tl_class' => 'w50'],
    'sql'       => 'char(1) NOT NULL default \'\''
];

$GLOBALS['TL_DCA']['tl_metamodel_attribute']['fields']['combinedvalues_format'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_metamodel_attribute']['combinedvalues_format'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => ['mandatory' => true, 'tl_class' => 'long'],
    'sql'       => 'text NULL'
];
