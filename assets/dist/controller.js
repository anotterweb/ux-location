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
        let initialLat = this.latValue;
        let initialLng = this.lngValue;
        let hasMarker = false;
        const inputValue = this.inputTarget.value;
        if (inputValue) {
            const parts = inputValue.split(',');
            if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
                initialLat = parseFloat(parts[0].trim());
                initialLng = parseFloat(parts[1].trim());
                hasMarker = true;
            }
        }

        mapboxgl.accessToken = this.tokenValue;
        this.map = new mapboxgl.Map({
            container: this.mapTarget,
            style: this.styleValue,
            center: [initialLng, initialLat],
            zoom: this.zoomValue
        });

        if (hasMarker) {
            this.marker = new mapboxgl.Marker().setLngLat([initialLng, initialLat]).addTo(this.map);
        }

        this.map.on('click', (e) => {
            if (this.marker) {
                this.marker.setLngLat(e.lngLat);
            } else {
                this.marker = new mapboxgl.Marker().setLngLat(e.lngLat).addTo(this.map);
            }

            const newValue = `${e.latlng.lat},${e.latlng.lng}`;
            this.inputTarget.value = newValue;
            this.inputTarget.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    disconnect() {
        if (this.map) {
            this.map.remove();
        }
    }
}