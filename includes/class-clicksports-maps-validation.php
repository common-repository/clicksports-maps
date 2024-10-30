<?php
/**
 * Handles all validation of global settings and user input provided with a shortcode.
 *
 * @package    Clicksports_Maps
 * @subpackage Clicksports_Maps/public
 * @author     CLICKSPORTS
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Clicksports_Maps_Validation {

	/**
	 * Validate plugin settings.
	 *
	 * To differentiate validation between global settings and shortcode parameters,
	 * we can pass the $shortcode flag (bool) as a function parameter.
	 *
	 * Setting affected by shortcode parameters:
	 * - width (specifically the unit: px or %)
	 * - fullwidth (can include the strings 'true' and 'false')
	 *
	 * @param $input
	 * @param false $shortcode
	 * @return array
	 *
	 * @since	1.1.0
     * @update  1.3.3
     * @update  1.3.5
	 */
	public static function clicksports_maps_validate_settings( $input, $shortcode = false ) {

		$valid = array();

		/**
		 * Check the current settings page and set the screen check accordingly
		 * to only show validation errors on the map settings instead of any other admin page.
		 */
		if( is_admin() ) {
			$current_screen = get_current_screen()->base;
			if( $current_screen == 'settings_page_clicksports-maps' ) {
				$screen_check = true;
			} else {
				$screen_check = false;
			}
		}

		/**
		 * Width.
		 */
		if( $shortcode === false ) {
		// Global settings.

			// Validate width, must be numeric.
			if( isset( $input['width'] ) ) {
				$valid['width'] = preg_replace( '/[^0-9]/', '', str_replace( ' ', '', $input['width'] ) );
				if( $valid['width'] !== $input['width'] ) {
					if( is_admin() && $screen_check === true ) {
						add_settings_error( 'clicksports-maps-width', 'clicksports-maps-width-error', esc_html__( 'Invalid width.', 'clicksports-maps' ), 'error' );
					}
				} else {
					if( isset( $input['width-pixel'] ) ) {
						$valid['width'] .= 'px';
					}
					if( isset( $input['width-percent'] ) ) {
						$valid['width'] .= '%';
					}
				}
			}

		} else {
		// Shortcode parameters.

			// Validate width, must be numeric, can have unit.
			if( isset( $input['width'] ) ) {
				$valid['width'] = preg_replace( '/[^0-9px|%]/', '', str_replace( ' ', '', $input['width'] ) );
			}

            if( isset( $input['routetext'] ) ) {
                $valid['routetext'] = preg_replace( '/[^a-zA-Z0-9\s]/', '', $input['routetext'] );
                if ( $valid['routetext'] !== $input['routetext'] ) {
                    if ( is_admin() && $screen_check === true ) {
                        add_settings_error( 'clicksports-maps', 'clicksports-maps-routetext-error', esc_html__( 'Invalid route link title.', 'clicksports-maps' ), 'error' );
                    }
                }
            }

		}

		/**
		 * Fullwidth
		 */
		if( $shortcode === false ) {
		// Global settings.

			// Validate fullwidth, can only be '1' if set.
			if( isset( $input['fullwidth'] ) ) {
				$valid['fullwidth'] = preg_replace( '/[^(1)]/', '', str_replace( ' ', '', $input['fullwidth'] ) );
				if( $valid['fullwidth'] !== $input['fullwidth'] ) {
					if( is_admin() && $screen_check === true ) {
						add_settings_error( 'clicksports-maps', 'clicksports-maps-fullwidth-error', esc_html__( 'Invalid value for fullwidth.', 'clicksports-maps' ), 'error' );
					}
				}
			}

		} else {
		// Shortcode parameters.

			if( isset( $input['fullwidth'] ) ) {
				$valid['fullwidth'] = preg_replace( '/[^a-zA-Z0-9\s]/', '', str_replace( ' ', '', $input['fullwidth'] ) );

			}

		}

		// Validate height, must be numeric.
        if( isset( $input['height'] ) ) {
            $valid['height'] = preg_replace( '/[^0-9]/', '', str_replace( ' ', '', $input['height'] ) );
            if( $valid['height'] !== $input['height'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps', 'clicksports-maps-height-error', esc_html__( 'Invalid height.', 'clicksports-maps' ), 'error' );
                }
            }
        }

		// Validate fullwidth, can only be '0' or '1' if set.
		if( isset( $input['widthtype'] ) ) {
			$valid['widthtype'] = preg_replace( '/[^(0|1)]/', '', str_replace( ' ', '', $input['widthtype'] ) );
			if( $valid['widthtype'] !== $input['widthtype'] ) {
				if( is_admin() && $screen_check === true ) {
					add_settings_error( 'clicksports-maps', 'clicksports-maps-widthtype-error', esc_html__( 'Invalid value for pixel selection.', 'clicksports-maps' ), 'error' );
				}
			}
		}

        // Validate zoom level, must be numeric.
        if( isset( $input['zoomlevel'] ) ) {
            $valid['zoomlevel'] = preg_replace( '/[^0-9]/', '', str_replace( ' ', '', $input['zoomlevel'] ) );
            if( $valid['zoomlevel'] !== $input['zoomlevel'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps-level', 'clicksports-maps-zoom-level-error', esc_html__( 'Invalid zoom setting.', 'clicksports-maps' ), 'error' );
                }
            }
        }

		// Validate maptheme, must be alphanumeric including '-'.
        if( isset( $input['maptheme'] ) ) {
            $valid['maptheme'] = preg_replace( '/[^a-zA-Z0-9\s]-/', '', str_replace( ' ', '', $input['maptheme'] ) );
            if( $valid['maptheme'] !== $input['maptheme'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps', 'clicksports-maps-maptheme-error', esc_html__( 'Invalid maptheme setting.', 'clicksports-maps' ), 'error' );
                }
            }
        }

		// Validate colortheme by sanitizing to hex color.
        if( isset( $input['colortheme'] ) ) {
            $valid['colortheme'] = sanitize_hex_color( $input['colortheme'] );
        }

		// Validate longitude, must be numeric including dot.
        if( isset( $input['longitude'] ) ) {
            $valid['longitude'] = preg_replace( '/[^0-9.]/', '', str_replace( ' ', '', $input['longitude'] ) );
            if( $valid['longitude'] !== $input['longitude'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps', 'clicksports-maps-longitude-error', esc_html__( 'Invalid longitude setting.', 'clicksports-maps' ), 'error' );
                }
            }
        }

		// Validate latitude, must be numeric including dot.
        if( isset( $input['latitude'] ) ) {
            $valid['latitude'] = preg_replace( '/[^0-9.]/', '', str_replace( ' ', '', $input['latitude'] ) );
            if( $valid['latitude'] !== $input['latitude'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps', 'clicksports-maps-latitude-error', esc_html__( 'Invalid latitude setting.', 'clicksports-maps' ), 'error' );
                }
            }
        }

        // Validate footer script, can only be '1' if set.
        if( isset( $input['script_footer'] ) ) {
            $valid['script_footer'] = preg_replace( '/[^(1)]/', '', str_replace( ' ', '', $input['script_footer'] ) );
            if( $valid['script_footer'] !== $input['script_footer'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps', 'clicksports-maps-script-footer-error', esc_html__( 'Invalid value for footer script.', 'clicksports-maps' ), 'error' );
                }
            }
        }

        // Validate conditional style loading, can only be '1' if set.
        if( isset( $input['styles_conditional_disabled'] ) ) {
            $valid['styles_conditional_disabled'] = preg_replace( '/[^(1)]/', '', str_replace( ' ', '', $input['styles_conditional_disabled'] ) );
            if( $valid['styles_conditional_disabled'] !== $input['styles_conditional_disabled'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps', 'clicksports-maps-conditional-styles-error', esc_html__( 'Invalid value for conditional CSS loading.', 'clicksports-maps' ), 'error' );
                }
            }
        }

        // Validate conditional script loading, can only be '1' if set.
        if( isset( $input['scripts_conditional_disabled'] ) ) {
            $valid['scripts_conditional_disabled'] = preg_replace( '/[^(1)]/', '', str_replace( ' ', '', $input['scripts_conditional_disabled'] ) );
            if( $valid['scripts_conditional_disabled'] !== $input['scripts_conditional_disabled'] ) {
                if( is_admin() && $screen_check === true ) {
                    add_settings_error( 'clicksports-maps', 'clicksports-maps-conditional-scripts-error', esc_html__( 'Invalid value for conditional script loading.', 'clicksports-maps' ), 'error' );
                }
            }
        }

		// Validate marker text by allowed HTML tags.
        $allowed_html = Clicksports_Maps_Sanitation::get_allowed_marker_html();

        if( isset ( $input['markertext'] ) ) {
            $valid['markertext'] = wp_kses( $input['markertext'], $allowed_html );
        }

		$i = 1;
		if( isset( $input['markers'] ) && !is_null( $input['markers'] ) ) {

			foreach( $input['markers'] as $marker ) {

				// Validate longitude, must be numeric including dot.
				$valid['markers']['marker-' . $i]['longitude'] = preg_replace( '/[^0-9.]/', '', str_replace( ' ', '', $marker['longitude'] ) );
				if( $valid['markers']['marker-' . $i]['longitude'] !== $marker['longitude'] ) {
					if( is_admin() && $screen_check === true ) {
						add_settings_error( 'clicksports-maps', 'clicksports-maps-longitude-error', esc_html__( 'Invalid longitude setting.', 'clicksports-maps'  ), 'error' );
					}
				}

				// Validate latitude, must be numeric including dot.
				$valid['markers']['marker-' . $i]['latitude'] = preg_replace( '/[^0-9.]/', '', str_replace( ' ', '', $marker['latitude'] ) );
				if( $valid['markers']['marker-' . $i]['latitude'] !== $marker['latitude'] ) {
					if( is_admin() && $screen_check === true ) {
						add_settings_error( 'clicksports-maps', 'clicksports-maps-latitude-error', esc_html__( 'Invalid latitude setting.', 'clicksports-maps' ), 'error' );
					}
				}

				// Validate marker text by allowed HTML tags - see array above for details.
				$valid['markers']['marker-' . $i]['markertext'] = wp_kses( $marker['markertext'], $allowed_html );

				$i++;

			}
		}

		return $valid;

	}

    /**
     * Format the marker text for the map container's ARIA label:
     *
     * - Remove HTML tags.
     * - Remove control characters.
     *
     * Notice: Only the primary marker's text will be formatted and put out for the ARIA label.
     *
     * @param $markertext
     * @return string
     *
     * @since   1.2.1
     */
    public static function get_markertext_aria_label( $markertext ) {

        $markertext = preg_replace ( '/<[^>]*>/', ' ', $markertext );
        $markertext = str_replace( "\r", '', $markertext );
        $markertext = str_replace( "\n", ' ', $markertext );
        $markertext = str_replace( "\t", ' ', $markertext );
        $markertext = trim( preg_replace( '/ {2,}/', ', ', $markertext ) );

        $markertext = esc_html__( 'Map view and location:', 'clicksports-maps' ) . ' ' . $markertext;

        return $markertext;

    }

    /**
     * Format the marker text for the map container's data attribute:
     *
     * - Remove control characters.
     *
     * Notice: Only the primary marker's text will be formatted and put out for the ARIA label.
     *
     * @param $markertext
     * @return array|string|string[]
     *
     * @since   1.2.1
     */
    public static function get_markertext_data( $markertext ) {

        $markertext = str_replace( "\r", '', $markertext );
        $markertext = str_replace( "\n", '', $markertext );
        $markertext = str_replace( "\t", ' ', $markertext );

        return $markertext;

    }

}
