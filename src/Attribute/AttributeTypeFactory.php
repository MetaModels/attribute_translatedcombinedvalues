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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2012-2018 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCombinedValuesBundle\Attribute;

use Doctrine\DBAL\Connection;
use MetaModels\Attribute\AbstractAttributeTypeFactory;
use MetaModels\Helper\TableManipulator;

/**
 * Attribute type factory for translated combined values attributes.
 */
class AttributeTypeFactory extends AbstractAttributeTypeFactory
{
    /**
     * Database connection.
     *
     * @var Connection
     */
    private $connection;

    /**
     * Table manipulator.
     *
     * @var TableManipulator
     */
    private $tableManipulator;

    /**
     * Construct.
     *
     * @param Connection       $connection       Database connection.
     * @param TableManipulator $tableManipulator Table manipulator.
     */
    public function __construct(Connection $connection, TableManipulator $tableManipulator)
    {
        parent::__construct();

        $this->typeName  = 'translatedcombinedvalues';
        $this->typeIcon  = 'bundles/metamodelsattributetranslatedcombinedvalues/combinedvalues.png';
        $this->typeClass = 'MetaModels\AttributeTranslatedCombinedValuesBundle\Attribute\TranslatedCombinedValues';

        $this->connection       = $connection;
        $this->tableManipulator = $tableManipulator;
    }

    /**
     * {@inheritdoc}
     */
    public function createInstance($information, $metaModel)
    {
        return new TranslatedCombinedValues($metaModel, $information, $this->connection, $this->tableManipulator);
    }
}
