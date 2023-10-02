/**
 * Entry GPS Coordinates plugin for Craft CMS
 *
 * EntryCoordinates Field JS
 *
 * @author    NTH media
 * @copyright Copyright (c) 2019 NTH media
 * @link      https://nthmedia.nl
 * @package   EntryGpsCoordinates
 * @since     1.0.0EntryGpsCoordinatesEntryCoordinates
 */

 ;(function ( $, window, document, undefined ) {

    var pluginName = "EntryGpsCoordinatesEntryCoordinates",
        defaults = {
        };

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            var _this = this;

            $(function () {

/* -- _this.options gives us access to the $jsonVars that our FieldType passed down to us */

            });
        }
    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );

class CoordinatesField {
    options = {
        originalLocation: null,
        suffix: null,
        zoomLevel: null,
        defaultZoomLevel: null,
    }

    map = null;
    marker = null;

    searchInput = null;
    coordsInput = null;
    zoomInput = null;
    mapElement = null;

    constructor (name, options) {
        this.options = options;

        if (this.options.zoomLevel === '' || this.options.zoomLevel === null) {
            this.options.zoomLevel = options.defaultZoomLevel;
        }

        const container = document.getElementById(this.options.namespace + '-field');
        this.searchInput = container.querySelector('.location-search-' + this.options.suffix);
        this.coordsInput = container.querySelector('.location-coords-' + this.options.suffix);
        this.addressInput = container.querySelector('.location-address-' + this.options.suffix);
        this.zoomInput = container.querySelector('.location-zoom-' + this.options.suffix);

        this.mapElement = container.querySelector('.fields-map-' + this.options.suffix);
    }

    // Deletes all markers in the array by removing references to them.
    // Adds a marker to the map and push to the array.
    placeMarker = (location) => {
        if (this.marker) {
            this.marker.setPosition(location);
        } else {
            let marker = new google.maps.Marker({
                position: location,
                map: this.map,
                draggable: true,
            });
            this.marker = marker;

        }

        let coordsInput = this.coordsInput;
        let addressInput = this.addressInput;

        let updateInputFields = this.updateInputFields;
        let transformLatLngToString = this.transformLatLngToString;

        updateInputFields(this.marker, transformLatLngToString, coordsInput, addressInput);;

        this.marker.addListener('position_changed', function () {
            updateInputFields(this, transformLatLngToString, coordsInput, addressInput);
        });
    }

    updateInputFields(marker, transformLatLngToString, coordsInput, addressInput) {
        let latLng = marker.getPosition();

        coordsInput.value = transformLatLngToString(latLng)

        // Update Search input with the address for the updated coordinates
        let geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'latLng': latLng }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    addressInput.value = results[1].formatted_address
                }
            }
        });
    }

    deleteMarker = () => {
        if (this.marker) {
            this.marker.setMap(null);
            this.marker = null;
        }

        this.coordsInput.value = '';
        this.searchInput.value = '';
        this.addressInput.value = '';
        this.zoomInput.value = '';
    }

    setZoomLevel = (zoomLevel) => {
        this.zoomInput.value = zoomLevel.toString();
    }

    transformLatLngToString(latLng) {
        // Used to transform a LatLng object to a String (google.maps.LatLng)
        return latLng.lat() + ',' + latLng.lng();
    }

    transformLocationToLatLng(locationString) {
        let string = locationString.split(',');

        return new google.maps.LatLng(parseFloat(string[0]), parseFloat(string[1]));
    }

    getCenter(location) {
        if (location !== '') {
            return this.transformLocationToLatLng(location);
        }

        if (this.options.defaultCenterCoordinates !== null && this.options.defaultCenterCoordinates !== '') {
            return this.transformLocationToLatLng(this.options.defaultCenterCoordinates);

        }
        return this.transformLocationToLatLng("52.3793773,4.8981");
    }

    initThisMap() {
        let placeMarker = this.placeMarker;
        let deleteMarker = this.deleteMarker;
        let setZoomLevel = this.setZoomLevel;

        let zoomLevel = this.options.zoomLevel;

        // Create map instance
        const map = new google.maps.Map(this.mapElement, {
            zoom: parseInt(zoomLevel),
            center: this.getCenter(this.options.originalLocation)
        });

        this.map = map;

        // Init Google Places autocomplete
        const autocomplete = new google.maps.places.Autocomplete(this.searchInput);

        // Show Google Places location search
        autocomplete.addListener('place_changed', () => {
            let place = autocomplete.getPlace();

            // If the place has a geometry, then present it on a map.
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
            }

            placeMarker(place.geometry.location);
        });

        // This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
            placeMarker(event.latLng);
        });

        map.addListener('zoom_changed', function() {
            setZoomLevel(map.getZoom());
        });

        // // Set marker for original location
        if (this.options.originalLocation) {
            placeMarker(this.transformLocationToLatLng(this.options.originalLocation));
        }
        else {
            this.coordsInput.addEventListener('keyup', function (event) {
                let inputValue = event.srcElement.value;

                if (inputValue.match(/[0-9],[0-9]/g)) {

                    placeMarker(this.transformLocationToLatLng(inputValue));

                    this.map.setCenter(inputValue);
                }
            });
        }

        this.deleteButton = document.querySelector('.location-delete-' + this.options.suffix);

        this.deleteButton.addEventListener('click', function () {
            deleteMarker();
        });

    }
}

class EntryCoordinatesContainer {
    markers = []
    map = null;
    apiKey = null;

    constructor () {

    }

    loadMapsScript = (apiKey) => {
        if (!window.mapsScriptLoaded) {
            let script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key=' + apiKey + '&callback=initMap&libraries=places';
            script.async = true;

            window.initMap = this.initMarkers;

            document.head.appendChild(script);

            window.mapsScriptLoaded = true;
        }
    }

    initMarkers = () => {
        this.markers.forEach((marker) => {
            marker.initThisMap();
        });
    }

    setApiKey = (apiKey) => {
        this.apiKey = apiKey
    }

    addField = (name, options) => {
        if (!this.map) {
            this.loadMapsScript(this.apiKey ?? options.apiKey)
        }

        const marker = new CoordinatesField(name, options);

        // Ensure markers created after the Google Maps API script is loaded are still initialised
        if (typeof google !== 'undefined') {
            marker.initThisMap();
        }

        this.markers.push(marker);

        return true;
    }
}

window.EntryCoordinates = new EntryCoordinatesContainer;