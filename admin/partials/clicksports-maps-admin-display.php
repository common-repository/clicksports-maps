<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.clicksports.de
 * @since      1.0.0
 *
 * @package    Clicksports_Maps
 * @subpackage Clicksports_Maps/admin/partials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

<script>

    /**
     * Disables or enables the width input field depending on the fullwidth checkbox.
     * On reactivation we set a default value of 100%.
     */
    jQuery(document).ready(function($) {

        if( document.getElementById( 'clicksports-maps-fullwidth' ).checked === false ) {
            document.getElementById( 'clicksports-maps-width' ).disabled = false;
            document.getElementById( 'clicksports-maps-width-pixel' ).disabled = false;
            document.getElementById( 'clicksports-maps-width-percent' ).disabled = false;
        } else {
            document.getElementById( 'clicksports-maps-width' ).disabled = true;
            document.getElementById( 'clicksports-maps-width-pixel' ).disabled = true;
            document.getElementById( 'clicksports-maps-width-pixel' ).checked = false;
            document.getElementById( 'clicksports-maps-width-percent' ).disabled = true;
            document.getElementById( 'clicksports-maps-width-percent' ).checked = false;
        }

        document.getElementById( 'clicksports-maps-fullwidth' ).onchange = function() {
            document.getElementById( 'clicksports-maps-width' ).disabled = this.checked;
            if( document.getElementById( 'clicksports-maps-width' ).disabled === false) {
                document.getElementById( 'clicksports-maps-width' ).value = "100";
            }

			document.getElementById( 'clicksports-maps-width-pixel' ).disabled = this.checked;
			document.getElementById( 'clicksports-maps-width-percent' ).disabled = this.checked;

        };

    });

</script>

<?php

	// Check user capabilities.
	if( !current_user_can( 'manage_options' ) ) {
		return;
	}

?>

<div class="wrap">

	<form method="post" action="options.php">

		<div class="clicksports-maps-admin-settings-wrapper">

            <h1 class=""><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <?php settings_fields($this->plugin_name); ?>

                <?php

                    echo wp_kses( '<div class="clicksports-maps-admin-settings-section-container">', Clicksports_Maps_Sanitation::get_allowed_output_html() );
                        do_settings_sections( 'clicksports-maps-settings' );
                    echo wp_kses( '</div>', Clicksports_Maps_Sanitation::get_allowed_output_html() );

                    echo wp_kses( '<div class="clicksports-maps-admin-settings-section-container">', Clicksports_Maps_Sanitation::get_allowed_output_html() );
                        do_settings_sections( 'clicksports-maps-coordinates' );
                    echo wp_kses( '</div>', Clicksports_Maps_Sanitation::get_allowed_output_html() );

                    echo wp_kses( '<div class="clicksports-maps-admin-settings-section-container">', Clicksports_Maps_Sanitation::get_allowed_output_html() );
                        do_settings_sections( 'clicksports-maps-preview' );
                    echo wp_kses( '</div>', Clicksports_Maps_Sanitation::get_allowed_output_html() );

                    echo wp_kses( '<div class="clicksports-maps-admin-settings-section-container">', Clicksports_Maps_Sanitation::get_allowed_output_html() );
                        do_settings_sections( 'clicksports-maps-help' );
                    echo wp_kses( '</div>', Clicksports_Maps_Sanitation::get_allowed_output_html() );

                    echo wp_kses( '<div class="clicksports-maps-admin-settings-section-container">', Clicksports_Maps_Sanitation::get_allowed_output_html() );
                        do_settings_sections( 'clicksports-maps-developer' );
                    echo wp_kses( '</div>', Clicksports_Maps_Sanitation::get_allowed_output_html() );

                ?>

            <?php
                submit_button( esc_html__( 'Save Settings', 'clicksports-maps' ), 'primary','submit', TRUE );
            ?>

		</div>

	</form>

</div>
