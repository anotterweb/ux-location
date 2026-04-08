<?php

namespace Anotterweb\UxLocation\Form\Type;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    private string $mapboxAccessToken;
    private string $defaultMapStyle;
    private int $defaultZoom;
    private float $defaultLat;
    private float $defaultLng;
    private string $mapHeight;

    public function __construct(
        string $mapboxAccessToken,
        string $defaultMapStyle,
        int $defaultZoom,
        float $defaultLat,
        float $defaultLng,
        string $mapHeight,
    ) {
        $this->mapboxAccessToken = $mapboxAccessToken;
        $this->defaultMapStyle = $defaultMapStyle;
        $this->defaultZoom = $defaultZoom;
        $this->defaultLat = $defaultLat;
        $this->defaultLng = $defaultLng;
        $this->mapHeight = $mapHeight;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'default_zoom' => $this->defaultZoom,
            'default_lat' => $this->defaultLat,
            'default_lng' => $this->defaultLng,
            'map_height' => $this->mapHeight,
            'map_style' => $this->defaultMapStyle,
            'access_token' => $this->mapboxAccessToken
        ]);

        $resolver->setAllowedTypes('default_zoom', 'int');
        $resolver->setAllowedTypes('default_lat', 'numeric');
        $resolver->setAllowedTypes('default_lng', 'numeric');
        $resolver->setAllowedTypes('map_height', 'string');
        $resolver->setAllowedTypes('map_style', 'string');
        $resolver->setAllowedTypes('access_token', 'string');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['default_zoom'] = $options['default_zoom'];
        $view->vars['default_lat'] = $options['default_lat'];
        $view->vars['default_lng'] = $options['default_lng'];
        $view->vars['map_height'] = $options['map_height'];
        $view->vars['map_style'] = $options['map_style'];
        $view->vars['access_token'] = $options['access_token'];
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}