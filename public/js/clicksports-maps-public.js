(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
     *
     * @since   1.0.0
	 */

    $(document).ready(function($){

        /**
         * This function is calls the map script and displays the map.
         */

        // Plugin settings are passed as an object from wp_localize_script function (found in /public/class-clicksports-maps-public.php).
        var mapSettings = clicksports_maps_settings;
        var latitude;
        var longitude;
        var markertext;
        var width;
        var fullwidth;
        var height;
        var zoomlevel;
        var colortheme;
        var maptheme;
        var markers;

        var translation_map_error_text;
        var translation_google_route_text;

        // Iterate through the object and populate variables by key.
        $.each(mapSettings, function() {

            latitude    = this['latitude'];
            longitude   = this['longitude'];
            markertext  = this['markertext'];
            width       = this['width'];
            fullwidth   = this['fullwidth'];
            height      = this['height'];
            zoomlevel   = this['zoomlevel'];
            colortheme  = this['colortheme'];
            maptheme    = this['maptheme'];
            markers     = this['markers'];

            translation_map_error_text      = this['translation_map_error_text'];
            translation_google_route_text   = this['translation_google_route_text'];

        });

        /**
         * Get data attributes of the shortcode, which are set in clicksports-maps.php if overwritten.
         *
         * @since   1.0.4
         * @update  1.3.1
         */
        var data_attributes = getDataAttributes(['lat', 'long', 'zoomlevel', 'maptheme', 'colortheme', 'markertext']);
        if(data_attributes['lat']) latitude = data_attributes['lat'];
        if(data_attributes['long']) longitude = data_attributes['long'];
        if(data_attributes['zoomlevel']) zoomlevel = data_attributes['zoomlevel'];
        if(data_attributes['maptheme']) maptheme = data_attributes['maptheme'];
        if(data_attributes['colortheme']) colortheme = data_attributes['colortheme'];
        if(data_attributes['markertext']) markertext = data_attributes['markertext'];

        /**
         * Undocumented shortcode attribute to overwrite the Google Maps route link title translation.
         *
         * @since   1.3.1
         */
        var shortcode_google_route_text = getDataAttributeRouteText();
        if(shortcode_google_route_text) translation_google_route_text = shortcode_google_route_text;

        if (latitude && longitude && markertext && height && (width || fullwidth) && zoomlevel && colortheme && maptheme) {

            // The ID for the map container always stays the same.
            var map_class = 'clicksports-maps-public';

            // Sets the script paths using the passed plugin directory.
            var script_path_mapbox          = 'https://maps.clicksports.de/mapbox-gl.min.js';
            var script_path_mapbox_language = 'https://maps.clicksports.de/openmaptiles-language.min.js';
            var script_path_mobile_detect   = 'https://maps.clicksports.de/mobile-detect.js';

            /**
             * Check if the map container actually exists.
             * This way there won't be any script errors on pages without a map.
             */
            if ($('#' + map_class).length) {

                /**
                 * Calls the needed scripts.
                 */
                $.getScript(script_path_mapbox, function () {

                    $.getScript(script_path_mapbox_language, function () {

                        $.getScript(script_path_mobile_detect, function () {

                            var map = new mapboxgl.Map({
                                container: map_class,
                                style: 'https://maps.clicksports.de/styles/' + maptheme + '/style.json',
                                center: [longitude, latitude],
                                zoom: zoomlevel,
                            });

                            // Enables language auto detection.
                            map.autodetectLanguage();

                            // Disables zoom on mouse scroll.
                            map.scrollZoom.disable();

                            // Adds navigation controller to map.
                            map.addControl(new mapboxgl.NavigationControl());

                            /**
                             * Disables dragging on mobile devices.
                             * Details: http://hgoebl.github.io/mobile-detect.js/
                             */
                            const md = new MobileDetect(window.navigator.userAgent);
                            if (md.mobile()) {
                                map.dragPan.disable();
                            }

                            // Creates a popup from HTML content.
                            var popup = new mapboxgl.Popup()
                                .setHTML(
                                    '<div class="csmaps-popup-marker-text">' +
                                    markertext +
                                    '<br /><a href="https://www.google.de/maps/dir//' +
                                    latitude + ',' +
                                    longitude + '/@' +
                                    latitude + ',' +
                                    longitude + ',' +
                                    zoomlevel +
                                    '" target="_blank">' + translation_google_route_text + '</a>' +
                                    '</div>'
                                );

                            // Adds the marker with the popup to the map.
                            new mapboxgl.Marker({"color": colortheme})
                                .setLngLat([longitude, latitude])
                                .setPopup(popup)
                                .addTo(map);

                            /**
                             * Additional markers and popups.
                             */
                            var marker_array = $.map(markers, function(value) {
                                return [value];
                            });

                            for (var i = 0; i < marker_array.length; i++) {

                                var popup = new mapboxgl.Popup()
                                    .setHTML(
                                        '<div class="csmaps-popup-marker-text">' +
                                        marker_array[i]['markertext'] +
                                        '<br /><a href="https://www.google.de/maps/dir//' +
                                        marker_array[i]['latitude'] + ',' +
                                        marker_array[i]['longitude'] + '/@' +
                                        marker_array[i]['latitude'] + ',' +
                                        marker_array[i]['longitude'] + ',' +
                                        zoomlevel +
                                        '" target="_blank">' + translation_google_route_text + '</a>' +
                                        '</div>'
                                    );

                                new mapboxgl.Marker({"color": colortheme})
                                    .setLngLat([marker_array[i]['longitude'], marker_array[i]['latitude']])
                                    .setPopup(popup)
                                    .addTo(map);

                            }

                        })

                    })

                });

            }

            // Display the map on frontend.
            $('#' + map_class).show();
        } else {
            $('#clicksports-maps-public-wrapper').html('<div id="clicksports-maps-public-error"><span class="clicksports-maps-public-error-text">' + translation_map_error_text + '</span></div>');
        }

    });

    /**
     * Get data attributes.
     *
     * Checks if the maps container has the data attributes, passed by the function parameter and
     * returns all existing data attributes as an array.
     *
     * @param   $array
     * @returns {*[]}
     * @since   1.3.1
     */
    function getDataAttributes($array) {
        var result = [];
        $.each($array, function(index, data_attribute) {
            if($('#clicksports-maps-public').attr('data-' + data_attribute)) {
                result[data_attribute] = $('#clicksports-maps-public').attr('data-' + data_attribute);
            }
        });
        return result;
    }

    /**
     * Get the Google Maps route link title data attribute, which is an undocumented shortcode parameter.
     *
     * @returns {*|jQuery}
     * @since   1.3.1
     */
    function getDataAttributeRouteText() {
        return $('#clicksports-maps-public').attr('data-routetext');
    }

})( jQuery );


