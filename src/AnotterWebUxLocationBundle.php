<?php

namespace Anotterweb\UxLocation;

use Anotterweb\UxLocation\Form\Type\LocationType;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class AnotterWebUxLocationBundle extends AbstractBundle
{
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->services()
            ->set('anotter_web_ux_location.location_type', LocationType::class)
            ->arg('$mapboxAccessToken', $config['mapbox_access_token'])
            ->arg('$defaultMapStyle', $config['default_map_style'])
            ->tag('form.type');
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('mapbox_access_token')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('default_map_style')
                    ->defaultValue('mapbox://styles/mapbox/standard')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if ($builder->hasExtension('twig')) {
            $builder->prependExtensionConfig('twig', [
                'form_themes' => ['@AnotterWebUxLocation/location_form_theme.html.twig'],
            ]);
        }

        if ($this->isAssetMapperAvailable($builder)) {
            $builder->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [
                        __DIR__ . '/../assets/dist' => '@anotterweb/ux-location',
                    ],
                ],
            ]);
        }
    }

    private function isAssetMapperAvailable(ContainerBuilder $builder): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            return false;
        }

        $bundlesMetadata = $builder->getParameter('kernel.bundles_metadata');
        if (!isset($bundlesMetadata['FrameworkBundle'])) {
            return false;
        }

        return is_file($bundlesMetadata['FrameworkBundle']['path'] . '/Resources/config/asset_mapper.php');
    }
}