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
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCombinedValuesBundle\Attribute;

use Doctrine\DBAL\Connection;
use MetaModels\Attribute\AbstractAttributeTypeFactory;
use MetaModels\Helper\TableManipulator;
use MetaModels\AttributeTranslatedCombinedValuesBundle\Attribute\TranslatedCombinedValues;

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
        $this->typeClass = TranslatedCombinedValues::class;

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
