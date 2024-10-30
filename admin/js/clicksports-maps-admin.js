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
         * Calls the Wordpress color picker.
         * Overwrites all inputs with set class to become a color picker.
         */
        $('.cs-color-picker').wpColorPicker();

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

        var translation_latitude;
        var translation_longitude;
        var translation_markertext;
        var translation_map_error_text;
        var translation_remove_marker_text;
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

            translation_latitude            = this['translation_latitude'];
            translation_longitude           = this['translation_longitude'];
            translation_markertext          = this['translation_markertext'];
            translation_map_error_text      = this['translation_map_error_text'];
            translation_remove_marker_text  = this['translation_remove_marker_text'];
            translation_google_route_text   = this['translation_google_route_text'];

        });

        if (latitude && longitude && markertext && height && (width || fullwidth) && zoomlevel && colortheme && maptheme) {

            // The ID for the map container always stays the same.
            var map_class = 'clicksports-maps-admin-map';

            // Sets the script paths using the passed plugin directory.
            var script_path_mapbox = 'https://maps.clicksports.de/mapbox-gl.min.js';
            var script_path_mapbox_language = 'https://maps.clicksports.de/openmaptiles-language.min.js';
            var script_path_mobile_detect = 'https://maps.clicksports.de/mobile-detect.js';

            /**
             * Check if the map container actually exists.
             * This way there won't be any script errors on pages without a map.
             */
            if ($('#' + map_class).length) {

                /**
                 * Calls the needed scripts using a callback since the language script fully depends on the Mapbox script.
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

                            /**
                             * Default settings popup.
                             */
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

                            /**
                             * Default settings marker.
                             */
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

            // Display the map on admin backend.
            $("#", map_class).show();

        } else {

            $('#clicksports-maps-admin-map-wrapper').html('<div id="clicksports-maps-admin-map-error"><span class="clicksports-maps-admin-map-error-text">' + translation_map_error_text + '</span></div>');

        }

        /*
         * Count the additional map markers.
         *
         * The default map marker will always be processed.
         */
        if( markers != null ) {
            var markers_count = Object.keys(markers).length;
            markers_count++;
        }

        /**
         * Dynamically adds a map marker.
         *
         * The markup will be appended to the div ( #clicksports-maps-new-marker )
         * found in: class-clicksports-maps-admin.php
         *
         * @since   1.1.0
         */
        $('#clicksports-maps-add-marker').click(function(event) {
            event.preventDefault();
            $('#clicksports-maps-new-marker').append(
                '<div class="clicksports-maps-coordinates-container">' +

                '<div class="clicksports-maps-admin-block-wrapper">' +
                '<div class="clicksports-maps-admin-block-right">' +
                '<h4>' + translation_latitude + '</h4>' +
                '<input type="text" class="clicksports-maps-admin-text" name="clicksports-maps[markers][marker-' + markers_count + '][latitude]" />' +
                '</div>' +

                '<div class="clicksports-maps-admin-block-left">' +
                '<h4>' + translation_longitude + '</h4>' +
                '<input type="text" class="clicksports-maps-admin-text" name="clicksports-maps[markers][marker-' + markers_count + '][longitude]" />' +
                '</div>' +
                '</div>' +

                '<div class="clicksports-maps-admin-block">' +
                '<h4>' + translation_markertext + '</h4>' +
                '<textarea rows="5" cols="50" class="clicksports-maps-admin-text" name="clicksports-maps[markers][marker-' + markers_count + '][markertext]"></textarea>' +
                '<div class="clicksports-maps-delete-marker-wrapper">' +
                '<button class="clicksports-maps-delete-marker" type="button">' + translation_remove_marker_text + '</button>' +
                '</div>' +
                '</div>' +

                '</div>'
            );
            markers_count++;
        });

        /**
         * Remove the map marker, works also on new dynamically created markers.
         *
         * @since   1.1.0
         */
        $('body').on('click', '.clicksports-maps-delete-marker', function() {
            $(this).closest('.clicksports-maps-coordinates-container').fadeOut( 400, function() {
                $(this).remove();
            });
        });

    }); // (document).ready()

})( jQuery );
