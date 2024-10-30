<?php
/**
 * Handles global plugin settings.
 *
 * @package    Clicksports_Maps
 * @subpackage Clicksports_Maps/public
 * @author     CLICKSPORTS
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Clicksports_Maps_Settings {

	/**
	 * @return mixed
	 *
	 * @since	1.1.0
     * @update  1.3.3
     * @update  1.3.5
	 */
	public static function get_plugin_settings() {

		// Get plugin's settings from the database.
		$plugin_settings = get_option( 'clicksports-maps' );

		// Validate the settings.
		$plugin_settings = Clicksports_Maps_Validation::clicksports_maps_validate_settings( $plugin_settings );

		// Latitude.
		if( isset( $plugin_settings['latitude'] ) ) {
			$settings['latitude'] = esc_html( $plugin_settings['latitude'] );
		} else {
			$settings['latitude'] = '';
		}

		// Longitude.
		if( isset( $plugin_settings['longitude'] ) ) {
			$settings['longitude'] = esc_html( $plugin_settings['longitude'] );
		} else {
			$settings['longitude'] = '';
		}

		// Markertext.
		if( isset( $plugin_settings['markertext'] ) ) {
			$settings['markertext'] = $plugin_settings['markertext'];
		} else {
			$settings['markertext'] = '';
		}

		// Width.
		if( isset( $plugin_settings['width'] ) ) {
			$settings['width'] = esc_html( $plugin_settings['width'] );
		} else {
			$settings['width'] = '';
		}

		// Fullwidth checkbox - sets separate setting 'checked' if true.
		if( isset( $plugin_settings['fullwidth'] ) ) {
			$settings['fullwidth'] = esc_html( $plugin_settings['fullwidth'] );
			$settings['checked_fullwidth'] = 'checked';
		} else {
			$settings['fullwidth'] = '';
			$settings['checked_fullwidth'] = '';
		}

		// Width type (percent or pixel) - sets separate settings 'checked' if true.
		if( isset( $plugin_settings['widthtype'] ) ) {
			$settings['type'] = $plugin_settings['widthtype'];
			if( $settings['type'] === '0' ) {
				$settings['checked_percent'] = 'checked';
			} else {
				$settings['checked_percent'] = '';
			}
			if( $settings['type'] === '1' ) {
				$settings['checked_pixel'] = 'checked';
			} else {
				$settings['checked_pixel'] = '';
			}
		} else {
			$settings['checked_percent'] = 'checked';
			$settings['checked_pixel'] = '';
		}

		// Height.
		if( isset( $plugin_settings['height'] ) ) {
			$settings['height'] = esc_html( $plugin_settings['height'] );
		} else {
			$settings['height'] = '';
		}

		// Zoom level.
		if( isset( $plugin_settings['zoomlevel'] ) ) {
			$settings['zoomlevel'] = esc_html( $plugin_settings['zoomlevel'] );
		} else {
			$settings['zoomlevel'] = '';
		}

		// Color theme.
		if( isset( $plugin_settings['colortheme'] ) ) {
			$settings['colortheme'] = sanitize_hex_color( $plugin_settings['colortheme'] );
		} else {
			$settings['colortheme'] = '';
		}

		// Map theme.
		if( isset( $plugin_settings['maptheme'] ) ) {
			$settings['maptheme'] = esc_html( $plugin_settings['maptheme'] );
		} else {
			$settings['maptheme'] = 'basic';
		}

		// Additional markers - NULL if none.
		if( isset( $plugin_settings['markers'] ) ) {
			$settings['markers'] = $plugin_settings['markers'];
		} else {
			$settings['markers'] = NULL;
		}

        // Load script in footer checkbox - sets separate setting 'checked' if true.
        if( isset( $plugin_settings['script_footer'] ) ) {
            $settings['script_footer'] = esc_html( $plugin_settings['script_footer'] );
            $settings['checked_script_footer'] = 'checked';
        } else {
            $settings['script_footer'] = '';
            $settings['checked_script_footer'] = '';
        }

        // Disable loading of styles conditionally - sets separate setting 'checked' if true.
        if( isset( $plugin_settings['styles_conditional_disabled'] ) ) {
            $settings['styles_conditional_disabled'] = esc_html( $plugin_settings['styles_conditional_disabled'] );
            $settings['checked_styles_conditional_disabled'] = 'checked';
        } else {
            $settings['styles_conditional_disabled'] = '';
            $settings['checked_styles_conditional_disabled'] = '';
        }

        // Disable loading of scripts conditionally - sets separate setting 'checked' if true.
        if( isset( $plugin_settings['scripts_conditional_disabled'] ) ) {
            $settings['scripts_conditional_disabled'] = esc_html( $plugin_settings['scripts_conditional_disabled'] );
            $settings['checked_scripts_conditional_disabled'] = 'checked';
        } else {
            $settings['scripts_conditional_disabled'] = '';
            $settings['checked_scripts_conditional_disabled'] = '';
        }

		return $settings;

	}

}
