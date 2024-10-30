<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Clicksports_Maps
 * @subpackage Clicksports_Maps/public
 * @author     CLICKSPORTS
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Clicksports_Maps_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since   1.0.0
     * @update  1.3.0
     * @update  1.3.3
     * @update  1.3.5
     */
    public function clicksports_maps_register_public_styles() {

        $settings = Clicksports_Maps_Settings::get_plugin_settings();

        // Public CSS.
        wp_register_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/clicksports-maps-public.css', array(), $this->version, 'all' );

        // Public Mapbox CSS.
        wp_register_style( $this->plugin_name . '-public-mapbox', 'https://maps.clicksports.de/mapbox-gl.min.css', array(), $this->version, 'all' );

        /**
         * Check if setting to disable conditional styles loading is set.
         * In that case we enqueue the styles already at this point instead of later on in the shortcode.
         */
        if( $settings['styles_conditional_disabled'] == 1 ) {

            // Public CSS.
            wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/clicksports-maps-public.css', array(), $this->version, 'all' );

            // Public Mapbox CSS.
            wp_enqueue_style( $this->plugin_name . '-public-mapbox', 'https://maps.clicksports.de/mapbox-gl.min.css', array(), $this->version, 'all' );

        }

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since   1.0.0
     * @update  1.3.0
     */
    public function clicksports_maps_register_public_scripts() {

        /**
         * Check the plugin settings if the frontend map script is supposed to be loaded in the footer (as opposed to the header).
         *
         * @since   1.1.4
         * @update  1.3.5
         */
        $settings = Clicksports_Maps_Settings::get_plugin_settings();
        if( isset( $settings['script_footer'] ) ) {
            if( $settings['script_footer'] == 1 ) {
                $load_in_footer = true;
            } else {
                $load_in_footer = false;
            }
        }

        wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/clicksports-maps-public.js', array( 'jquery' ), $this->version, $load_in_footer );

        /**
         * Check if setting to disable conditional scripts loading is set.
         * In that case we enqueue the scripts already at this point instead of later on in the shortcode.
         */
        if( $settings['scripts_conditional_disabled'] == 1 ) {

            // Public scripts.
            wp_enqueue_script( $this->plugin_name );

        }

        /**
         * Localizes the registered script with data for a JavaScript variable.
         */
        wp_localize_script( $this->plugin_name, 'clicksports_maps_settings', array('clicksports_maps_settings_data' => $this->clicksports_maps_get_localized_settings()) );

    }

    /**
     * Render the settings page for this plugin.
     *
     * Not utilized as of 1.1.0.
     *
     * @since	1.0.0
     */
    public function display_plugin_setup_page() {

        include_once( 'partials/clicksports-maps-public-display.php' );

    }

    /**
     * Localizes plugin settings and translations for further processing in JavaScript files.
     *
     * @return array
     *
     * @since	1.1.0
     */
    function clicksports_maps_get_localized_settings() {

        // Plugin settings.
        $settings = Clicksports_Maps_Settings::get_plugin_settings();

        // Translation strings.
        $translation_map_error_text     = esc_html__( 'Please set and save all default settings first.', 'clicksports-maps' );
        $translation_google_route_text  = esc_html__( 'Plan route with Google Maps', 'clicksports-maps' );

        $script_vars = array(

            'latitude' 		=> $settings['latitude'],
            'longitude' 	=> $settings['longitude'],
            'markertext' 	=> $settings['markertext'],
            'width' 		=> $settings['width'],
            'fullwidth' 	=> $settings['fullwidth'],
            'height' 		=> $settings['height'],
            'zoomlevel' 	=> $settings['zoomlevel'],
            'colortheme' 	=> $settings['colortheme'],
            'maptheme' 		=> $settings['maptheme'],
            'markers' 		=> $settings['markers'],

            'translation_map_error_text'    => $translation_map_error_text,
            'translation_google_route_text' => $translation_google_route_text,

        );

        return $script_vars;

    }

    /**
     * Initializes the WordPress shortcode ( '[clicksports-maps]' ).
     *
     * The shortcode function clicksports_maps_shortcode() is called from within this class,
     * so we need to refer to the function within the class itself.
     *
     * @since	1.0.0
     * @update  1.3.0
     */
    public function clicksports_maps_shortcode_init()
    {
        add_shortcode( 'clicksports-maps', array($this, 'clicksports_maps_shortcode') );
    }

    /**
     * Outputs the content when using the shortcode within the WordPress backend.
     *
     * Note: By enqueueing the resources at this point and within the shortcode function we make sure
     * that they are exclusively loaded on frontend pages when the shortcode is actually present.
     *
     * @param   array $atts
     * @param   null $output
     * @return  string
     *
     * @since   1.0.0
     * @update  1.3.0
     */
    public function clicksports_maps_shortcode( $atts = [], $output = null )
    {

        /**
         * Enqueue registered styles and scripts.
         *
         * Note: Will be ignored when already enqueued due to disablement of conditional loading.
         *
         * @update  1.3.5
         */

        // Public CSS.
        wp_enqueue_style( $this->plugin_name . '-public', plugin_dir_url( __FILE__ ) . 'css/clicksports-maps-public.css', array(), $this->version, 'all' );

        // Public Mapbox CSS.
        wp_enqueue_style( $this->plugin_name . '-public-mapbox', 'https://maps.clicksports.de/mapbox-gl.min.css', array(), $this->version, 'all' );

        // Public scripts.
        wp_enqueue_script( $this->plugin_name );

        /**
         * The ID for the map container always stays the same.
         */
        $map_id = 'clicksports-maps-public';

        /**
         * Gets the global plugin settings and sets the variables accordingly.
         */
        $settings = Clicksports_Maps_Settings::get_plugin_settings();

        $atts = shortcode_atts(
            array(
                'latitude' 		=> $settings['latitude'],
                'longitude' 	=> $settings['longitude'],
                'height' 		=> $settings['height'],
                'width' 		=> $settings['width'],
                'fullwidth' 	=> $settings['fullwidth'],
                'markertext' 	=> $settings['markertext'],
                'zoomlevel' 	=> $settings['zoomlevel'],
                'colortheme' 	=> $settings['colortheme'],
                'maptheme' 		=> $settings['maptheme'],
                'routetext'     => '',
            ), $atts, 'cs_maps_shortcode'
        );

        /**
         * Validate any shortcode user input.
         *
         * Function parameter 'shortcode' is set to true, indicating we need to validate a shortcode.
         */
        $atts = Clicksports_Maps_Validation::clicksports_maps_validate_settings( $atts, true );

        /**
         * Overwrite any global setting with the according shortcode parameter value.
         *
         * The overwritten values will be partially passed to the map script by HTML data attributes.
         */
        if( isset( $atts['latitude'] ) ) {
            $settings['latitude'] = $atts['latitude'];
        }

        if( isset( $atts['longitude'] ) ) {
            $settings['longitude'] = $atts['longitude'];
        }

        if( isset( $atts['height'] ) ) {
            $settings['height'] = $atts['height'];
        }

        /**
         * Set $unit to NULL initially.
         *
         * If the shortcode parameter is present, we set the unit according to the
         * passed parameter's unit and overwrite the global settings.
         */
        $unit = NULL;
        if( isset( $atts['width'] ) ) {
            if( strpos( $atts['width'], 'px' ) !== false ) {
                $atts['width'] = str_replace( 'px', '', $atts['width'] );
                $unit = 'px';
            }
            if( strpos( $atts['width'], '%' ) !== false ) {
                $atts['width'] = str_replace( '%', '', $atts['width'] );
                $unit = '%';
            }
            $settings['width'] = $atts['width'];
        }

        /**
         * If $unit is NULL and therefore no shortcode is present, we check the global
         * settings for a passed unit and set the variable accordingly.
         */
        if( is_null( $unit ) ) {
            if( isset( $settings['checked_percent'] ) ) {
                if( $settings['checked_percent'] == 'checked') {
                    $unit = '%';
                }
            }
            if( isset( $settings['checked_pixel'] ) ) {
                if( $settings['checked_pixel'] == 'checked') {
                    $unit = 'px';
                }
            }
        }

        if( isset( $atts['markertext'] ) ) {
            $settings['markertext'] = $atts['markertext'];
        }

        if( isset( $atts['zoomlevel'] ) ) {
            $settings['zoomlevel'] = $atts['zoomlevel'];
        }

        if( isset( $atts['colortheme'] ) ) {
            $settings['colortheme'] = $atts['colortheme'];
        }

        /**
         * @update 1.3.8
         *
         * If the map theme given in the shortcode is not one of the existing themes,
         * we fall back to the map theme set in the global settings.
         */
        if( isset( $atts['maptheme'] ) ) {
            if( preg_match('/\b(basic|klokantech-basic|positron)\b/', $atts['maptheme']) ) {
                $settings['maptheme'] = $atts['maptheme'];
            }
        }

        /**
         * If $fullwidth is NULL and therefore no shortcode is present, we check the global
         * settings for a passed unit and set the variable accordingly.
         *
         * Depending on global settings and passed shortcode parameters we either display a regular or a fullwidth map.
         * This way we can cover every possible case to individually display both kinds of maps.
         */
        $fullwidth = NULL;
        // If parameter value is 'true' - fullwidth.
        if( isset( $atts['fullwidth'] ) ) {
            if( strcmp( $atts['fullwidth'], 'true' ) === 0 ) {
                $fullwidth = true;
                unset( $settings['width'] );
            }
            // If parameter value is 'false' - regular.
            if( strcmp( $atts['fullwidth'], 'false' ) === 0 ) {
                $fullwidth = false;
            }
        }

        /**
         * If a fullwidth map needs to be displayed the container class is set accordingly.
         * We control the look of the map only by the container class and its specific CSS.
         * */
        if( is_null( $fullwidth ) ) {
            if( isset( $settings['checked_fullwidth'] ) ) {
                if( $settings['checked_fullwidth'] == 'checked' ) {
                    $fullwidth = true;
                    unset( $settings['width'] );
                } else {
                    $fullwidth = false;
                }
            }
        }

        /**
         * Set the map class depending on global settings and shortcode parameters.
         */
        if( $fullwidth === true ) {
            $map_class = 'clicksports-maps-public-fullwidth';
        } else {
            $map_class = 'clicksports-maps-public-regular';
        }

        /**
         * Returns the actual map wrapper for the frontend.
         *
         * Settings are passed as data attributes to the map script.
         *
         * @update  1.3.4
         *
         * Use of htmlspecialchars() to prevent broken strings when using link attributes with double quotes in markers.
         */
        $output .= '<div id="clicksports-maps-public-wrapper">';
        $output .= '<div role="application" id="' . $map_id . '" class="' . $map_class . '" style="height: ' .  $settings['height']  . 'px;';
        if(isset($settings['width'])) {
            $output .= ' width: ' . $settings['width'] . $unit . ';';
        }
        $output .= '" ';
        $output .= 'data-lat="' . $settings['latitude'] . '"';
        $output .= 'data-long="' . $settings['longitude'] . '"';
        $output .= 'data-zoomlevel="' . $settings['zoomlevel'] . '"';
        $output .= 'data-maptheme="' . $settings['maptheme'] . '"';
        $output .= 'data-colortheme="' . $settings['colortheme'] . '"';
        $output .= 'data-markertext="' . Clicksports_Maps_Validation::get_markertext_data( htmlspecialchars($settings['markertext']) ) . '"';

        /**
         * Undocumented shortcode attribute to overwrite the Google Maps route link title translation.
         *
         * @since   1.3.1
         */
        if( isset( $atts['routetext'] ) ) {
            $output .= 'data-routetext="' . $atts['routetext'] . '"';
        }

        $output .= 'aria-label="' . Clicksports_Maps_Validation::get_markertext_aria_label( $settings['markertext'] ) . '"';
        $output .= '></div></div>';

        return $output;

    }

}
