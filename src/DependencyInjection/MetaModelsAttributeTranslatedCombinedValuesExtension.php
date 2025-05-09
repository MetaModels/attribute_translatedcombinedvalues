<?php

/**
 * This file is part of MetaModels/attribute_translatedcombinedvalues.
 *
 * (c) 2012-2022 The MetaModels team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    MetaModels/attribute_translatedcombinedvalues
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2012-2022 The MetaModels team.
 * @license    https://github.com/MetaModels/attribute_translatedcombinedvalues/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace MetaModels\AttributeTranslatedCombinedValuesBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the Bundle extension.
 *
 * @SuppressWarnings(PHPMD.LongClassName)
 */
class MetaModelsAttributeTranslatedCombinedValuesExtension extends Extension
{
    /**
     * {@inheritDoc}
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('listeners.yml');

        $typeNames                = $container->getParameter('metamodels.managed-schema-type-names');
        $managedSchemaTypeNames   = \is_array($typeNames) ? $typeNames : [];
        $managedSchemaTypeNames[] = 'translatedcombinedvalues';
        $container->setParameter('metamodels.managed-schema-type-names', $managedSchemaTypeNames);
    }
}
