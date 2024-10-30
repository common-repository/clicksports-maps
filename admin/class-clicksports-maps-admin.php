<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.clicksports.de
 * @since      1.0.0
 *
 * @package    Clicksports_Maps
 * @subpackage Clicksports_Maps/admin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Clicksports_Maps_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since   1.0.0
     * @update  1.3.0
	 */
	public function clicksports_maps_register_admin_styles() {

		// Admin CSS.
		wp_register_style( $this->plugin_name . '-admin', plugin_dir_url( __FILE__ ) . 'css/clicksports-maps-admin.css', array(), $this->version, 'all' );

		// Admin Mapbox CSS.
		wp_register_style( $this->plugin_name . '-admin-mapbox', 'https://maps.clicksports.de/mapbox-gl.min.css', array(), $this->version, 'all' );

	}

    /**
     * Register the JavaScript for the admin area.
     *
     * @since   1.0.0
     * @update  1.3.0
     */
    public function clicksports_maps_register_admin_scripts() {

        // Plugin: admin scripts.
        wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/clicksports-maps-admin.js', array( 'jquery' ), $this->version, false );

        // Localizes the registered script with data for a JavaScript variable.
        wp_localize_script( $this->plugin_name, 'clicksports_maps_settings', array( 'clicksports_maps_settings_data' => $this->clicksports_maps_get_localized_settings() ) );


    }

	/**
	 * Add a settings page for this plugin to the Settings menu.
	 *
	 * @since	1.0.0
	 */
	public function add_plugin_admin_menu() {

		add_options_page( 'CLICKSPORTS Maps' , 'CLICKSPORTS Maps', 'manage_options', $this->plugin_name, array( $this, 'display_plugin_setup_page' ) );

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since	1.0.0
	 */
	public function add_action_links( $links ) {

		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . esc_html__( 'Settings', 'clicksports-maps' ) . '</a>',
		);
		return array_merge( $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
     *
     * Note: By enqueueing the resources at this point we make sure they are only loaded on the plugin's settings page.
	 *
	 * @since	1.0.0
     * @update  1.3.0
	 */
	public function display_plugin_setup_page() {

		include_once( 'partials/clicksports-maps-admin-display.php' );

        /**
         * Enqueue registered styles and scripts.
         */

        // Admin styles.
        wp_enqueue_style( $this->plugin_name . '-admin' );

        // Admin Mapbox styles.
        wp_enqueue_style( $this->plugin_name . '-admin-mapbox' );

        // WordPress color picker.
        wp_enqueue_style( 'wp-color-picker' );

        // Admin scripts.
        wp_enqueue_script( $this->plugin_name );

        // WordPress color picker.
        wp_enqueue_script( $this->plugin_name . '-admin-color-picker', plugin_dir_url( __FILE__ ), array( 'wp-color-picker' ), $this->version, false );

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
		$translation_latitude 		        = esc_html__( 'Latitude', 'clicksports-maps' );
		$translation_longitude 		        = esc_html__( 'Longitude', 'clicksports-maps' );
		$translation_markertext		        = esc_html__( 'Marker Popup Text', 'clicksports-maps' );
        $translation_remove_marker_text     = esc_html__( 'Remove map marker', 'clicksports-maps' );
		$translation_map_error_text         = esc_html__( 'Please set and save all default settings first.', 'clicksports-maps' );
        $translation_google_route_text      = esc_html__( 'Plan route with Google Maps', 'clicksports-maps' );

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

			'translation_latitude' 			    => $translation_latitude,
			'translation_longitude' 		    => $translation_longitude,
			'translation_markertext' 		    => $translation_markertext,
			'translation_map_error_text' 	    => $translation_map_error_text,
			'translation_google_route_text' 	=> $translation_google_route_text,
			'translation_remove_marker_text' 	=> $translation_remove_marker_text,

		);

		return $script_vars;
	}

	/**
	 * Registration of setting and sections.
	 *
	 * @since	1.1.0
	 */
	public function clicksports_maps_settings() {

		$args = array(
			'type' 				=> 'array',
			'sanitize_callback' => array( $this, 'clicksports_maps_validate_options' ),
			'default' 			=> NULL
		);

		register_setting( $this->plugin_name, $this->plugin_name, $args );

		add_settings_section('clicksports-maps-wrapper-style', esc_html__( 'Map Style', 'clicksports-maps' ), array( $this, 'clicksports_maps_section_settings_display' ), 'clicksports-maps-settings' );

		add_settings_section('clicksports-maps-wrapper-preview', esc_html__( 'Map Preview', 'clicksports-maps' ), array( $this, 'clicksports_maps_section_preview_display' ), 'clicksports-maps-preview' );

		add_settings_section('clicksports-maps-wrapper-coordinates', esc_html__( 'Map Markers', 'clicksports-maps' ), array( $this, 'clicksports_maps_section_coordinates_display' ), 'clicksports-maps-coordinates' );

        add_settings_section('clicksports-maps-wrapper-developer', esc_html__( 'Developer Settings', 'clicksports-maps' ), array( $this, 'clicksports_maps_section_developer_display'), 'clicksports-maps-developer' );

        add_settings_section('clicksports-maps-wrapper-help', esc_html__( 'Help', 'clicksports-maps' ), array( $this, 'clicksports_maps_section_help_display'), 'clicksports-maps-help' );

    }

	/**
	 * Setting section - General settings.
	 *
	 * @since   1.0.0
	 */
	public function clicksports_maps_section_settings_display() {

		// Get the global plugin settings.
		$settings = Clicksports_Maps_Settings::get_plugin_settings();

        $output = '<div class="clicksports-maps-admin-settings-section">';
		$output .= '<h3>' . esc_html__( 'Width', 'clicksports-maps' ) . '</h3>';
		$output .= '<input type="text" class="clicksports-maps-admin-text" id="clicksports-maps-width" name="clicksports-maps[width]" value="' . esc_attr( $settings['width'] ) . '" />';
		$output .= '<div class="clicksports-maps-radio-section">';
  		$output .= '<input type="radio" id="clicksports-maps-width-percent" name="clicksports-maps[widthtype]" value="0" ' . esc_attr($settings['checked_percent']) . ' />';
		$output .= '<label for="clicksports-maps-width-percent" class="clicksports-maps-radio-button-label">' . esc_html__( 'Percent', 'clicksports-maps' ) . '</label>';
		$output .= '<input type="radio" id="clicksports-maps-width-pixel" name="clicksports-maps[widthtype]" value="1" ' . esc_attr($settings['checked_pixel']) . ' />';
		$output .= '<label for="clicksports-maps-width-pixel" class="clicksports-maps-radio-button-label">' . esc_html__( 'Pixel', 'clicksports-maps' ) . '</label>';
        $output .= '</div>'; // .clicksports-maps-radio-section

		$output .= '<div class="clicksports-maps-admin-settings-section-inner">';
		$output .= '<input type="checkbox" id="clicksports-maps-fullwidth" name="clicksports-maps[fullwidth]" value="1" '. $settings['checked_fullwidth'] . ' />';
		$output .= '<label for="clicksports-maps-fullwidth">' . esc_html__( 'Full Width Map', 'clicksports-maps' ) . '</label>';
		$output .= '<p class="clicksports-maps-info-paragraph">' . esc_html__( 'Deactivates the manual width setting and stretches the map across the full page width. Support for this feature highly depends on the activated theme and cannot be guaranteed.', 'clicksports-maps' ) . '</p>';
        $output .= '</div>'; // .clicksports-maps-admin-settings-section-inner

		$output .= '<h3>' . esc_html__( 'Height', 'clicksports-maps' ) . '</h3>';
		$output .= '<input type="text" class="clicksports-maps-admin-text" id="clicksports-maps-height" name="clicksports-maps[height]" value="' . esc_attr( $settings['height'] ) . '"/>';

		$output .= '<h3>' . esc_html__( 'Zoom', 'clicksports-maps' ) . '</h3>';
		$output .= '<select name="clicksports-maps[zoomlevel]" class="clicksports-maps-admin-select">';

		// Zoom select options.
		$zoomValues = range(0,16);
		foreach($zoomValues as $zoomValue) {
			$output .= '<option value="' . intval( $zoomValue ) . '" ' . selected( $zoomValue, $settings['zoomlevel'], false ) . ' >' . esc_attr( $zoomValue ) . '</option>';
		}

		$output .= '</select>';

		$output .= '<h3>' . esc_html__( 'Map Theme', 'clicksports-maps' ) . '</h3>';
		$output .= '<select name="clicksports-maps[maptheme]" class="clicksports-maps-admin-select">';

		// Theme select options.
		$output .= '<option value="' . esc_attr( 'basic' ) . '" ' . selected( 'basic', $settings['maptheme'], false ) . '>' . esc_html__( 'CS Basic', 'clicksports-maps' ) . '</option>';
		$output .= '<option value="' . esc_attr( 'klokantech-basic' ) . '" ' . selected( 'klokantech-basic', $settings['maptheme'], false ) . '>' . esc_html__( 'Klokantech Basic', 'clicksports-maps' ) . '</option>';
		$output .= '<option value="' . esc_attr( 'positron' ) . '" ' . selected( 'positron', $settings['maptheme'], false ) . '>' . esc_html__( 'Positron', 'clicksports-maps' ) . '</option>';

		$output .= '</select>';

		$output .= '<div class="clicksports-maps-admin-block-section">';
		$output .= '<div class="clicksports-maps-admin-block">';
		$output .= '<h3>' . esc_html__( 'Color Theme', 'clicksports-maps' ) . '</h3>';
		$output .= '<input type="text" class="cs-color-picker" id="clicksports-maps-colortheme" name="clicksports-maps[colortheme]" value="' . esc_attr( $settings['colortheme'] ) . '" />';
		$output .= '</div>'; // .clicksports-maps-admin-block
		$output .= '</div>'; // .clicksports-maps-admin-block-section

		$output .= '</div>'; // .clicksports-maps-admin-settings-section

        echo wp_kses($output, Clicksports_Maps_Sanitation::get_allowed_output_html() );

	}

	/**
	 * Preview Section.
	 *
	 * @since	1.1.0
	 */
	public function clicksports_maps_section_preview_display() {

        $output = '<div class="clicksports-maps-admin-settings-section">';
		$output .= '<div class="clicksports-maps-admin-preview">';
		$output .= '<div id="clicksports-maps-admin-map-wrapper">';
		$output .= '<div id="clicksports-maps-admin-map"></div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		echo wp_kses( $output, Clicksports_Maps_Sanitation::get_allowed_output_html() );

	}

	/**
	 * Map marker / coordinates section.
	 *
	 * @since	1.1.0
	 */
	public function clicksports_maps_section_coordinates_display() {

		// Get the global plugin settings.
		$settings = Clicksports_Maps_Settings::get_plugin_settings();

        $output = '<div class="clicksports-maps-admin-settings-section">';
		$output .= '<p class="clicksports-maps-info-paragraph">' . esc_html__( 'The map is centered automatically to the first set of coordinates. Additionally a marker is placed at that location. If you do not know the coordinates for your location, you can get them here:', 'clicksports-maps' );
		$output .= ' <a href="' . esc_url( 'https://nominatim.openstreetmap.org/' ) . '" target="_blank">OpenStreetMap Nominatim' . '</a>, ';
		$output .= '<a href="' . esc_url( 'https://www.latlong.net/' ) . '" target="_blank">latlong.net</a>.</p>';
		$output .= '<p class="clicksports-maps-info-paragraph">' . esc_html__( 'The marker popup supports the following HTML tags:', 'clicksports-maps' ) . ' <code>&lt;strong&gt;</code><code>&lt;br&gt;</code><code>&lt;p&gt;</code><code>&lt;a&gt;</code></p>';

		$output .= '<div class="clicksports-maps-coordinates-container">';

		$output .= '<div class="clicksports-maps-admin-block-wrapper">';
		$output .= '<div class="clicksports-maps-admin-block-right">';
		$output .= '<h4>' . esc_html__( 'Latitude', 'clicksports-maps' ) . '</h4>';
		$output .= '<input type="text" class="clicksports-maps-admin-text" id="clicksports-maps-latitude" name="clicksports-maps[latitude]" value="' . esc_attr( $settings['latitude'] ) . '" />';
		$output .= '</div>';

		$output .= '<div class="clicksports-maps-admin-block-left">';
		$output .= '<h4>' . esc_html__( 'Longitude', 'clicksports-maps' ) . '</h4>';
		$output .= '<input type="text" class="clicksports-maps-admin-text" id="clicksports-maps-longitude" name="clicksports-maps[longitude]" value="' . esc_attr( $settings['longitude'] ) . '" />';
		$output .= '</div>';
		$output .= '</div>';

		$output .= '<div class="clicksports-maps-admin-block">';
		$output .= '<h4>' . esc_html__( 'Marker Popup Text', 'clicksports-maps' ) . '</h4>';
		$output .= '<textarea rows="4" class="clicksports-maps-admin-text" id="clicksports-maps-markertext" name="clicksports-maps[markertext]">' . esc_textarea( $settings['markertext'] ) . '</textarea>';
		$output .= '</div>';
		$output .= '</div>';

		/**
		 * We receive all markers sequentially by the setting's database entries, therefore starting with i = 1.
		 */
		if( !is_null( $settings['markers'] ) ) {

			$i = 1;
			$markers = $settings['markers'];

			foreach( $markers as $marker ) {

				$output .= '<div class="clicksports-maps-coordinates-container">';

				$output .= '<div class="clicksports-maps-admin-block-wrapper">';
				$output .= '<div class="clicksports-maps-admin-block-right">';
				$output .= '<h4>' . esc_html__( 'Latitude', 'clicksports-maps' ) . '</h4>';
				$output .= '<input type="text" class="clicksports-maps-admin-text" name="clicksports-maps[markers][marker-' . $i . '][latitude]" value="' . esc_attr( $marker['latitude'] ) . '" />';
				$output .= '</div>';

				$output .= '<div class="clicksports-maps-admin-block-left">';
				$output .= '<h4>' . esc_html__( 'Longitude', 'clicksports-maps' ) . '</h4>';
				$output .= '<input type="text" class="clicksports-maps-admin-text" name="clicksports-maps[markers][marker-' . $i . '][longitude]" value="' . esc_attr( $marker['longitude'] ) . '" />';
				$output .= '</div>';
				$output .= '</div>';

				$output .= '<div class="clicksports-maps-admin-block">';
				$output .= '<h4>' . esc_html__( 'Marker Popup Text', 'clicksports-maps' ) . '</h4>';
				$output .= '<textarea rows="4" class="clicksports-maps-admin-text" name="clicksports-maps[markers][marker-' . $i . '][markertext]">' . esc_textarea( $marker['markertext'] ) . '</textarea>';

				$output .= '<div class="clicksports-maps-delete-marker-wrapper">';
				$output .= '<button class="clicksports-maps-delete-marker" type="button">' . esc_html__( 'Remove map marker', 'clicksports-maps' ) . '</button>';
				$output .= '</div>';

				$output .= '</div>';
				$output .= '</div>';

				$i++;

			}
		}

		$output .= '<div id="clicksports-maps-new-marker"></div>';

		$output .= '<button id="clicksports-maps-add-marker">' . esc_html__( 'Add Map Marker' , 'clicksports-maps' ) . '</button>';

        $output .= '</div>';

        echo wp_kses( $output, Clicksports_Maps_Sanitation::get_allowed_output_html() );

	}

	/**
	 * Help section.
	 *
	 * @since	1.1.0
	 */
	public function clicksports_maps_section_help_display() {

		$output = '<div class="clicksports-maps-admin-settings-section clicksports-maps-admin-map-instructions">';
		$output .= '<p>' . esc_html__( 'The usage of CLICKSPORTS Maps is completely free for private and charitable use. You simply need to register your domain with us and we activate the service for you. Business customers are charged a small fee to use our service. You can find the details in our', 'clicksports-maps' );
		$output .= '<a href="https://link.clicksports.de/maps/"> ' . esc_html__( 'blogpost', 'clicksports-maps' ) . '</a> ' . esc_html__( 'or on our', 'clicksports-maps' );
		$output .= '<a href="https://www.clicksports.de"> ' . esc_html__( 'website', 'clicksports-maps' ) . '</a>. ' . esc_html__( 'Neither this plugin nor our servers save any personal information. Therefore this plugin is fully GDPR compliant.', 'clicksports-maps' );
		$output .= '</p>';
		$output .= '<h3>' . esc_html__( 'Shortcode' , 'clicksports-maps' ) . '</h3>';
		$output .= '<p>' . esc_html__( 'You can display the map using this shortcode:', 'clicksports-maps' ) . '</p>';
		$output .= '<p><code>[clicksports-maps]</code></p>';
		$output .= '<h3>' . esc_html__( 'Shortcode Parameters' , 'clicksports-maps' ) . '</h3>';
		$output .= '<p>' . esc_html__( 'The shortcode supports multiple parameters to display an individual map beyond any global setting.', 'clicksports-maps' ) . '</p>';

		$output .= '<table>';
		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Latitude:', 'clicksports-maps' ) . '</td><td><code>latitude="50.2725"</code></td>';
		$output .= '</tr>';

		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Longitude:', 'clicksports-maps' ) . '</td><td><code>longitude="10.9900"</code></td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Height:', 'clicksports-maps' ) . '</td><td><code>height="500"</code></td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Width:', 'clicksports-maps' ) . '</td><td><code>width="100%"</code> (px, %)</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Full Width:', 'clicksports-maps' ) . '</td><td><code>fullwidth="true"</code> (true, false) - '  . esc_html__( 'Overwrites any width parameter', 'clicksports-maps' ) . '</td>';
		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Marker Popup Text:', 'clicksports-maps' ) . '<br /></td><td><code>markertext="&lt;strong&gt;CLICKSPORTS&lt;/strong&gt;&lt;br /&gt;Cortendorfer Str. 37&lt;br/&gt;96450 Coburg"</code></td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Map Theme:', 'clicksports-maps' ) . '</td><td><code>maptheme="basic"</code> (basic, klokantech-basic, positron)</td>';
		$output .= '</tr>';
		$output .= '<tr>';
		$output .= '<td>' . esc_html__( 'Color Theme:', 'clicksports-maps' ) . '</td><td><code>colortheme="#abc811"</code> (hex)</td>';
		$output .= '</tr>';
		$output .= '</table>';

		$output .= '<p>';
		$output .= '<strong>' . esc_html__( 'Complete example:', 'clicksports-maps' ) . '</strong><br />';
		$output .= '<p><code>[clicksports-maps latitude="50.2725" longitude="10.9900" height="500" width="100%" fullwidth="false" maptheme="basic" zoomlevel="16" markertext="&lt;strong&gt;CLICKSPORTS GmbH&lt;/strong&gt;&lt;br /&gt;Cortendorfer Str. 37&lt;br/&gt;96450 Coburg"]</code></p>';
		$output .= '</p>';
		$output .= '</div>';

		echo wp_kses( $output, Clicksports_Maps_Sanitation::get_allowed_output_html() );

	}

    /**
     * Developer section.
     *
     * @since   1.1.4
     * @update  1.3.3
     * @update  1.3.5
     */
	public function clicksports_maps_section_developer_display() {

        $settings = Clicksports_Maps_Settings::get_plugin_settings();

        $output = '<div class="clicksports-maps-admin-settings-section">';

        $output .= '<div class="clicksports-maps-admin-settings-single-option">';
        $output .= '<input type="checkbox" id="clicksports-maps-load-script-footer" name="clicksports-maps[script_footer]" value="1" '. $settings['checked_script_footer'] . ' />';
        $output .= '<label for="clicksports-maps-load-script-footer">' . esc_html__( 'Load map script in footer.', 'clicksports-maps' ) . '</label>';
        $output .= '<p class="clicksports-maps-info-paragraph">' . esc_html__( 'Loads the map script in the site footer instead of the site header.', 'clicksports-maps' ) . '</p>';
        $output .= '</div>';


        $output .= '<div class="clicksports-maps-admin-settings-single-option">';
        $output .= '<input type="checkbox" id="clicksports-maps-load-styles-conditionally" name="clicksports-maps[styles_conditional_disabled]" value="1" '. $settings['checked_styles_conditional_disabled'] . ' />';
        $output .= '<label for="clicksports-maps-load-styles-conditionally">' . esc_html__( 'Load map CSS on all pages.', 'clicksports-maps' ) . '</label>';
        $output .= '</div>';

        $output .= '<div class="clicksports-maps-admin-settings-single-option">';
        $output .= '<input type="checkbox" id="clicksports-maps-load-scripts-conditionally" name="clicksports-maps[scripts_conditional_disabled]" value="1" '. $settings['checked_scripts_conditional_disabled'] . ' />';
        $output .= '<label for="clicksports-maps-load-scripts-conditionally">' . esc_html__( 'Load map scripts on all pages.', 'clicksports-maps' ) . '</label>';
        $output .= '<p class="clicksports-maps-info-paragraph">' . esc_html__( 'Loads map styles or scripts on every page and disables conditional loading of resources.', 'clicksports-maps' ) . '</p>';
        $output .= '</div>';

        $output .= '</div>';

        echo wp_kses( $output, Clicksports_Maps_Sanitation::get_allowed_output_html() );

    }

	/**
	 * Setting field - Color theme.
	 *
	 * @since	1.0.0
	 */
	public function clicksports_maps_settings_color_theme_display() {

		$options = get_option( 'clicksports_maps_options' );
		if( isset( $options['colortheme'] ) ) {
			$color_theme = $options['colortheme'];
		} else {
			$color_theme = '';
		}

        $output = '<input type="text" class="clicksports-maps-color-picker" id="color-theme" name="clicksports_maps_options[colortheme]" value="' . esc_attr( $color_theme ) . '"/>';

        echo wp_kses( $output, Clicksports_Maps_Sanitation::get_allowed_output_html() );

	}

	/**
	 * Validate the setting fields and user input.
	 *
	 * @param $input
	 * @return array
	 *
	 * @since	1.1.0
	 */
	public function clicksports_maps_validate_options( $input ) {

		$valid = Clicksports_Maps_Validation::clicksports_maps_validate_settings( $input );

		return $valid;

	}

}
