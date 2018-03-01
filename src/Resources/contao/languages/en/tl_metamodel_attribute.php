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
 * @author      Andreas Isaak <andy.jared@googlemail.com>
 * @author      David Greminger <david.greminger@1up.io>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['typeOptions']['translatedcombinedvalues'] = 'Translated Combined values.';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['combinedvalues_fields']                   = array('Fields', 'Please select one or more attributes to combine.');
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['combinedvalues_format']                   = array('Formate', 'Use "%s" or any other placeholder to create a formated text. See <a href="http://php.net/sprintf" onclick="window.open(this.href)">sprintf</a> for more informations.');
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['force_combinedvalues']                    = array('Force regenerating', 'Check this, if you want the combined values to be regenerated whenever any of the dependant fields is changed.');
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['field_attribute']                         = 'Attributes';

$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['id']                     = 'ID';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['pid']                    = 'PID';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['sorting']                = 'Sorting';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['tstamp']                 = 'Timestamp';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['vargroup']               = 'Vargroup';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['varbase']                = 'Varbase';

$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['meta']                   = 'Metafields';
$GLOBALS['TL_LANG']['tl_metamodel_attribute']['select_values']['attributes']             = 'Attributes';
