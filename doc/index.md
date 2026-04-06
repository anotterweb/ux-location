# AnotterwebUxLocationBundle documentation

This bundle provides a `LocationType` form field designed to integrate seamlessly with Symfony UX (Stimulus & AssetMapper). It embeds an interactive Mapbox map to allow users to pinpoint a geographic location.

## Installation

Install the bundle using Composer:

```bash
composer require anotterweb/ux-location
```

Since this bundle relies on Symfony UX and Mapbox, you also need to make sure the JavaScript dependencies are installed. If you are using AssetMapper, run:

```bash
php bin/console importmap:require mapbox-gl mapbox-gl/dist/mapbox-gl.css
```

## Configuration

To use the bundle, you need to configure your Mapbox access token. Create or update the `config/packages/anotter_web_ux_location.yaml` file:

```yaml
anotter_web_ux_location:
    # Required: Your Mapbox access token
    mapbox_access_token: '%env(MAPBOX_ACCESS_TOKEN)%'
    
    # Optional: Set a default map style for all LocationType fields
    # default_map_style: 'mapbox://styles/mapbox/standard'
```

### Required Configuration
* `mapbox_access_token`: A valid Mapbox token is required to load the map and fetch location data. You can get a free token by creating an account on [Mapbox](https://www.mapbox.com/).

### Optional Configuration
* `default_map_style`: Allows you to globally change the style of the Mapbox map. By default, it uses `mapbox://styles/mapbox/standard`.

## Usage

You can use the `LocationType` in your Symfony forms to display an interactive map. It extends `TextType` and handles the geographic coordinates.

```php
namespace App\Form;

use Anotterweb\UxLocation\Form\Type\LocationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('location', LocationType::class, [
                'label' => 'Pinpoint your store location',
                'default_zoom' => 12,
                'default_lat' => 45.7640,
                'default_lng' => 4.8357,
            ])
        ;
    }
}
```

The bundle automatically registers the necessary form themes and AssetMapper paths, so rendering the form field in Twig is simply:

```twig
{{ form_row(form.location) }}
```

## LocationType Options

The `LocationType` provides several options to customize the appearance and behavior of the map per form field:

| Option | Type | Default Value | Description |
|--------|------|---------------|-------------|
| `default_zoom` | `int` | `4` | The initial zoom level of the map when it loads. |
| `default_lat` | `numeric` | `48.8566` (Paris) | The default latitude to center the map on. |
| `default_lng` | `numeric` | `2.3522` (Paris) | The default longitude to center the map on. |
| `map_height` | `string` | `'300px'` | The CSS height applied to the map container. |
| `map_style` | `string` | Value from bundle configuration | The Mapbox style URL to use for this specific field. Overrides the global `default_map_style`. |
| `access_token` | `string` | Value from bundle configuration | The Mapbox access token to use. Overrides the global `mapbox_access_token`. |
