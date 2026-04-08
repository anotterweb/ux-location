<?php

namespace Anotterweb\UxLocation\Tests\Form\Type;

use Anotterweb\UxLocation\Form\Type\LocationType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationTypeTest extends TestCase
{
    public function testConfigureOptions(): void
    {
        $type = new LocationType('my-token', 'mapbox://styles', 5, 42.0, 2.0, '400px');
        $resolver = new OptionsResolver();
        $type->configureOptions($resolver);

        $options = $resolver->resolve([]);
        $this->assertEquals('my-token', $options['access_token']);
        $this->assertEquals('mapbox://styles', $options['map_style']);
        $this->assertEquals(5, $options['default_zoom']);
        $this->assertEquals(42.0, $options['default_lat']);
        $this->assertEquals(2.0, $options['default_lng']);
        $this->assertEquals('400px', $options['map_height']);
    }

    public function testBuildView(): void
    {
        $type = new LocationType('token', 'style', 1, 2.0, 3.0, '200px');
        $view = new FormView();
        $form = $this->createStub(FormInterface::class);

        $options = [
            'default_zoom' => 10,
            'default_lat' => 11.0,
            'default_lng' => 12.0,
            'map_height' => '500px',
            'map_style' => 'custom',
            'access_token' => 'custom-token',
        ];

        $type->buildView($view, $form, $options);

        $this->assertArrayHasKey('default_zoom', $view->vars);
        $this->assertEquals(10, $view->vars['default_zoom']);
        $this->assertEquals(11.0, $view->vars['default_lat']);
        $this->assertEquals(12.0, $view->vars['default_lng']);
        $this->assertEquals('500px', $view->vars['map_height']);
        $this->assertEquals('custom', $view->vars['map_style']);
        $this->assertEquals('custom-token', $view->vars['access_token']);
    }

    public function testGetParent(): void
    {
        $type = new LocationType('token', 'style', 1, 2.0, 3.0, '200px');
        $this->assertEquals(TextType::class, $type->getParent());
    }
}
