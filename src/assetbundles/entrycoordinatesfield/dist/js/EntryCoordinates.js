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
        suffix: null
    }

    map = null;
    marker = null;

    searchInput = null;
    coordsInput = null;
    mapElement = null;

    constructor (name, options) {
        this.options = options;

        this.searchInput = document.querySelector('.location-search-' + this.options.suffix);
        this.coordsInput = document.querySelector('.location-coords-' + this.options.suffix);
        this.addressInput = document.querySelector('.location-address-' + this.options.suffix);
        this.mapElement = document.querySelector('.fields-map-' + this.options.suffix);
        this.deleteButton = document.querySelector('.location-delete-' + this.options.suffix);

        // let marker = this.marker;
        let deleteMarker = this.deleteMarker;

        let marker = this.marker;
        let coordsInput = this.coordsInput;
        let addressInput = this.addressInput;
        let searchInput = this.searchInput;

        this.deleteButton.addEventListener('click', function () {
            deleteMarker(this.marker, coordsInput, addressInput, searchInput);
        });
    }

    // Deletes all markers in the array by removing references to them.
    // Adds a marker to the map and push to the array.
    placeMarker = (location) => {
        console.log("placeMarker");
        console.log(location);
        console.log(this.marker);

        if (this.marker) {
            console.log('this.marker exists');
            this.marker.setPosition(location);
        } else {
            let marker = new google.maps.Marker({
                position: location,
                map: this.map,
                draggable: true,
            });
            this.marker = marker;
            console.log('Set this.marker');
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

    deleteMarker(marker, coordsInput, addressInput, searchInput) {
        if (marker) {
            marker.setMap(null);
            marker = null;
        }

        coordsInput.value = '';
        searchInput.value = '';
        addressInput.value = '';
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
        console.log("getCenter");
        console.log("location: " + location);
        if (location !== '') {
            return this.transformLocationToLatLng(location);
        }

        console.log("No location. Fallback to Amsterdam")
        return this.transformLocationToLatLng("52.3793773,4.8981");
    }

    initThisMap() {
        console.log("initThisMap");

        let placeMarker = this.placeMarker;

        // Create map instance
        const map = new google.maps.Map(this.mapElement, {
            zoom: 13,
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

        this.markers.push(new CoordinatesField(name, options));

        return true;
    }
}

window.EntryCoordinates = new EntryCoordinatesContainer;