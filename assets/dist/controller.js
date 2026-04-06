import { Controller } from '@hotwired/stimulus';
import mapboxgl from 'mapbox-gl';
import 'mapbox-gl/dist/mapbox-gl.css';

export default class extends Controller {

    static targets = [
        'map',
        'input'
    ];

    static values = {
        token: String,
        style: String,
        lat: Number,
        lng: Number,
        zoom: Number
    };

    connect() {
        mapboxgl.accessToken = this.tokenValue;
        this.map = new mapboxgl.Map({
            container: this.mapTarget,
            style: this.styleValue,
            center: [this.lngValue, this.latValue],
            zoom: this.zoomValue
        });

        this.map.on('click', (e) => {
            if (this.marker) {
                this.marker.setLngLat([e.lngLat.lng, e.lngLat.lat]);
            } else {
                this.marker = new mapboxgl.Marker().setLngLat([e.lngLat.lng, e.lngLat.lat]).addTo(this.map);
            }
        });
    }

    disconnect() {
        if (this.map) {
            this.map.remove();
        }
    }
}