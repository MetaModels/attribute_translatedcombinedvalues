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
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2012-2019 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCombinedValuesBundle\Attribute;

use Doctrine\DBAL\Connection;
use MetaModels\Attribute\IAttributeTypeFactory;
use MetaModels\Helper\TableManipulator;

/**
 * Attribute type factory for translated combined values attributes.
 */
class AttributeTypeFactory implements IAttributeTypeFactory
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
        $this->connection       = $connection;
        $this->tableManipulator = $tableManipulator;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeName()
    {
        return 'translatedcombinedvalues';
    }

    /**
     * {@inheritdoc}
     */
    public function getTypeIcon()
    {
        return 'bundles/metamodelsattributetranslatedcombinedvalues/combinedvalues.png';
    }

    /**
     * {@inheritdoc}
     */
    public function createInstance($information, $metaModel)
    {
        return new TranslatedCombinedValues($metaModel, $information, $this->connection, $this->tableManipulator);
    }

    /**
     * {@inheritdoc}
     */
    public function isTranslatedType()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isSimpleType()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isComplexType()
    {
        return true;
    }
}
