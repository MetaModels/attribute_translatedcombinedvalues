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
 * @author      Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author      Stefan Heimes <cms@men-at-work.de>
 * @copyright   The MetaModels team.
 * @license     LGPL.
 * @filesource
 */

$GLOBALS['METAMODELS']['attributes']['translatedcombinedvalues']['class'] = 'MetaModels\Attribute\TranslatedCombinedValues\TranslatedCombinedValues';
$GLOBALS['METAMODELS']['attributes']['translatedcombinedvalues']['image'] = 'system/modules/metamodelsattribute_translatedcombinedvalues/html/combinedvalues.png';

// non composerized Contao 2.X autoload support.
$GLOBALS['MM_AUTOLOAD'][] = dirname(__DIR__);
$GLOBALS['MM_AUTOLOAD'][] = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'deprecated';

// Meta Informations
$GLOBALS['METAMODELS']['metainformation']['allowedTitle'][]       = 'translatedcombinedvalues';
