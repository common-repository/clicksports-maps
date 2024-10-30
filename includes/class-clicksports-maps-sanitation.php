<?php
/**
 * Handles definition of allowed entities for display output.
 *
 * @package    Clicksports_Maps
 * @subpackage Clicksports_Maps/public
 * @author     CLICKSPORTS
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Clicksports_Maps_Sanitation {

    /**
     * @return array
     *
     * @since   1.4.0
     */
    public static function get_allowed_output_html() {

        $allowed_html = array(

            'div' => array(
                'id' => array(),
                'class' => array(),
                'style' => array()
            ),

            'h1' => array(
                'class' => array(),
                'id' => array()
            ),
            'h2' => array(
                'class' => array(),
                'id' => array()
            ),
            'h3' => array(
                'class' => array(),
                'id' => array()
            ),
            'h4' => array(
                'class' => array(),
                'id' => array()
            ),
            'h5' => array(
                'class' => array(),
                'id' => array()
            ),
            'h6' => array(
                'class' => array(),
                'id' => array()
            ),

            'p'  => array(
                'class' => array(),
                'id' => array()
            ),

            'table' => array(),
            'tbody' => array(),
            'tr' => array(),
            'td' => array(),

            'code'=> array(),

            'button' => array(
                'class' => array(),
                'id' => array(),
                'type' => array()
            ),

            'input' => array(
                'checked' => array(),
                'class' => array(),
                'disabled'  => array(),
                'id'    => array(),
                'name'  => array(),
                'required' => array(),
                'type'  => array(),
                'value' => array()
            ),

            'label'  => array(
                'class' => array(),
                'for' => array()
            ),

            'option' => array(
                'selected' => array(),
                'value' => array()
            ),

            'select' => array(
                'class' => array(),
                'id'  => array(),
                'name' => array(),
                'value' => array()
            ),

            'textarea' => array(
                'class' => array(),
                'id' => array(),
                'name' => array(),
                'rows' => array()
            )

        );

        return $allowed_html;

    }

    public static function get_allowed_marker_html() {

        $allowed_html = array(

            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),

            'br' => array(),

            'p' => array(
                'class' => array()
            ),

            'strong' => array(
                'class' => array()
            ),

        );

        return $allowed_html;
    }

}